<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Cart;
use Omnipay\Omnipay;
use Exception;
use Illuminate\Support\Facades\Log;


class EsewaController extends Controller
{
    protected $gateway;

    public function __construct()
    {
        $merchantCode = env('ESEWA_MERCHANT_ID');
        $testMode = env('ESEWA_TEST_MODE', true);

        // Debugging
        Log::info('Merchant Code: ' . $merchantCode);
        Log::info('Test Mode: ' . $testMode);

        $this->gateway = Omnipay::create('Esewa_Secure');
        $this->gateway->initialize([
            'merchantCode' => $merchantCode,
            'testMode'     => (bool) $testMode,
        ]);
    }

    public function esewa()
    {
        if (Session::has('order_id')) {
            return view('front.esewa.esewa');
        } else {
            return redirect('cart');
        }
    }

    public function pay(Request $request)
    {
        try {
            // Retrieve the grand total and ensure it's a numeric value
            $amount = Session::get('grand_total');
            // Ensure amount is a plain numeric value without commas
            $amount = str_replace(',', '', number_format($amount, 2, '.', ''));

            // Log the amount for debugging purposes
            Log::info('Payment Amount: ' . $amount);

            $response = $this->gateway->purchase([
                'amount'        => $amount,
                'currency'      => env('ESEWA_CURRENCY'),
                'returnUrl'     => url('esewa-success'),
                'cancelUrl'     => url('esewa-error'),
                'failedUrl'     => url('esewa-failed'),
                'merchantCode'  => env('ESEWA_MERCHANT_ID'),
                'totalAmount'   => $amount,
                'productCode'   => Session::get('order_id'), // Replace with actual product code or identifier
                'testMode'      => (bool) env('ESEWA_TEST_MODE', true),
            ])->send();

            // Check response and handle redirection or error
            if ($response->isRedirect()) {
                $response->redirect();
            } else {
                return $response->getMessage();
            }
        } catch (\Throwable $th) {
            Log::error('eSewa payment error: ' . $th->getMessage());
            return 'An error occurred during the payment process.';
        }
    }

