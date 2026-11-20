@extends('layouts.app')

@section('title', $aspirasi->judul)

@section('content')
    <div class="ka-breadcrumb">
        <a href="{{ route('aspirasi.index') }}">Aspirasi saya</a>
        <x-icon name="chevron-right" />
        <span>Detail laporan</span>
    </div>

    <div class="ka-detail-grid">
        <article class="ka-card ka-card-body">
            <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
                <x-status-badge :status="$aspirasi->status" />
                <x-priority-badge :prioritas="$aspirasi->prioritas" />
            </div>

            <h1 class="ka-detail-title">{{ $aspirasi->judul }}</h1>

            <div class="ka-meta">
                <span><x-icon name="grid-1x2" />{{ $aspirasi->kategori->nama }}</span>
                <span><x-icon name="geo-alt" />{{ $aspirasi->lokasi }}</span>
                <span><x-icon name="calendar3" />Dikirim {{ $aspirasi->created_at->translatedFormat('d F Y, H:i') }}</span>
            </div>

            <hr class="ka-divider" style="margin:20px 0;">

            <div class="ka-detail-body">{{ $aspirasi->pesan }}</div>

            @if ($aspirasi->foto)
                <div style="margin-top:24px;">
                    <div class="ka-side-title" style="margin-bottom:8px;">Foto bukti</div>
                    <a href="{{ Storage::disk('public')->url($aspirasi->foto) }}" target="_blank" rel="noopener">
                        <img src="{{ Storage::disk('public')->url($aspirasi->foto) }}" alt="Foto bukti untuk {{ $aspirasi->judul }}" class="ka-detail-photo">
                    </a>
                    <div class="form-text">Klik foto untuk membuka ukuran penuh.</div>
                </div>
            @endif
        </article>

        <aside>
            <div class="ka-side-card">
                <div class="ka-side-title">Progres penanganan</div>

                @php
                    $urutan = ['menunggu' => 1, 'diproses' => 2, 'selesai' => 3];
                    $posisi = $urutan[$aspirasi->status] ?? 1;
                @endphp

                <div class="ka-timeline">
                    <div class="ka-timeline-step {{ $posisi > 1 ? 'is-done' : 'is-current' }}">
                        <div class="ka-timeline-dot">
                            @if ($posisi > 1)
                                <x-icon name="check2" />
                            @else
                                <x-icon name="hourglass-split" />
                            @endif
                        </div>
                        <div>
                            <div class="ka-timeline-label">Laporan diterima</div>
                            <div class="ka-timeline-note">{{ $aspirasi->created_at->translatedFormat('d M Y, H:i') }}</div>
                        </div>
                    </div>

                    <div class="ka-timeline-step {{ $posisi > 2 ? 'is-done' : ($posisi === 2 ? 'is-current' : '') }}">
                        <div class="ka-timeline-dot">
                            @if ($posisi > 2)
                                <x-icon name="check2" />
                            @else
                                <x-icon name="clock-history" />
                            @endif
                        </div>
                        <div>
                            <div class="ka-timeline-label" style="{{ $posisi < 2 ? 'color:var(--ka-muted)' : '' }}">Sedang diproses</div>
                            <div class="ka-timeline-note">{{ $posisi >= 2 ? 'Admin sedang menindaklanjuti' : 'Menunggu peninjauan admin' }}</div>
                        </div>
                    </div>

                    <div class="ka-timeline-step {{ $posisi === 3 ? 'is-done' : '' }}">
                        <div class="ka-timeline-dot">
                            @if ($posisi === 3)
                                <x-icon name="check2" />
                            @endif
                        </div>
                        <div>
                            <div class="ka-timeline-label" style="{{ $posisi < 3 ? 'color:var(--ka-muted)' : '' }}">Selesai</div>
                            <div class="ka-timeline-note">
                                {{ $posisi === 3 ? 'Laporan sudah ditangani' : 'Menunggu penyelesaian' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ka-side-card">
                <div class="ka-side-title">Rincian laporan</div>
                <dl style="margin:0;">
                    <div class="ka-info-row">
                        <dt>Kategori</dt>
                        <dd>{{ $aspirasi->kategori->nama }}</dd>
                    </div>
                    <div class="ka-info-row">
                        <dt>Prioritas</dt>
                        <dd>{{ $aspirasi->prioritasLabel() }}</dd>
                    </div>
                    <div class="ka-info-row">
                        <dt>Lokasi</dt>
                        <dd>{{ $aspirasi->lokasi }}</dd>
                    </div>
                    <div class="ka-info-row">
                        <dt>Pelapor</dt>
                        <dd>{{ $aspirasi->user->name }}</dd>
                    </div>
                    <div class="ka-info-row">
                        <dt>Kelas</dt>
                        <dd>{{ $aspirasi->user->kelas }}</dd>
                    </div>
                </dl>
            </div>

            @if ($aspirasi->tanggapan)
                <div class="ka-side-card" style="background:transparent;border:0;padding:0;">
                    <div class="ka-response">
                        <div class="ka-response-head">
                            <x-icon name="chat-left-text" />
                            Tanggapan sekolah
                        </div>
                        <p>{{ $aspirasi->tanggapan }}</p>
                        @if ($aspirasi->ditanggapi_pada)
                            <div class="ka-small ka-muted" style="margin-top:10px;">
                                Diberikan {{ $aspirasi->ditanggapi_pada->translatedFormat('d F Y, H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="ka-side-card">
                    <div class="ka-side-title">Tanggapan sekolah</div>
                    <p class="ka-small ka-muted" style="margin:0;">
                        Belum ada tanggapan. Admin akan membalas setelah laporan ditinjau.
                    </p>
                </div>
            @endif

            @if ($aspirasi->status === \App\Models\Aspirasi::STATUS_MENUNGGU)
                <div class="ka-side-card">
                    <div class="ka-side-title">Tindakan</div>
                    <div style="display:grid;gap:8px;">
                        <a href="{{ route('aspirasi.edit', $aspirasi) }}" class="btn btn-ka-outline w-100">
                            <x-icon name="pencil-square" />
                            Ubah laporan
                        </a>
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#hapus-laporan">
                            <x-icon name="trash" />
                            Hapus laporan
                        </button>
                    </div>
                </div>
            @endif
        </aside>
    </div>

    @if ($aspirasi->status === \App\Models\Aspirasi::STATUS_MENUNGGU)
        <div class="modal fade" id="hapus-laporan" tabindex="-1" aria-labelledby="hapus-laporan-label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="hapus-laporan-label">Hapus aspirasi?</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        Laporan <strong>{{ $aspirasi->judul }}</strong> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-ka-outline" data-bs-dismiss="modal">Batal</button>
                        <form method="POST" action="{{ route('aspirasi.destroy', $aspirasi) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus aspirasi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
