<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Kotak Aspirasi Sekolah SMK Plus Pelita Nusantara Bogor. Sampaikan laporan sarana dan prasarana secara terstruktur dan pantau penanganannya.">
    <title>@yield('title', 'Beranda') &middot; {{ config('app.name') }}</title>

    <link rel="preload" href="{{ asset('fonts/plus-jakarta-sans.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.navbar')

    <main class="ka-main">
        <div class="{{ Auth::check() && Auth::user()->isAdmin() ? 'container' : 'ka-shell' }}">
            @include('partials.flash')
            @yield('content')
        </div>
    </main>

    @include('partials.footer')

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
