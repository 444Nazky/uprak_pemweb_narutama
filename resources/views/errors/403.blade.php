@extends('layouts.app')

@section('title', 'Akses ditolak')

@section('content')
    <div style="max-width:560px;margin:48px auto;text-align:center;">
        <div class="ka-empty-icon" style="background:var(--ka-danger-bg);color:var(--ka-danger);">
            <x-icon name="shield-lock" />
        </div>
        <h1 style="font-size:2rem;letter-spacing:-0.035em;">Akses ditolak</h1>
        <p class="ka-muted" style="max-width:44ch;margin:0 auto 24px;">
            {{ $exception->getMessage() ?: 'Halaman ini tidak tersedia untuk peran akun Anda.' }}
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
            <a href="{{ route('login') }}" class="btn btn-ka">
                <x-icon name="box-arrow-right" />
                Masuk ke akun
            </a>
        @endauth

        <p style="margin-top:22px;">
            <a href="{{ route('home') }}" class="ka-small ka-muted">Kembali ke beranda</a>
        </p>
    </div>
@endsection
