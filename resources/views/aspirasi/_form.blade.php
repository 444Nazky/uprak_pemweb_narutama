@php
    $aspirasi = $aspirasi ?? null;
@endphp

<div class="ka-field">
    <label for="judul" class="form-label">Judul laporan <span class="ka-required">*</span></label>
    <input
        type="text"
        id="judul"
        name="judul"
        value="{{ old('judul', $aspirasi->judul ?? '') }}"
        class="form-control @error('judul') is-invalid @enderror"
        placeholder="Contoh: AC ruang 11 tidak dingin"
        maxlength="150"
        required
    >
    @error('judul')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text">Tulis inti masalah dalam satu kalimat singkat.</div>
</div>

<div class="row g-0">
    <div class="col-md-6 pe-md-2">
        <div class="ka-field">
            <label for="kategori_id" class="form-label">Kategori <span class="ka-required">*</span></label>
            <select id="kategori_id" name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                <option value="" disabled {{ old('kategori_id', $aspirasi->kategori_id ?? '') ? '' : 'selected' }}>Pilih kategori</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" @selected((string) old('kategori_id', $aspirasi->kategori_id ?? '') === (string) $kategori->id)>
                        {{ $kategori->nama }}
                    </option>
                @endforeach
            </select>
            @error('kategori_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6 ps-md-2">
        <div class="ka-field">
            <label for="lokasi" class="form-label">Lokasi <span class="ka-required">*</span></label>
            <input
                type="text"
                id="lokasi"
                name="lokasi"
                value="{{ old('lokasi', $aspirasi->lokasi ?? '') }}"
                class="form-control @error('lokasi') is-invalid @enderror"
                placeholder="Contoh: Ruang 11, Gedung A"
                maxlength="150"
                required
            >
            @error('lokasi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="ka-field">
    <label for="pesan" class="form-label">Isi aspirasi <span class="ka-required">*</span></label>
    <textarea
        id="pesan"
        name="pesan"
        class="form-control @error('pesan') is-invalid @enderror"
        rows="6"
        maxlength="2000"
        placeholder="Jelaskan kondisi yang Anda temui, sejak kapan terjadi, dan dampaknya bagi kegiatan belajar."
        required
    >{{ old('pesan', $aspirasi->pesan ?? '') }}</textarea>
    @error('pesan')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text">Minimal 20 karakter. Tulis satu masalah utama per laporan.</div>
</div>

<div class="ka-field">
    <label for="prioritas" class="form-label">Tingkat prioritas <span class="ka-required">*</span></label>
    <select id="prioritas" name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
        @foreach (\App\Models\Aspirasi::PRIORITAS as $value => $label)
            <option value="{{ $value }}" @selected(old('prioritas', $aspirasi->prioritas ?? 'sedang') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    @error('prioritas')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text">Pilih tinggi bila masalah menghambat kegiatan belajar atau berisiko membahayakan.</div>
</div>

<div class="ka-field">
    <label for="foto" class="form-label">Foto bukti <span class="ka-muted">(opsional)</span></label>

    @if ($aspirasi?->foto)
        <div class="ka-current-photo">
            <img src="{{ Storage::disk('public')->url($aspirasi->foto) }}" alt="Foto bukti saat ini">
            <div>
                <div style="font-weight:700;font-size:13.5px;">Foto saat ini</div>
                <div class="ka-small ka-muted">Pilih berkas baru untuk menggantinya.</div>
            </div>
        </div>
    @endif

    <div class="ka-upload">
        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png" class="@error('foto') is-invalid @enderror" data-photo-input>
        <div class="form-text">Format JPG atau PNG, ukuran maksimal 2 MB.</div>
        @error('foto')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <img data-photo-preview hidden alt="Pratinjau foto yang dipilih" style="max-width:100%;max-height:200px;border-radius:10px;margin-top:12px;border:1px solid var(--ka-line);">
    </div>
</div>
