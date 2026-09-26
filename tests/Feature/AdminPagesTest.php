<?php

namespace Tests\Feature;

use App\Models\Adherent;
use App\Models\Actualite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class AdminPagesTest extends TestCase
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
    }

    #[DataProvider('pagesAdminSimples')]
    public function test_pages_admin_index_repondent_200(string $uri, string $label): void
    {
        $response = $this->actingAs($this->admin)->get($uri);

        $response->assertOk();
        $this->assertNotEmpty($response->getContent(), "Page [{$label}] vide");
    }

    public static function pagesAdminSimples(): array
    {
        return [
            'dashboard'         => ['/dashboard', 'Dashboard'],
            'actualites'        => ['/admin/actualites', 'Actualités'],
            'actions'           => ['/admin/actions', 'Actions'],
            'partenaires'       => ['/admin/partenaires', 'Partenaires'],
            'adherents'         => ['/admin/adherents', 'Adhérents'],
            'documents'         => ['/admin/documents', 'Documents'],
            'temoignages'       => ['/admin/temoignages', 'Témoignages'],
            'evenements'        => ['/admin/evenements', 'Événements'],
            'albums'            => ['/admin/albums', 'Albums'],
            'ressources'        => ['/admin/ressources', 'Ressources'],
            'messages'          => ['/admin/messages', 'Messages'],
        ];
    }

    #[DataProvider('pagesAdminCreate')]
    public function test_pages_admin_create_repondent_200(string $uri, string $label): void
    {
        $response = $this->actingAs($this->admin)->get($uri);

        $response->assertOk();
        $this->assertNotEmpty($response->getContent(), "Page création [{$label}] vide");
    }

    public static function pagesAdminCreate(): array
    {
        return [
            'actualites.create'  => ['/admin/actualites/create', 'Actualité'],
            'actions.create'     => ['/admin/actions/create', 'Action'],
            'partenaires.create' => ['/admin/partenaires/create', 'Partenaire'],
            'documents.create'   => ['/admin/documents/create', 'Document'],
            'temoignages.create' => ['/admin/temoignages/create', 'Témoignage'],
            'evenements.create'  => ['/admin/evenements/create', 'Événement'],
            'albums.create'      => ['/admin/albums/create', 'Album'],
            'ressources.create'  => ['/admin/ressources/create', 'Ressource'],
        ];
    }

    public function test_page_edit_actualite_repond_200(): void
    {
        $actu = Actualite::factory()->create();

        $this->actingAs($this->admin)
            ->get("/admin/actualites/{$actu->id}/edit")
            ->assertOk();
    }

    public function test_page_show_adherent_repond_200(): void
    {
        $adherent = Adherent::factory()->create();

        $this->actingAs($this->admin)
            ->get("/admin/adherents/{$adherent->id}")
            ->assertOk();
    }

    public function test_page_edit_adherent_repond_200(): void
    {
        $adherent = Adherent::factory()->create();

        $this->actingAs($this->admin)
            ->get("/admin/adherents/{$adherent->id}/edit")
            ->assertOk();
    }

    public function test_export_adherents_renvoie_csv(): void
    {
        Adherent::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get('/admin/export/adherents');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_export_messages_renvoie_csv(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/export/messages');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_non_admin_ne_peut_pas_acceder_aux_pages_admin(): void
    {
        $user = User::factory()->create(['role' => 'membre']);

        $this->actingAs($user)
            ->get('/admin/actualites')
            ->assertForbidden();
    }

    public function test_visiteur_redirige_vers_login(): void
    {
        $this->get('/admin/actualites')
            ->assertRedirect(route('login'));
    }
}