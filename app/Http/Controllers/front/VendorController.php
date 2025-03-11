<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Vendor;
use Illuminate\Support\Facades\Mail;

class VendorController extends Controller
{
    public function loginRegister()
    {
        return view('front.vendors.login_register');
    }

    public function vendorRegister(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:admins,email|unique:vendors,email',
                'mobile' => 'required|min:10|unique:admins,mobile|unique:vendors,mobile',
                'accept' => 'required',
            ];

            $customMessages = [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exists',
                'mobile.required' => 'Mobile is required',
                'mobile.unique' => 'Mobile already exists',
                'accept.required' => 'Accept is required',
            ];
        $validator = Validator::make($data,$rules,$customMessages);
        if($validator->fails()){
            return Redirect::back()->withErrors($validator);
          }

            DB::beginTransaction();
            //Create Vendor Account

            // Insert the Vendor Details in vendors table
            $vendor = new Vendor;
            $vendor->name =$data['name'];
            $vendor->mobile =$data['mobile'];
            $vendor->email =$data['email'];
            $vendor->status =0;

            //Set Default Timezone to Nepal
            date_default_timezone_set("Asia/Kathmandu");
            $vendor->created_at = date("Y-m-d H:i:s");
            $vendor->updated_at = date("Y-m-d H:i:s");
            $vendor->save();

            $vendor_id = DB::getPdo()->lastInsertId();

            //Insert the Vendor Details in admins table
            $admin = new Admin;
            $admin->type ='vendor';
            $admin->vendor_id =$vendor_id;
            $admin->name =$data['name'];
            $admin->mobile =$data['mobile'];
            $admin->email =$data['email'];
            $admin->password =bcrypt($data['password']);
            $admin->status =0;

            //Set Default Timezone to Nepal
            date_default_timezone_set("Asia/Kathmandu");
            $admin->created_at = date("Y-m-d H:i:s");
            $admin->updated_at = date("Y-m-d H:i:s");
            $admin->save();

            //Send Confirmation Email
            $email =$data['email'];
            $messageData = [
                'email' => $data['email'],
                'name' => $data['name'],
                'mobile' => $data['mobile'],
                'code' =>base64_encode($data['email'])
            ];

            Mail::send('emails.vendor_confirmed',$messageData, function($message)use($email){
                $message->to($email)->subject('Confirm your Vendor Account');
            });

            DB::commit();

            // Redirect back Vendor with Success message
            $message = "Thanks for registering as Vendor. We will confirm by email once your account is appreoved.";
            return redirect()->back()->with('success_message',$message);
        }
    }

    public function confirmVendor($email){
        //Decode Vendor Email
        $email = base64_decode($email);
        //Check Vendor Email Exists
        $vendorCount  = Vendor::where('email',$email)->count();
        if($vendorCount>0){
            //Vendor::where is already activated or not
            $vendorDetails = Vendor::where('email',$email)->first();
            if($vendorDetails->confirm =="Yes"){
                $message = "Your Vendor Account is already confirmed. You can login";
                return redirect()->route('front.vendor-login-register')->with('success-message',$message);
            }else{
                //Update confirm column to yes in both admins/ vendor table to activate
                Admin::where('email',$email)->update(['confirm'=>'Yes']);
                Vendor::where('email',$email)->update(['confirm'=>'Yes']);

                //Send Register Email
                $messageData = [
                    'email' => $email,
                    'name' => $vendorDetails->name,
                    'mobile' =>$vendorDetails->mobile
                ];

                Mail::send('emails.vendor_confirmed',$messageData,function($message)use($email){
                    $message->to($email)->subject('Your Vendor Account Confirmed');
                });

                //Redirect to vendor login/Register page with Success message
                $message = "Your Vendor Email account is Confirmed. You can login and add you personal, business and bank details to activate your vendor acccount to add you products";
                return redirect()->route('front.vendor-login-register')->with('success-message',$message);

            }
        }else{
            abort(404);
        }
    }
}
