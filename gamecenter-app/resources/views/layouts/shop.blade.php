<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Beranda') — STOREA</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <a href="{{ route('home') }}" class="sidebar-brand brand-link">
                <span class="brand brand-name">STOREA</span>
                <span class="brand-tag">Serba Ada, Serba Murah</span>
            </a>

            <nav class="nav" aria-label="Menu utama">
                <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'is-active' : '' }}" @if (request()->routeIs('home')) aria-current="page" @endif>
                    <x-icon name="home" /> Beranda
                </a>
                <a href="{{ route('produk.index') }}" class="nav-item {{ request()->routeIs('produk.*') ? 'is-active' : '' }}" @if (request()->routeIs('produk.*')) aria-current="page" @endif>
                    <x-icon name="box" /> Produk
                </a>
                <a href="{{ route('cart.index') }}" class="nav-item {{ request()->routeIs('cart.*', 'checkout.*') ? 'is-active' : '' }}" @if (request()->routeIs('cart.*', 'checkout.*')) aria-current="page" @endif>
                    <x-icon name="cart" /> Keranjang
                    @if (\App\Support\Cart::count() > 0)
                        <span class="nav-badge" aria-label="{{ \App\Support\Cart::count() }} barang">{{ \App\Support\Cart::count() }}</span>
                    @endif
                </a>
                <a href="{{ route('pesanan.index') }}" class="nav-item {{ request()->routeIs('pesanan.*') ? 'is-active' : '' }}" @if (request()->routeIs('pesanan.*')) aria-current="page" @endif>
                    <x-icon name="receipt" /> Pesanan
                </a>
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" @if (request()->routeIs('dashboard')) aria-current="page" @endif>
                    <x-icon name="user" /> Akun
                </a>
                @auth
                    @if (auth()->user()->isAdmin())
                        <div class="nav-sep" role="separator"></div>
                        <a href="{{ route('admin.dashboard') }}" class="nav-item"><x-icon name="shield" /> Panel Admin</a>
                    @endif
                @endauth
            </nav>
        </aside>

        <div class="main">
            <header class="topbar">
                <form class="searchbar" method="GET" action="{{ route('produk.index') }}" role="search">
                    <x-icon name="search" />
                    <label for="q" class="sr-only">Cari produk</label>
                    <input id="q" name="q" type="search" value="{{ request()->routeIs('produk.index') ? request('q') : '' }}" placeholder="Cari produk favoritmu...">
                    <button type="submit" class="btn btn-brown btn-sm">Cari</button>
                </form>

                <div class="topbar-user">
                    @auth
                        <span class="user-chip">
                            <span class="avatar" aria-hidden="true">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                            {{ auth()->user()->name }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm"><x-icon name="logout" :size="18" /> Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-olive btn-sm">Masuk</a>
                    @endauth
                </div>
            </header>

            @include('partials.flash')

            @yield('content')
        </div>
    </div>
</body>
</html>
