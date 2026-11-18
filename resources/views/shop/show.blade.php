@extends('layouts.shop')

@section('title', $product->name)

@section('content')
    <nav class="crumbs" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Beranda</a><span>/</span>
        <a href="{{ route('produk.index', ['kategori' => $product->category]) }}">{{ $product->category }}</a><span>/</span>
        <span>{{ $product->name }}</span>
    </nav>

    <section class="detail">
        <div class="detail-art"><x-icon :name="$product->icon" :size="140" /></div>

        <div class="detail-info">
            <span class="product-cat">{{ $product->category }}</span>
            <h1>{{ $product->name }}</h1>
            <div class="price-big">@rupiah($product->price)</div>

            @if ($product->stock > 0)
                <p><span class="badge badge-ship">Stok tersedia: {{ $product->stock }}</span></p>
            @else
                <p><span class="badge badge-cancel">Stok habis</span></p>
            @endif

            <p>{{ $product->description ?: 'Belum ada deskripsi untuk produk ini.' }}</p>

            <form method="POST" action="{{ route('cart.add') }}" class="buy-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <label for="qty" class="sr-only">Jumlah</label>
                <input id="qty" class="qty-input" type="number" name="qty" value="1" min="1" max="{{ max($product->stock, 1) }}" @disabled($product->stock < 1)>
                <button type="submit" class="btn btn-olive" @disabled($product->stock < 1)>
                    <x-icon name="cart" :size="18" /> Tambah ke Keranjang
                </button>
                <button type="submit" name="buy" value="1" class="btn btn-brown" @disabled($product->stock < 1)>Beli Sekarang</button>
            </form>
            @error('qty') <span class="field-error">{{ $message }}</span> @enderror
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="stack">
            <div class="section-head"><h2>Produk Serupa</h2></div>
            <div class="grid">
                @foreach ($related as $p)
                    @include('partials.product-card', ['p' => $p])
                @endforeach
            </div>
        </section>
    @endif
@endsection
