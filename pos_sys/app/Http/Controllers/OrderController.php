<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Get all orders
    public function index()
    {
        $orders = Order::with([
            'customer',
            'user',
            'items.product'
        ])
        ->latest()
        ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }


    // Create Order + Order Items
    // Create Order + Order Items
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'nullable|string|unique:orders,order_number',
            'user_id' => 'nullable', // កុំប្រើ exists:users,id ដើម្បីការពារ error ពេលไม่มี user logged in
            'customer_id' => 'nullable|exists:customers,id',
            'order_type' => 'nullable|in:Dine In,Takeaway,Delivery',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'nullable|in:pending,preparing,completed,cancelled',

            'items' => 'required|array|min:1',

            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {

            // Create Order
            $order = Order::create([
                'order_number' => $validated['order_number'] ?? 'ORD-' . time(),
                'user_id' => \App\Models\User::first()->id ?? 1, // ទាញយក ID របស់ User ដំបូងដែលមានស្រាប់ក្នុង DB
                'customer_id' => $validated['customer_id'] ?? null,
                'order_type' => $validated['order_type'] ?? 'Dine In',
                'total_amount' => $validated['total_amount'],
                'status' => $validated['status'] ?? 'pending',
            ]);


            // Create Order Items
            foreach ($validated['items'] as $item) {

                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }


            DB::commit();


            // Load relationships
            $order->load([
                'customer',
                'user',
                'items.product'
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order
            ], 201);


        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Get one order
    public function show($id)
    {
        $order = Order::with([
            'customer',
            'user',
            'items.product'
        ])->find($id);


        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }


        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }


    // Update Order
    public function update(Request $request, $id)
    {
        $order = Order::find($id);


        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }


        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'order_type' => 'required|in:Dine In,Takeaway,Delivery',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,preparing,completed,cancelled',
        ]);


        $order->update($validated);


        $order->load([
            'customer',
            'user',
            'items.product'
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'data' => $order
        ]);
    }


    // Delete Order
    public function destroy($id)
    {
        $order = Order::find($id);


        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }


        $order->delete();


        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully'
        ]);
    }
}