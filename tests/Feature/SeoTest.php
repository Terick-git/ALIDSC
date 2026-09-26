<?php

namespace Tests\Feature;

use App\Models\Actualite;
use App\Models\Evenement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_contient_json_ld_organization(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@type":"NGO"', false);
        $response->assertSee('ALIDSC', false);
    }

    public function test_home_contient_json_ld_website(): void
    {
        $response = $this->get('/');

        $response->assertSee('"@type":"WebSite"', false);
        $response->assertSee('SearchAction', false);
    }

    public function test_home_contient_meta_og(): void
    {
        $response = $this->get('/');

        $response->assertSee('og:title', false);
        $response->assertSee('og:description', false);
        $response->assertSee('og:image', false);
        $response->assertSee('og:type', false);
    }

    public function test_home_contient_twitter_card(): void
    {
        $response = $this->get('/');

        $response->assertSee('twitter:card', false);
        $response->assertSee('summary_large_image', false);
    }

    public function test_home_contient_canonical(): void
    {
        $response = $this->get('/');

        $response->assertSee('rel="canonical"', false);
    }

    public function test_actualite_contient_json_ld_article(): void
    {
        $auteur = User::factory()->create();
        $actu = Actualite::factory()->create([
            'titre'   => 'Test SEO Article',
            'slug'    => 'test-seo-article',
            'publie'  => true,
            'interne' => false,
            'user_id' => $auteur->id,
        ]);

        $response = $this->get(route('actualites.show', $actu->slug));

        $response->assertOk();
        $response->assertSee('"@type":"NewsArticle"', false);
        $response->assertSee('Test SEO Article', false);
        $response->assertSee('datePublished', false);
        $response->assertSee('dateModified', false);
    }

    public function test_actualite_contient_breadcrumb(): void
    {
        $auteur = User::factory()->create();
        $actu = Actualite::factory()->create([
            'slug'    => 'test-breadcrumb',
            'publie'  => true,
            'interne' => false,
            'user_id' => $auteur->id,
        ]);

        $response = $this->get(route('actualites.show', $actu->slug));

        $response->assertSee('"@type":"BreadcrumbList"', false);
        $response->assertSee('"position":1', false);
    }

    public function test_evenement_contient_json_ld_event(): void
    {
        $evenement = Evenement::create([
            'titre'      => 'Test Event SEO',
            'slug'       => 'test-event-seo',
            'extrait'    => 'Extrait de test',
            'contenu'    => 'Contenu de test',
            'date_debut' => now()->addDays(10),
            'lieu'       => 'Maroua',
            'ville'      => 'Maroua',
            'statut'     => 'publie',
        ]);

        $response = $this->get(route('evenements.show', $evenement->slug));

        $response->assertOk();
        $response->assertSee('"@type":"Event"', false);
        $response->assertSee('EventScheduled', false);
        $response->assertSee('OfflineEventAttendanceMode', false);
    }

    public function test_sitemap_est_un_xml_valide(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
        $this->assertGreaterThan(10, count($xml->url));
    }

    public function test_sitemap_contient_urls_dynamiques(): void
    {
        $auteur = User::factory()->create();
        Actualite::factory()->create([
            'slug'    => 'sitemap-test-actu',
            'publie'  => true,
            'interne' => false,
            'user_id' => $auteur->id,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertSee('sitemap-test-actu', false);
    }

    public function test_hreflang_est_present(): void
    {
        $response = $this->get('/');

        $response->assertSee('hreflang="fr"', false);
        $response->assertSee('hreflang="x-default"', false);
    }

    public function test_meta_description_presente_sur_page_actualite(): void
    {
        $auteur = User::factory()->create();
        $actu = Actualite::factory()->create([
            'slug'    => 'test-meta-desc',
            'extrait' => 'Ceci est un extrait unique',
            'publie'  => true,
            'interne' => false,
            'user_id' => $auteur->id,
        ]);

        $response = $this->get(route('actualites.show', $actu->slug));

        $response->assertSee('name="description"', false);
        $response->assertSee('Ceci est un extrait unique', false);
    }
}