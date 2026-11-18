@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('heading', 'Kelola Produk')
@section('subheading', 'Tambah, ubah, dan hapus produk yang tampil di toko.')

@section('content')
    <div class="page-head">
        <span class="hint">{{ $products->count() }} produk</span>
        <a href="{{ route('admin.produk.create') }}" class="btn btn-olive"><x-icon name="plus" :size="18" /> Tambah Produk</a>
    </div>

    <section class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($products as $p)
                        <tr>
                            <td>
                                <div class="cell-prod">
                                    <span class="cell-thumb"><x-icon :name="$p->icon" :size="24" /></span>
                                    <strong>{{ $p->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $p->category }}</td>
                            <td>@rupiah($p->price)</td>
                            <td class="{{ $p->stock === 0 ? 'low-stock' : '' }}">{{ $p->stock }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.produk.edit', $p) }}" class="btn btn-outline btn-sm">Ubah</a>
                                    <form method="POST" action="{{ route('admin.produk.destroy', $p) }}"
                                          onsubmit="return confirm('Hapus {{ e(addslashes($p->name)) }}? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">Belum ada produk. Klik "Tambah Produk".</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
