<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Omnipay\Omnipay;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\Catch_;

class PaypalController extends Controller
{
    protected $gateway;
    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        // $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        // $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        // $this->gateway->setTestMode(true);
        $this->gateway->initialize([
            'clientId' => env('PAYPAL_CLIENT_ID'),
            'secret'   => env('PAYPAL_CLIENT_SECRET'),
            'testMode' => true,
        ]);
    }

    public function paypal(){
        if(Session::has('order_id')){
            return view('front.paypal.paypal');
        }else{
            return redirect('cart');
        }
    }

    public function pay(Request $request)
    {
        try {
            $paypal_amount = round(Session::get('grand_total') / 130, 2);
            $response = $this->gateway->purchase([
                'amount' => $paypal_amount,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => url('success'),
                'cancelUrl' => url('error')
            ])->send();

            if ($response->isRedirect()) {
                $response->redirect();
            } else {
                return $response->getMessage();
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function success(Request $request){
        if(!Session::has('order_id')){
            return redirect('cart');
        }
       if($request->input('paymentId') && $request->input('PayerID')){
           $transaction = $this->gateway->completePurchase(array(
               'payer_id' =>$request->input('PayerID'),
               'transactionReference' =>$request->input('payementId'),
           ));
           $response = $transaction->send();
           if($response->isSuccessful()){
                $arr = $response->getData();
                $payment = new Payment();
                $payment->order_id = Session::get('order_id');
                $payment->user_id = Auth::user()->id;
                $payment->payment_id = $arr['id'];
                $payment->payer_id = $arr['payer']['payer_info']['payer_id'];
                $payment->payer_email = $arr['payer']['payer_info']['email'];
                $payment->amount = $arr['transactions'][0]['amount']['total'];
                $payment->currency = env('PAYPAL_CURRENCY');
                $payment->payment_status = $arr['state'];
                $payment->save();
                // return "Payment is Successful. Your transaction is".$arr['id'];

                //Update the order
                $order_id = Session::get('order_id');

                //Update order status to Paid
                Order::where('id',$order_id)->update(['order_status'=>'Paid']);

                //Send Order sms
                // $message = "Dear Customer, your order ".$order_id." has been successfully placed with WebHatDevelopers.com. We will intimate you once your order is Shipped.";
                // $mobile = Auth::user()->mobile;
                // Sms = sendSms::($message,$mobile);

                $orderDetails = Order::with('orders_products')->where('id',$order_id)->first()->toArray();

                $email = Auth::user()->email;
                $messageData = [
                    'email' => $email,
                    'name' => Auth::user()->name,
                    'order_id' => $order_id,
                    'orderDetails' => $orderDetails,
                ];
                Mail::send('emails.order',$messageData,function($message)use($email){
                    $message->to($email)->subject('Order Placed - WebHatDeveloper.com');
                });

                //Empty the cart
                Cart::where('user_id',Auth::user()->id)->delete();
                return view('front.paypal.success');
           }else{
              return $response->getMessage();
        }
       }else{
            return "Payment Declined!";
       }
    }

    public function error(){
        // return "User declined the payment";
        return view('front.paypal.fail');
    }

}
