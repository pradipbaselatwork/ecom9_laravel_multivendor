@extends('front.layout.layout')
@section('content')
    <!-- Page Introduction Wrapper /- -->
    <div class="page-style-a">
        <div class="container">
            <div class="page-intro">
                <h2>Thanks</h2>
                <ul class="bread-crumb">
                    <li class="has-separator">
                        <i class="ion ion-md-home"></i>
                        <a href="index.html">Home</a>
                    </li>
                    <li class="is-marked">
                        <a href="#">Thanks</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Cart-Page -->
    <div class="page-cart u-s-p-t-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-12" style="text-align: center">
                   <h3>YOUR ORDER HASBEEN PLACED SUCCESSFULLY</h3>
                   <P>Your order number is {{ Session::get('order_id') }} and your Grand total is NPR -/ {{ Session::get('grand_total') }}</P>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart-Page /- -->
@endsection
@php
     Session::get('grand_total');
     Session::get('order_id');
     Session::get('couponCode');
     Session::get('couponAmount');
@endphp
