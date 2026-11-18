@extends('layouts.shop')

@section('title', 'Beranda')

@section('content')
    <section class="hero">
        <div class="hero-text">
            <h2>Dari Kebutuhan Harian Hingga Impian, Semua Ada!</h2>
            <p>Belanja gadget aman dan terpercaya dengan harga terbaik.</p>
            <div class="hero-actions">
                <a href="{{ route('produk.index') }}" class="btn btn-brown">Mulai Belanja <x-icon name="arrow" :size="18" /></a>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                @endguest
            </div>
        </div>
        <div class="hero-art">
            <svg viewBox="0 0 160 220" width="150" role="img" aria-label="Ilustrasi smartphone">
                <rect x="20" y="8" width="120" height="204" rx="22" fill="#2f3a22"/>
                <rect x="28" y="18" width="104" height="184" rx="16" fill="#e8ebd8"/>
                <rect x="40" y="36" width="80" height="80" rx="12" fill="#6a7848"/>
                <rect x="40" y="128" width="56" height="9" rx="4" fill="#55623b"/>
                <rect x="40" y="146" width="80" height="7" rx="3" fill="#b9c39a"/>
                <rect x="40" y="160" width="62" height="7" rx="3" fill="#b9c39a"/>
                <rect x="62" y="12" width="36" height="6" rx="3" fill="#2f3a22"/>
            </svg>
        </div>
    </section>

    <nav class="chips" aria-label="Kategori">
        @foreach ($kategori as $k)
            <a class="chip" href="{{ route('produk.index', ['kategori' => $k]) }}">{{ $k }}</a>
        @endforeach
    </nav>

    <section class="stack">
        <div class="section-head">
            <h2>Produk Unggulan</h2>
            <a href="{{ route('produk.index') }}">Lihat semua →</a>
        </div>

        @if ($unggulan->isEmpty())
            <div class="empty">
                <strong>Belum ada produk.</strong>
                <span>Jalankan <code>php artisan migrate --seed</code> untuk mengisi produk contoh.</span>
            </div>
        @else
            <div class="grid">
                @foreach ($unggulan as $p)
                    @include('partials.product-card', ['p' => $p])
                @endforeach
            </div>
        @endif
    </section>
@endsection
