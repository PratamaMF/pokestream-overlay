<?php

namespace App\Http\Controllers;

use App\Events\RealtimeDataUpdated;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function posIndex()
    {
        $products = Product::with('category')->where('status', 'ready')->orderBy('product_name', 'asc')->get();
        return view('orders.pos', compact('products'));
    }

    public function posStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'items'         => 'required|array|min:1',
            'items.*.id'    => 'required|exists:products,id',
            'items.*.qty'   => 'required|integer|min:1',
        ], [
            'customer_name.required' => 'Customer name is required.',
            'items.required'         => 'Please add at least 1 product to cart.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('SA-error', 'Failed! Please check your input.');
        }

        DB::beginTransaction();

        try {
            $order = new Order();
            $order->customer_name = $request->customer_name;
            $order->status = 'in_queue';
            $order->save();

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['id']);

                if ($product->stock < $item['qty']) {
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('SA-error', "Stock for '{$product->product_name}' is insufficient. Available: {$product->stock}");
                }

                $detail = new OrderDetail();
                $detail->order_id = $order->id;
                $detail->product_id = $product->id;
                $detail->qty = $item['qty'];
                $detail->price_at_purchase = $product->price;
                $detail->save();

                $product->stock -= $item['qty'];
                
                if ($product->stock == 0) {
                    $product->status = 'empty';
                }
                $product->save();
            }

            event(new RealtimeDataUpdated());

            DB::commit();
            return redirect()->route('pos.index')->with('SA-success', 'Order has been added to queue!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('SA-error', 'Failed to add order to queue.');
        }
    }

    public function historyIndex(Request $request)
    {
        $query = Order::with(['orderDetails.product'])->orderBy('created_at', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->get();

        return view('orders.history', compact('orders'));
    }

    public function updateStatus(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:in_queue,done,canceled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('SA-error', 'Invalid status configuration.');
        }

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            if ($newStatus === 'canceled' && $oldStatus !== 'canceled') {
                foreach ($order->orderDetails as $detail) {
                    $product = $detail->product;
                    if ($product) {
                        $product->stock += $detail->qty;
                        if ($product->status === 'empty') {
                            $product->status = 'ready'; 
                        }
                        $product->save();
                    }
                }
            }

            if ($oldStatus === 'canceled' && $newStatus !== 'canceled') {
                foreach ($order->orderDetails as $detail) {
                    $product = $detail->product;
                    if ($product) {
                        if ($product->stock < $detail->qty) {
                            DB::rollBack();
                            return redirect()->back()->with('SA-error', "Cannot reactivate. Stock for '{$product->product_name}' is insufficient.");
                        }
                        $product->stock -= $detail->qty;
                        if ($product->stock == 0) {
                            $product->status = 'empty';
                        }
                        $product->save();
                    }
                }
            }

            $order->status = $newStatus;
            $order->save();

            event(new RealtimeDataUpdated());

            DB::commit();
            return redirect()->back()->with('SA-success', "Order status successfully updated.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('SA-error', 'Failed to change transaction status.');
        }
    }

    public function exportPDF(Request $request)
    {
        $query = Order::with(['orderDetails.product'])->orderBy('created_at', 'asc');

        $dateInfo = "All Time Records";

        if ($request->filled('start_date') || $request->filled('end_date')) {
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            
            $start = $request->start_date ? date('d M Y', strtotime($request->start_date)) : 'Beginning';
            $end = $request->end_date ? date('d M Y', strtotime($request->end_date)) : 'Today';
            $dateInfo = "Period: {$start} - {$end}";
        }

        $orders = $query->get();

        $data = [
            'orders'   => $orders,
            'dateInfo' => $dateInfo,
            'printedAt'=> date('d M Y, H:i:s') . ' WIB'
        ];

        $pdf = Pdf::loadView('orders.pdf_report', $data)->setPaper('a4', 'portrait');

        $filename = 'pokeshop_sales_report_' . date('Ymd_His') . '.pdf';
        return $pdf->stream($filename);
    }
}
