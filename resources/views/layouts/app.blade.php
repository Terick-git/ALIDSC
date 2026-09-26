<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <x-seo-meta
        :title="View::getSection('title')"
        :description="View::getSection('description')"
        :image="View::getSection('og_image')"
        :type="View::getSection('og_type') ?: 'website'"
        :published-at="View::getSection('og_published_at')"
        :modified-at="View::getSection('og_modified_at')"
        :author="View::getSection('og_author')"
    />

    <x-json-ld :data="\App\Support\Seo::organizationJsonLd()" />
    @stack('json-ld')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/alidsc.css', 'resources/js/alidsc.js'])

    @stack('styles')
</head>
<body>

    @include('components.site-header')
    @include('components.mobile-menu')

    <main>
        @yield('content')
    </main>

    @include('components.site-footer')

    @stack('scripts')
</body>
</html>
