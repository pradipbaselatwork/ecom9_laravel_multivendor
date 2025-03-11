<?php

namespace App\Http\Controllers\ADmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderItemStatus;
use App\Models\OrdersLog;
use App\Models\OrdersProducts;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Dompdf\Dompdf;

class OrdersController extends Controller
{
    public function orders()
    {
        Session::put('page', 'orders');
        $adminType = Auth::guard('admin')->user()->type;
        $vendor_id = Auth::guard('admin')->user()->vendor_id;

        if ($adminType == "vendor") {
            $vendorStatus = Auth::guard('admin')->user()->status;
            if ($vendorStatus == 0) {
                return redirect('admin/update-vendor-details/personal')->with('error_message', 'Your Vendor Account is not approved yet. Please make sure to fill your valid personal, business and bank details');
            }
        }

        if ($adminType == "vendor") {
            $orders = Order::whereHas('orders_products', function ($query) use ($vendor_id) {
                $query->where('vendor_id', $vendor_id);
            })->with(['orders_products' => function ($query) use ($vendor_id) {
                $query->where('vendor_id', $vendor_id);
            }])->orderBy('id', 'desc')->get()->toArray();
        } else {
            $orders = Order::with('orders_products')->orderBy('id', 'desc')->get()->toArray();
        }


        //Pradip Synster update the code

        // if ($adminType == "vendor") {
        //     $orders = Order::with(['orders_products' => function ($query) use ($vendor_id) {
        //         $query->where('vendor_id', $vendor_id);
        //     }])->orderBy('id', 'Desc')->get()->toArray();
        // } else {
        //     $orders = Order::with('orders_products')->orderBy('id', 'Desc')->get()->toArray();
        // }
        // dd($orders);
        // echo "<pre>"; print_r($orders); die;
        return view('admin.orders.orders')->with(compact('orders'));
    }

    public function orderDetails($id)
    {
        Session::put('page', 'orders');
        $adminType = Auth::guard('admin')->user()->type;
        $vendor_id = Auth::guard('admin')->user()->vendor_id;

        if ($adminType == "vendor") {
            $vendorStatus = Auth::guard('admin')->user()->status;
            if ($vendorStatus == 0) {
                return redirect('admin/update-vendor-details/personal')->with('error_message', 'Your Vendor Account is not approved yet. Please make sure to fill your valid personal, business and bank details');
            }
        }
        if ($adminType == "vendor") {
            $orderDetails = Order::with(['orders_products' => function ($query) use ($vendor_id) {
                $query->where('vendor_id', $vendor_id);
            }])->where('id', $id)->first()->toArray();
        } else {
            $orderDetails = Order::with('orders_products')->where('id', $id)->first()->toArray();
        }
        // dd($orderDetails);
        $userDetails = User::where('id', $orderDetails['user_id'])->first()->toArray();
        $orderStatuses = OrderStatus::where('status', 1)->get()->toArray();
        $orderItemStatuses = OrderItemStatus::where('status', 1)->get()->toArray();
        $orderLogs = OrdersLog::with('orders_products')->where('order_id', $id)->orderBy('id', 'desc')->get()->toArray();
        // dd($orderLogs);

        //Calculate Total Items in Cart
        $total_items = 0;
        foreach($orderDetails['orders_products'] as $product){
             $total_items = $total_items + $product['product_qty'];
        }

        //Calculate Item Discount
        if($orderDetails['coupon_amount']>0){
          $item_discount = round($orderDetails['coupon_amount']/$total_items,2);
        }else{
          $item_discount = 0;
        }

        return view('admin.orders.order_details')->with(compact('orderDetails', 'userDetails', 'orderStatuses', 'orderItemStatuses', 'orderLogs','item_discount'));
    }

