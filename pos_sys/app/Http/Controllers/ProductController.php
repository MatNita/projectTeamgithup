<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name'     => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'stock'    => 'required|integer',
        'price'    => 'required|numeric',
        'status'   => 'required|string',
        'image'    => 'nullable|image',
    ]);

    // Upload image
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        // Create unique image name
        $imageName = time() . '_' . $image->getClientOriginalName();
        // Save image to public/images/products
        $image->move(
            public_path('images/products'),
            $imageName
        );

        // Save image path in database
        $validatedData['image'] = 'images/products/' . $imageName;
    }

    // Create product
    $product = Product::create($validatedData);
    return response()->json([
        'message' => 'Product created successfully',
        'data' => $product
    ], 201);
}

    public function show(Product $product)
    {
        return response()->json($product);
    }
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'stock'    => 'sometimes|required|integer',
            'price'    => 'sometimes|required|numeric',
            'status'   => 'sometimes|required|string',
            'image'    => 'nullable|image',
        ]);
        $product->update($validatedData);
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}