<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    // ត្រូវតែប្រកាស Columns ទាំងនេះ ៖
    protected $fillable = [
        'order_id',
        'payment_method',
        'amount_paid',
        'change_given'
    ];

    // Relationship ទៅកាន់ Order Model
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}