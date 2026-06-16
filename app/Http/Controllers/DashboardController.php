<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $queueOrders = Order::with('orderDetails.product')
            ->where('status', 'in_queue')
            ->orderBy('created_at', 'asc')
            ->get();

        $readyProducts = Product::with('category')
            ->where('status', 'ready')
            ->where('stock', '>', 0)
            ->orderBy('category_id')
            ->orderBy('product_name', 'asc')
            ->get();

        return view('dashboard', compact('queueOrders', 'readyProducts'));
    }

    public function getLiveSnapshot()
    {
        $queue = Order::with('orderDetails.product')
            ->where('status', 'in_queue')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($order, $index) {
                return [
                    'no' => $index + 1,
                    'customer_name' => $order->customer_name,
                    'items' => $order->orderDetails->map(function ($d) {
                        return "{$d->product->product_name} (x{$d->qty})";
                    })->join(', '),
                    'time' => $order->created_at->format('H:i')
                ];
            });

        $products = Product::with('category')
            ->where('status', 'ready')
            ->where('stock', '>', 0)
            ->orderBy('category_id')
            ->orderBy('product_name', 'asc')
            ->get()
            ->map(function ($product) {
                return [
                    'category' => $product->category->category_name ?? 'Uncategorized',
                    'name' => $product->product_name,
                    'price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                    'stock' => $product->stock
                ];
            });

        return response()->json([
            'queue' => $queue,
            'products' => $products
        ]);
    }
}
