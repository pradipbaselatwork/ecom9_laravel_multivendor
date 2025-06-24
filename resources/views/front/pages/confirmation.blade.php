@extends('front.layout.layout')
@section('content')
<!-- app -->
<div id="app">
    <!-- Checkout-Confirmation-Page -->
    <div class="page-checkout-confirm">
        <div class="vertical-center">
            <div class="text-center">
                <h1>Thank you!</h1>
                <h5>If you haven't received it yet. click to
                    <button>resend confirmation email</button>.</h5>
                <a href="index.html" class="thank-you-back">Back to homepage</a>
            </div>
        </div>
    </div>
    <!-- Checkout-Confirmation-Page /- -->
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