<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('status');
        $filter = in_array($filter, Order::STATUSES, true) ? $filter : null;

        $orders = Order::with(['user', 'items'])
            ->when($filter, fn ($q) => $q->where('status', $filter))
            ->latest('id')
            ->get();

        $statuses = Order::STATUSES;

        return view('admin.orders.index', compact('orders', 'statuses', 'filter'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
        ], [
            'status.in' => 'Status tidak valid.',
        ]);

        // Pesanan yang sudah dibatalkan bersifat final (stok sudah dikembalikan).
        if ($order->status === Order::CANCELLED) {
            return back()->with('error', 'Pesanan yang dibatalkan tidak bisa diubah lagi.');
        }

        DB::transaction(function () use ($order, $data) {
            if ($data['status'] === Order::CANCELLED) {
                foreach ($order->items()->whereNotNull('product_id')->get() as $item) {
                    Product::whereKey($item->product_id)->increment('stock', $item->qty);
                }
            }

            $order->update(['status' => $data['status']]);
        });

        return back()->with('success', 'Status '.$order->code.' diubah menjadi '.$data['status'].'.');
    }
}
