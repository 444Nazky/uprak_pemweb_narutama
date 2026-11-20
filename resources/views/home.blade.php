@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="ka-hero">
        <div class="ka-hero-grid">
            <div class="ka-hero-copy-group">
                <span class="ka-eyebrow">
                    <x-icon name="inbox" />
                    Kanal aspirasi resmi sekolah
                </span>

                <h1>Laporan siswa, ditangani dengan jelas.</h1>

                <p class="ka-hero-copy">
                    Sampaikan masalah sarana, kebersihan, atau kegiatan belajar. Pantau status penanganan dan baca tanggapan resmi sekolah di satu tempat.
                </p>

                <div class="ka-hero-actions">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-ka btn-lg">
                            <x-icon name="person-plus" />
                            Buat akun siswa
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-ka-outline btn-lg">Masuk ke akun</a>
                    @endguest
                    @auth
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-ka btn-lg">
                                <x-icon name="grid-1x2" />
                                Buka dashboard
                            </a>
                            <a href="{{ route('admin.aspirasi.index') }}" class="btn btn-ka-outline btn-lg">Kelola aspirasi</a>
                        @else
                            <a href="{{ route('aspirasi.create') }}" class="btn btn-ka btn-lg">
                                <x-icon name="plus-lg" />
                                Kirim aspirasi
                            </a>
                            <a href="{{ route('aspirasi.index') }}" class="btn btn-ka-outline btn-lg">Aspirasi saya</a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="ka-board-wrap">
                <div class="ka-board-shadow" aria-hidden="true"></div>

                <div class="ka-board">
                    <div class="ka-board-head">
                        <div>
                            <div class="ka-board-label">Ringkasan aspirasi</div>
                            <div class="ka-board-total ka-num">
                                {{ $total }}<span>laporan tercatat</span>
                            </div>
                        </div>
                        <span class="ka-chip">
                            <x-icon name="bar-chart" />
                            Data sekolah
                        </span>
                    </div>

                    <div class="ka-board-rows">
                        <div class="ka-board-row">
                            <span class="ka-board-icon" style="background:var(--ka-warning-bg);color:var(--ka-warning);">
                                <x-icon name="hourglass-split" />
                            </span>
                            <span class="ka-board-row-label">Menunggu peninjauan</span>
                            <span class="ka-board-row-count ka-num">{{ $menunggu }}</span>
                        </div>
                        <div class="ka-board-row">
                            <span class="ka-board-icon" style="background:var(--ka-info-bg);color:var(--ka-info);">
                                <x-icon name="clock-history" />
                            </span>
                            <span class="ka-board-row-label">Sedang diproses</span>
                            <span class="ka-board-row-count ka-num">{{ $diproses }}</span>
                        </div>
                        <div class="ka-board-row">
                            <span class="ka-board-icon" style="background:var(--ka-success-bg);color:var(--ka-success);">
                                <x-icon name="check-circle" />
                            </span>
                            <span class="ka-board-row-label">Selesai ditangani</span>
                            <span class="ka-board-row-count ka-num">{{ $selesai }}</span>
                        </div>
                    </div>

                    <div class="ka-board-foot">
                        <span><strong class="ka-num">{{ $persenSelesai }}%</strong> laporan sudah selesai</span>
                        <span style="display:inline-flex;align-items:center;gap:6px;color:#cfe3d6;">
                            <x-icon name="shield-lock" />
                            Identitas terjaga
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ka-section" id="alur">
        <div class="ka-section-head">
            <h2>Dari laporan menjadi perbaikan</h2>
            <p>Tiga tahap yang sama berlaku untuk setiap aspirasi, sehingga tidak ada laporan yang menggantung tanpa status.</p>
        </div>

        <div class="ka-steps">
            <div class="ka-step">
                <div class="ka-step-num">
                    <x-icon name="send" />
                </div>
                <h3>Kirim laporan</h3>
                <p>Pilih kategori, tuliskan lokasi dan detail masalah, lalu lampirkan foto bukti bila ada.</p>
            </div>
            <div class="ka-step">
                <div class="ka-step-num">
                    <x-icon name="eye" />
                </div>
                <h3>Sekolah meninjau</h3>
                <p>Admin memeriksa laporan, menetapkan prioritas, dan mengubah status sesuai proses di lapangan.</p>
            </div>
            <div class="ka-step">
                <div class="ka-step-num">
                    <x-icon name="check-circle" />
                </div>
                <h3>Terima tanggapan</h3>
                <p>Baca tanggapan resmi dan pantau status sampai laporan dinyatakan selesai.</p>
            </div>
        </div>
    </section>

    <section class="ka-section">
        <div class="ka-section-head">
            <h2>Empat kategori laporan</h2>
            <p>Setiap laporan masuk ke satu kategori agar penanganannya sampai ke bagian yang tepat.</p>
        </div>

        <div class="ka-kategori-grid">
            @foreach ($kategoris as $kategori)
                <div class="ka-kategori {{ $loop->first ? 'ka-kategori--accent' : '' }}">
                    <div>
                        <h3>{{ $kategori->nama }}</h3>
                        <p>{{ $kategori->keterangan }}</p>
                    </div>
                    <div class="ka-kategori-count">
                        <span class="ka-num">{{ $kategori->aspirasis_count }}</span>
                        laporan
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="ka-band ka-privacy" id="privasi">
        <div class="ka-shell">
            <div class="ka-privacy-grid">
                <div>
                    <h2>Laporan Anda bukan konsumsi publik.</h2>
                    <p class="ka-muted" style="font-size:14.5px;max-width:38ch;">
                        Beranda hanya menampilkan angka. Isi laporan, foto, dan identitas pelapor tidak dibuka untuk pengunjung umum.
                    </p>

                    @auth
                        @if (! Auth::user()->isAdmin())
                            <a href="{{ route('aspirasi.create') }}" class="btn btn-ka" style="margin-top:8px;">
                                <x-icon name="plus-lg" />
                                Kirim aspirasi
                            </a>
                        @endif
                    @endauth
                </div>

                <div class="ka-privacy-list">
                    <div class="ka-privacy-item">
                        <x-icon name="lock" />
                        <div>
                            <strong>Akses terbatas pada pemilik akun</strong>
                            <p>Siswa hanya dapat membuka rincian aspirasi yang ia kirim sendiri.</p>
                        </div>
                    </div>
                    <div class="ka-privacy-item">
                        <x-icon name="person" />
                        <div>
                            <strong>Identitas dilihat admin</strong>
                            <p>Nama dan kelas pelapor hanya tampil pada panel admin untuk keperluan tindak lanjut.</p>
                        </div>
                    </div>
                    <div class="ka-privacy-item">
                        <x-icon name="image" />
                        <div>
                            <strong>Foto bukti tampil seperlunya</strong>
                            <p>Foto hanya muncul pada halaman detail laporan terkait, bukan pada daftar publik.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
