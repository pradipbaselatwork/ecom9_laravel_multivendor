<?php use App\Models\Product; ?>
@extends('front.layout.layout')
@section('content')
    <!-- Page Introduction Wrapper -->
    <div class="page-style-a">
        <div class="container">
            <div class="page-intro">
                <h2>Checkout</h2>
                <ul class="bread-crumb">
                    <li class="has-separator">
                        <i class="ion ion-md-home"></i>
                        <a href="index.html">Home</a>
                    </li>
                    <li class="is-marked">
                        <a href="checkout.html">Checkout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Page Introduction Wrapper /- -->
    <!-- Checkout-Page -->
    <div class="page-checkout u-s-p-t-80">
        <div class="container">
            @if (Session::has('error_message'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error: </strong>{{ Session::get('error_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <!-- Billing-&-Shipping-Details -->
                        <div class="col-lg-6" id="deliveryAddresses">
                            @include('front.products.delivery_addresses')
                        </div>
                        <!-- Billing-&-Shipping-Details /- -->
                        <!-- Checkout -->
                        <div class="col-lg-6">
                            <form class="checkoutForm" id="checkoutForm" action="{{ url('/checkout') }}" method="POST">
                                @csrf
                                @if (count($deliveryAddresses) > 0)
                                    <h4 class="section-h4" id="deliveryAddresses">Delivery Address</h4>
                                    @foreach ($deliveryAddresses as $address)
                                        <div class="control-group" style="float:left; margin-right:5px;">
                                            <input type="radio" id="address{{ $address['id'] }}" name="address_id" value="{{ $address['id'] }}" shipping_charges={{ $address['shipping_charges']}} total_price="{{ $total_price }}" coupon_amount="{{ Session::get('couponAmount') }}">
                                        </div>
                                        <div>
                                            <label class="control-label">{{ $address['name'] }}, {{ $address['address'] }},
                                                {{ $address['city'] }}, {{ $address['state'] }}, {{ $address['country'] }},
                                                {{ $address['mobile'] }}</label>
                                            <a style="float: right; margin-left:10px;" href="javascript:void(0);" data-addressid="{{ $address['id'] }}" class="removeAddress">Remove</a>
                                            <a style="float: right;" href="javascript:void(0);" data-addressid="{{ $address['id'] }}" class="editAddress">Edit</a>
                                        </div>
                                    @endforeach <br>
                                @endif
                                <h4 class="section-h4">Your Order</h4>
                                <div class="order-table">
                                    <table class="u-s-m-b-13">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $total_price = 0;  @endphp
                                            @foreach ($getCartItems as $item)
                                                <?php $getDiscountAttributePrice = Product::getDiscountAttributePrice($item['product_id'], $item['size']); ?>
                                                <tr>
                                                    <td>
                                                        <h6 class="order-h6">
                                                            <a
                                                                href="{{ route('product', ['id' => $item['product_id']]) }}">
                                                                <img style="width: 50px"
                                                                    src="{{ asset('front/images/product_images/small/' . $item['product']['product_image']) }}"
                                                                    alt="Product">
                                                                <h6 class="order-h6">
                                                                    {{ $item['product']['product_name'] }}<br>
                                                                    {{ $item['size'] }}/{{ $item['product']['product_color'] }}<br>
                                                                </h6>
                                                            </a>
                                                        </h6>
                                                        <span class="order-span-quantity">x {{ $item['quantity'] }}</span>
                                                    </td>
                                                    <td>
                                                        <h6 class="order-h6">
                                                            Rs.{{ number_format($getDiscountAttributePrice['final_price'] * $item['quantity']) }}
                                                        </h6>
                                                    </td>
                                                </tr>
                                                @php $total_price = $total_price + $getDiscountAttributePrice['final_price'] * $item['quantity'] @endphp
                                            @endforeach
                                            <tr>
                                                <td>
                                                    <h3 class="order-h3">Subtotal</h3>
                                                </td>
                                                <td>
                                                    <h3 class="order-h3">Rs.{{ number_format($total_price) }}</h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="order-h6">Shipping Charges</h6>
                                                </td>
                                                <td>
                                                    <h6 class="order-h6"><span class="shipping_charges">Rs.0</span></h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="order-h6">Coupon Discount</h6>
                                                </td>
                                                <td>
                                                    <h6 class="order-h6">
                                                    @if (Session::has('couponAmount'))
                                                        <span class="couponAmount">Rs.{{ Session::get('couponAmount') }}</span>
                                                    @else
                                                        Rs.00
                                                    @endif
                                                   </h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h3 class="order-h3">Grand Total</h3>
                                                </td>
                                                <td>
                                                    <h3 class="order-h3">
                                                        <strong class="grand_total">Rs.{{ number_format($total_price - (float) Session::get('couponAmount')) }}</strong>
                                                    </h3>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <label class="label-text">Select Payment Method</label>
                                    <div class="u-s-m-b-13">
                                        <input type="radio" name="payment_gateway" value="COD" class="radio-box"
                                            name="payment-method" id="cash-on-delivery">
                                        <label class="label-text" for="cash-on-delivery">Cash on Delivery</label>
                                    </div>
                                    <div class="u-s-m-b-13">
                                        <input type="radio" name="payment_gateway" value="Paypal" class="radio-box"
                                            name="payment-method" id="paypal">
                                        <label class="label-text" for="paypal">Paypal</label>
                                    </div>
                                    <div class="u-s-m-b-13">
                                        <input type="radio" name="payment_gateway" value="eSewa" class="radio-box"
                                            name="payment-method" id="eSewa">
                                        <label class="label-text" for="eSewa">eSewa</label>
                                    </div>
                                    <div class="u-s-m-b-13">
                                        <input type="checkbox" class="check-box" id="accept" name="accept"
                                            value="Yes" checked="" title="Please aggre to terms and conditions">
                                        <label class="label-text no-color" for="accept">I’ve read and accept the
                                            <a href="terms-and-conditions.html" class="u-c-brand">terms & conditions</a>
                                        </label>
                                    </div>
                                    <button type="submit" id="placeOrder" class="button button-outline-secondary">Place Order</button>
                                </div>
                            </form>
                        </div>
                        <!-- Checkout /- -->
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>


    <!-- Inline JavaScript BLACK RAIN ADDED HERE -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('input[name="address_id"]').on('click', function () {
                var shipping_charges = $(this).attr("shipping_charges");
                var total_price = $(this).attr("total_price");
                var coupon_amount = $(this).attr("coupon_amount");
                $(".shipping_charges").html("Rs." + shipping_charges);
                if (coupon_amount === "") {
                    coupon_amount = 0;
                }
                $(".couponAmount").html("Rs." + coupon_amount);
                var grand_total = parseInt(total_price) + parseInt(shipping_charges) - parseInt(coupon_amount);
                $(".grand_total").html("Rs." +grand_total);
            });
        });
    </script>
@endsection
