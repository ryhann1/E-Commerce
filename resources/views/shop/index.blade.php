@extends('layouts.shop')

@section('title', 'Produk')

@section('content')
    <div class="page-head">
        <div>
            <h1 class="page-title">Produk</h1>
            <p class="page-sub">
                {{ $products->count() }} produk ditemukan
                @if ($q !== '') untuk "{{ $q }}" @endif
                @if ($kategori) di kategori {{ $kategori }} @endif
            </p>
        </div>
    </div>

    <div class="filters">
        <nav class="chips" aria-label="Kategori">
            <a class="chip {{ $kategori ? '' : 'is-active' }}" href="{{ route('produk.index', array_filter(['q' => $q, 'urut' => $urut])) }}">Semua</a>
            @foreach ($categories as $k)
                <a class="chip {{ $kategori === $k ? 'is-active' : '' }}" href="{{ route('produk.index', array_filter(['q' => $q, 'kategori' => $k, 'urut' => $urut])) }}">{{ $k }}</a>
            @endforeach
        </nav>

        <form class="sort-form" method="GET" action="{{ route('produk.index') }}">
            @if ($q !== '') <input type="hidden" name="q" value="{{ $q }}"> @endif
            @if ($kategori) <input type="hidden" name="kategori" value="{{ $kategori }}"> @endif
            <label for="urut">Urutkan</label>
            <select id="urut" name="urut">
                <option value="" @selected(! $urut)>Terbaru</option>
                <option value="termurah" @selected($urut === 'termurah')>Harga terendah</option>
                <option value="termahal" @selected($urut === 'termahal')>Harga tertinggi</option>
            </select>
            <button type="submit" class="btn btn-outline btn-sm">Terapkan</button>
        </form>
    </div>

    @if ($products->isEmpty())
        <div class="empty">
            <strong>Produk tidak ditemukan.</strong>
            <span>Coba kata kunci atau kategori lain.</span>
            <a href="{{ route('produk.index') }}" class="btn btn-olive btn-sm">Lihat semua produk</a>
        </div>
    @else
        <div class="grid">
            @foreach ($products as $p)
                @include('partials.product-card', ['p' => $p])
            @endforeach
        </div>
    @endif
@endsection
