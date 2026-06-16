<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('created_at', 'asc')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
        ], [
            'category_name.required' => 'The category name is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('SA-error', 'Validation failed. Please check your input.');
        }

        try {
            $category = new Category(); 
            $category->category_name = $request->category_name;
            $category->save();

            return redirect()->route('categories.index')->with('SA-success', 'Category created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('SA-error', 'Failed to create category.');
        }
    }

    public function show(string $id) {}

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
        ], [
            'category_name.required' => 'The category name is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('SA-error', 'Validation failed. Please check your input.');
        }

        try {
            $oldName = $category->category_name;
            $category->category_name = $request->category_name;
            $category->save();

            return redirect()->route('categories.index')->with('SA-success', 'Category updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('SA-error', 'Failed to update category.');
        }
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        try {
            $categoryName = $category->category_name;
            $category->delete();

            return redirect()->route('categories.index')->with('SA-success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('SA-error', 'Failed to delete category.');
        }
    }
}