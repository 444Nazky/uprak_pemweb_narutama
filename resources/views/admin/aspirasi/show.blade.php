@extends('layouts.app')

@section('title', 'Tinjau Aspirasi')

@section('content')
    <div class="ka-breadcrumb">
        <a href="{{ route('admin.aspirasi.index') }}">Kelola aspirasi</a>
        <x-icon name="chevron-right" />
        <span>Tinjau laporan</span>
    </div>

    <div class="ka-page-head">
        <div>
            <h1>Tinjau aspirasi</h1>
            <p>Perbarui status penanganan dan tulis tanggapan untuk pelapor.</p>
        </div>
        <x-status-badge :status="$aspirasi->status" />
    </div>

    <div class="ka-detail-grid">
        <article class="ka-card">
            <div style="display:flex;align-items:center;gap:14px;padding:20px 24px;border-bottom:1px solid var(--ka-line);">
                <div class="ka-brand-mark" style="width:44px;height:44px;border-radius:999px;font-size:18px;">
                    <x-icon name="person" />
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:700;font-size:15px;">{{ $aspirasi->user->name }}</div>
                    <div class="ka-small ka-muted">
                        {{ $aspirasi->user->kelas ?? 'Tanpa kelas' }} &middot; {{ $aspirasi->user->email }}
                    </div>
                </div>
                <x-priority-badge :prioritas="$aspirasi->prioritas" />
            </div>

            <div class="ka-card-body">
                <h2 class="ka-detail-title" style="margin-top:0;">{{ $aspirasi->judul }}</h2>

                <div class="ka-meta">
                    <span><x-icon name="grid-1x2" />{{ $aspirasi->kategori->nama }}</span>
                    <span><x-icon name="geo-alt" />{{ $aspirasi->lokasi }}</span>
                    <span><x-icon name="calendar3" />{{ $aspirasi->created_at->translatedFormat('d F Y, H:i') }}</span>
                </div>

                <hr class="ka-divider" style="margin:20px 0;">

                <div class="ka-detail-body">{{ $aspirasi->pesan }}</div>

                @if ($aspirasi->foto)
                    <div style="margin-top:24px;">
                        <div class="ka-side-title" style="margin-bottom:8px;">Foto bukti</div>
                        <a href="{{ Storage::disk('public')->url($aspirasi->foto) }}" target="_blank" rel="noopener">
                            <img src="{{ Storage::disk('public')->url($aspirasi->foto) }}" alt="Foto bukti untuk {{ $aspirasi->judul }}" class="ka-detail-photo">
                        </a>
                    </div>
                @endif

                <dl style="margin:26px 0 0;display:grid;grid-template-columns:1fr 1fr;gap:0 24px;">
                    <div class="ka-info-row">
                        <dt>Tanggal kirim</dt>
                        <dd>{{ $aspirasi->created_at->translatedFormat('d M Y') }}</dd>
                    </div>
                    <div class="ka-info-row">
                        <dt>Terakhir diperbarui</dt>
                        <dd>{{ $aspirasi->updated_at->translatedFormat('d M Y') }}</dd>
                    </div>
                    <div class="ka-info-row">
                        <dt>Tanggapan terakhir</dt>
                        <dd>{{ $aspirasi->ditanggapi_pada?->translatedFormat('d M Y') ?? 'Belum ada' }}</dd>
                    </div>
                    <div class="ka-info-row">
                        <dt>Nomor laporan</dt>
                        <dd class="ka-num">#{{ str_pad((string) $aspirasi->id, 4, '0', STR_PAD_LEFT) }}</dd>
                    </div>
                </dl>
            </div>
        </article>

        <aside>
            <div class="ka-filter-card" style="position:static;">
                <div class="ka-side-title">Perbarui penanganan</div>

                <form method="POST" action="{{ route('admin.aspirasi.update', $aspirasi) }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="ka-field">
                        <label for="status" class="form-label">Status <span class="ka-required">*</span></label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach (\App\Models\Aspirasi::STATUSES as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $aspirasi->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="ka-field">
                        <label for="prioritas" class="form-label">Prioritas <span class="ka-required">*</span></label>
                        <select id="prioritas" name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                            @foreach (\App\Models\Aspirasi::PRIORITAS as $value => $label)
                                <option value="{{ $value }}" @selected(old('prioritas', $aspirasi->prioritas) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('prioritas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="ka-field">
                        <label for="tanggapan" class="form-label">Tanggapan sekolah</label>
                        <textarea
                            id="tanggapan"
                            name="tanggapan"
                            class="form-control @error('tanggapan') is-invalid @enderror"
                            rows="6"
                            maxlength="2000"
                            placeholder="Tuliskan tindakan yang sudah atau akan dilakukan."
                        >{{ old('tanggapan', $aspirasi->tanggapan) }}</textarea>
                        @error('tanggapan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Tanggapan langsung terlihat oleh pelapor.</div>
                    </div>

                    <button type="submit" class="btn btn-ka w-100">
                        <x-icon name="check2" />
                        Simpan perubahan
                    </button>
                </form>
            </div>

            <div class="ka-side-card">
                <div class="ka-side-title">Hapus laporan</div>
                <p class="ka-small ka-muted" style="margin-bottom:14px;">
                    Gunakan hanya untuk laporan uji coba atau duplikat. Data dan foto akan dihapus permanen.
                </p>
                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#hapus-admin">
                    <x-icon name="trash" />
                    Hapus aspirasi
                </button>
            </div>
        </aside>
    </div>

    <div class="modal fade" id="hapus-admin" tabindex="-1" aria-labelledby="hapus-admin-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="hapus-admin-label">Hapus aspirasi ini?</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Laporan <strong>{{ $aspirasi->judul }}</strong> milik <strong>{{ $aspirasi->user->name }}</strong> akan dihapus permanen beserta foto buktinya.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ka-outline" data-bs-dismiss="modal">Batal</button>
                    <form method="POST" action="{{ route('admin.aspirasi.destroy', $aspirasi) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus aspirasi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
