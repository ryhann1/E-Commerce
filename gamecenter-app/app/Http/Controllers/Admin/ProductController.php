<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::orderByDesc('id')->get();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => new Product(['icon' => 'phone'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $product = Product::create($this->validated($request));

        return redirect()->route('admin.produk.index')->with('success', $product->name.' berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validated($request));

        return redirect()->route('admin.produk.index')->with('success', $product->name.' berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.produk.index')->with('success', $product->name.' dihapus.');
    }

    /** @return array<string,mixed> */
    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', Rule::in(Product::CATEGORIES)],
            'price' => ['required', 'integer', 'min:0', 'max:1000000000'],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'icon' => ['required', Rule::in(array_keys(Product::ICONS))],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'category.required' => 'Pilih kategori.',
            'category.in' => 'Kategori tidak valid.',
            'price.required' => 'Harga wajib diisi.',
            'price.integer' => 'Harga harus berupa angka bulat.',
            'price.min' => 'Harga tidak boleh negatif.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka bulat.',
            'stock.min' => 'Stok tidak boleh negatif.',
            'icon.in' => 'Ikon tidak valid.',
        ]);
    }
}
