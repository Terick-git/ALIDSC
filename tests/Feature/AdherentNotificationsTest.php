<?php

namespace Tests\Feature;

use App\Models\Adherent;
use App\Models\Document;
use App\Models\Evenement;
use App\Models\User;
use App\Notifications\NouveauDocumentAdherent;
use App\Notifications\NouvelEvenementAdherent;
use App\Notifications\RappelCotisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdherentNotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        Storage::fake('private');
        Storage::fake('public');
    }

    // ═══════════════════════════════════════════════════════
    // DOCUMENTS
    // ═══════════════════════════════════════════════════════

    public function test_adherents_notifies_quand_document_actif_est_cree(): void
    {
        Notification::fake();

        $adherents = Adherent::factory()->count(3)->actif()->create();
        $inactif   = Adherent::factory()->create(['espace_actif' => false, 'statut_demande' => 'en_attente']);

        $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'titre'       => 'Rapport annuel 2026',
            'description' => 'Bilan des actions de l\'année',
            'categorie'   => 'rapports',
            'fichier'     => \Illuminate\Http\UploadedFile::fake()->create('rapport.pdf', 500, 'application/pdf'),
            'actif'       => 1,
        ]);

        // Les 3 adhérents actifs ont reçu la notification
        foreach ($adherents as $adherent) {
            Notification::assertSentTo($adherent, NouveauDocumentAdherent::class);
        }

        // L'adhérent inactif n'a PAS reçu
        Notification::assertNotSentTo($inactif, NouveauDocumentAdherent::class);
    }

    public function test_adherents_pas_notifies_quand_document_inactif(): void
    {
        Notification::fake();

        Adherent::factory()->count(2)->actif()->create();

        $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'titre'     => 'Brouillon interne',
            'categorie' => 'general',
            'fichier'   => \Illuminate\Http\UploadedFile::fake()->create('doc.pdf', 500, 'application/pdf'),
            // pas de checkbox actif
        ]);

        Notification::assertNothingSent();
    }

    // ═══════════════════════════════════════════════════════
    // ÉVÉNEMENTS
    // ═══════════════════════════════════════════════════════

    public function test_adherents_notifies_quand_evenement_publie_des_la_creation(): void
    {
        Notification::fake();

        $adherents = Adherent::factory()->count(2)->actif()->create();

        $this->actingAs($this->admin)->post(route('admin.evenements.store'), [
            'titre'      => 'Causerie éducative',
            'extrait'    => 'Une rencontre avec les familles',
            'contenu'    => 'Détails à venir...',
            'date_debut' => now()->addDays(15)->format('Y-m-d\TH:i'),
            'lieu'       => 'Maroua',
            'statut'     => 'publie',
        ]);

        foreach ($adherents as $adherent) {
            Notification::assertSentTo($adherent, NouvelEvenementAdherent::class);
        }
    }

    public function test_adherents_pas_notifies_quand_evenement_brouillon(): void
    {
        Notification::fake();

        Adherent::factory()->count(2)->actif()->create();

        $this->actingAs($this->admin)->post(route('admin.evenements.store'), [
            'titre'      => 'Événement brouillon',
            'extrait'    => 'En préparation',
            'contenu'    => 'Pas encore prêt',
            'date_debut' => now()->addDays(15)->format('Y-m-d\TH:i'),
            'lieu'       => 'Maroua',
            'statut'     => 'brouillon',
        ]);

        Notification::assertNothingSent();
    }

    public function test_adherents_notifies_quand_evenement_passe_a_publie(): void
    {
        Notification::fake();

        $adherents = Adherent::factory()->count(2)->actif()->create();

        $evenement = Evenement::create([
            'titre'      => 'Table ronde',
            'slug'       => 'table-ronde',
            'extrait'    => 'Extrait',
            'contenu'    => 'Contenu',
            'date_debut' => now()->addDays(20),
            'lieu'       => 'Maroua',
            'statut'     => 'brouillon',
        ]);

        $this->actingAs($this->admin)->put(route('admin.evenements.update', $evenement), [
            'titre'      => 'Table ronde',
            'extrait'    => 'Extrait',
            'contenu'    => 'Contenu',
            'date_debut' => now()->addDays(20)->format('Y-m-d\TH:i'),
            'lieu'       => 'Maroua',
            'statut'     => 'publie',
        ]);

        foreach ($adherents as $adherent) {
            Notification::assertSentTo($adherent, NouvelEvenementAdherent::class);
        }
    }

    // ═══════════════════════════════════════════════════════
    // RAPPEL COTISATION (commande)
    // ═══════════════════════════════════════════════════════

    public function test_commande_rappel_envoie_aux_adherents_proches_expiration(): void
    {
        Notification::fake();

        // Adhérent dont la cotisation expire dans 15 jours
        $proche = Adherent::factory()->actif()->create([
            'date_dernier_paiement' => now()->subDays(350), // expire dans 15j
        ]);

        // Adhérent dont la cotisation expire dans 6 mois
        $loin = Adherent::factory()->actif()->create([
            'date_dernier_paiement' => now()->subDays(180),
        ]);

        $this->artisan('alidsc:rappel-cotisation', ['--jours' => 30])
            ->assertSuccessful();

        Notification::assertSentTo($proche, RappelCotisation::class);
        Notification::assertNotSentTo($loin, RappelCotisation::class);
    }

    public function test_commande_rappel_marque_adherent_comme_notifie(): void
    {
        Notification::fake();

        $adherent = Adherent::factory()->actif()->create([
            'date_dernier_paiement' => now()->subDays(350),
        ]);

        $this->assertNull($adherent->rappel_cotisation_envoye_le);

        $this->artisan('alidsc:rappel-cotisation', ['--jours' => 30])
            ->assertSuccessful();

        $adherent->refresh();
        $this->assertNotNull($adherent->rappel_cotisation_envoye_le);
    }

    public function test_commande_rappel_idempotente(): void
    {
        Notification::fake();

        $adherent = Adherent::factory()->actif()->create([
            'date_dernier_paiement' => now()->subDays(350),
        ]);

        // 1re exécution → envoie
        $this->artisan('alidsc:rappel-cotisation', ['--jours' => 30])
            ->assertSuccessful();

        Notification::assertSentToTimes($adherent, RappelCotisation::class, 1);

        // 2e exécution immédiate → ne renvoie pas
        $this->artisan('alidsc:rappel-cotisation', ['--jours' => 30])
            ->assertSuccessful();

        Notification::assertSentToTimes($adherent, RappelCotisation::class, 1);
    }

    public function test_commande_rappel_dry_run_nenvoie_rien(): void
    {
        Notification::fake();

        Adherent::factory()->actif()->create([
            'date_dernier_paiement' => now()->subDays(350),
        ]);

        $this->artisan('alidsc:rappel-cotisation', [
            '--jours'   => 30,
            '--dry-run' => true,
        ])->assertSuccessful();

        Notification::assertNothingSent();
    }
}