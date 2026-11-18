@extends('layouts.shop')

@section('title', 'Checkout')

@section('content')
    <div class="page-head">
        <div>
            <h1 class="page-title">Checkout</h1>
            <p class="page-sub">Isi data pengiriman, lalu konfirmasi pesanan. Pembayaran hanya simulasi.</p>
        </div>
    </div>

    <div class="split">
        <section class="split-main">
            <form method="POST" action="{{ route('checkout.store') }}" class="form-card form" id="checkout-form" novalidate>
                @csrf
                <h2 style="font-size:20px;font-weight:800;color:var(--olive-900)">Data Pengiriman</h2>

                <div class="form-row">
                    <div class="field">
                        <label for="recipient">Nama penerima</label>
                        <input id="recipient" name="recipient" type="text" class="control {{ $errors->has('recipient') ? 'is-invalid' : '' }}"
                               value="{{ old('recipient', auth()->user()->name) }}" autocomplete="name" required>
                        @error('recipient') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label for="phone">No. telepon</label>
                        <input id="phone" name="phone" type="tel" class="control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                               value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" autocomplete="tel" required>
                        @error('phone') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="field">
                    <label for="address">Alamat lengkap</label>
                    <textarea id="address" name="address" class="control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                              placeholder="Jalan, nomor, kota, kode pos" required>{{ old('address') }}</textarea>
                    @error('address') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="payment">Metode pembayaran</label>
                    <select id="payment" name="payment" class="control {{ $errors->has('payment') ? 'is-invalid' : '' }}" required>
                        @foreach ($payments as $m)
                            <option value="{{ $m }}" @selected(old('payment', $payments[0]) === $m)>{{ $m }}</option>
                        @endforeach
                    </select>
                    @error('payment') <span class="field-error">{{ $message }}</span> @enderror
                </div>
            </form>
        </section>

        <aside class="split-side">
            <div class="summary">
                <h2>Ringkasan Pesanan</h2>
                @foreach ($items as $item)
                    <div class="line">
                        <span>{{ $item->product->name }} × {{ $item->qty }}</span>
                        <strong>@rupiah($item->subtotal)</strong>
                    </div>
                @endforeach
                <div class="line total"><span>Total</span><strong>@rupiah($total)</strong></div>
                <button type="submit" form="checkout-form" class="btn btn-brown btn-block">Konfirmasi Pesanan <x-icon name="arrow" :size="18" /></button>
                <a href="{{ route('cart.index') }}" style="color:var(--sage-200)">← Kembali ke keranjang</a>
            </div>
        </aside>
    </div>
@endsection
