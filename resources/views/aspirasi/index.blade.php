@extends('layouts.app')

@section('title', 'Aspirasi Saya')

@section('content')
    <div class="ka-page-head">
        <div>
            <h1>Aspirasi saya</h1>
            <p>Semua laporan yang Anda kirim, lengkap dengan status penanganannya.</p>
        </div>
        <a href="{{ route('aspirasi.create') }}" class="btn btn-ka">
            <x-icon name="plus-lg" />
            Kirim aspirasi
        </a>
    </div>

    <div class="ka-summary">
        <div class="ka-summary-item">
            <div class="ka-summary-label">Total laporan</div>
            <div class="ka-summary-value ka-num">{{ $ringkasan['total'] }}</div>
        </div>
        <div class="ka-summary-item">
            <div class="ka-summary-label">Menunggu peninjauan</div>
            <div class="ka-summary-value ka-num">{{ $ringkasan['menunggu'] }}</div>
        </div>
        <div class="ka-summary-item">
            <div class="ka-summary-label">Selesai ditangani</div>
            <div class="ka-summary-value ka-num">{{ $ringkasan['selesai'] }}</div>
        </div>
    </div>

    @if ($aspirasis->isEmpty())
        <x-empty-state
            icon="journal-text"
            title="Belum ada aspirasi"
            description="Laporan pertama Anda akan muncul di sini. Tuliskan masalah sarana atau masukan untuk sekolah."
        >
            <a href="{{ route('aspirasi.create') }}" class="btn btn-ka">
                <x-icon name="plus-lg" />
                Kirim aspirasi pertama
            </a>
        </x-empty-state>
    @else
        <div class="ka-aspirasi-list">
            @foreach ($aspirasis as $aspirasi)
                <article class="ka-aspirasi-item">
                    <div class="ka-aspirasi-top">
                        <h2 class="ka-aspirasi-title">
                            <a href="{{ route('aspirasi.show', $aspirasi) }}">{{ $aspirasi->judul }}</a>
                        </h2>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;">
                            <x-status-badge :status="$aspirasi->status" />
                            <x-priority-badge :prioritas="$aspirasi->prioritas" />
                        </div>
                    </div>

                    <div class="ka-meta">
                        <span><x-icon name="grid-1x2" />{{ $aspirasi->kategori->nama }}</span>
                        <span><x-icon name="geo-alt" />{{ $aspirasi->lokasi }}</span>
                        <span><x-icon name="calendar3" />{{ $aspirasi->created_at->translatedFormat('d M Y') }}</span>
                    </div>

                    <p class="ka-aspirasi-excerpt">{{ $aspirasi->ringkasan() }}</p>

                    @if ($aspirasi->tanggapan)
                        <div class="ka-tanggapan-preview">
                            <x-icon name="chat-left-text" />
                            <div>
                                <strong>Tanggapan sekolah:</strong>
                                {{ \Illuminate\Support\Str::limit($aspirasi->tanggapan, 140) }}
                            </div>
                        </div>
                    @endif

                    <div class="ka-aspirasi-actions">
                        <a href="{{ route('aspirasi.show', $aspirasi) }}" class="btn btn-ka-quiet btn-sm">
                            <x-icon name="eye" />
                            Lihat detail
                        </a>

                        @if ($aspirasi->status === \App\Models\Aspirasi::STATUS_MENUNGGU)
                            <a href="{{ route('aspirasi.edit', $aspirasi) }}" class="btn btn-outline-secondary btn-sm">
                                <x-icon name="pencil-square" />
                                Ubah
                            </a>
                            <button
                                type="button"
                                class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#hapus-aspirasi-{{ $aspirasi->id }}"
                            >
                                <x-icon name="trash" />
                                Hapus
                            </button>
                        @else
                            <span class="ka-small ka-muted ka-spacer">Laporan terkunci karena sudah diproses.</span>
                        @endif
                    </div>
                </article>

                @if ($aspirasi->status === \App\Models\Aspirasi::STATUS_MENUNGGU)
                    <div class="modal fade" id="hapus-aspirasi-{{ $aspirasi->id }}" tabindex="-1" aria-labelledby="hapus-label-{{ $aspirasi->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title" id="hapus-label-{{ $aspirasi->id }}">Hapus aspirasi?</h2>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    Laporan <strong>{{ $aspirasi->judul }}</strong> akan dihapus permanen beserta foto buktinya. Tindakan ini tidak dapat dibatalkan.
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
            @endforeach
        </div>

        {{ $aspirasis->links() }}
    @endif
@endsection
