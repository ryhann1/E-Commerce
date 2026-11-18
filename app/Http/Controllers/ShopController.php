<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function home(): View
    {
        $kategori = Product::CATEGORIES;
        $unggulan = Product::where('stock', '>', 0)->latest('id')->take(8)->get();

        return view('shop.home', compact('kategori', 'unggulan'));
    }

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $kategori = $request->query('kategori');
        $urut = $request->query('urut');

        $query = Product::query()->search($q);

        if (in_array($kategori, Product::CATEGORIES, true)) {
            $query->where('category', $kategori);
        } else {
            $kategori = null;
        }

        match ($urut) {
            'termurah' => $query->orderBy('price'),
            'termahal' => $query->orderByDesc('price'),
            default => $query->orderByDesc('id'),
        };

        $products = $query->get();
        $categories = Product::CATEGORIES;

        return view('shop.index', compact('products', 'categories', 'q', 'kategori', 'urut'));
    }

    public function show(Product $product): View
    {
        $related = Product::where('category', $product->category)
            ->whereKeyNot($product->id)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'related'));
    }
}
