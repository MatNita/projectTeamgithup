<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    /**
     * ទាញយក Items ទាំងអស់នៃ Order នីមួយៗ
     */
    public function index()
    {
        $items = OrderItem::with('product')->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $items
        ], 200);
    }

    /**
     * មើល Detail នៃ Item មួយ
     */
    public function show(OrderItem $orderItem)
    {
        return response()->json([
            'status' => 'success',
            'data' => $orderItem->load('product')
        ], 200);
    }
}