@extends('layouts.shop')

@section('title', 'Keranjang')

@section('content')
    <div class="page-head">
        <div>
            <h1 class="page-title">Keranjang Belanja</h1>
            <p class="page-sub">Atur jumlah barang sebelum checkout.</p>
        </div>
    </div>

    @if ($items->isEmpty())
        <div class="empty">
            <x-icon name="cart" :size="40" />
            <strong>Keranjangmu masih kosong.</strong>
            <a href="{{ route('produk.index') }}" class="btn btn-olive btn-sm">Mulai belanja</a>
        </div>
    @else
        <div class="split">
            <section class="split-main" aria-label="Isi keranjang">
                @foreach ($items as $item)
                    <article class="row-card">
                        <div class="row-thumb"><x-icon :name="$item->product->icon" :size="36" /></div>
                        <div class="row-info">
                            <span class="product-cat">{{ $item->product->category }}</span>
                            <a href="{{ route('produk.show', $item->product) }}">{{ $item->product->name }}</a>
                            <span>@rupiah($item->product->price)</span>
                        </div>

                        <div class="row-price">@rupiah($item->subtotal)</div>

                        <div class="row-actions">
                            <form method="POST" action="{{ route('cart.update', $item->product) }}" class="inline-form">
                                @csrf
                                @method('PATCH')
                                <label for="qty-{{ $item->product->id }}" class="sr-only">Jumlah {{ $item->product->name }}</label>
                                <input id="qty-{{ $item->product->id }}" class="qty-input sm" type="number" name="qty"
                                       value="{{ $item->qty }}" min="0" max="{{ $item->product->stock }}">
                                <button type="submit" class="btn btn-outline btn-sm">Ubah jumlah</button>
                            </form>

                            <form method="POST" action="{{ route('cart.remove', $item->product) }}" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <x-icon name="trash" :size="18" /> Hapus
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
                <a href="{{ route('produk.index') }}">← Lanjut belanja</a>
            </section>

            <aside class="split-side">
                <div class="summary">
                    <h2>Ringkasan Belanja</h2>
                    <div class="line"><span>Jumlah barang</span><strong>{{ $items->sum('qty') }}</strong></div>
                    <div class="line total"><span>Total</span><strong>@rupiah($total)</strong></div>
                    <a href="{{ route('checkout.show') }}" class="btn btn-brown btn-block">Checkout <x-icon name="arrow" :size="18" /></a>
                    @guest
                        <p class="hint" style="color:var(--sage-200)">Kamu akan diminta masuk dulu saat checkout. Isi keranjang tetap tersimpan.</p>
                    @endguest
                </div>
            </aside>
        </div>
    @endif
@endsection
