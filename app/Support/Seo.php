<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Seo
{
    /**
     * Retourne les données de l'organisation.
     */
    public static function organization(): array
    {
        return config('seo.organization', []);
    }

    /**
     * Construit le JSON-LD Organization.
     */
    public static function organizationJsonLd(): array
    {
        $org = static::organization();

        $data = [
            '@context'    => 'https://schema.org',
            '@type'       => 'NGO',
            'name'        => $org['name'] ?? 'ALIDSC',
            'legalName'   => $org['legal_name'] ?? null,
            'url'         => $org['url'] ?? url('/'),
            'logo'        => url($org['logo'] ?? '/images/logo-alidsc.jpg'),
            'foundingDate' => $org['founding'] ?? null,
            'email'       => $org['email'] ?? null,
            'telephone'   => $org['phone'] ?? null,
            'slogan'      => $org['slogan'] ?? null,
            'areaServed'  => $org['area_served'] ?? null,
            'knowsLanguage' => $org['knows_language'] ?? ['fr'],
        ];

        if (!empty($org['address'])) {
            $data['address'] = [
                '@type'           => 'PostalAddress',
                'streetAddress'   => $org['address']['street'] ?? null,
                'addressLocality' => $org['address']['city'] ?? null,
                'addressRegion'   => $org['address']['region'] ?? null,
                'postalCode'      => $org['address']['postal_code'] ?? null,
                'addressCountry'  => $org['address']['country'] ?? null,
            ];
        }

        if (!empty($org['geo']['latitude'])) {
            $data['geo'] = [
                '@type'     => 'GeoCoordinates',
                'latitude'  => $org['geo']['latitude'],
                'longitude' => $org['geo']['longitude'],
            ];
        }

        if (!empty($org['same_as'])) {
            $data['sameAs'] = $org['same_as'];
        }

        return array_filter($data, fn ($v) => !is_null($v));
    }

    /**
     * JSON-LD WebSite avec SearchAction.
     */
    public static function websiteJsonLd(): array
    {
        $org = static::organization();

        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'WebSite',
            'name'            => $org['name'] ?? 'ALIDSC',
            'url'             => $org['url'] ?? url('/'),
            'inLanguage'      => app()->getLocale(),
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => url('/actualites?q={search_term_string}'),
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * JSON-LD Article (actualités).
     */
    public static function articleJsonLd($actualite): array
    {
        $org = static::organization();

        $data = [
            '@context'         => 'https://schema.org',
            '@type'            => 'NewsArticle',
            'headline'         => $actualite->titre,
            'description'      => $actualite->extrait,
            'datePublished'    => ($actualite->publie_le ?? $actualite->created_at)->toIso8601String(),
            'dateModified'     => $actualite->updated_at->toIso8601String(),
            'inLanguage'       => 'fr-FR',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id'   => route('actualites.show', $actualite->slug),
            ],
            'author' => [
                '@type' => 'Organization',
                'name'  => $org['name'] ?? 'ALIDSC',
                'url'   => $org['url'] ?? url('/'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name'  => $org['name'] ?? 'ALIDSC',
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => url($org['logo'] ?? '/images/logo-alidsc.jpg'),
                ],
            ],
        ];

        if ($actualite->image) {
            $data['image'] = [
                '@type'  => 'ImageObject',
                'url'    => asset('storage/' . $actualite->image),
                'width'  => 1600,
                'height' => 900,
            ];
        }

        return array_filter($data);
    }

    /**
     * JSON-LD Event (événements).
     */
    public static function eventJsonLd($evenement): array
    {
        $org = static::organization();

        $data = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Event',
            'name'        => $evenement->titre,
            'description' => $evenement->extrait,
            'startDate'   => $evenement->date_debut->toIso8601String(),
            'endDate'     => $evenement->date_fin?->toIso8601String(),
            'eventStatus' => 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'inLanguage'  => 'fr-FR',
            'url'         => route('evenements.show', $evenement->slug),
            'organizer'   => [
                '@type' => 'Organization',
                'name'  => $org['name'] ?? 'ALIDSC',
                'url'   => $org['url'] ?? url('/'),
            ],
            'location' => [
                '@type'   => 'Place',
                'name'    => $evenement->lieu,
                'address' => [
                    '@type'           => 'PostalAddress',
                    'addressLocality' => $evenement->ville ?? 'Maroua',
                    'addressRegion'   => 'Extrême-Nord',
                    'addressCountry'  => 'CM',
                ],
            ],
        ];

        if ($evenement->image) {
            $data['image'] = asset('storage/' . $evenement->image);
        }

        if ($evenement->lien_inscription) {
            $data['offers'] = [
                '@type'         => 'Offer',
                'url'           => $evenement->lien_inscription,
                'availability'  => 'https://schema.org/InStock',
            ];
        }

        return array_filter($data);
    }

    /**
     * JSON-LD BreadcrumbList.
     */
    public static function breadcrumbJsonLd(array $items): array
    {
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(fn ($item, $i) => [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $item['name'],
                'item'     => $item['url'] ?? null,
            ])->filter(fn ($i) => !empty($i['item']))->all(),
        ];
    }

    /**
     * Rend un bloc JSON-LD prêt à insérer.
     *
     * Les flags JSON_HEX_* empêchent l'injection via </script> dans une chaîne.
     */
    public static function toScript(array $data): string
    {
        return '<script type="application/ld+json">' . json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
            | JSON_HEX_TAG
            | JSON_HEX_AMP
            | JSON_HEX_APOS
            | JSON_HEX_QUOT
        ) . '</script>';
    }
}