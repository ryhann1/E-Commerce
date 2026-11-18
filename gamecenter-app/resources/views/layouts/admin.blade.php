<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — STOREA Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand brand-link">
                <span class="brand brand-name">STOREA</span>
                <span class="brand-tag">Serba Ada, Serba Murah</span>
            </a>
            <span class="sidebar-role">ADMIN</span>

            <nav class="nav" aria-label="Menu admin">
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif>
                    <x-icon name="grid" /> Dashboard
                </a>
                <a href="{{ route('admin.produk.index') }}" class="nav-item {{ request()->routeIs('admin.produk.*') ? 'is-active' : '' }}" @if (request()->routeIs('admin.produk.*')) aria-current="page" @endif>
                    <x-icon name="box" /> Kelola Produk
                </a>
                <a href="{{ route('admin.pesanan.index') }}" class="nav-item {{ request()->routeIs('admin.pesanan.*') ? 'is-active' : '' }}" @if (request()->routeIs('admin.pesanan.*')) aria-current="page" @endif>
                    <x-icon name="receipt" /> Kelola Pesanan
                </a>
                <div class="nav-sep" role="separator"></div>
                <a href="{{ route('home') }}" class="nav-item"><x-icon name="store" /> Lihat Toko</a>
            </nav>
        </aside>

        <div class="main">
            <header class="topbar">
                <div>
                    <h1 class="topbar-title">@yield('heading')</h1>
                    <p class="topbar-sub">@yield('subheading')</p>
                </div>
                <div class="topbar-user">
                    <span class="user-chip">
                        <span class="avatar" aria-hidden="true">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm"><x-icon name="logout" :size="18" /> Keluar</button>
                    </form>
                </div>
            </header>

            @include('partials.flash')

            @yield('content')
        </div>
    </div>
</body>
</html>
