<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'package_id',
        'name',
        'number_of_client',
        'number_of_order',
        'monthly_price',
        'yearly_price',
        'start_date',
        'end_date',
        'order_id',
        'status',
        'is_trail',
    ];
}
