@extends('layouts.shop')

@section('title', 'Pesanan '.$order->code)

@section('content')
    @if (session('ordered'))
        <div class="success-banner">
            <span class="stat-icon"><x-icon name="check" :size="28" /></span>
            <div>
                <h2 style="font-size:24px;font-weight:800">Pesanan Berhasil Dibuat!</h2>
                <p>Nomor pesanan: <strong>{{ $order->code }}</strong>. Terima kasih telah berbelanja di STOREA.</p>
            </div>
        </div>
    @endif

    <div class="page-head">
        <div>
            <h1 class="page-title">{{ $order->code }}</h1>
            <p class="page-sub">Dibuat {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <span class="badge {{ $order->badgeClass() }}">{{ $order->status }}</span>
    </div>

    <div class="split">
        <section class="split-main">
            <div class="card panel">
                <h2 style="font-size:20px;font-weight:800;color:var(--olive-900)">Barang</h2>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td><strong>{{ $item->name }}</strong></td>
                                    <td>@rupiah($item->price)</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>@rupiah($item->subtotal)</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <aside class="split-side stack">
            <div class="card panel">
                <h2 style="font-size:20px;font-weight:800;color:var(--olive-900)">Pengiriman</h2>
                <dl class="info-list">
                    <dt>Penerima</dt><dd>{{ $order->recipient }}</dd>
                    <dt>Telepon</dt><dd>{{ $order->phone }}</dd>
                    <dt>Alamat</dt><dd>{{ $order->address }}</dd>
                    <dt>Pembayaran</dt><dd>{{ $order->payment }}</dd>
                    <dt>Total</dt><dd>@rupiah($order->total)</dd>
                </dl>
            </div>

            @if ($order->status === \App\Models\Order::WAITING)
                <form method="POST" action="{{ route('pesanan.bayar', $order) }}">
                    @csrf
                    <button type="submit" class="btn btn-brown btn-block">Bayar Sekarang (simulasi)</button>
                </form>
            @endif
            <a href="{{ route('pesanan.index') }}" class="btn btn-outline btn-block">← Semua pesanan</a>
        </aside>
    </div>
@endsection
