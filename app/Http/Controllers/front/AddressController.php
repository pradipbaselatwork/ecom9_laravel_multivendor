<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliveryAddress;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function getDeliveryAddress(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $deliveryAddress = DeliveryAddress::where('id', $data['addressid'])->first()->toArray();
            return response()->json(['address' => $deliveryAddress]);
        }
    }

    public function saveDeliveryAddress(Request $request)
    {
        if($request->ajax()){
            $validator = Validator::make($request->all(), [
                'delivery_name' =>'required|string|max:100',
                'delivery_address' =>'required',
                'delivery_city' =>'required',
                'delivery_state' =>'required',
                'delivery_country' =>'required',
                'delivery_pincode' =>'required|numeric',
                'delivery_mobile' =>'required|numeric|digits:10',
            ]);

            if($validator->passes()){
                $data = $request->all();
                // echo "<pre>"; print_r($request); die;
                $address = array();
                $address['user_id'] = Auth::user()->id;
                $address['name'] = $data['delivery_name'];
                $address['address'] = $data['delivery_address'];
                $address['city'] = $data['delivery_city'];
                $address['state'] = $data['delivery_state'];
                $address['country'] = $data['delivery_country'];
                $address['pincode'] = $data['delivery_pincode'];
                $address['mobile'] = $data['delivery_mobile'];
                $address['status'] = 1;
                if (!empty($data['delivery_id'])) {
                    // Edit Delivery Address
                    DeliveryAddress::where('id', $data['delivery_id'])->update($address);
                } else {
                    // Add Delivery Address
                    DeliveryAddress::create($address);
                }
                $countries = Country::where('status',1)->get()->toArray();
                $deliveryAddresses = DeliveryAddress::deliveryAddresses();
                // dd($deliveryAddresses);
                return response()->json([
                    'view'=>(String)View::make('front.products.delivery_addresses')->with(compact('deliveryAddresses','countries'))->render()
                ]);
            }else{
                return response()->json(['type'=>'error','errors'=>$validator->messages()]);
            }
        }
    }

    public function removeDeliveryAddress(Request $request){
         if($request->ajax()){
            $data = $request->all();
            DeliveryAddress::where('id',$data['addressid'])->delete();
            $countries = Country::where('status',1)->get()->toArray();
            $deliveryAddresses = DeliveryAddress::deliveryAddresses();
            return response()->json([
                'view'=>(String)View::make('front.products.delivery_addresses')->with(compact('deliveryAddresses','countries'))->render()
            ]);
         }
    }
}
