<?php

namespace Tests\Feature;

use App\Models\Adherent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdherentAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_adherent_actif_peut_se_connecter(): void
    {
        $adherent = Adherent::factory()->create([
            'email' => 'actif@example.com',
            'password' => Hash::make('motdepasse123'),
            'statut_demande' => 'active',
            'espace_actif' => true,
        ]);

        $reponse = $this->post(route('adherent.login.post'), [
            'email' => 'actif@example.com',
            'password' => 'motdepasse123',
        ]);

        $reponse->assertRedirect(route('adherent.dashboard'));
        $this->assertAuthenticatedAs($adherent, 'adherent');
    }

    public function test_un_adherent_non_active_ne_peut_pas_se_connecter(): void
    {
        Adherent::factory()->create([
            'email' => 'inactif@example.com',
            'password' => Hash::make('motdepasse123'),
            'statut_demande' => 'en_attente',
            'espace_actif' => false,
        ]);

        $reponse = $this->post(route('adherent.login.post'), [
            'email' => 'inactif@example.com',
            'password' => 'motdepasse123',
        ]);

        $reponse->assertSessionHasErrors('email');
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
            'espace_actif' => true,
        ]);

        $reponse = $this->actingAs($adherent, 'adherent')
            ->get(route('adherent.dashboard'));

        $reponse->assertOk();
        $reponse->assertSee($adherent->prenom);
    }
}
