<?php

namespace Tests\Feature;

use App\Models\Adherent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdherentAuthTest extends TestCase
{
    use RefreshDatabase;

    // ═══════════════════════════════════════════════════════
    // Connexion par EMAIL
    // ═══════════════════════════════════════════════════════

    public function test_un_adherent_actif_peut_se_connecter_par_email(): void
    {
        $adherent = Adherent::factory()->create([
            'email'          => 'actif@example.com',
            'password'       => Hash::make('motdepasse123'),
            'statut_demande' => 'active',
            'espace_actif'   => true,
        ]);

        $reponse = $this->post(route('adherent.login.post'), [
            'identifiant' => 'actif@example.com',
            'password'    => 'motdepasse123',
        ]);

        $reponse->assertRedirect(route('adherent.dashboard'));
        $this->assertAuthenticatedAs($adherent, 'adherent');
    }

    // ═══════════════════════════════════════════════════════
    // Connexion par TÉLÉPHONE
    // ═══════════════════════════════════════════════════════

    public function test_un_adherent_peut_se_connecter_par_son_telephone(): void
    {
        $adherent = Adherent::factory()->create([
            'email'          => 'avec.tel@example.com',
            'telephone'      => '690123456',
            'password'       => Hash::make('motdepasse123'),
            'statut_demande' => 'active',
            'espace_actif'   => true,
        ]);

        $reponse = $this->post(route('adherent.login.post'), [
            'identifiant' => '690123456',
            'password'    => 'motdepasse123',
        ]);

        $reponse->assertRedirect(route('adherent.dashboard'));
        $this->assertAuthenticatedAs($adherent, 'adherent');
    }

    public function test_un_adherent_peut_se_connecter_avec_telephone_formate(): void
    {
        $adherent = Adherent::factory()->create([
            'telephone'      => '690123456',
            'password'       => Hash::make('motdepasse123'),
            'statut_demande' => 'active',
            'espace_actif'   => true,
        ]);

        // Variante avec indicatif + espaces
        $reponse = $this->post(route('adherent.login.post'), [
            'identifiant' => '+237 690 12 34 56',
            'password'    => 'motdepasse123',
        ]);

        $reponse->assertRedirect(route('adherent.dashboard'));
        $this->assertAuthenticatedAs($adherent, 'adherent');
    }

    public function test_un_adherent_peut_se_connecter_si_telephone_stocke_avec_indicatif(): void
    {
        $adherent = Adherent::factory()->create([
            'telephone'      => '+237690123456',
            'password'       => Hash::make('motdepasse123'),
            'statut_demande' => 'active',
            'espace_actif'   => true,
        ]);

        // L'adhérent saisit sans indicatif cette fois
        $reponse = $this->post(route('adherent.login.post'), [
            'identifiant' => '690123456',
            'password'    => 'motdepasse123',
        ]);

        $reponse->assertRedirect(route('adherent.dashboard'));
        $this->assertAuthenticatedAs($adherent, 'adherent');
    }

    public function test_connexion_avec_telephone_inconnu_echoue(): void
    {
        $reponse = $this->post(route('adherent.login.post'), [
            'identifiant' => '699999999',
            'password'    => 'nimporte',
        ]);

        $reponse->assertSessionHasErrors('identifiant');
        $this->assertGuest('adherent');
    }

    public function test_connexion_avec_telephone_trop_court_echoue(): void
    {
        Adherent::factory()->create([
            'telephone'      => '690123456',
            'password'       => Hash::make('motdepasse123'),
            'statut_demande' => 'active',
            'espace_actif'   => true,
        ]);

        $reponse = $this->post(route('adherent.login.post'), [
            'identifiant' => '69012', // trop court
            'password'    => 'motdepasse123',
        ]);

        $reponse->assertSessionHasErrors('identifiant');
        $this->assertGuest('adherent');
    }

    // ═══════════════════════════════════════════════════════
    // Cas d'échec communs
    // ═══════════════════════════════════════════════════════

    public function test_un_adherent_non_active_ne_peut_pas_se_connecter(): void
    {
        Adherent::factory()->create([
            'email'          => 'inactif@example.com',
            'password'       => Hash::make('motdepasse123'),
            'statut_demande' => 'en_attente',
            'espace_actif'   => false,
        ]);

        $reponse = $this->post(route('adherent.login.post'), [
            'identifiant' => 'inactif@example.com',
            'password'    => 'motdepasse123',
        ]);

        $reponse->assertSessionHasErrors('identifiant');
        $this->assertGuest('adherent');
    }

    public function test_un_visiteur_ne_peut_pas_acceder_au_dashboard_adherent(): void
    {
        $reponse = $this->get(route('adherent.dashboard'));
        $reponse->assertRedirect(route('adherent.login'));
    }

    public function test_un_adherent_connecte_peut_acceder_a_son_dashboard(): void
    {
        $adherent = Adherent::factory()->create([
            'statut_demande' => 'active',
            'espace_actif'   => true,
        ]);

        $reponse = $this->actingAs($adherent, 'adherent')
            ->get(route('adherent.dashboard'));

        $reponse->assertOk();
        $reponse->assertSee($adherent->prenom);
    }
}