<?php

namespace Tests\Feature;

use App\Models\Adherent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class AdherentPagesTest extends TestCase
{
    use RefreshDatabase;

    protected Adherent $adherent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adherent = Adherent::factory()->actif()->create();
    }

    #[DataProvider('pagesAdherent')]
    public function test_pages_adherent_repondent_200(string $uri, string $label): void
    {
        $response = $this->actingAs($this->adherent, 'adherent')->get($uri);

        $response->assertOk();
        $this->assertNotEmpty($response->getContent(), "Page [{$label}] vide");
    }

    public static function pagesAdherent(): array
    {
        return [
            'dashboard'   => ['/adherent/dashboard', 'Dashboard adhérent'],
            'profil'      => ['/adherent/profil', 'Profil'],
            'documents'   => ['/adherent/documents', 'Documents'],
            'actualites'  => ['/adherent/actualites', 'Actualités internes'],
        ];
    }

    public function test_actualites_internes_visibles_pour_adherent(): void
    {
        $actu = \App\Models\Actualite::factory()->create([
            'titre'   => 'Article Interne Test',
            'publie'  => true,
            'interne' => true,
        ]);

        $response = $this->actingAs($this->adherent, 'adherent')
            ->get('/adherent/actualites');

        $response->assertOk();
        $response->assertSee('Article Interne Test');
    }

    public function test_actualite_interne_accessible_en_detail(): void
    {
        $actu = \App\Models\Actualite::factory()->create([
            'publie'  => true,
            'interne' => true,
        ]);

        $this->actingAs($this->adherent, 'adherent')
            ->get("/adherent/actualites/{$actu->id}")
            ->assertOk();
    }

    public function test_actualite_publique_non_accessible_dans_espace_adherent(): void
    {
        $actu = \App\Models\Actualite::factory()->create([
            'publie'  => true,
            'interne' => false,
        ]);

        $this->actingAs($this->adherent, 'adherent')
            ->get("/adherent/actualites/{$actu->id}")
            ->assertNotFound();
    }
}