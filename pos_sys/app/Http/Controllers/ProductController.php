<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('id', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|string'
        ]);

        $product = Product::create($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    public function show(Product $product)
    {
        $product->load('category');
        return response()->json([
            'status' => 'success',
            'data' => $product
        ], 200);
    }
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'image' => 'nullable|string'
        ]);

        $product->update($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'data' => $product
        ], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully'
        ], 200);
    }
}