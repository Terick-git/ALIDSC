<?php

namespace Tests\Feature;

use App\Models\Adherent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AdminSidebarTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        Cache::flush(); // Évite les interférences entre tests
    }

    public function test_sidebar_affiche_badge_messages_non_lus(): void
    {
        Message::factory()->count(3)->create(['lu' => false]);
        Message::factory()->create(['lu' => true]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertOk();
        // Le badge est rendu par Alpine mais la valeur initiale vient du serveur
        $response->assertSee('messages_non_lus:     3', false);
    }

    public function test_sidebar_affiche_badge_adherents_en_attente(): void
    {
        Adherent::factory()->count(5)->create(['statut_demande' => 'en_attente']);
        Adherent::factory()->create(['statut_demande' => 'active']);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('adherents_en_attente: 5', false);
    }

    public function test_sidebar_badges_a_zero_quand_aucun(): void
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('messages_non_lus:     0', false);
        $response->assertSee('adherents_en_attente: 0', false);
    }

    public function test_endpoint_sidebar_counts_retourne_json(): void
    {
        Message::factory()->count(2)->create(['lu' => false]);
        Adherent::factory()->count(4)->create(['statut_demande' => 'en_attente']);

        $response = $this->actingAs($this->admin)->getJson(route('admin.sidebar-counts'));

        $response->assertOk();
        $response->assertJson([
            'messages_non_lus'     => 2,
            'adherents_en_attente' => 4,
        ]);
    }

    public function test_endpoint_sidebar_counts_bloque_non_admin(): void
    {
        $user = User::factory()->create(['role' => 'membre']);

        $this->actingAs($user)
            ->getJson(route('admin.sidebar-counts'))
            ->assertForbidden();
    }

    public function test_endpoint_sidebar_counts_bloque_visiteur(): void
    {
        $this->getJson(route('admin.sidebar-counts'))
            ->assertUnauthorized();
    }

    public function test_cache_est_invalide_apres_une_ecriture(): void
    {
        Message::factory()->count(3)->create(['lu' => false]);

        // Premier hit : la valeur est calculée et mise en cache
        $response1 = $this->actingAs($this->admin)->getJson(route('admin.sidebar-counts'));
        $response1->assertJson(['messages_non_lus' => 3]);

        // Nouveau message → l'observer invalide le cache
        Message::factory()->create(['lu' => false]);

        // Le compteur doit immédiatement passer à 4
        $response2 = $this->actingAs($this->admin)->getJson(route('admin.sidebar-counts'));
        $response2->assertJson(['messages_non_lus' => 4]);
    }
}