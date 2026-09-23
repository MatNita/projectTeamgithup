<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'items.product', 'payment'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'customer_id' => 'nullable|exists:customers,id',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,qr_code,card',
            'amount_paid' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(6)),
                'user_id' => $request->user_id,
                'customer_id' => $request->customer_id,
                'total_amount' => $request->total_amount,
                'status' => 'completed',
            ]);

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => "ទំនិញ {$product->name} មិនមានស្តុកគ្រប់គ្រាន់ទេ!"
                    ], 400);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                ]);

                $product->decrement('stock_quantity', $item['quantity']);
            }

            $changeGiven = max(0, $request->amount_paid - $request->total_amount);

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount_paid' => $request->amount_paid,
                'change_given' => $changeGiven,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => ' Order created successfully!',
                'data' => $order->load(['items.product', 'payment'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }
    public function show(Order $order)
    {
        return response()->json([
            'status' => 'success',
            'data' => $order->load(['customer', 'items.product', 'payment'])
        ], 200);
    }
}