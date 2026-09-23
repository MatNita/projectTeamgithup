<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $payments
        ], 200);
    }

    public function show(Payment $payment)
    {
        return response()->json([
            'status' => 'success',
            'data' => $payment
        ], 200);
    }
}