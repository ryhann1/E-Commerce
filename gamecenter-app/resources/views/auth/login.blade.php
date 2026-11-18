@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<div class="auth-card">
    <div class="auth-head">
        <div class="logo-badge"><x-icon name="lock" :size="28" /></div>
        <span class="brand brand-name">STOREA</span>
        <span class="brand-tag">Serba Ada, Serba Murah</span>
    </div>

    <div style="text-align:center">
        <h1 class="auth-title">Masuk ke STOREA</h1>
        <p class="auth-sub">Mode demo: tanpa email dan password. Pilih mau masuk sebagai siapa.</p>
    </div>

    @if (config('app.demo_login'))
        <div class="role-list">
            <form method="POST" action="{{ route('login.demo', 'user') }}">
                @csrf
                <button type="submit" class="role-btn">
                    <span class="stat-icon"><x-icon name="user" :size="22" /></span>
                    <span><strong>Masuk sebagai User</strong><small>Belanja, keranjang, checkout, dan pesanan</small></span>
                </button>
            </form>
            <form method="POST" action="{{ route('login.demo', 'admin') }}">
                @csrf
                <button type="submit" class="role-btn">
                    <span class="stat-icon"><x-icon name="shield" :size="22" /></span>
                    <span><strong>Masuk sebagai Admin</strong><small>Kelola produk dan pesanan</small></span>
                </button>
            </form>
        </div>
    @else
        <div class="alert" role="alert">Login demo dinonaktifkan (DEMO_LOGIN=false).</div>
    @endif

    <div class="auth-links">
        <a href="{{ route('home') }}">← Kembali ke toko</a>
    </div>
</div>
@endsection