    public function updateOrderStatus(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            //Update Order Status
            Order::where('id', $data['order_id'])->update(['order_status' => $data['order_status']]);

            //Update Courier Name & Tracking Number
            if (!empty($data['courier_name']) && !empty($data['tracking_number'])) {
                Order::where('id', $data['order_id'])->update(['courier_name' => $data['courier_name'], 'tracking_number' => $data['tracking_number']]);
            }

            //Update Order Log
            $log = new OrdersLog;
            $log->order_id = $data['order_id'];
            $log->order_status = $data['order_status'];
            $log->save();

            //Get Delivery Details
            $deliveryDetails = Order::select('mobile', 'email', 'name')->where('id', $data['order_id'])->first()->toArray();
            $orderDetails = Order::with('orders_products')->where('id', $data['order_id'])->first()->toArray();
            //dd($deliveryDetails);

            //Send order status update email
            $email = $deliveryDetails['email'];
            $messageData = [
                'email' => $email,
                'name' => $deliveryDetails['name'],
                'order_id' => $data['order_id'],
                'orderDetails' => $orderDetails,
                'order_status' => $data['order_status'],
                'courier_name' => $data['courier_name'],
                'tracking_number' => $data['tracking_number'],
            ];
            // dd($messageData);

            Mail::send('emails.order_status', $messageData, function ($message) use ($email) {
                $message->to($email)->subject('Order Status updated - WebHatDeveloper.com');
            });

            // Send order status update Sms
            // $message = "Dear Customer, your order #".$order_id." status has been updated to $data['order_status']." Placed with WebHatDevelopers.com";
            // $mobile = $deliveryDetails['mobile'],
            // Sms = sendSms::($message,$mobile);

            $message = "Order Status has been updated successfulley!";
            return redirect()->back()->with('success_message', $message);
        }
    }

    public function updateOrderItemStatus(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // dd($data);
            OrdersProducts::where('id', $data['item_order_id'])->update(['item_status' => $data['item_order_status']]);
            if (!empty($data['item_courier_name']) && !empty($data['item_tracking_number'])) {
                OrdersProducts::where('id', $data['item_order_id'])->update(['courier_name' => $data['item_courier_name'], 'tracking_number' => $data['item_tracking_number']]);
            }
            $getOrderId = OrdersProducts::select('order_id')->where('id', $data['item_order_id'])->first()->toArray();
            // dd($getOrderId);

            //Update Order Log
            $log = new OrdersLog;
            $log->order_id = $getOrderId['order_id'];
            $log->order_item_id = $data['item_order_id'];
            $log->order_status = $data['item_order_status'];
            $log->save();

            //Get Delivery Details
            $deliveryDetails = Order::select('mobile', 'email', 'name')->where('id', $getOrderId['order_id'])->first()->toArray();
            $order_item_id = $data['item_order_id'];
            $orderDetails = Order::with(['orders_products' => function ($query) use ($order_item_id) {
                $query->where('id', $order_item_id);
            }])->where('id', $getOrderId['order_id'])->first()->toArray();
            // dd($orderDetails);

            if (!empty($data['item_courier_name']) && !empty($data['item_tracking_number'])) {
                //Send order item status update email
                $email = $deliveryDetails['email'];
                $messageData = [
                    'email' => $email,
                    'name' => $deliveryDetails['name'],
                    'order_id' => $getOrderId['order_id'],
                    'orderDetails' => $orderDetails,
                    'order_status' => $data['item_order_status'],
                    'courier_name' => $data['item_courier_name'],
                    'tracking_number' => $data['item_tracking_number'],
                ];
                // dd($messageData);
                Mail::send('emails.order_item_status', $messageData, function ($message) use ($email) {
                    $message->to($email)->subject('Order Item Status updated - WebHatDeveloper.com');
                });
            } else {
                //Send order item status update email
                $email = $deliveryDetails['email'];
                $messageData = [
                    'email' => $email,
                    'name' => $deliveryDetails['name'],
                    'order_id' => $getOrderId['order_id'],
                    'order_item_id' => $data['item_order_id'],
                    'orderDetails' => $orderDetails,
                    'order_status' => $data['item_order_status'],
                ];
                // dd($messageData);
                Mail::send('emails.order_item_status', $messageData, function ($message) use ($email) {
                    $message->to($email)->subject('Order Item Status updated - WebHatDeveloper.com');
                });
            }

            // Send order status update Sms
            // $message = "Dear Customer, your order #".$order_id." status has been updated to $data['order_status']." Placed with WebHatDevelopers.com";
            // $mobile = $deliveryDetails['mobile'],
            // Sms = sendSms::($message,$mobile);
            $message = "Order item status has been updated successfulley!";
            return redirect()->back()->with('success_message', $message);
        }
    }

    public function ordersInvoice($order_id)
    {
        $orderDetails = Order::with('orders_products')->where('id', $order_id)->first()->toArray();
        $userDetails = User::where('id', $orderDetails['user_id'])->first()->toArray();
        // dd($orderDetails,$userDetails);
        return view('admin.orders.order_invoice')->with(compact('orderDetails', 'userDetails'));
    }

    public function viewPDFInvoice($order_id)
    {
        $orderDetails = Order::with('orders_products')->where('id', $order_id)->first()->toArray();
        $userDetails = User::where('id', $orderDetails['user_id'])->first()->toArray();
        $invoiceHTML = '<!DOCTYPE html>
        <html lang="en">
          <head>
            <meta charset="utf-8">
            <title>Example 2</title>
            <link rel="stylesheet" media="all" />
          </head>
          <style>
          @font-face {
            font-family: SourceSansPro;
            src: url(SourceSansPro-Regular.ttf);
          }

          .clearfix:after {
            content: "";
            display: table;
            clear: both;
          }

          a {
            color: #0087C3;
            text-decoration: none;
          }

          body {
            position: relative;
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-family: SourceSansPro;
          }

          header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
          }

          #logo {
            float: left;
            margin-top: 8px;
          }

          #logo img {
            height: 70px;
          }

          #company {
            float: right;
            text-align: right;
          }


          #details {
            margin-bottom: 50px;
          }

          #client {
            padding-left: 6px;
            border-left: 6px solid #0087C3;
            float: left;
          }

          #client .to {
            color: #777777;
          }

          h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
          }

          #invoice {
            float: right;
            text-align: right;
          }

          #invoice h1 {
            color: #0087C3;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0  0 10px 0;
          }

          #invoice .date {
            font-size: 1.1em;
            color: #777777;
          }

          table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
          }

          table th,
          table td {
            padding: 20px;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
          }

          table th {
            white-space: nowrap;
            font-weight: normal;
          }

          table td {
            text-align: right;
          }

          table td h3{
            color: #57B223;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0.2em 0;
          }

          table .no {
            color: #FFFFFF;
            font-size: 1.6em;
            background: #57B223;
          }

          table .desc {
            text-align: left;
          }

          table .unit {
            background: #DDDDDD;
          }

          table .qty {
          }

          table .total {
            background: #57B223;
            color: #FFFFFF;
          }

          table td.unit,
          table td.qty,
          table td.total {
            font-size: 1.2em;
          }

          table tbody tr:last-child td {
            border: none;
          }

          table tfoot td {
            padding: 10px 20px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1.2em;
            white-space: nowrap;
            border-top: 1px solid #AAAAAA;
          }

          table tfoot tr:first-child td {
            border-top: none;
          }

          table tfoot tr:last-child td {
            color: #57B223;
            font-size: 1.4em;
            border-top: 1px solid #57B223;

          }

          table tfoot tr td:first-child {
            border: none;
          }

          #thanks{
            font-size: 2em;
            margin-bottom: 50px;
          }

          #notices{
            padding-left: 6px;
            border-left: 6px solid #0087C3;
          }

          #notices .notice {
            font-size: 1.2em;
          }

          footer {
            color: #777777;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #AAAAAA;
            padding: 8px 0;
            text-align: center;
          }
          </style>
          <body>
            <main>
              <div id="details" class="clearfix">
                <div id="client">
                  <div class="to">INVOICE TO:</div>
                  <h2 class="name">' . $orderDetails['name'] . '</h2>
                  <div class="address">' . $orderDetails['city'] . ', ' . $orderDetails['address'] . ', ' . $orderDetails['mobile'] . ', ' . $orderDetails['country'] . ',</div>
                  <div class="email"><a href="mailto:' . $orderDetails['email'] . '">' . $orderDetails['email'] . '</a></div>
                </div>
                <div id="invoice">
                  <h1>INVOICE ' . $orderDetails['id'] . '</h1>
                  <div class="date">Order Date:' . date('Y-m-d, h:i:s', strtotime($orderDetails['created_at'])) . '</div>
                  <div class="date">Order Amount:Rs ' . number_format($orderDetails['grand_total']) . '</div>
                  <div class="date">Order Status: ' . $orderDetails['order_status'] . '</div>
                  <div class="date">Order Payment: ' . $orderDetails['payment_method'] . '</div>
                </div>
              </div>
              <table border="0" cellspacing="0" cellpadding="0">
                <thead>
                  <tr>
                    <th class="total">PRODUCT CODE</th>
                    <th class="total">SIZE</th>
                    <th class="total">COLOR</th>
                    <th class="total">PRICE</th>
                    <th class="total">QUANTITY</th>
                    <th class="total">TOTAL</th>
                  </tr>
                </thead>
                <tbody>';
        $subTotal = 0;
        foreach ($orderDetails['orders_products'] as $product) {
            $invoiceHTML .= '<tr>
                    <td class="unit">' . $product['product_code'] . '</td>
                    <td class="unit">' . $product['product_size'] . '</td>
                    <td class="unit">' . $product['product_color'] . '</td>
                    <td class="unit">Rs ' . number_format($product['product_price'] ). '</td>
                    <td class="unit">' . $product['product_qty'] . '</td>
                    <td class="unit">Rs ' . number_format($product['product_qty'] * $product['product_price']) . '</td>
                    </tr>';
            $subTotal = $subTotal + ($product['product_price'] * $product['product_qty']);
        }
        $invoiceHTML .= '</tbody>
                <tfoot>
                  <tr>
                    <td colspan="2"></td>
                    <td colspan="2">SUBTOTAL AMOUNT</td>
                    <td>Rs ' . number_format($subTotal) . '</td>
                  </tr>
                  <tr>
                    <td colspan="2"></td>
                    <td colspan="2">SHIPPING CHARGES</td>
                    <td>Rs ' . (!empty($orderDetails['shipping_charges']) ? number_format($orderDetails['shipping_charges']) : '0') . '</td>
                  </tr>
                    <tr>
                    <td colspan="2"></td>
                    <td colspan="2">COUPON DISCOUNT</td>
                    <td>Rs ' . (!empty($orderDetails['coupon_amount']) ? number_format($orderDetails['coupon_amount']) : '0') . '</td>
                  </tr>
                  <tr>
                    <td colspan="2"></td>
                    <td colspan="2">GRAND TOTAL</td>
                    <td>Rs ' . number_format($orderDetails['grand_total']) . '</td>
                  </tr>
                </tfoot>
              </table>
              <div id="thanks">Thank you!</div>
            </main>
            <footer>
              Invoice was created on a computer and is valid without the signature and seal.
            </footer>
          </body>
        </html>';
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($invoiceHTML);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();
    }
}
