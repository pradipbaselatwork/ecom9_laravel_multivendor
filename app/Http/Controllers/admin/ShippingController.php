<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingCharges;
use Illuminate\Support\Facades\Session;

class ShippingController extends Controller
{
    public function shippingCharges(){
        Session::put('page','shipping');
        $shippingCharges = ShippingCharges::get()->toArray();
        // echo "<pre>"; print_r($shippingCharges); die;
        return view('admin.shippings.shipping_charges')->with(compact('shippingCharges'));
    }

    public function updateShippingStatus(Request $request){
        if($request->ajax()){
           $data = $request->all();
        //    echo "<pre>"; print_r($data); die;
           if($data['status']=="Active"){
               $status = 0;
           }else{
               $status = 1;
           }
        ShippingCharges::where('id',$data['shipping_id'])->update(['status'=>$status]);
        return response()->json(['status'=>$status,'shipping_id'=>$data['shipping_id']]);
        }
   }

   public function addEditShippingCharges(Request $request, $id = null)
   {
       Session::put('page', 'shipping');

       if ($id == null) {
           $title = "Add Shipping Charges";
           $shipping = new ShippingCharges;
           $message = "Shipping Charges added successfully!";
       } else {
           $title = "Edit Shipping Charges";
           $shipping = ShippingCharges::find($id);
           $message = "Shipping Charges updated successfully!";
       }

       if ($request->isMethod('post')) {
           $data = $request->all();

           // Validation rules
           $rules = [
               'country' => 'required',
           ];
           $customMessages = [
               'country.required' => 'Country is required',
           ];
           $this->validate($request, $rules, $customMessages);

           // Save the data using array notation for column names with special characters
           $shipping->country = $data['country'];
           $shipping->status = 1;
           $shipping['0_500g'] = $data['0_500g'];
           $shipping['501_1000g'] = $data['501_1000g'];
           $shipping['1001_2000g'] = $data['1001_2000g'];
           $shipping['2001_5000g'] = $data['2001_5000g'];
           $shipping['above_5000g'] = $data['above_5000g'];
           $shipping->save();

           return redirect()->route('admin.shipping-charges')->with('success_message', $message);
       }

       return view('admin.shippings.add_edit_shipping_charges')->with(compact('title', 'shipping'));
   }

   public function deleteShippingCharges($id){
     ShippingCharges::where('id',$id)->delete();
     $message = "Shipping Charges has been deleted successfulley!";
     return redirect()->back()->with('success_message',$message);
   }
}
