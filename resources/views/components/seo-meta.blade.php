@props([
    'title'       => null,
    'description' => null,
    'image'       => null,
    'type'        => 'website',
    'robots'      => 'index, follow',
    'publishedAt' => null,
    'modifiedAt'  => null,
    'author'      => null,
])

@php
    $defaults = config('seo.defaults', []);
    $org      = config('seo.organization', []);

    // Titre : personnalisé > par défaut
    $pageTitle = $title
        ? $title . ' | ' . ($org['name'] ?? 'ALIDSC')
        : ($defaults['title'] ?? 'ALIDSC');

    // Description
    $pageDescription = $description ?? ($defaults['description'] ?? '');

    // Image : personnalisée > défaut
    $imagePath = $image ?? ($defaults['image'] ?? '/images/og-default.jpg');
    $imageUrl  = \Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://'])
        ? $imagePath
        : asset(ltrim($imagePath, '/'));

    // URL canonique (sans les query strings inutiles)
    $canonicalUrl = url()->current();

    // Locale
    $locale       = $defaults['locale'] ?? 'fr_FR';
    $currentLocale = app()->getLocale();
@endphp

{{-- ═══════════════════════════════════════════════════
     Balises standards
     ═══════════════════════════════════════════════════ --}}
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonicalUrl }}">

{{-- Auteur --}}
@if($author)
    <meta name="author" content="{{ $author }}">
@endif

{{-- ═══════════════════════════════════════════════════
     Open Graph (Facebook, LinkedIn, WhatsApp...)
     ═══════════════════════════════════════════════════ --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="{{ $org['name'] ?? 'ALIDSC' }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="{{ $imageUrl }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="{{ $locale }}">

@if($publishedAt)
    <meta property="article:published_time" content="{{ \Illuminate\Support\Carbon::parse($publishedAt)->toIso8601String() }}">
@endif

@if($modifiedAt)
    <meta property="article:modified_time" content="{{ \Illuminate\Support\Carbon::parse($modifiedAt)->toIso8601String() }}">
@endif

{{-- ═══════════════════════════════════════════════════
     Twitter Cards
     ═══════════════════════════════════════════════════ --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="{{ $defaults['twitter'] ?? '' }}">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
<meta name="twitter:image" content="{{ $imageUrl }}">

{{-- ═══════════════════════════════════════════════════
     Hreflang (préparation bilinguisme FR/EN)
     ═══════════════════════════════════════════════════ --}}
<link rel="alternate" hreflang="fr" href="{{ $canonicalUrl }}">
<link rel="alternate" hreflang="x-default" href="{{ $canonicalUrl }}">
{{-- Quand le bilinguisme sera actif :
<link rel="alternate" hreflang="en" href="{{ str_replace(url('/'), url('/en'), $canonicalUrl) }}">
--}}

{{-- Favicon et couleurs de thème --}}
<link rel="icon" type="image/jpeg" href="{{ asset('images/logo-alidsc.jpg') }}">
<meta name="theme-color" content="#10263B">
<meta name="msapplication-TileColor" content="#10263B">
