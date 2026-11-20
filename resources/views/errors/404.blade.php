@extends('layouts.app')

@section('title', 'Halaman tidak ditemukan')

@section('content')
    <div style="max-width:560px;margin:48px auto;text-align:center;">
        <div class="ka-empty-icon">
            <x-icon name="search" />
        </div>
        <h1 style="font-size:2rem;letter-spacing:-0.035em;">Halaman tidak ditemukan</h1>
        <p class="ka-muted" style="max-width:44ch;margin:0 auto 24px;">
            Alamat yang Anda buka tidak tersedia atau laporannya sudah dihapus.
        </p>

        @auth
            @if (Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn btn-ka">
                    <x-icon name="grid-1x2" />
                    Kembali ke dashboard
                </a>
            @else
                <a href="{{ route('aspirasi.index') }}" class="btn btn-ka">
                    <x-icon name="journal-text" />
                    Kembali ke aspirasi saya
                </a>
            @endif
        @else
            <a href="{{ route('home') }}" class="btn btn-ka">
                <x-icon name="arrow-left" />
                Kembali ke beranda
            </a>
        @endauth
    </div>
@endsection
