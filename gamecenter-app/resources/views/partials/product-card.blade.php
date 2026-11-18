<article class="card product-card">
    <a href="{{ route('produk.show', $p) }}" class="product-thumb" aria-label="Lihat {{ $p->name }}">
        <x-icon :name="$p->icon" :size="64" />
    </a>
    <div class="product-body">
        <span class="product-cat">{{ $p->category }}</span>
        <a href="{{ route('produk.show', $p) }}" class="product-name">{{ $p->name }}</a>
        <span class="product-price">@rupiah($p->price)</span>
        <span class="product-stock">{{ $p->stock > 0 ? 'Stok: '.$p->stock : 'Stok habis' }}</span>
        <div class="product-actions">
            <a href="{{ route('produk.show', $p) }}" class="btn btn-outline btn-sm">Detail</a>
            <form method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $p->id }}">
                <button type="submit" class="btn btn-olive btn-sm" @disabled($p->stock < 1)>+ Keranjang</button>
            </form>
        </div>
    </div>
</article>
