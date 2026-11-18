<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function user(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $orders = $user->orders();
        $stat = [
            'total' => (clone $orders)->count(),
            'menunggu' => (clone $orders)->where('status', Order::WAITING)->count(),
            'dikirim' => (clone $orders)->where('status', Order::SHIPPED)->count(),
        ];
        $terbaru = $user->orders()->with('items')->latest('id')->take(3)->get();

        return view('user.dashboard', compact('stat', 'terbaru'));
    }

    public function admin(): View
    {
        $stat = [
            'produk' => Product::count(),
            'pesanan' => Order::count(),
            'menunggu' => Order::where('status', Order::WAITING)->count(),
            'pendapatan' => (int) Order::where('status', '!=', Order::CANCELLED)->sum('total'),
        ];
        $terbaru = Order::with('user')->latest('id')->take(5)->get();
        $menipis = Product::where('stock', '<=', 10)->orderBy('stock')->take(5)->get();

        return view('admin.dashboard', compact('stat', 'terbaru', 'menipis'));
    }
}
