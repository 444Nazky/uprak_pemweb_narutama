<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') &middot; {{ config('app.name') }}</title>

    <link rel="preload" href="{{ asset('fonts/plus-jakarta-sans.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="ka-auth">
        <aside class="ka-auth-aside">
            <a href="{{ route('home') }}" class="ka-brand">
                <span class="ka-brand-mark">
                    <x-icon name="inbox" />
                </span>
                <span>
                    Kotak Aspirasi
                    <small>SMK Plus Pelita Nusantara</small>
                </span>
            </a>

            <div class="ka-auth-quote">
                <h2>Satu laporan yang jelas lebih mudah ditindaklanjuti.</h2>
                <p>Tuliskan lokasi dan masalahnya dengan runtut. Pihak sekolah akan mencatat, memproses, dan membalas melalui akun Anda.</p>

                <div class="ka-auth-points">
                    <div class="ka-auth-point">
                        <x-icon name="shield-lock" />
                        Laporan hanya dapat dibuka oleh pemilik akun dan admin.
                    </div>
                    <div class="ka-auth-point">
                        <x-icon name="clock-history" />
                        Status penanganan diperbarui bertahap.
                    </div>
                    <div class="ka-auth-point">
                        <x-icon name="chat-left-text" />
                        Tanggapan resmi tampil pada halaman laporan.
                    </div>
                </div>
            </div>

            <p style="margin:0;font-size:12px;color:rgba(255,255,255,0.55);">
                &copy; {{ date('Y') }} SMK Plus Pelita Nusantara Bogor
            </p>
        </aside>

        <main class="ka-auth-main">
            <div class="ka-auth-card">
                <a href="{{ route('home') }}" class="ka-auth-back">
                    <x-icon name="arrow-left" />
                    Kembali ke beranda
                </a>

                @include('partials.flash')

                @yield('content')
            </div>
        </main>
    </div>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
