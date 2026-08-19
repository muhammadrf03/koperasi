@props([
    'code' => '404',
    'title' => 'Page not found',
    'description' => 'The page you are looking for does not exist or has been moved.',
    'homeHref' => '/',
    'homeLabel' => 'Go home',
    'browseHref' => '/',
    'browseLabel' => 'Browse pages',
])

@php
    $assetsReady = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $code }} - {{ config('app.name', 'Koperasi') }}</title>

    <link rel="icon" type="image/png" href="/images/logo.png">

    {{-- Dark Mode: Terapkan kelas 'dark' sebelum render (cegah flash) --}}
    <script>
        (function () {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    {{-- Konfigurasi untuk komponen React --}}
    <script>
        window.__ERROR_PAGE_CONFIG = {
            code: @json($code),
            title: @json($title),
            description: @json($description),
            homeHref: @json($homeHref),
            homeLabel: @json($homeLabel),
            browseHref: @json($browseHref),
            browseLabel: @json($browseLabel),
        };
    </script>

    @if ($assetsReady)
        {!! Vite::reactRefresh() !!}
        @vite(['resources/css/app.css', 'resources/js/error-page.jsx'])
    @else
        <style>
            html, body { margin: 0; height: 100%; }
        </style>
    @endif
</head>
<body class="flex min-h-screen items-center justify-center bg-background font-sans antialiased">
    <div id="root" class="w-full">
        @if (!$assetsReady)
            <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;text-align:center;font-family:system-ui,sans-serif;gap:8px;padding:24px;">
                <div style="font-size:96px;font-weight:700;font-family:ui-monospace,monospace;line-height:1;">{{ $code }}</div>
                <div style="font-size:20px;font-weight:600;">{{ $title }}</div>
                <div style="color:#64748b;max-width:420px;">{{ $description }}</div>
            </div>
        @endif
    </div>
</body>
</html>
