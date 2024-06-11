<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Numo ERP') }}</title>
    {{-- favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;500;600;700&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"
        rel="stylesheet">
    {{-- seo and og meta  --}}

    <!-- Scripts -->
    @routes
    @php
        $split = explode('::', $page['component']);

        if (count($split) === 1) {
            $currentPage = "resources/js/Pages/{$page['component']}.vue";
        } else {
            $module = $split[0];
            $modulePage = $split[1];

            $currentPage = "modules/{$module}/resources/js/Pages/{$modulePage}.vue";
        }
    @endphp
    @vite(['resources/js/app.js', $currentPage])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
