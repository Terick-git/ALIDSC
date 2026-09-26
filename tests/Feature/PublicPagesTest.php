<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('pagesStatiques')]
    public function test_pages_publiques_repondent_200(string $routeName, string $label): void
    {
        $response = $this->get(route($routeName));

        $response->assertOk();
        $this->assertNotEmpty($response->getContent(), "La page [{$label}] est vide");
    }

    public static function pagesStatiques(): array
    {
        return [
            'accueil'              => ['home', 'Accueil'],
            'manifeste'            => ['a-propos', 'Manifeste'],
            'objectifs'            => ['objectifs', 'Objectifs'],
            'actions'              => ['actions', 'Nos actions'],
            'impact'               => ['impact', 'Impact'],
            'actualites'           => ['actualites', 'Journal'],
            'evenements'           => ['evenements', 'Événements'],
            'galerie'              => ['galerie', 'Galerie'],
            'ressources'           => ['ressources', 'Ressources'],
            'partenaires'          => ['partenaires', 'Partenaires'],
            'adherer'              => ['adherer', 'Adhérer'],
            'don'                  => ['don', 'Don'],
            'contact'              => ['contact', 'Contact'],
            'mentions'             => ['legal.mentions', 'Mentions légales'],
            'confidentialite'      => ['legal.confidentialite', 'Confidentialité'],
        ];
    }

    public function test_sitemap_est_du_xml_valide(): void
    {
        $response = $this->get(route('sitemap'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml, 'Le sitemap n\'est pas un XML valide');
        $this->assertGreaterThan(0, count($xml->url));
    }

    public function test_actualite_publiee_accessible(): void
    {
        $actu = \App\Models\Actualite::factory()->create([
            'slug'    => 'test-actu-publique',
            'publie'  => true,
            'interne' => false,
        ]);

        $this->get(route('actualites.show', $actu->slug))->assertOk();
    }

    public function test_actualite_brouillon_renvoie_404(): void
    {
        $actu = \App\Models\Actualite::factory()->create([
            'slug'   => 'test-actu-brouillon',
            'publie' => false,
        ]);

        $this->get(route('actualites.show', $actu->slug))->assertNotFound();
    }
}