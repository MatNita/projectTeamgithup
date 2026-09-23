<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('orders as total_orders')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $customers
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:customers,email',
            'status' => 'nullable|in:active,inactive',
        ]);

        $customer = Customer::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer created successfully',
            'data' => $customer
        ], 201);
    }

    public function show(Customer $customer)
    {
        $customer->loadCount('orders as total_orders');

        return response()->json([
            'status' => 'success',
            'data' => $customer
        ], 200);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'status' => 'nullable|in:active,inactive',
        ]);

        $customer->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer updated successfully',
            'data' => $customer
        ], 200);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Customer deleted successfully'
        ], 200);
    }
}