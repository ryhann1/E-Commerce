@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')
<div class="auth-card">
    <div class="auth-head">
        <div class="logo-badge"><x-icon name="user" :size="28" /></div>
        <span class="brand brand-name">STOREA</span>
        <span class="brand-tag">Serba Ada, Serba Murah</span>
    </div>
    <h1 class="auth-title" style="text-align:center">Buat Akun</h1>

    <form method="POST" action="{{ route('register') }}" class="form" novalidate>
        @csrf

        <div class="field">
            <label for="name">Nama lengkap</label>
            <div class="input-wrap">
                <x-icon name="user" />
                <input id="name" name="name" type="text" value="{{ old('name') }}"
                       placeholder="Nama kamu" autocomplete="name" required
                       class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
            </div>
            @error('name') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label for="email">Email</label>
            <div class="input-wrap">
                <x-icon name="mail" />
                <input id="email" name="email" type="email" value="{{ old('email') }}"
                       placeholder="nama@email.com" autocomplete="email" required
                       class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
            </div>
            @error('email') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <div class="input-wrap">
                <x-icon name="lock" />
                <input id="password" name="password" type="password"
                       placeholder="Minimal 8 karakter" autocomplete="new-password" required
                       class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
            </div>
            @error('password') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Konfirmasi password</label>
            <div class="input-wrap">
                <x-icon name="lock" />
                <input id="password_confirmation" name="password_confirmation" type="password"
                       placeholder="Ulangi password" autocomplete="new-password" required>
            </div>
        </div>

        <button type="submit" class="btn btn-brown btn-block">
            Daftar <x-icon name="arrow" :size="18" />
        </button>
    </form>

    <div class="auth-links">
        <span>Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></span>
    </div>
</div>
@endsection
