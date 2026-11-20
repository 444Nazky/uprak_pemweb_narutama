@extends('layouts.app')

@section('title', 'Kelola Aspirasi')

@section('content')
    <div class="ka-page-head">
        <div>
            <h1>Kelola aspirasi</h1>
            <p>Tinjau seluruh laporan siswa, ubah status, dan berikan tanggapan resmi.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-ka-outline">
            <x-icon name="bar-chart" />
            Lihat dashboard
        </a>
    </div>

    <nav class="ka-tabs" aria-label="Filter status">
        @php
            $tabAktif = $filters['status'] ?? null;
            $tabs = [
                null => ['label' => 'Semua', 'jumlah' => $jumlah['semua']],
                'menunggu' => ['label' => 'Menunggu', 'jumlah' => $jumlah['menunggu']],
                'diproses' => ['label' => 'Diproses', 'jumlah' => $jumlah['diproses']],
                'selesai' => ['label' => 'Selesai', 'jumlah' => $jumlah['selesai']],
            ];
        @endphp

        @foreach ($tabs as $nilai => $tab)
            <a
                href="{{ request()->fullUrlWithQuery(['status' => $nilai, 'page' => null]) }}"
                class="ka-tab {{ $tabAktif === $nilai ? 'is-active' : '' }}"
            >
                {{ $tab['label'] }}
                <span class="ka-tab-count ka-num">{{ $tab['jumlah'] }}</span>
            </a>
        @endforeach
    </nav>

    <form method="GET" action="{{ route('admin.aspirasi.index') }}" class="ka-toolbar">
        @if ($tabAktif)
            <input type="hidden" name="status" value="{{ $tabAktif }}">
        @endif

        <div class="ka-toolbar-grid">
            <div>
                <label class="ka-filter-label" for="q">Cari laporan</label>
                <div class="ka-input-group">
                    <x-icon name="search" />
                    <input
                        type="search"
                        id="q"
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        class="form-control"
                        placeholder="Judul, isi, lokasi, atau nama siswa"
                    >
                </div>
            </div>

            <div>
                <label class="ka-filter-label" for="kategori">Kategori</label>
                <select id="kategori" name="kategori" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" @selected((string) ($filters['kategori'] ?? '') === (string) $kategori->id)>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="ka-filter-label" for="urut">Urutkan</label>
                <select id="urut" name="urut" class="form-select">
                    <option value="terbaru" @selected(($filters['urut'] ?? 'terbaru') === 'terbaru')>Terbaru</option>
                    <option value="terlama" @selected(($filters['urut'] ?? '') === 'terlama')>Terlama</option>
                    <option value="prioritas" @selected(($filters['urut'] ?? '') === 'prioritas')>Prioritas tertinggi</option>
                </select>
            </div>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-ka w-100">
                    <x-icon name="funnel" />
                    Terapkan
                </button>
            </div>

            @if (($filters['q'] ?? null) || ($filters['kategori'] ?? null) || ($filters['status'] ?? null) || (($filters['urut'] ?? 'terbaru') !== 'terbaru'))
                <div>
                    <a href="{{ route('admin.aspirasi.index') }}" class="btn btn-ka-outline w-100" style="min-height:46px;">
                        <x-icon name="arrow-counterclockwise" />
                        Reset
                    </a>
                </div>
            @endif
        </div>
    </form>

    @if ($aspirasis->isEmpty())
        <x-empty-state
            icon="search"
            title="Tidak ada aspirasi yang cocok"
            description="Coba ubah kata kunci, kategori, atau status filter yang dipilih."
        >
            <a href="{{ route('admin.aspirasi.index') }}" class="btn btn-ka-outline">Tampilkan semua laporan</a>
        </x-empty-state>
    @else
        <div class="ka-table-wrap d-none d-lg-block">
            <table class="ka-table">
                <thead>
                    <tr>
                        <th>Laporan</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($aspirasis as $aspirasi)
                        <tr>
                            <td style="max-width:320px;">
                                <a href="{{ route('admin.aspirasi.show', $aspirasi) }}" class="ka-table-title">{{ $aspirasi->judul }}</a>
                                <span class="ka-table-sub"><x-icon name="geo-alt" />{{ $aspirasi->lokasi }}</span>
                            </td>
                            <td>
                                <span style="font-weight:600;">{{ $aspirasi->user->name }}</span>
                                <span class="ka-table-sub">{{ $aspirasi->user->kelas ?? 'Tanpa kelas' }}</span>
                            </td>
                            <td>{{ $aspirasi->kategori->nama }}</td>
                            <td><x-priority-badge :prioritas="$aspirasi->prioritas" /></td>
                            <td><x-status-badge :status="$aspirasi->status" /></td>
                            <td class="ka-small ka-muted">{{ $aspirasi->created_at->translatedFormat('d M Y') }}</td>
                            <td style="text-align:right;">
                                <a href="{{ route('admin.aspirasi.show', $aspirasi) }}" class="btn btn-ka-quiet btn-sm">
                                    Tinjau
                                    <x-icon name="arrow-right" />
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="ka-mobile-list d-lg-none">
            @foreach ($aspirasis as $aspirasi)
                <div class="ka-mobile-card">
                    <div class="ka-mobile-card-top">
                        <a href="{{ route('admin.aspirasi.show', $aspirasi) }}" class="ka-table-title">{{ $aspirasi->judul }}</a>
                        <x-status-badge :status="$aspirasi->status" />
                    </div>
                    <div class="ka-meta" style="margin:8px 0 10px;">
                        <span><x-icon name="person" />{{ $aspirasi->user->name }}</span>
                        <span><x-icon name="grid-1x2" />{{ $aspirasi->kategori->nama }}</span>
                        <span><x-icon name="calendar3" />{{ $aspirasi->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <x-priority-badge :prioritas="$aspirasi->prioritas" />
                        <a href="{{ route('admin.aspirasi.show', $aspirasi) }}" class="btn btn-ka-quiet btn-sm" style="margin-left:auto;">
                            Tinjau
                            <x-icon name="arrow-right" />
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;">
            <div class="ka-small ka-muted">
                Menampilkan {{ $aspirasis->firstItem() }} sampai {{ $aspirasis->lastItem() }} dari {{ $aspirasis->total() }} laporan
            </div>
            {{ $aspirasis->links() }}
        </div>
    @endif
@endsection
