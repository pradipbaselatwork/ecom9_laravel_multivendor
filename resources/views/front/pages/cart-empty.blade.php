@extends('front.layout.layout')
@section('content')
<!-- app -->
<div id="app">
    <!-- Cart-Page -->
    <div class="page-cart">
        <div class="vertical-center">
            <div class="text-center">
                <h1>Em
                    <i class="fas fa-child"></i>ty!</h1>
                <h5>Your cart is currently empty.</h5>
                <div class="redirect-link-wrapper u-s-p-t-25">
                    <a class="redirect-link" href="shop-v1-root-category.html">
                        <span>Return to Shop</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart-Page /- -->
</div>
<!-- app /- -->
<!--[if lte IE 9]>
<div class="app-issue">
    <div class="vertical-center">
        <div class="text-center">
            <h1>You are using an outdated browser.</h1>
            <span>This web app is not compatible with following browser. Please upgrade your browser to improve your security and experience.</span>
        </div>
    </div>
</div>
<style> #app {
    display: none;
} </style>
<![endif]-->
<!-- NoScript -->
<noscript>
    <div class="app-issue">
        <div class="vertical-center">
            <div class="text-center">
                <h1>JavaScript is disabled in your browser.</h1>
                <span>Please enable JavaScript in your browser or upgrade to a JavaScript-capable browser</span>
            </div>
        </div>
    </div>
    <style>
    #app {
        display: none;
    }
    </style>
</noscript>
@endsection