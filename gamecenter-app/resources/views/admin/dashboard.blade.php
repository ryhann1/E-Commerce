@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Selamat Datang, '.auth()->user()->name.'!')
@section('subheading', 'Kelola produk dan pesanan dengan mudah.')

@section('content')
    <section class="grid" aria-label="Ringkasan">
        <div class="card stat">
            <span class="stat-icon"><x-icon name="box" :size="24" /></span>
            <div><div class="stat-num">{{ $stat['produk'] }}</div><div class="stat-label">Total produk</div></div>
        </div>
        <div class="card stat">
            <span class="stat-icon"><x-icon name="receipt" :size="24" /></span>
            <div><div class="stat-num">{{ $stat['pesanan'] }}</div><div class="stat-label">Total pesanan</div></div>
        </div>
        <div class="card stat">
            <span class="stat-icon"><x-icon name="cart" :size="24" /></span>
            <div><div class="stat-num">{{ $stat['menunggu'] }}</div><div class="stat-label">Menunggu pembayaran</div></div>
        </div>
        <div class="card stat">
            <span class="stat-icon"><x-icon name="tag" :size="24" /></span>
            <div>
                <div class="stat-num" title="@rupiah($stat['pendapatan'])">Rp {{ number_format($stat['pendapatan'] / 1000000, 1, ',', '.') }} jt</div>
                <div class="stat-label">Pendapatan (tanpa yang dibatalkan)</div>
            </div>
        </div>
    </section>

    <section class="grid" aria-label="Pintasan">
        <a href="{{ route('admin.produk.index') }}" class="card shortcut" style="text-decoration:none">
            <span class="stat-icon"><x-icon name="box" :size="26" /></span>
            <h3>Kelola Produk</h3>
            <p>Tambah, ubah, dan hapus produk</p>
        </a>
        <a href="{{ route('admin.pesanan.index') }}" class="card shortcut" style="text-decoration:none">
            <span class="stat-icon"><x-icon name="receipt" :size="26" /></span>
            <h3>Kelola Pesanan</h3>
            <p>Lihat pesanan dan ubah statusnya</p>
        </a>
    </section>

    <section class="card panel">
        <div class="section-head">
            <h2 style="font-size:20px">Pesanan Terbaru</h2>
            <a href="{{ route('admin.pesanan.index') }}">Semua pesanan →</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Kode</th><th>Pelanggan</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($terbaru as $o)
                        <tr>
                            <td><strong>{{ $o->code }}</strong></td>
                            <td>{{ $o->recipient }}</td>
                            <td>@rupiah($o->total)</td>
                            <td><span class="badge {{ $o->badgeClass() }}">{{ $o->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="card panel">
        <div class="section-head">
            <h2 style="font-size:20px">Stok Menipis</h2>
            <span>Stok 10 atau kurang</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Produk</th><th>Kategori</th><th>Stok</th><th></th></tr></thead>
                <tbody>
                    @forelse ($menipis as $p)
                        <tr>
                            <td><strong>{{ $p->name }}</strong></td>
                            <td>{{ $p->category }}</td>
                            <td class="{{ $p->stock === 0 ? 'low-stock' : '' }}">{{ $p->stock }}</td>
                            <td><a href="{{ route('admin.produk.edit', $p) }}">Ubah</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Semua stok aman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
