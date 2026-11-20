@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')
    <h1>Buat akun siswa</h1>
    <p>Isi data diri dengan benar. Nama dan kelas akan terlihat oleh admin saat menindaklanjuti laporan.</p>

    <form method="POST" action="{{ route('register.store') }}" novalidate>
        @csrf

        <div class="ka-field">
            <label for="name" class="form-label">Nama lengkap</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="Contoh: Bagas Prakoso"
                autocomplete="name"
                autofocus
                required
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-3">
            <div class="col-sm-7">
                <div class="ka-field">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="nama@email.com"
                        autocomplete="email"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-sm-5">
                <div class="ka-field">
                    <label for="kelas" class="form-label">Kelas</label>
                    <input
                        type="text"
                        id="kelas"
                        name="kelas"
                        value="{{ old('kelas') }}"
                        class="form-control @error('kelas') is-invalid @enderror"
                        placeholder="XI RPL 1"
                        autocomplete="off"
                        required
                    >
                    @error('kelas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="ka-field">
            <label for="password" class="form-label">Kata sandi</label>
            <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Minimal 8 karakter"
                autocomplete="new-password"
                required
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="ka-field">
            <label for="password_confirmation" class="form-label">Ulangi kata sandi</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control"
                placeholder="Ketik ulang kata sandi"
                autocomplete="new-password"
                required
            >
        </div>

        <button type="submit" class="btn btn-ka btn-lg w-100">
            <x-icon name="person-plus" />
            Daftar sebagai siswa
        </button>
    </form>

    <p class="ka-auth-footer">
        Sudah punya akun?
        <a href="{{ route('login') }}">Masuk di sini</a>
    </p>
@endsection
