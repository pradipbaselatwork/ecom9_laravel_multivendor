<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Http\Middleware\Admin;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Country;
use App\Models\Vendor;
use App\Models\VendorBusinessDetail;
use App\Models\VendorBankDetail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Guard as SanctumGuard;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function dashboard()
    {
        Session::put('page','dashboard');
        return view('admin.dashboard');
    }

    public function updateAdminPassword(Request $request)
    {
        Session::put('page','update_admin_password');
        if($request->isMethod('post')){
            $data = $request->all();
        //   echo "<pre>"; print_r($data); die;

        //CHECK IF CURRENT PASSOWRD ENTERED BY ADMIN IS CORRECT
        if(Hash::check($data['current_password'],Auth::guard('admin')->user()->password)){

            //CHECK IF NEW PASSWORD IS MATCHING WITH CONFIRM PASSWORD
            if($data['confirm_password']==$data['new_password']){
               Admin::where('id',Auth::guard('admin')->user()->id)->update(['password'=>bcrypt($data['new_password'])]);
               return redirect()->back()->with('success_message','Password has been updated successfully !');
            }else{
                return redirect()->back()->with('error_message','New Password and Confirm Password does not match !');
            }
        }else{
            return redirect()->back()->with('error_message','Your current password is Incorrect !');
        }

        }
        // echo "<pre>"; print_r(Auth::guard('admin')->user()->email); die;
        $adminDetails = Admin::where('email', Auth::guard('admin')->user()->email)->first()->toArray();
        return view('admin.settings.update_admin_password')->with(compact('adminDetails'));
    }

    public function checkAdminPassword(Request $request)
    {
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        if(Hash::check($data['current_password'],Auth::guard('admin')->user()->password)){
            return "true";
        }else{
            return "false";
        }
    }

    public function updateAdminDetails(Request $request)
    {
        Session::put('page','update_admin_details');
        if ($request->isMethod('post')) {
            $data = $request->all();
        //   echo "<pre>"; print_r($data); die;

        $rules = [
            'admin_name' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'admin_mobile' => 'required|numeric|digits:10',
            'admin_image' => 'required',
        ];

        $customMessages = [
            'admin_name.required' => 'Name is required',
            'admin_name.regex' => 'Valid Name is required',
            'admin_mobile.required' => 'Mobile is required',
            'admin_mobile.numeric' => 'Valid Mobile is required',
            'admin_image.required' => 'Admin Image is required',
            'admin_image.image' => 'Valid Admin Image is required',
        ];

        $this->validate($request, $rules, $customMessages);
        //Upload Admin Photo
        if($request->hasFile('admin_image')){
            $image_tmp = $request->file('admin_image');
            if($image_tmp->isValid()){
                 // get extension
                 $extension = $image_tmp->getClientOriginalExtension();
                 //Generate New Image name
                 $imageName = rand(111,99999).'.'.$extension;
                 $imagePath ='admin/images/admin_photos/'.$imageName;
                Image::make($image_tmp)->resize(320, 240)->save(public_path($imagePath));
            }
        }else if(!empty($data['current_admin_image'])){
            $imageName = $data['current_admin_image'];
        }else{
            $imageName ="";
        }

        //Update Admin Details
        Admin::where('id',Auth::guard('admin')->user()->id)
        ->update(['name'=>$data['admin_name'],'mobile'=>$data['admin_mobile'],'image'=>$imageName]);

        return redirect()->back()->with('success_message','Admin details updated successfulley!');
        }
        return view('admin.settings.update_admin_details');
    }

    public function updateVendorDetails($slug, Request $request){
        if($slug=="personal"){
            Session::put('page','update_personal_details');
            if($request->isMethod('post')){
                // dd('here');
                $data = $request->all();
                // echo "<pre>"; print_r($data); die;
                $rules = [
                    'vendor_name' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
                    'vendor_address' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
                    'vendor_mobile' => 'required|numeric|digits:10',
                    // 'vendor_image' => 'required',
                ];

                $customMessages = [
                    'vendor_name.required' => 'Name is required',
                    'vendor_name.regex' => 'Valid Name is required',
                    'vendor_address.required' => 'Name is required',
                    'vendor_address.regex' => 'Valid Name is required',
                    'vendor_mobile.required' => 'Mobile is required',
                    'vendor_mobile.numeric' => 'Valid Mobile is required',
                    // 'vendor_image.required' => 'Vendor Image is required',
                    // 'vendor_image.image' => 'Valid Vendor Image is required',
                ];

                $this->validate($request, $rules, $customMessages);
                //Upload Admin Photo
                if($request->hasFile('vendor_image')){
                    $image_tmp = $request->file('vendor_image');
                    if($image_tmp->isValid()){
                         // get extension
                         $extension = $image_tmp->getClientOriginalExtension();
                         //Generate New Image name
                         $imageName = rand(111,99999).'.'.$extension;
                         $imagePath ='admin/images/admin_photos/'.$imageName;
                        Image::make($image_tmp)->resize(320, 240)->save(public_path($imagePath));

                    }
                }else if(!empty($data['current_vendor_image'])){
                    $imageName = $data['current_vendor_image'];
                }else{
                    $imageName ="";
                }

                //Update in vendors table
                Vendor::where('id',Auth::guard('admin')->user()->vendor_id)->update([
                    'email'=>$data['vendor_email'],
                    'name'=>$data['vendor_name'],
                    'address'=>$data['vendor_address'],
                    'mobile'=>$data['vendor_mobile'],
                    'city'=>$data['vendor_city'],
                    'state'=>$data['vendor_state'],
                    'country'=>$data['vendor_country'],
                    'pincode'=>$data['vendor_pincode']]);
                return redirect()->back()->with('success_message','Personal information updated successfulley !');
            }
            $vendorDetails = Vendor::where('id', Auth::guard('admin')->user()->vendor_id)->first()->toArray();

        }else if($slug=="business"){
            Session::put('page','update_business_details');
            if($request->isMethod('post')){
                $data = $request->all();
              //echo "<pre>"; print_r($data); die;
                $rules = [
                    'shop_name' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
                    'shop_address' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
                    'shop_mobile' => 'required|numeric|digits:10',
                    // 'address_proof_image' => 'required',
                ];

                $customMessages = [
                    'shop_name.required' => 'Name is required',
                    'shop_name.regex' => 'Valid Name is required',
                    'shop_address.required' => 'Name is required',
                    'shop_address.regex' => 'Valid Name is required',
                    'shop_mobile.required' => 'Mobile is required',
                    'shop_mobile.numeric' => 'Valid Mobile is required',
                    // 'address_proof_image.required' => 'Address Proof Image is required',
                    // 'address_proof_image.image' => 'Valid Address Proof Image is required',
                ];

                $this->validate($request, $rules, $customMessages);
                //Upload Admin Photo
                if($request->hasFile('address_proof_image')){
                    $image_tmp = $request->file('address_proof_image');
                    if($image_tmp->isValid()){
                         // get extension
                        $extension = $image_tmp->getClientOriginalExtension();
                         //Generate New Image name
                        $imageName = rand(111,99999).'.'.$extension;
                        $imagePath ='admin/images/proofs_photos/'.$imageName;
                        Image::make($image_tmp)->save($imagePath);
                    }
                }else if(!empty($data['current_address_proof'])){
                    $imageName = $data['current_address_proof'];
                }else{
                    $imageName ="";
                }
                 // echo Auth::guard('admin')->user()->vendor_id; die;
              $vendorCount =VendorBusinessDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->count();
              if($vendorCount>0){
              //Update in vendors Business Detail
                VendorBusinessDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->update([
                    'shop_name' => $data['shop_name'],
                    'shop_mobile' => $data['shop_mobile'],
                    'shop_address' => $data['shop_address'],
                    'address_proof' => $data['address_proof'],
                    'shop_city' => $data['shop_city'],
                    'shop_state' => $data['shop_state'],
                    'shop_country' => $data['shop_country'],
                    'business_license_number' => $data['business_license_number'],
                    'get_number' => $data['get_number'],
                    'shop_website' => $data['shop_website'],
                    'pan_number' => $data['pan_number'],
                    'shop_pincode' => $data['shop_pincode'],
                    'address_proof_image' => $imageName,]);
              }else{
                 //Insert in vendors Business Detail
                 VendorBusinessDetail::insert(['vendor_id'=>Auth::guard('admin')->user()->vendor_id,
                    'shop_name' => $data['shop_name'],
                    'shop_mobile' => $data['shop_mobile'],
                    'shop_address' => $data['shop_address'],
                    'address_proof' => $data['address_proof'],
                    'shop_city' => $data['shop_city'],
                    'shop_state' => $data['shop_state'],
                    'shop_country' => $data['shop_country'],
                    'business_license_number' => $data['business_license_number'],
                    'get_number' => $data['get_number'],
                    'shop_website' => $data['shop_website'],
                    'pan_number' => $data['pan_number'],
                    'shop_pincode' => $data['shop_pincode'],
                    'address_proof_image' => $imageName,]);
              }
              return redirect()->back()->with('success_message','Business information updated successfulley !');
            }
            $vendorCount = VendorBusinessDetail::where('vendor_id', Auth::guard('admin')->user()->vendor_id)->count();
            if($vendorCount>0){
                $vendorDetails = VendorBusinessDetail::where('vendor_id', Auth::guard('admin')->user()->vendor_id)->first()->toArray();
            }else{
                $vendorDetails = array();
            }
        }else if($slug=="bank"){
            Session::put('page','update_bank_details');
            if($request->isMethod('post')){
                $data = $request->all();
                // echo "<pre>"; print_r($data); die;
                $rules = [
                    'account_holder_name' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
                    'bank_name' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
                    // 'vendor_mobile' => 'required|numeric|digits:10',
                ];

                $customMessages = [
                    'account_holder_name.required' => 'Account Holder Name is required',
                    'account_holder_name.regex' => 'Valid Account Holder Name is required',
                    'bank_name.required' => 'Bank Name is required',
                    'bank_name.regex' => 'Valid Bank Name is required',
                    // 'vendor_mobile.required' => 'Mobile is required',
                    // 'vendor_mobile.numeric' => 'Valid Mobile is required',
                ];

                $this->validate($request, $rules, $customMessages);
                //Upload Admin Photo
                if($request->hasFile('vendor_image')){
                    $image_tmp = $request->file('vendor_image');
                    if($image_tmp->isValid()){
                         // get extension
                         $extension = $image_tmp->getClientOriginalExtension();
                         //Generate New Image name
                         $imageName = rand(111,99999).'.'.$extension;
                         $imagePath ='images/admin_images/admin_photos/'.$imageName;
                        Image::make($image_tmp)->resize(320, 240)->save(public_path($imagePath));

                    }
                }else if(!empty($data['current_vendor_image'])){
                    $imageName = $data['current_vendor_image'];
                }else{
                    $imageName ="";
                }
                // echo Auth::guard('admin')->user()->vendor_id; die;
                $vendorCount =VendorBankDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->count();
                if($vendorCount>0){
                //Update in vendors bank detail
                VendorBankDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->update([
                    'account_holder_name' => $data['account_holder_name'],
                    'bank_name' => $data['bank_name'],
                    'account_number' => $data['account_number'],
                    'bank_ifsc_code' => $data['bank_ifsc_code']]);
                }else{
                //Insert in vendors bank detail
                VendorBankDetail::insert(['vendor_id'=>Auth::guard('admin')->user()->vendor_id,
                    'account_holder_name' => $data['account_holder_name'],
                    'bank_name' => $data['bank_name'],
                    'account_number' => $data['account_number'],
                    'bank_ifsc_code' => $data['bank_ifsc_code']]);
                }
                return redirect()->back()->with('success_message','Bank information updated successfulley !');
            }
            $vendorCount = VendorBankDetail::where('vendor_id', Auth::guard('admin')->user()->vendor_id)->count();
            if($vendorCount>0){
                $vendorDetails = VendorBankDetail::where('vendor_id', Auth::guard('admin')->user()->vendor_id)->first()->toArray();
            }else{
                $vendorDetails = array();
            }
        }
        $countries = Country::where('status',1)->get()->toArray();
        return view('admin.settings.update_vendor_details')->with(compact('slug','vendorDetails','countries'));
    }

    public function updateVendorCommission(Request $request){
        if($request->isMethod('post')){
             $data = $request->all();
             //Update in vendors table
             Vendor::where('id',$data['vendor_id'])->update(['commission'=>$data['commission']]);
             return redirect()->back()->with('success_message','Vendor commission updated successfully!');
        }
    }

    public function login(Request $request)
    {
        // echo $password = Hash::make('admin'); die;
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $rules = [
                'email' => 'required|email:rfc,dns|max:255',
                'password' => 'required',
            ];

            $customMessages = [
                'email.required' => 'Email is required',
                'email.email' => 'Valid Email is required',
                'password.required' => 'Password is required',
            ];
            $this->validate($request, $rules, $customMessages);

            // if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 1])) {
            //     return redirect()->route('admin.dashboard');
            // } else {
            //     return redirect()->back()->with('error_message','Login Failed!');
            // }

            if(Auth::guard('admin')->attempt(['email'=>$data['email'],'password'=>$data['password']])){
                if(Auth::guard('admin')->user()->type=="vendor" && Auth::guard('admin')->user()->confirm=="No"){
                 return redirect()->back()->with('error_message','Please confirm your email to activate your Vendor Account');
            }else if(Auth::guard('admin')->user()->type!="vendor" && Auth::guard('admin')->user()->status=="0"){
                return redirect()->back()->with('error_message','Your admin account is not activate');
            }else{
                return redirect()->route('admin.dashboard');
            }
            }else{
                 return redirect()->back()->with('error_message','Invalid Email or Password');
        }
      }
        return view('admin.login');
    }

    public function admins($type=null){
       $admins = Admin::query();
       if(!empty($type)){
         $admins = $admins->where('type',$type);
         $title = ucfirst($type)."s";
         Session::put('page','view_'.strtolower($title));
       }else{
         $title = "All Admins/Subadmins/Vendors";
         Session::put('page','view_all');
       }
       $admins = $admins->get()->toArray();
        //  echo "<pre>"; print_r($admins); die;
        return view('admin.admins.admins')->with(compact('admins','title'));
    }

    public function viewVendorDetails($id){
        $adminDetails = Admin::with('vendorPersonal','vendorBusiness','vendorBank')->where('id',$id)->first();
        $adminDetails = json_decode(json_encode($adminDetails),true);
        // dd($adminDetails);
        return view('admin.admins.view_vendor_details')->with(compact('adminDetails'));
    }

    public function updateAdminStatus(Request $request){
         if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }else{
                $status = 1;
            }
            Admin::where('id',$data['admin_id'])->update(['status'=>$status]);
            $adminDetails = Admin::where('id',$data['admin_id'])->first()->toArray();
            if($adminDetails['type']=="vendor" && $status==1){
                Vendor::where('id',$adminDetails['vendor_id'])->update(['status'=>$status]);
                //Send Approval Email
                $email = $adminDetails['email'];
                $messageData = [
                    'email' => $adminDetails['email'],
                    'name' => $adminDetails['name'],
                    'mobile' => $adminDetails['mobile']
                ];

            Mail::send('emails.vendor_approved',$messageData,function($message)use($email){
                $message->to($email)->subject('Vendor Account Approved');
            });
          }
        return response()->json(['status'=>$status,'admin_id'=>$data['admin_id']]);
         }
    }


    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.logout');
    }
}
