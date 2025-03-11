<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public function orders_products(){
        return $this->hasMany('App\Models\OrdersProducts','order_id');
    }

    public function brand(){
        return $this->belongsTo('App\Models\Brand','brand_id');
    }
}
