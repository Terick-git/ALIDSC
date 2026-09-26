<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Informations de l'organisation
    |--------------------------------------------------------------------------
    */

    'organization' => [
        'name'       => 'ALIDSC',
        'legal_name' => 'Association de Lutte contre les Inégalités et les Décrochages Scolaires au Cameroun',
        'url'        => env('APP_URL', 'https://alidsc.org'),
        'logo'       => '/images/logo-alidsc.jpg',
        'founding'   => '2022-01-01',
        'email'      => 'contact@alidsc.org',
        'phone'      => '+237696506940',
        'same_as'    => [
            // 'https://www.facebook.com/alidsc',
            // 'https://www.linkedin.com/company/alidsc',
        ],
        'address' => [
            'street'      => 'Pitoire (Ancien décanat), derrière la CNPS',
            'city'        => 'Maroua',
            'region'      => 'Extrême-Nord',
            'country'     => 'CM',
            'postal_code' => '',
        ],
        'geo' => [
            'latitude'  => '10.5956',
            'longitude' => '14.3247',
        ],
        'area_served'      => 'CM',
        'knows_language'   => ['fr', 'en'],
        'slogan'           => 'Étudiez, Rêvez, Réussissez.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Valeurs par défaut
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'title'       => 'ALIDSC — Étudiez, Rêvez, Réussissez',
        'description' => 'L\'ALIDSC agit au Cameroun pour prévenir le décrochage scolaire et accompagner les élèves vers la réussite.',
        'image'       => '/images/og-default.jpg',
        'locale'      => 'fr_FR',
        'twitter'     => '@alidsc',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sitemap
    |--------------------------------------------------------------------------
    */

    'sitemap' => [
        'cache_ttl'     => 3600, // 1 heure
        'default_freq'  => 'monthly',
        'default_prio'  => '0.5',
    ],

];
