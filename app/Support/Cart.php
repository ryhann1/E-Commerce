<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Keranjang belanja berbasis session: [product_id => jumlah].
 * Bisa dipakai tamu (belum login); isinya ikut terbawa saat login.
 */
class Cart
{
    private const KEY = 'cart';

    /** @return array<int,int> */
    private static function raw(): array
    {
        return session(self::KEY, []);
    }

    public static function add(Product $product, int $qty = 1): void
    {
        $cart = self::raw();
        $cart[$product->id] = min(($cart[$product->id] ?? 0) + $qty, $product->stock);
        session([self::KEY => $cart]);
    }

    public static function set(Product $product, int $qty): void
    {
        $cart = self::raw();

        if ($qty <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = min($qty, $product->stock);
        }

        session([self::KEY => $cart]);
    }

    public static function remove(Product $product): void
    {
        $cart = self::raw();
        unset($cart[$product->id]);
        session([self::KEY => $cart]);
    }

    public static function clear(): void
    {
        session()->forget(self::KEY);
    }

    /** Jumlah total barang (untuk badge di menu), tanpa query database. */
    public static function count(): int
    {
        return array_sum(self::raw());
    }

    /**
     * Isi keranjang dengan data produk terbaru.
     * Produk yang sudah dihapus / stoknya habis dibuang, jumlah dibatasi sesuai stok.
     *
     * @return Collection<int,object{product:Product,qty:int,subtotal:int}>
     */
    public static function items(): Collection
    {
        $cart = self::raw();

        if ($cart === []) {
            return collect();
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $clean = [];
        $items = collect();

        foreach ($cart as $id => $qty) {
            $product = $products->get($id);

            if (! $product || $product->stock < 1) {
                continue;
            }

            $qty = min((int) $qty, $product->stock);
            $clean[$id] = $qty;
            $items->push((object) [
                'product' => $product,
                'qty' => $qty,
                'subtotal' => $product->price * $qty,
            ]);
        }

        if ($clean !== $cart) {
            session([self::KEY => $clean]);
        }

        return $items;
    }

    public static function total(): int
    {
        return (int) self::items()->sum('subtotal');
    }
}
