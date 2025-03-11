@extends('front.layout.layout')
@section('content')
    <!-- Page Introduction Wrapper /- -->
    <div class="page-style-a">
        <div class="container">
            <div class="page-intro">
                <h2>Success</h2>
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
                   <h3>YOUR PAYMENT HAS BEEN CONFIRMED</h3>
                   <P>Thanks for the Payment. We will process you order very soon.</P>
                   <P>Your order number is {{ Session::get('order_id') }} and your Grand total paid is NPR -/ {{ Session::get('grand_total') }}</P>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart-Page /- -->
@endsection
@php
    Session::forget('grand_total');
    Session::forget('order_id');
    Session::forget('couponCode');
    Session::forget('couponAmount');
@endphp
