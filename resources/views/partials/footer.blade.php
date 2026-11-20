@php($user = Auth::user())

<footer class="ka-footer">
    <div class="{{ $user?->isAdmin() ? 'container' : 'ka-shell' }}">
        <div class="ka-footer-grid">
            <div>
                <a href="{{ route('home') }}" class="ka-brand">
                    <span class="ka-brand-mark">
                        <x-icon name="inbox" />
                    </span>
                    <span>
                        Kotak Aspirasi
                        <small>SMK Plus Pelita Nusantara</small>
                    </span>
                </a>
                <p>Kanal resmi siswa untuk melaporkan kondisi sarana, kebersihan, dan kegiatan belajar di sekolah.</p>
            </div>

            <div class="ka-footer-links">
                @guest
                    <a href="{{ route('home') }}">Beranda</a>
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Daftar</a>
                @endguest
                @auth
                    @if ($user->isAdmin())
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <a href="{{ route('admin.aspirasi.index') }}">Kelola Aspirasi</a>
                    @else
                        <a href="{{ route('aspirasi.create') }}">Kirim Aspirasi</a>
                        <a href="{{ route('aspirasi.index') }}">Aspirasi Saya</a>
                    @endif
                @endauth
                <a href="{{ route('home') }}#alur">Alur Penanganan</a>
                <a href="{{ route('home') }}#privasi">Privasi</a>
            </div>
        </div>

        <div class="ka-footer-bottom">
            <span>&copy; {{ date('Y') }} SMK Plus Pelita Nusantara Bogor</span>
            <span>Dibuat untuk Ujian Praktik Kejuruan RPL</span>
        </div>
    </div>
</footer>
