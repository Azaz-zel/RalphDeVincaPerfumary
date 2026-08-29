<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        // yieldContent returns already-escaped output; decode it first so the
        // {{ }} below doesn't double-escape apostrophes into &amp;#039;.
        $decode = fn ($value) => html_entity_decode(trim($value), ENT_QUOTES, 'UTF-8');

        $pageTitle = $decode($__env->yieldContent('title'));
        $fullTitle = $pageTitle ? $pageTitle.' - '.config('app.name') : config('app.name');
        $pageDescription = $decode($__env->yieldContent('description'))
            ?: 'Explore perfumes, fragrance houses, and the raw materials behind every scent — a fragrance encyclopedia by Ralph de Vinca Perfumary.';
    @endphp

    <title>{{ $fullTitle }}</title>

    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">

    @hasSection('robots')
        <meta name="robots" content="@yield('robots')">
    @endif

    {{-- Social sharing (WhatsApp, Instagram, X, Facebook) --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $fullTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-stone-50 text-stone-900 dark:bg-[#1B1A17] dark:text-stone-100 transition-colors duration-300">

    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

</body>

</html>