@extends('layouts.app')

@section('title', 'Kirim Aspirasi')

@section('content')
    <div class="ka-breadcrumb">
        <a href="{{ route('aspirasi.index') }}">Aspirasi saya</a>
        <x-icon name="chevron-right" />
        <span>Kirim baru</span>
    </div>

    <div class="ka-page-head">
        <div>
            <h1>Kirim aspirasi</h1>
            <p>Laporan Anda masuk dengan status Menunggu dan ditinjau oleh admin sekolah.</p>
        </div>
    </div>

    <div class="ka-form-grid">
        <div class="ka-card ka-card-body">
            <form method="POST" action="{{ route('aspirasi.store') }}" enctype="multipart/form-data" novalidate>
                @csrf
                @include('aspirasi._form')

                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:8px;">
                    <button type="submit" class="btn btn-ka btn-lg">
                        <x-icon name="send" />
                        Kirim aspirasi
                    </button>
                    <a href="{{ route('aspirasi.index') }}" class="btn btn-ka-outline btn-lg">Batal</a>
                </div>
            </form>
        </div>

        <aside>
            <div class="ka-filter-card">
                <div class="ka-side-title">Panduan singkat</div>
                <ul class="ka-tip-list">
                    <li class="ka-tip">
                        <x-icon name="geo-alt" />
                        <span>Sebutkan lokasi secara spesifik, misalnya nomor ruang dan nama gedung.</span>
                    </li>
                    <li class="ka-tip">
                        <x-icon name="journal-text" />
                        <span>Satu laporan untuk satu masalah agar mudah ditindaklanjuti.</span>
                    </li>
                    <li class="ka-tip">
                        <x-icon name="image" />
                        <span>Foto membantu admin memahami kondisi sebenarnya. Pastikan gambar jelas.</span>
                    </li>
                    <li class="ka-tip">
                        <x-icon name="shield-lock" />
                        <span>Laporan hanya bisa dibuka oleh Anda dan admin sekolah.</span>
                    </li>
                </ul>
            </div>
        </aside>
    </div>
@endsection
