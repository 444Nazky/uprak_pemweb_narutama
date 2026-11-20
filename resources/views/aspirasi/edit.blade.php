@extends('layouts.app')

@section('title', 'Ubah Aspirasi')

@section('content')
    <div class="ka-breadcrumb">
        <a href="{{ route('aspirasi.index') }}">Aspirasi saya</a>
        <x-icon name="chevron-right" />
        <a href="{{ route('aspirasi.show', $aspirasi) }}">Detail laporan</a>
        <x-icon name="chevron-right" />
        <span>Ubah</span>
    </div>

    <div class="ka-page-head">
        <div>
            <h1>Ubah aspirasi</h1>
            <p>Perubahan hanya dapat dilakukan selama status laporan masih Menunggu.</p>
        </div>
        <x-status-badge :status="$aspirasi->status" />
    </div>

    <div class="ka-form-grid">
        <div class="ka-card ka-card-body">
            <form method="POST" action="{{ route('aspirasi.update', $aspirasi) }}" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                @include('aspirasi._form', ['aspirasi' => $aspirasi])

                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:8px;">
                    <button type="submit" class="btn btn-ka btn-lg">
                        <x-icon name="check2" />
                        Simpan perubahan
                    </button>
                    <a href="{{ route('aspirasi.show', $aspirasi) }}" class="btn btn-ka-outline btn-lg">Batal</a>
                </div>
            </form>
        </div>

        <aside>
            <div class="ka-filter-card">
                <div class="ka-side-title">Sebelum menyimpan</div>
                <ul class="ka-tip-list">
                    <li class="ka-tip">
                        <x-icon name="clock-history" />
                        <span>Setelah admin memproses laporan, isi laporan tidak dapat diubah lagi.</span>
                    </li>
                    <li class="ka-tip">
                        <x-icon name="image" />
                        <span>Mengunggah foto baru akan menggantikan foto bukti sebelumnya.</span>
                    </li>
                    <li class="ka-tip">
                        <x-icon name="chat-left-text" />
                        <span>Tanggapan admin tidak berubah saat Anda menyunting laporan.</span>
                    </li>
                </ul>
            </div>
        </aside>
    </div>
@endsection
