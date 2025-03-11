<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrdersProducts;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function orders($id=null){
        if(empty($id)){
            $orders = Order::with('orders_products')->where('user_id',Auth::user()->id)->orderBy('id','Desc')->get()->toArray();
            return view('front.orders.orders')->with(compact('orders'));
        }else{
            $orderDetails = Order::with('orders_products')->where('id',$id)->first()->toArray();
            return view('front.orders.order_details')->with(compact('orderDetails'));
        }
    }
}
