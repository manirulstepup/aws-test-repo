<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php($site = \App\Models\SiteSetting::current())
    @php($faviconUrl = ($site->favicon_url ?? asset('llb_favicon.png')).'?v='.($site->updated_at?->timestamp ?? 1))
    @php($siteName = $site->translated('name')[app()->getLocale()] ?? config('app.name', 'AinPath'))
    <link rel="icon" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    @php($siteSlogan = $site->translated('slogan')[app()->getLocale()] ?? '')
    @php($shareImage = $site->logo_url ? url($site->logo_url) : asset('llb.jpg'))
    <title inertia>{{ $siteName }}</title>
    <meta name="description" content="{{ $siteSlogan }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $siteName }}">
    <meta property="og:description" content="{{ $siteSlogan }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $shareImage }}">
    <meta property="og:image:alt" content="{{ $siteName }}">
    <meta property="og:locale" content="{{ app()->getLocale() === 'bn' ? 'bn_BD' : 'en_US' }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $siteName }}">
    <meta name="twitter:description" content="{{ $siteSlogan }}">
    <meta name="twitter:image" content="{{ $shareImage }}">

    {{-- React Fast Refresh preamble — must come before the Vite tag below,
             or the React plugin throws "can't detect preamble" in dev. --}}
    @viteReactRefresh
    @vite(['resources/js/app.tsx', 'resources/css/app.css'])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    <!-- Inertia mounts the React app right here -->
    @inertia
</body>

</html>
