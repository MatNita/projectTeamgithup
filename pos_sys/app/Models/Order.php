<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_id',
        'order_type',
        'total_amount',
        'status',
    ];

    // Order belongs to Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Order belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Order has many Order Items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}