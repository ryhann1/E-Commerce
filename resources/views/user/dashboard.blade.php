@extends('layouts.shop')

@section('title', 'Akun')

@section('content')
    <div class="page-head">
        <div>
            <h1 class="page-title">Halo, {{ auth()->user()->name }}!</h1>
            <p class="page-sub">Ringkasan akun dan pesananmu.</p>
        </div>
    </div>

    <section class="grid" aria-label="Ringkasan">
        <div class="card stat">
            <span class="stat-icon"><x-icon name="receipt" :size="24" /></span>
            <div><div class="stat-num">{{ $stat['total'] }}</div><div class="stat-label">Total pesanan</div></div>
        </div>
        <div class="card stat">
            <span class="stat-icon"><x-icon name="cart" :size="24" /></span>
            <div><div class="stat-num">{{ $stat['menunggu'] }}</div><div class="stat-label">Menunggu pembayaran</div></div>
        </div>
        <div class="card stat">
            <span class="stat-icon"><x-icon name="box" :size="24" /></span>
            <div><div class="stat-num">{{ $stat['dikirim'] }}</div><div class="stat-label">Sedang dikirim</div></div>
        </div>
    </section>

    <section class="card panel">
        <div class="section-head">
            <h2 style="font-size:20px">Pesanan Terbaru</h2>
            <a href="{{ route('pesanan.index') }}">Lihat semua →</a>
        </div>
        @forelse ($terbaru as $order)
            <div class="order-foot" style="border-top:0;padding-top:0">
                <div>
                    <div class="order-code">{{ $order->code }}</div>
                    <div class="order-meta">{{ $order->items->pluck('name')->join(', ') }}</div>
                </div>
                <div style="text-align:right">
                    <span class="badge {{ $order->badgeClass() }}">{{ $order->status }}</span>
                    <div class="order-total" style="font-size:16px">@rupiah($order->total)</div>
                </div>
            </div>
        @empty
            <p class="hint">Belum ada pesanan. <a href="{{ route('produk.index') }}">Mulai belanja</a></p>
        @endforelse
    </section>

    <section class="card panel">
        <h2 style="font-size:20px;font-weight:800;color:var(--olive-900)">Data Akun</h2>
        <dl class="info-list">
            <dt>Nama</dt><dd>{{ auth()->user()->name }}</dd>
            <dt>Email</dt><dd>{{ auth()->user()->email }}</dd>
            <dt>Bergabung</dt><dd>{{ auth()->user()->created_at?->format('d/m/Y') }}</dd>
        </dl>
    </section>
@endsection
