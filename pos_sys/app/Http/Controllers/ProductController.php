<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET: /api/products
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);
    }

    // POST: /api/products (ADD)
    public function store(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|string',
            'product_name' => 'required|string',
            'category'     => 'required|string',
            'stock'        => 'required|integer',
            'price'        => 'required|numeric',
            'status'       => 'required|string',
        ]);

        $product = Product::create([
            'product_id'   => $request->product_id,
            'product_name' => $request->product_name,
            'category'     => $request->category,
            'stock'        => $request->stock,
            'price'        => $request->price,
            'status'       => $request->status,
            'image'        => $request->image, // Base64 string
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully!',
            'data'    => $product
        ], 201);
    }

    // PUT: /api/products/{id} (UPDATE)
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found!'
            ], 404);
        }

        $product->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'data'    => $product
        ], 200);
    }

    // DELETE: /api/products/{id} (DELETE)
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found!'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully!'
        ], 200);
    }
}