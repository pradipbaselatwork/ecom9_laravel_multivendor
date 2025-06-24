@extends('front.layout.layout')
@section('content')
<!-- app -->
<div id="app">
    <!-- Page Introduction Wrapper -->
    <div class="page-style-a">
        <div class="container">
            <div class="page-intro">
                <h2>FAQ</h2>
                <ul class="bread-crumb">
                    <li class="has-separator">
                        <i class="ion ion-md-home"></i>
                        <a href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="is-marked">
                        <a href="{{ url('faq') }}">FAQ</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Page Introduction Wrapper /- -->
    <div class="page-faq u-s-p-t-80">
        <div class="container">
            <div class="faq u-s-m-b-50">
                <h1>FREQUENTLY QUESTIONS</h1>
                <h1>Below are frequently asked questions, you may find the answer for yourself.</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id erat sagittis, faucibus metus malesuada, eleifend turpis. Mauris semper augue id nisl aliquet, a porta lectus mattis. Nulla at tortor augue. In eget enim diam. Donec gravida tortor sem, ac fermentum nibh rutrum sit amet. Nulla convallis mauris vitae congue consequat. Donec interdum nunc purus, vitae vulputate arcu fringilla quis. Vivamus iaculis euismod dui.</p>
            </div>
            <div class="u-s-m-b-50">
                <div class="f-a-q u-s-m-b-30">
                    <a data-toggle="collapse" href="#faq-1">How can I get discount coupon ?</a>
                    <div class="collapse show" id="faq-1">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda blanditiis error incidunt laborum modi nisi odio. Aut dolore earum fugit itaque laborum, necessitatibus quos ullam. Dolores ex porro praesentium sequi.
                        </p>
                    </div>
                </div>
                <div class="f-a-q u-s-m-b-30">
                    <a data-toggle="collapse" href="#faq-2">Do I need creat account for buy products ?</a>
                    <div class="collapse" id="faq-2">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda blanditiis error incidunt laborum modi nisi odio. Aut dolore earum fugit itaque laborum, necessitatibus quos ullam. Dolores ex porro praesentium sequi.
                        </p>
                    </div>
                </div>
                <div class="f-a-q u-s-m-b-30">
                    <a data-toggle="collapse" href="#faq-3">How can I track my order ?</a>
                    <div class="collapse" id="faq-3">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda blanditiis error incidunt laborum modi nisi odio. Aut dolore earum fugit itaque laborum, necessitatibus quos ullam. Dolores ex porro praesentium sequi.
                        </p>
                    </div>
                </div>
                <div class="f-a-q u-s-m-b-30">
                    <a data-toggle="collapse" href="#faq-4">What is the payment security system ?</a>
                    <div class="collapse" id="faq-4">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda blanditiis error incidunt laborum modi nisi odio. Aut dolore earum fugit itaque laborum, necessitatibus quos ullam. Dolores ex porro praesentium sequi.
                        </p>
                    </div>
                </div>
                <div class="f-a-q u-s-m-b-30">
                    <a data-toggle="collapse" href="#faq-5">What policy do you have for product sell ?</a>
                    <div class="collapse" id="faq-5">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda blanditiis error incidunt laborum modi nisi odio. Aut dolore earum fugit itaque laborum, necessitatibus quos ullam. Dolores ex porro praesentium sequi.
                        </p>
                    </div>
                </div>
                <div class="f-a-q u-s-m-b-30">
                    <a data-toggle="collapse" href="#faq-6">How I Return back my product ?</a>
                    <div class="collapse" id="faq-6">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda blanditiis error incidunt laborum modi nisi odio. Aut dolore earum fugit itaque laborum, necessitatibus quos ullam. Dolores ex porro praesentium sequi.
                        </p>
                    </div>
                </div>
                <div class="f-a-q u-s-m-b-30">
                    <a data-toggle="collapse" href="#faq-7">What Payment Methods are Available ?</a>
                    <div class="collapse" id="faq-7">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda blanditiis error incidunt laborum modi nisi odio. Aut dolore earum fugit itaque laborum, necessitatibus quos ullam. Dolores ex porro praesentium sequi.
                        </p>
                    </div>
                </div>
                <div class="f-a-q u-s-m-b-30">
                    <a data-toggle="collapse" href="#faq-8">What Shipping Methods are Available ?</a>
                    <div class="collapse" id="faq-8">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda blanditiis error incidunt laborum modi nisi odio. Aut dolore earum fugit itaque laborum, necessitatibus quos ullam. Dolores ex porro praesentium sequi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="select-dummy-wrapper">
        <select id="compute-select">
            <option id="compute-option">All</option>
        </select>
    </div>
    <div class="responsive-search-wrapper">
        <button type="button" class="button ion ion-md-close" id="responsive-search-close-button"></button>
        <div class="responsive-search-container">
            <div class="container">
                <p>Start typing and press Enter to search</p>
                <form class="responsive-search-form">
                    <label class="sr-only" for="search-text">Search</label>
                    <input id="search-text" type="text" class="responsive-search-field" placeholder="PLEASE SEARCH">
                    <i class="fas fa-search"></i>
                </form>
            </div>
        </div>
    </div>
</div>
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
