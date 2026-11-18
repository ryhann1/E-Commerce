<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — STOREA</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <span class="brand brand-name">STOREA</span>
                <span class="brand-tag">Serba Ada, Serba Murah</span>
            </div>
            @yield('role-badge')
            <nav class="nav" aria-label="Menu utama">
                @yield('nav')
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
                        <button type="submit" class="btn btn-outline btn-sm">
                            <x-icon name="logout" :size="18" /> Keluar
                        </button>
                    </form>
                </div>
            </header>

            @yield('content')
        </div>
    </div>
</body>
</html>
