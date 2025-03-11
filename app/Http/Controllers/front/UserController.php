<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sms;
use App\Models\Country;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    public function loginRegister(){
        return view('front.users.login_register');
    }

    public function userRegister(Request $request){
        if($request->ajax()){
            $data= $request->all();
            // echo "<pre>"; print_r($data); die;

            $validator = Validator::make($request->all(), [
                'name' =>'required|string|max:100',
                'mobile' =>'required|numeric|digits:10',
                'email' =>'required|email:rfc,dns|max:150|unique:users',
                'password' =>'required|min:6',
                'accept' =>'required',
            ],
            [
                'accept.required'=>'Please accept our Terms & Conditions'
            ]
         );

        if($validator->passes()){
                //Register New User
                $user = new User;
                $user->name =$data['name'];
                $user->mobile =$data['mobile'];
                $user->email =$data['email'];
                $user->password = bcrypt($data['password']);
                $user->status = 0;
                $user->save();

                //Activate the User only when User confirms his email account
                $email = $data['email'];
                $messageData = ['name'=>$data['name'],'email'=>$data['email'],'code'=>base64_encode($data['email'])];
                Mail::send('emails.confirmation',$messageData, function($message)use($email){
                     $message->to($email)->subject('Confirm your Web Hat Developer Account');
                });

                $redirectTo = url('user/login-register');
                return response()->json(['type'=>'success', 'url'=>$redirectTo, 'message'=>'Please confirm your email to activate your account!']);
                }

                //Activate the User Straight Away wihtout sending any Email confirmation

                //Send Register Email
                // $email =$data['email'];
                // $messageData = ['name'=>$data['name'],'mobile'=>$data['mobile'],'email'=>$data['email']];
                // Mail::send('emails.register',$messageData, function($message)use($email){
                //      $message->to($email)->subject('Welcome to Web Hat Developer');
                // });

                //Send Register SMS
                // $message = "Dear Customer, you have been successfully registered with Stack Developers. Login to your account to access orders, addresses & available offers.";
                // $mobile = $data['mobile'];
                // Sms::sendSms($message,$mobile);

                // if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){
                //     $redirectTo = url('cart');
                //     return response()->json(['type'=>'success', 'url'=>$redirectTo]);
                // }
                // }
             else{
                return response()->json(['type'=>'error', 'errors'=>$validator->messages()]);
            }
        }
    }

    public function userAccount(Request $request){
           if($request->ajax()){
             $data = $request->all();

             $validator = Validator::make($request->all(), [
                'name' =>'required|string|max:100',
                'city' =>'required|string|max:100',
                'state' =>'required|string|max:100',
                'address' =>'required|string|max:100',
                'country' =>'required|string|max:100',
                'mobile' =>'required|numeric|digits:10',
                'pincode' =>'required|numeric|digits:5',
            ],
          );

          if($validator->passes()){
            //Update User Details
            User::where('id',Auth::user()->id)->update(['name'=>$data['name'],'mobile'=>$data['mobile'],
            'city'=>$data['city'],'state'=>$data['state'],'country'=>$data['country'],'pincode'=>$data['pincode'],'address'=>$data['address']]);

            //Redirect Back user with success message
            return response()->json(['type'=>'success', 'message'=>'Your contact details successfully updated!']);
        }else{
            return response()->json(['type'=>'error', 'errors'=>$validator->messages()]);
        }
        }else{
            $countries = Country::where('status',1)->get()->toArray();
            return view('front.users.user_account')->with(compact('countries'));
       }
    }

    public function userUpdatePassword(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $validator = Validator::make($request->all(), [
                'current_password' =>'required',
                'new_password' =>'required|min:6',
                'confirm_password' =>'required|min:6|same:new_password',
            ],
          );
          if($validator->passes()){

            $current_password = $data['current_password'];
            $checkPassword = User::where('id',Auth::user()->id)->first();
            if(Hash::check($current_password, $checkPassword->password)){
                 //Update User Current Password
                $user = User::find(Auth::user()->id);
                $user->password = \bcrypt($data['new_password']);
                $user->save();

                //Redirect Back user with success message
                return response()->json(['type'=>'success', 'message'=>'Account password successfully updated!']);
            }else{
                //Redirect Back user with error message
                return response()->json(['type'=>'incorrect', 'message'=>'Your current password is incorrect!']);
            }
        }else{
            return response()->json(['type'=>'error', 'errors'=>$validator->messages()]);
        }
        }else{
            return view('front.users.user_account');
       }

    }

    public function userLogin(Request $request){
        if($request->Ajax()){
            $data= $request->all();
            $validator = Validator::make($request->all(), [
                'email' => 'required|email:rfc,dns|max:150|exists:users',
                'password' =>'required|min:6',
            ]);

            if($validator->passes()){
                if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){

                    //Check if status if activer of inactive
                    if(Auth::user()->status==0){
                       Auth::logout();
                       return response()->json(['type'=>'inactive','message'=>'Your account is not Activated. Please confirm your Account to Activate  your Account ']);
                    }

                    //Update User Cart with user id
                    if(!empty(Session::get('session_id'))){
                        $user_id = Auth::user()->id;
                        $session_id = Session::get('session_id');
                        Cart::where('session_id',$session_id)->update(['user_id'=>$user_id]);
                    }

                    $redirectTo = url('cart');
                    return response()->json(['type'=>'success', 'url'=>$redirectTo]);
                }else{
                    return response()->json(['type'=>'incorrect', 'message'=>'Incorrect Email or Password!']);
                }
            }else{
                return response()->json(['type'=>'error', 'errors'=>$validator->messages()]);
            }
        }
    }

    public function forgotPassword(Request $request){
        if($request->Ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $validator = Validator::make($request->all(), [
                'email' => 'required|email:rfc,dns|max:150|exists:users',
            ],
            [
                'email.exists'=>'Email does not exists!',
            ]
        );

        //After Validation Success
        if($validator->passes()){
            //Generate New Password
            $new_password = Str::random(16);

            //Upadte New Password
            User::where('email',$data['email'])->update(['password'=>\bcrypt($new_password)]);

            //Get User Details
            $userDetails = User::where('email',$data['email'])->first()->toArray();

            //Send Email to User
            $email = $data['email'];
            $messageData = ['name'=>$userDetails['name'],'email'=>$email,'password'=>$new_password];
            Mail::send('emails.user_forgot_password',$messageData, function($message)use($email){
                    $message->to($email)->subject('New Password - Web Hat Developer');
            });

            //Show Success Message
            return response()->json(['type'=>'success', 'message'=>'New Password sent to your registered email.']);
        }else{
            return response()->json(['type'=>'error', 'errors'=>$validator->messages()]);
        }
       }else{
            return view('front.users.forgot_password');
        }
    }

    public function userLogout(){
         Auth::logout();
         Session::flush();
         return redirect('/');
    }

    public function confirmUser($code){
         $email = base64_decode($code);
         $userCount = User::where('email',$email)->count();
         if($userCount>0){
           $userDetails = User::where('email',$email)->first();
           if($userDetails->status==1){
              //Redirect the user to Login/Register with error message
              return redirect('user/login-register')->with('error_message','Your account is already activated. You can login now.');
           }else{
            User::where('email',$email)->update(['status'=>1]);

              //Send Welcome Email
            $messageData = ['name'=>$userDetails->name,'mobile'=>$userDetails->mobile,'email'=>$email];
            Mail::send('emails.register',$messageData, function($message)use($email){
                $message->to($email)->subject('Welcome to Web Hat Developer');
            });

            //Redirect the user to Login/Register Page with success message
             return redirect('user/login-register')->with('success_message','Your account is activated.You can login now.');
           }
         }else{
             abort(404);
         }
    }
}
