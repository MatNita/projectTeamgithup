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
        'total_amount',
        'status',
    ];

    // Relationship ទៅកាន់ Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship ទៅកាន់ OrderItems
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship ទៅកាន់ Payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Relationship ទៅកាន់ User (Cashier/Seller)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}