    public function success(Request $request)
    {
        if (!Session::has('order_id')) {
            return redirect('cart');
        }


        if ($request->oid && $request->amt && $request->refId) {
            // dd($request);
            // $order = Order::where('invoice_no', $request->oid)->first();
            $order = Order::with('orders_products')->where('id', $request->oid)->first()->toArray();
            // dd($order);
            if ($order) {
                $url = "https://uat.esewa.com.np/epay/transrec";
                $data = [
                    'amount' => $request->amt,
                    'payment_id' => $request->refId,
                    'order_id' => $request->oid,
                    'currency' => 'NPR',
                    'payment_status' => 'Paid',
                ];
            }
            // dd($url);

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // $response = curl_exec($curl);
            // dd($response);
            curl_close($curl);
            //   dd($data);

            $payment = new Payment();
            $payment->order_id = Session::get('order_id');
            // $payment->order_id = Session::get('order_id');
            $payment->user_id = Auth::user()->id;
            $payment->payer_email = Auth::user()->email;
            $payment->payment_id = $data['payment_id'];
            $payment->amount = $data['amount'];
            // $payment->payment_id = $arr['id'];
            // $payment->payer_id = $arr['payer']['payer_info']['payer_id'];
            // $payment->payer_email = $arr['payer']['payer_info']['email'];
            // $payment->amount = $arr['transactions'][0]['amount']['total'];
            $payment->currency = 'NPR';
            $payment->payment_status = $data['payment_status'];
            $payment->save();
            // $response_code = $this->get_response('response_code',$response);

            // Update the order
            $order_id = Session::get('order_id');
            Order::where('id', $order_id)->update(['order_status' => 'Paid']);
            
            $orderDetails = Order::with('orders_products')->where('id', $request->oid)->first()->toArray();
            //Send Order Email
            $email = Auth::user()->email;
            $messageData = [
                'email' => $email,
                'name' => Auth::user()->name,
                'order_id' => $order_id,
                'orderDetails' => $order,
            ];
            Mail::send('emails.order', $messageData, function ($message) use ($email) {
                $message->to($email)->subject('Order Placed - WebHatDeveloper.com');
            });

            //Reduce Stock Script Starts
              foreach($orderDetails['orders_products'] as $key =>$order){
                 $getProductStock = ProductAttribute::getProductStock($order['product_id'],$order['product_size']);
                 $newStock = $getProductStock - $order['product_qty'];
                 ProductAttribute::where(['product_id'=>$order['product_id'],'size'=>$order['product_size']])->update(['stock'=>$newStock]);
              }
            //Reduce Stock Script Ends

            // Empty the cart
            Cart::where('user_id', Auth::user()->id)->delete();
            return view('front.esewa.success');
        }
        // if ($request->input('paymentId') && $request->input('PayerID')) {
        // $transaction = $this->gateway->completePurchase([
        //     'payer_id' => $request->input('PayerID'),
        //     'transactionReference' => $request->input('paymentId'),
        // ]);
        // dd($transaction);
        //     $response = $transaction->send();

        //     if ($response->isSuccessful()) {
        //         $arr = $response->getData();
        //         $payment = new Payment();
        //         $payment->order_id = Session::get('order_id');
        //         $payment->user_id = Auth::user()->id;
        //         $payment->payment_id = $arr['id'];
        //         $payment->payer_id = $arr['payer']['payer_info']['payer_id'];
        //         $payment->payer_email = $arr['payer']['payer_info']['email'];
        //         $payment->amount = $arr['transactions'][0]['amount']['total'];
        //         $payment->currency = 'NPR';
        //         $payment->payment_status = $arr['state'];
        //         $payment->save();

        //         // Update the order
        //         $order_id = Session::get('order_id');
        //         Order::where('id', $order_id)->update(['order_status' => 'Paid']);

        //         // Send Order email
        //         $orderDetails = Order::with('orders_products')->where('id', $order_id)->first()->toArray();
        //         $email = Auth::user()->email;
        //         $messageData = [
        //             'email' => $email,
        //             'name' => Auth::user()->name,
        //             'order_id' => $order_id,
        //             'orderDetails' => $orderDetails,
        //         ];
        //         Mail::send('emails.order', $messageData, function ($message) use ($email) {
        //             $message->to($email)->subject('Order Placed - WebHatDeveloper.com');
        //         });

        //         // Empty the cart
        //         Cart::where('user_id', Auth::user()->id)->delete();
        //         return view('front.esewa.success');
        //     } else {
        //         return $response->getMessage();
        //     }
        // } else {
        //     return "Payment Declined!";
        // }


        // if ($request->input('paymentId') && $request->input('PayerID')) {
        //     $transaction = $this->gateway->completePurchase([
        //         'payer_id' => $request->input('PayerID'),
        //         'transactionReference' => $request->input('paymentId'),
        //     ]);
        //     // dd($transaction);
        //     $response = $transaction->send();

        //     if ($response->isSuccessful()) {
        //         $arr = $response->getData();
        //         $payment = new Payment();
        //         $payment->order_id = Session::get('order_id');
        //         $payment->user_id = Auth::user()->id;
        //         $payment->payment_id = $arr['id'];
        //         $payment->payer_id = $arr['payer']['payer_info']['payer_id'];
        //         $payment->payer_email = $arr['payer']['payer_info']['email'];
        //         $payment->amount = $arr['transactions'][0]['amount']['total'];
        //         $payment->currency = 'NPR';
        //         $payment->payment_status = $arr['state'];
        //         $payment->save();

        //         // Update the order
        //         $order_id = Session::get('order_id');
        //         Order::where('id', $order_id)->update(['order_status' => 'Paid']);

        //         // Send Order email
        //         $orderDetails = Order::with('orders_products')->where('id', $order_id)->first()->toArray();
        //         $email = Auth::user()->email;
        //         $messageData = [
        //             'email' => $email,
        //             'name' => Auth::user()->name,
        //             'order_id' => $order_id,
        //             'orderDetails' => $orderDetails,
        //         ];
        //         Mail::send('emails.order', $messageData, function($message) use ($email) {
        //             $message->to($email)->subject('Order Placed - WebHatDeveloper.com');
        //         });

        //         // Empty the cart
        //         Cart::where('user_id', Auth::user()->id)->delete();
        //         return view('front.esewa.success');
        //     } else {
        //         return $response->getMessage();
        //     }
        // } else {
        //     return "Payment Declined!";
        // }
    }

    public function error()
    {
        return view('front.esewa.fail');
    }
}
