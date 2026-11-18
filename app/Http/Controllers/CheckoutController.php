<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Support\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(): View|RedirectResponse
    {
        $items = Cart::items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $total = (int) $items->sum('subtotal');
        $payments = Order::PAYMENTS;

        return view('checkout.show', compact('items', 'total', 'payments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $items = Cart::items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $data = $request->validate([
            'recipient' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'payment' => ['required', Rule::in(Order::PAYMENTS)],
        ], [
            'recipient.required' => 'Nama penerima wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'payment.required' => 'Pilih metode pembayaran.',
            'payment.in' => 'Metode pembayaran tidak valid.',
        ]);

        $order = DB::transaction(function () use ($items, $data, $request) {
            $total = 0;
            $lines = [];

            // Cek stok sekali lagi dan hitung total dari harga di database, bukan dari session.
            foreach ($items as $item) {
                $product = Product::lockForUpdate()->find($item->product->id);

                if (! $product || $product->stock < $item->qty) {
                    throw ValidationException::withMessages([
                        'cart' => 'Stok '.$item->product->name.' tidak mencukupi. Periksa keranjangmu.',
                    ]);
                }

                $total += $product->price * $item->qty;
                $lines[] = [$product, $item->qty];
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'recipient' => $data['recipient'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'payment' => $data['payment'],
                'total' => $total,
                'status' => Order::WAITING,
            ]);
            $order->assignCode();

            foreach ($lines as [$product, $qty]) {
                $order->items()->create([
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'qty' => $qty,
                ]);
                $product->decrement('stock', $qty);
            }

            return $order;
        });

        Cart::clear();

        // 'ordered' memicu banner sukses di halaman pesanan (tanpa pesan flash dobel).
        return redirect()->route('pesanan.show', $order)->with('ordered', true);
    }
}
