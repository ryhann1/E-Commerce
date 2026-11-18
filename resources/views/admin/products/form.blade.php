@extends('layouts.admin')

@php $editing = $product->exists; @endphp

@section('title', $editing ? 'Ubah Produk' : 'Tambah Produk')
@section('heading', $editing ? 'Ubah Produk' : 'Tambah Produk')
@section('subheading', $editing ? $product->name : 'Isi data produk baru.')

@section('content')
    <form method="POST" class="form-card form" novalidate
          action="{{ $editing ? route('admin.produk.update', $product) : route('admin.produk.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="field">
            <label for="name">Nama produk</label>
            <input id="name" name="name" type="text" class="control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                   value="{{ old('name', $product->name) }}" required>
            @error('name') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="field">
                <label for="category">Kategori</label>
                <select id="category" name="category" class="control {{ $errors->has('category') ? 'is-invalid' : '' }}" required>
                    @foreach (\App\Models\Product::CATEGORIES as $c)
                        <option value="{{ $c }}" @selected(old('category', $product->category) === $c)>{{ $c }}</option>
                    @endforeach
                </select>
                @error('category') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="icon">Ikon</label>
                <select id="icon" name="icon" class="control {{ $errors->has('icon') ? 'is-invalid' : '' }}" required>
                    @foreach (\App\Models\Product::ICONS as $key => $label)
                        <option value="{{ $key }}" @selected(old('icon', $product->icon) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('icon') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="field">
                <label for="price">Harga (Rp)</label>
                <input id="price" name="price" type="number" min="0" step="1" class="control {{ $errors->has('price') ? 'is-invalid' : '' }}"
                       value="{{ old('price', $product->price) }}" required>
                @error('price') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
                <label for="stock">Stok</label>
                <input id="stock" name="stock" type="number" min="0" step="1" class="control {{ $errors->has('stock') ? 'is-invalid' : '' }}"
                       value="{{ old('stock', $product->stock) }}" required>
                @error('stock') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="field">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" class="control {{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description', $product->description) }}</textarea>
            @error('description') <span class="field-error">{{ $message }}</span> @enderror
            <span class="hint">Gambar produk memakai ikon (versi sederhana, tanpa unggah foto).</span>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-olive">{{ $editing ? 'Simpan Perubahan' : 'Simpan Produk' }}</button>
            <a href="{{ route('admin.produk.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
@endsection
