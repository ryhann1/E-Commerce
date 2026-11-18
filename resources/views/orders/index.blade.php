@extends('layouts.shop')

@section('title', 'Pesanan Saya')

@section('content')
    <div class="page-head">
        <div>
            <h1 class="page-title">Pesanan Saya</h1>
            <p class="page-sub">Riwayat pesanan dan status terbarunya.</p>
        </div>
    </div>

    @forelse ($orders as $order)
        <article class="order-card">
            <div class="order-head">
                <div>
                    <div class="order-code">{{ $order->code }}</div>
                    <div class="order-meta">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <span class="badge {{ $order->badgeClass() }}">{{ $order->status }}</span>
            </div>

            <ul class="order-lines">
                @foreach ($order->items as $item)
                    <li>{{ $item->name }} × {{ $item->qty }}</li>
                @endforeach
            </ul>

            <div class="order-foot">
                <div>
                    <div class="order-meta">Total pesanan</div>
                    <div class="order-total">@rupiah($order->total)</div>
                </div>
                <div class="actions">
                    @if ($order->status === \App\Models\Order::WAITING)
                        <form method="POST" action="{{ route('pesanan.bayar', $order) }}">
                            @csrf
                            <button type="submit" class="btn btn-brown btn-sm">Bayar Sekarang</button>
                        </form>
                    @endif
                    <a href="{{ route('pesanan.show', $order) }}" class="btn btn-outline btn-sm">Detail</a>
                </div>
            </div>
        </article>
    @empty
        <div class="empty">
            <x-icon name="receipt" :size="40" />
            <strong>Belum ada pesanan.</strong>
            <a href="{{ route('produk.index') }}" class="btn btn-olive btn-sm">Mulai belanja</a>
        </div>
    @endforelse
@endsection
