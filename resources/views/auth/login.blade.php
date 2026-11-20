@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
    <h1>Masuk ke akun</h1>
    <p>Gunakan email dan kata sandi yang terdaftar. Akun admin hanya dibuat oleh pihak sekolah.</p>

    <form method="POST" action="{{ route('login.store') }}" novalidate>
        @csrf

        <div class="ka-field">
            <label for="email" class="form-label">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="nama@pelitanusantara.sch.id"
                autocomplete="email"
                autofocus
                required
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="ka-field">
            <label for="password" class="form-label">Kata sandi</label>
            <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Masukkan kata sandi"
                autocomplete="current-password"
                required
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Ingat saya di perangkat ini</label>
        </div>

        <button type="submit" class="btn btn-ka btn-lg w-100">
            <x-icon name="box-arrow-right" />
            Masuk
        </button>
    </form>

    <p class="ka-auth-footer">
        Belum punya akun?
        <a href="{{ route('register') }}">Daftar sebagai siswa</a>
    </p>
@endsection
