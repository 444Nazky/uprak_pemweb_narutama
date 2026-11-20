@php
    $user = Auth::user();
    $isAdmin = $user?->isAdmin();
@endphp

<header class="ka-nav">
    <div class="{{ $isAdmin ? 'container' : 'ka-shell' }} ka-nav-inner">
        <a href="{{ route('home') }}" class="ka-brand">
            <span class="ka-brand-mark">
                <x-icon name="inbox" />
            </span>
            <span>
                Kotak Aspirasi
                <small>SMK Plus Pelita Nusantara</small>
            </span>
        </a>

        <nav class="ka-nav-links" aria-label="Navigasi utama">
            @auth
                @if ($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="ka-navlink {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                        <x-icon name="grid-1x2" />
                        Dashboard
                    </a>
                    <a href="{{ route('admin.aspirasi.index') }}" class="ka-navlink {{ request()->routeIs('admin.aspirasi.*') ? 'is-active' : '' }}">
                        <x-icon name="list-ul" />
                        Kelola Aspirasi
                    </a>
                @else
                    <a href="{{ route('aspirasi.create') }}" class="ka-navlink {{ request()->routeIs('aspirasi.create') ? 'is-active' : '' }}">
                        <x-icon name="plus-lg" />
                        Kirim Aspirasi
                    </a>
                    <a href="{{ route('aspirasi.index') }}" class="ka-navlink {{ request()->routeIs('aspirasi.index') ? 'is-active' : '' }}">
                        <x-icon name="journal-text" />
                        Aspirasi Saya
                    </a>
                @endif

                <div class="ka-nav-greeting">
                    Halo, <strong>{{ $user->name }}</strong>
                    {{ $isAdmin ? 'Admin sekolah' : $user->kelas }}
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="ka-navlink" style="border:0;background:transparent;">
                        <x-icon name="box-arrow-right" />
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-ka-outline">Login</a>
                <a href="{{ route('register') }}" class="btn btn-ka">Daftar</a>
            @endauth
        </nav>

        <button type="button" class="ka-nav-toggle" data-nav-toggle aria-controls="nav-mobile" aria-expanded="false" aria-label="Buka menu">
            <x-icon name="list" />
        </button>
    </div>

    <div class="ka-shell">
        <div class="ka-mobile-menu" id="nav-mobile">
            @auth
                @if ($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="ka-navlink">Dashboard</a>
                    <a href="{{ route('admin.aspirasi.index') }}" class="ka-navlink">Kelola Aspirasi</a>
                @else
                    <a href="{{ route('aspirasi.create') }}" class="ka-navlink">Kirim Aspirasi</a>
                    <a href="{{ route('aspirasi.index') }}" class="ka-navlink">Aspirasi Saya</a>
                @endif
                <div class="ka-mobile-divider"></div>
                <div class="ka-nav-greeting">
                    Halo, <strong>{{ $user->name }}</strong>
                    {{ $isAdmin ? 'Admin sekolah' : $user->kelas }}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="ka-navlink" style="border:0;background:transparent;">
                        <x-icon name="box-arrow-right" />
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="ka-navlink">Login</a>
                <a href="{{ route('register') }}" class="ka-navlink">Daftar akun siswa</a>
            @endauth
        </div>
    </div>
</header>
