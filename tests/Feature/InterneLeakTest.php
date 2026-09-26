<?php

namespace Tests\Feature;

use App\Models\Actualite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InterneLeakTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_article_interne_n_apparait_pas_sur_la_liste_publique(): void
    {
        $auteur = User::factory()->create();

        Actualite::factory()->create([
            'titre' => 'Article Public',
            'slug' => 'article-public',
            'publie' => true,
            'interne' => false,
            'user_id' => $auteur->id,
        ]);

        Actualite::factory()->create([
            'titre' => 'Article Secret Interne',
            'slug' => 'article-secret',
            'publie' => true,
            'interne' => true,
            'user_id' => $auteur->id,
        ]);

        $reponse = $this->get(route('actualites'));

        $reponse->assertSee('Article Public');
        $reponse->assertDontSee('Article Secret Interne');
    }

    public function test_un_article_interne_n_apparait_pas_sur_la_home(): void
    {
        $auteur = User::factory()->create();

        Actualite::factory()->create([
            'titre' => 'Secret Home',
            'publie' => true,
            'interne' => true,
            'user_id' => $auteur->id,
        ]);

        $reponse = $this->get(route('home'));
        $reponse->assertDontSee('Secret Home');
    }

    public function test_un_article_interne_renvoie_404_en_acces_direct(): void
    {
        $auteur = User::factory()->create();

        Actualite::factory()->create([
            'titre' => 'Article Interne',
            'slug' => 'article-interne-secret',
            'publie' => true,
            'interne' => true,
            'user_id' => $auteur->id,
        ]);

        $reponse = $this->get('/actualites/article-interne-secret');
        $reponse->assertNotFound();
    }

    public function test_un_article_brouillon_n_apparait_pas_publiquement(): void
    {
        $auteur = User::factory()->create();

        Actualite::factory()->create([
            'titre' => 'Brouillon Test',
            'publie' => false,
            'interne' => false,
            'user_id' => $auteur->id,
        ]);

        $reponse = $this->get(route('actualites'));
        $reponse->assertDontSee('Brouillon Test');
    }
}
