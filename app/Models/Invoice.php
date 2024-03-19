<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =
    [
        'user_id',
        'customer_id',
        'invoice_id',
        'product_id',
        'plan_id',
        'coupon_id',
        'subscription_id',
        'due_date',
        'coupon_code',
        'amount',
        'tax',
        'setup_fees',
        'shipping_charge',
        'is_mailed',
        'is_recurring',
        'payment_status',
    ];
}
