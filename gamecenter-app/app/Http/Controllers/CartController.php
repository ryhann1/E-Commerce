<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $items = Cart::items();
        $total = (int) $items->sum('subtotal');

        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        if ($product->stock < 1) {
            return back()->with('error', $product->name.' sedang habis.');
        }

        Cart::add($product, (int) ($data['qty'] ?? 1));

        // Tombol "Beli Sekarang" langsung membuka keranjang.
        if ($request->boolean('buy')) {
            return redirect()->route('cart.index');
        }

        return back()->with('success', $product->name.' ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate(['qty' => ['required', 'integer', 'min:0', 'max:99']]);

        Cart::set($product, (int) $data['qty']);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(Product $product): RedirectResponse
    {
        Cart::remove($product);

        return back()->with('success', $product->name.' dihapus dari keranjang.');
    }
}
