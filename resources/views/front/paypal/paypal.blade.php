@extends('front.layout.layout')
@section('content')
    <!-- Page Introduction Wrapper /- -->
    <div class="page-style-a">
        <div class="container">
            <div class="page-intro">
                <h2>Paypal</h2>
                <ul class="bread-crumb">
                    <li class="has-separator">
                        <i class="ion ion-md-home"></i>
                        <a href="index.html">Home</a>
                    </li>
                    <li class="is-marked">
                        <a href="#">Proceed to Pyament</a>
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
                    <form action="{{ route('payment') }}" method="POST">@csrf
                        <input type="hidden" name="amount" value="{{ round(Session::get('grand_total')/130,2) }}">
                        <input type="image" src="{{ asset('front/images/payment_logos/paypal.png') }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart-Page /- -->
@endsection
