<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order; // ហៅ Order Model មកប្រើ
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        // ទាញយក payments ព្រមទាំងទិន្នន័យ order ជាប់ជាមួយ (បើមាន relation)
        $payments = Payment::with('order')->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $payments
        ], 200);
    }

    public function show(Payment $payment)
    {
        return response()->json([
            'status' => 'success',
            'data' => $payment->load('order')
        ], 200);
    }

    // បន្ថែម Function store នេះ ដើម្បីទាញតម្លៃពី Order មកបង្កើត Payment
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'nullable|string',
            'amount_paid' => 'nullable|numeric',
            'change_given' => 'nullable|numeric'
        ]);

        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        // បង្កើត Payment ដោយប្រើឈ្មោះ Column ឱ្យត្រូវនឹង Migration
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method ?? 'qr_code', // ត្រូវជា cash, qr_code ឬ card
            'amount_paid' => $request->amount_paid ?? $order->total_amount, // ទាញតម្លៃពី Order
            'change_given' => $request->change_given ?? 0.00
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment created successfully!',
            'data' => $payment
        ], 201);
    }
}