<?php

namespace App\Http\Controllers;

use App\Events\RealtimeDataUpdated;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderByRaw("FIELD(status, 'ready', 'empty') ASC")
            ->orderBy('created_at', 'asc')
            ->get();

        $categories = Category::orderBy('category_name', 'asc')->get();
        
        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('SA-error', 'Validation failed.');
        }

        try {
            $product = new Product();
            $product->category_id = $request->category_id;
            $product->product_name = $request->product_name;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->status = 'ready';
            $product->save();

            event(new RealtimeDataUpdated());

            return redirect()->back()->with('SA-success', 'Product created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('SA-error', 'Failed to create product.');
        }
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:ready,empty',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('SA-error', 'Validation failed.');
        }

        try {
            $oldName = $product->product_name; 
            
            $product->category_id = $request->category_id;
            $product->product_name = $request->product_name;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->status = $request->status;
            $product->save();

            event(new RealtimeDataUpdated());

            return redirect()->back()->with('SA-success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('SA-error', 'Failed to update product.');
        }
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        try {
            $productName = $product->product_name; 
            $product->delete();

            event(new RealtimeDataUpdated());

            return redirect()->back()->with('SA-success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('SA-error', 'Failed to delete product.');
        }
    }
}