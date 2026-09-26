<?php

namespace Tests\Feature;

use App\Models\Adherent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdhesionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('private');
        Storage::fake('public');
    }

    /** ÉTAPE 1+2+3 : Soumission du formulaire */
    public function test_visiteur_peut_soumettre_une_demande(): void
    {
        Notification::fake();

        // Admin pour recevoir la notification
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->post(route('adherer.store'), [
            'nom'                 => 'Dupont',
            'prenom'              => 'Marie',
            'date_naissance'      => '2000-01-15',
            'etablissement'       => 'Lycée de Maroua',
            'categorie'           => 'simple',
            'cotisation_proposee' => 5000,
            'piece_identite'      => UploadedFile::fake()->image('cni.jpg'),
            'email'               => 'marie@example.com',
            'telephone'           => '690000000',
            'adresse'             => 'Quartier Pitoire',
            'ville'               => 'Maroua',
            'motivation'          => 'Je veux soutenir la cause.',
        ]);

        $response->assertRedirect(route('adherer'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('adherents', [
            'email'          => 'marie@example.com',
            'statut_demande' => 'en_attente',
            'actif'          => false,
            'espace_actif'   => false,
        ]);

        $adherent = Adherent::where('email', 'marie@example.com')->first();
        Storage::disk('private')->assertExists($adherent->piece_identite);

        Notification::assertSentTo($admin, \App\Notifications\NouvelleDemandeAdhesion::class);
    }

    public function test_email_unique_est_requis(): void
    {
        Adherent::factory()->create(['email' => 'existant@example.com']);

        $response = $this->post(route('adherer.store'), [
            'nom'            => 'Test',
            'prenom'         => 'Test',
            'date_naissance' => '2000-01-15',
            'etablissement'  => 'Test',
            'categorie'      => 'simple',
            'piece_identite' => UploadedFile::fake()->image('cni.jpg'),
            'email'          => 'existant@example.com',
            'telephone'      => '690000000',
            'adresse'        => 'Test',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** ÉTAPE 4 : Validation humaine */
    public function test_admin_peut_accepter_une_demande(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $adherent = Adherent::factory()->create(['statut_demande' => 'en_attente']);

        $response = $this->actingAs($admin)
            ->post(route('admin.adherents.accepter', $adherent));

        $response->assertRedirect(route('admin.adherents.show', $adherent));

        $adherent->refresh();
        $this->assertEquals('acceptee', $adherent->statut_demande);
        $this->assertEquals('en_attente', $adherent->statut_cotisation);
        $this->assertFalse($adherent->actif);
        $this->assertFalse($adherent->espace_actif);
    }

    public function test_admin_peut_refuser_avec_motif(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $adherent = Adherent::factory()->create(['statut_demande' => 'en_attente']);

        $this->actingAs($admin)->post(route('admin.adherents.refuser', $adherent), [
            'motif_refus' => 'Pièce d\'identité non lisible.',
        ]);

        $adherent->refresh();
        $this->assertEquals('refuse', $adherent->statut_demande);
        $this->assertEquals('Pièce d\'identité non lisible.', $adherent->motif_refus);
    }

    /** ÉTAPE 5+6 : Paiement */
    public function test_admin_peut_enregistrer_un_paiement(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $adherent = Adherent::factory()->create([
            'statut_demande'    => 'acceptee',
            'statut_cotisation' => 'en_attente',
        ]);

        $this->actingAs($admin)->post(route('admin.adherents.payer', $adherent), [
            'mode_paiement' => 'mobile_money',
            'montant'       => 5000,
        ]);

        $adherent->refresh();
        $this->assertEquals('a_jour', $adherent->statut_cotisation);
        $this->assertEquals(5000, $adherent->cotisation_montant);
        $this->assertEquals('mobile_money', $adherent->mode_paiement);
        $this->assertNotNull($adherent->date_dernier_paiement);
    }

    /** ÉTAPE 7+8 : Activation + credentials */
    public function test_admin_peut_activer_une_adhesion(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $adherent = Adherent::factory()->create([
            'statut_demande'    => 'acceptee',
            'statut_cotisation' => 'a_jour',
            'espace_actif'      => false,
            'password'          => null,
        ]);

        $this->actingAs($admin)->post(route('admin.adherents.activer', $adherent));

        $adherent->refresh();
        $this->assertEquals('active', $adherent->statut_demande);
        $this->assertTrue($adherent->actif);
        $this->assertTrue($adherent->espace_actif);
        $this->assertNotNull($adherent->password);
        $this->assertTrue(str_starts_with($adherent->password, '$2y$'));
        $this->assertTrue($adherent->peutAccederEspace());

        // Notification envoyée
        Notification::assertSentTo(
            $adherent,
            \App\Notifications\AdhesionActivee::class
        );
    }

    /** WORKFLOW COMPLET : de bout en bout */
    public function test_workflow_complet_de_bout_en_bout(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);

        // 1. Soumission
        $this->post(route('adherer.store'), [
            'nom'                 => 'Complet',
            'prenom'              => 'Test',
            'date_naissance'      => '1998-03-20',
            'etablissement'       => 'École Test',
            'categorie'           => 'actif',
            'cotisation_proposee' => 5000,
            'piece_identite'      => UploadedFile::fake()->image('cni.jpg'),
            'email'               => 'workflow.complet@example.com',
            'telephone'           => '690111222',
            'adresse'             => 'Adresse Test',
            'ville'               => 'Maroua',
        ]);

        $adherent = Adherent::where('email', 'workflow.complet@example.com')->firstOrFail();
        $this->assertEquals('en_attente', $adherent->statut_demande);

        // 2. Acceptation
        $this->actingAs($admin)->post(route('admin.adherents.accepter', $adherent));
        $this->assertEquals('acceptee', $adherent->fresh()->statut_demande);

        // 3. Paiement
        $this->actingAs($admin)->post(route('admin.adherents.payer', $adherent), [
            'mode_paiement' => 'mobile_money',
            'montant'       => 5000,
        ]);
        $this->assertEquals('a_jour', $adherent->fresh()->statut_cotisation);

        // 4. Activation
        $this->actingAs($admin)->post(route('admin.adherents.activer', $adherent));
        $adherent->refresh();

        $this->assertEquals('active', $adherent->statut_demande);
        $this->assertTrue($adherent->espace_actif);
        $this->assertTrue($adherent->peutAccederEspace());

        // 5. Connexion possible (password a été généré mais on ne le connaît pas en clair)
        // On vérifie juste que le champ password n'est plus null
        $this->assertNotNull($adherent->password);
    }

    /** SÉCURITÉ */
    public function test_non_admin_ne_peut_pas_accepter_de_demande(): void
    {
        $user = User::factory()->create(['role' => 'membre']);
        $adherent = Adherent::factory()->create(['statut_demande' => 'en_attente']);

        $this->actingAs($user)
            ->post(route('admin.adherents.accepter', $adherent))
            ->assertForbidden();

        $this->assertEquals('en_attente', $adherent->fresh()->statut_demande);
    }

    public function test_non_admin_ne_peut_pas_activer_adhésion(): void
    {
        $user = User::factory()->create(['role' => 'editeur']);
        $adherent = Adherent::factory()->create(['statut_demande' => 'acceptee']);

        $this->actingAs($user)
            ->post(route('admin.adherents.activer', $adherent))
            ->assertForbidden();

        $this->assertEquals('acceptee', $adherent->fresh()->statut_demande);
    }
}