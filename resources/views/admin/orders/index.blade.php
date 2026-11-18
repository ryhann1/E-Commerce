@extends('layouts.admin')

@section('title', 'Kelola Pesanan')
@section('heading', 'Kelola Pesanan')
@section('subheading', 'Lihat pesanan masuk dan ubah statusnya.')

@section('content')
    <nav class="chips" aria-label="Filter status">
        <a class="chip {{ $filter ? '' : 'is-active' }}" href="{{ route('admin.pesanan.index') }}">Semua</a>
        @foreach ($statuses as $s)
            <a class="chip {{ $filter === $s ? 'is-active' : '' }}" href="{{ route('admin.pesanan.index', ['status' => $s]) }}">{{ $s }}</a>
        @endforeach
    </nav>

    <section class="card">
        <div class="table-wrap">
            <table style="min-width:900px">
                <thead>
                    <tr><th>Kode</th><th>Pelanggan</th><th>Produk</th><th>Total</th><th>Status</th><th>Ubah Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($orders as $o)
                        <tr>
                            <td>
                                <strong>{{ $o->code }}</strong>
                                <div class="order-meta">{{ $o->created_at->format('d/m/Y') }}</div>
                            </td>
                            <td>{{ $o->recipient }}</td>
                            <td>{{ $o->items->map(fn ($i) => $i->name.' ×'.$i->qty)->join(', ') }}</td>
                            <td class="nowrap">@rupiah($o->total)</td>
                            <td><span class="badge {{ $o->badgeClass() }}">{{ $o->status }}</span></td>
                            <td>
                                @if ($o->status === \App\Models\Order::CANCELLED)
                                    <span class="hint">Final</span>
                                @else
                                    <form method="POST" action="{{ route('admin.pesanan.update', $o) }}" class="status-form">
                                        @csrf
                                        @method('PATCH')
                                        <label for="status-{{ $o->id }}" class="sr-only">Status {{ $o->code }}</label>
                                        <select id="status-{{ $o->id }}" name="status">
                                            @foreach ($statuses as $s)
                                                <option value="{{ $s }}" @selected($o->status === $s)>{{ $s }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-olive btn-sm">Simpan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Belum ada pesanan{{ $filter ? ' dengan status ini' : '' }}.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p class="hint" style="padding:12px 16px">Membatalkan pesanan mengembalikan stok dan tidak bisa diubah lagi.</p>
    </section>
@endsection
