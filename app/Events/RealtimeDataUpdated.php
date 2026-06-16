<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
// use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RealtimeDataUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function broadcastWith(): array
    {
        return [
            'queue' => Order::with('orderDetails.product')
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
                        }),
                        
            'products' => Product::with('category')
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
                            })
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('poke-stream-channel')
        ];
    }

    public function broadcastAs(): string
    {
        return 'stream.updated';
    }
}
