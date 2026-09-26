<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Actualite;
use App\Models\Album;
use App\Models\Evenement;
use App\Models\Ressource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $ttl = config('seo.sitemap.cache_ttl', 3600);

        $xml = Cache::remember('sitemap.xml', $ttl, function () {
            return $this->generateXml();
        });

        return response($xml, 200, [
            'Content-Type'  => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=' . $ttl,
        ]);
    }

    protected function generateXml(): string
    {
        $urls = [];

        // ─── Pages statiques ────────────────────────────────
        $urls = array_merge($urls, $this->staticPages());

        // ─── Contenus dynamiques ────────────────────────────
        $urls = array_merge($urls, $this->actualites());
        $urls = array_merge($urls, $this->actions());
        $urls = array_merge($urls, $this->evenements());
        $urls = array_merge($urls, $this->albums());
        $urls = array_merge($urls, $this->ressources());

        return $this->buildXml($urls);
    }

    protected function staticPages(): array
    {
        $pages = [
            ['route' => 'home',                    'priority' => '1.0', 'freq' => 'daily'],
            ['route' => 'a-propos',                'priority' => '0.9', 'freq' => 'monthly'],
            ['route' => 'objectifs',               'priority' => '0.9', 'freq' => 'monthly'],
            ['route' => 'actions',                 'priority' => '0.9', 'freq' => 'weekly'],
            ['route' => 'impact',                  'priority' => '0.9', 'freq' => 'weekly'],
            ['route' => 'actualites',              'priority' => '0.9', 'freq' => 'daily'],
            ['route' => 'evenements',              'priority' => '0.8', 'freq' => 'weekly'],
            ['route' => 'galerie',                 'priority' => '0.7', 'freq' => 'weekly'],
            ['route' => 'ressources',              'priority' => '0.8', 'freq' => 'weekly'],
            ['route' => 'partenaires',             'priority' => '0.7', 'freq' => 'monthly'],
            ['route' => 'adherer',                 'priority' => '0.9', 'freq' => 'monthly'],
            ['route' => 'contact',                 'priority' => '0.8', 'freq' => 'monthly'],
            ['route' => 'don',                     'priority' => '0.8', 'freq' => 'monthly'],
            ['route' => 'legal.mentions',          'priority' => '0.3', 'freq' => 'yearly'],
            ['route' => 'legal.confidentialite',   'priority' => '0.3', 'freq' => 'yearly'],
        ];

        return array_map(fn ($p) => [
            'loc'        => route($p['route']),
            'lastmod'    => now()->toAtomString(),
            'changefreq' => $p['freq'],
            'priority'   => $p['priority'],
        ], $pages);
    }

    protected function actualites(): array
    {
        $urls = [];
        Actualite::where('publie', true)
            ->where('interne', false)
            ->select('slug', 'updated_at', 'image')
            ->chunk(500, function ($items) use (&$urls) {
                foreach ($items as $item) {
                    $entry = [
                        'loc'        => route('actualites.show', $item->slug),
                        'lastmod'    => $item->updated_at->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority'   => '0.7',
                    ];
                    if ($item->image) {
                        $entry['image'] = asset('storage/' . $item->image);
                    }
                    $urls[] = $entry;
                }
            });
        return $urls;
    }

    protected function actions(): array
    {
        $urls = [];
        Action::where('actif', true)
            ->select('slug', 'updated_at', 'image')
            ->chunk(500, function ($items) use (&$urls) {
                foreach ($items as $item) {
                    $entry = [
                        'loc'        => route('actions.show', $item->slug),
                        'lastmod'    => $item->updated_at->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority'   => '0.7',
                    ];
                    if ($item->image) {
                        $entry['image'] = asset('storage/' . $item->image);
                    }
                    $urls[] = $entry;
                }
            });
        return $urls;
    }

    protected function evenements(): array
    {
        $urls = [];
        Evenement::where('statut', 'publie')
            ->select('slug', 'updated_at', 'image')
            ->chunk(500, function ($items) use (&$urls) {
                foreach ($items as $item) {
                    $entry = [
                        'loc'        => route('evenements.show', $item->slug),
                        'lastmod'    => $item->updated_at->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority'   => '0.6',
                    ];
                    if ($item->image) {
                        $entry['image'] = asset('storage/' . $item->image);
                    }
                    $urls[] = $entry;
                }
            });
        return $urls;
    }

    protected function albums(): array
    {
        $urls = [];
        Album::where('publie', true)
            ->select('slug', 'updated_at', 'couverture')
            ->chunk(500, function ($items) use (&$urls) {
                foreach ($items as $item) {
                    $entry = [
                        'loc'        => route('galerie.show', $item->slug),
                        'lastmod'    => $item->updated_at->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority'   => '0.6',
                    ];
                    if ($item->couverture) {
                        $entry['image'] = asset('storage/' . $item->couverture);
                    }
                    $urls[] = $entry;
                }
            });
        return $urls;
    }

    protected function ressources(): array
    {
        $urls = [];
        Ressource::where('actif', true)
            ->where('public', true)
            ->select('slug', 'updated_at', 'couverture')
            ->chunk(500, function ($items) use (&$urls) {
                foreach ($items as $item) {
                    $entry = [
                        'loc'        => route('ressources.show', $item->slug),
                        'lastmod'    => $item->updated_at->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority'   => '0.6',
                    ];
                    if ($item->couverture) {
                        $entry['image'] = asset('storage/' . $item->couverture);
                    }
                    $urls[] = $entry;
                }
            });
        return $urls;
    }

    protected function buildXml(array $urls): string
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url['loc'], ENT_XML1) . "</loc>\n";
            $xml .= "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
            $xml .= "    <changefreq>" . $url['changefreq'] . "</changefreq>\n";
            $xml .= "    <priority>" . $url['priority'] . "</priority>\n";

            if (!empty($url['image'])) {
                $xml .= "    <image:image>\n";
                $xml .= "      <image:loc>" . htmlspecialchars($url['image'], ENT_XML1) . "</image:loc>\n";
                $xml .= "    </image:image>\n";
            }

            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';
        return $xml;
    }
}