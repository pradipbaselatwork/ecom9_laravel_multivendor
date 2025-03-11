<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title></title>
</head>

<body>
    <table style="width: 700px">
        {{-- <tr>
            <td>&nbsp;</td>
        </tr> --}}
        {{-- <tr>
            <td><img src="{{ asset('front/images/main-logo/stack-developers-logo.png') }}"></td>
        </tr> --}}
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Hello {{ $name }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Your order #{{ $order_id }} status has been updated to {{ $order_status }}.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        @if(!empty($courier_name) && !empty($tracking_number))
        <tr>
            <td>Courier Name is {{ $courier_name }} and Tracking Number is {{ $tracking_number }}</td>
        </tr>
        @endif
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Your Order Details are as bellow:-</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <table style="width: 95%" cellpadding="5" bgcolor="#f7f4f4">
                    <tr bgcolor="#cccccc">
                        <td><strong>Product Name</strong></td>
                        <td><strong>Product Code</strong></td>
                        <td><strong>Product Size</strong></td>
                        <td><strong>Product Color</strong></td>
                        <td><strong>Product Quantity</strong></td>
                        <td><strong>Product Price</strong></td>
                    </tr>
                    {{-- @dd($orderDetails); --}}
                    @foreach ($orderDetails['orders_products'] as $order)
                        <tr bgcolor="#f9f9f9">
                            <td>{{ $order['product_name'] }}</td>
                            <td>{{ $order['product_code'] }}</td>
                            <td>{{ $order['product_size'] }}</td>
                            <td>{{ $order['product_color'] }}</td>
                            <td>{{ $order['product_qty'] }}</td>
                            <td>{{ $order['product_price'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" align="right">Shipping Charges</td>
                        <td>RS {{ $orderDetails['shipping_charges'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right">Coupon Discount</td>
                        <td>RS
                            @if($orderDetails['coupon_amount']>0)
                               {{ $orderDetails['coupon_amount'] }}
                            @else
                               0
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right">Grand Total</td>
                        <td>RS {{ $orderDetails['grand_total'] }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <table>
                    <tr>
                        <td><strong>Delivery Address:-</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Name :</strong>{{ $orderDetails['name'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Address :</strong>{{ $orderDetails['address'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>City :</strong>{{ $orderDetails['city'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>State :</strong> {{ $orderDetails['state'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Country :</strong>{{ $orderDetails['country'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pincode :</strong>{{ $orderDetails['pincode'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Mobile :</strong>{{ $orderDetails['mobile'] }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>For any queries, you can contact us at <a href="www.webhatdevelopers.com">www.webhatdevelopers.com</a>
            </td>
        </tr>
    </table>
</body>

</html>
