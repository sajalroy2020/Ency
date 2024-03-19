<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    public function clientOrderItems(){
        return $this->hasMany(ClientOrderItem::class);
    }

    public function clientOrders(){
        return $this->hasMany(ClientOrder::class);
    }
}
