<?php

namespace App\Http\Controllers\front\Api;

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
    /**
     * Register a new user (API).
     * Expects JSON payload:
     * {
     *   "name":        "string, required, max:100",
     *   "mobile":      "numeric, required, digits:10",
     *   "email":       "valid email, required, unique:users",
     *   "password":    "string, required, min:6",
     *   "accept":      "boolean, required"
     * }
     */
    public function register(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make(
            $data,
            [
                'name'     => 'required|string|max:100',
                'mobile'   => 'required|numeric|digits:10',
                'email'    => 'required|email:rfc,dns|max:150|unique:users,email',
                'password' => 'required|string|min:6',
                'accept'   => 'required|accepted',
            ],
            [
                'name.required'     => 'Name is required.',
                'name.string'       => 'Name must be a valid string.',
                'name.max'          => 'Name may not be greater than 100 characters.',

                'mobile.required'   => 'Mobile number is required.',
                'mobile.numeric'    => 'Mobile must be numeric.',
                'mobile.digits'     => 'Mobile must be exactly 10 digits.',

                'email.required'    => 'Email is required.',
                'email.email'       => 'Email must be a valid email address.',
                'email.max'         => 'Email may not be greater than 150 characters.',
                'email.unique'      => 'This email is already taken.',

                'password.required' => 'Password is required.',
                'password.string'   => 'Password must be a valid string.',
                'password.min'      => 'Password must be at least 6 characters.',

                'accept.required'   => 'You must accept our Terms & Conditions.',
                'accept.accepted'   => 'You must accept our Terms & Conditions.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = new User();
        $user->name     = $data['name'];
        $user->mobile   = $data['mobile'];
        $user->email    = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->status   = 0;
        $user->save();

        $email       = $data['email'];
        $messageData = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'code'  => base64_encode($data['email']),
        ];
        Mail::send('emails.confirmation', $messageData, function ($message) use ($email) {
            $message->to($email)
                    ->subject('Confirm your Web Hat Developer Account');
        });

        return response()->json([
            'status'  => 'true',
            'message' => 'Registration successful! Please check your email to confirm your account.',
        ], 201);
    }


    /**
     * User login (API).
     * Expects JSON payload:
     * {
     *   "email":    "required|email|exists:users,email",
     *   "password": "required|string|min:6"
     * }
     */
    public function login(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'email'    => 'required|email:rfc,dns|max:150|exists:users,email',
                'password' => 'required|string|min:6',
            ],
            [
                'email.required'    => 'Email is required.',
                'email.email'       => 'Email must be a valid email address.',
                'email.max'         => 'Email may not be greater than 150 characters.',
                'email.exists'      => 'This email is not registered.',

                'password.required' => 'Password is required.',
                'password.string'   => 'Password must be a valid string.',
                'password.min'      => 'Password must be at least 6 characters.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return response()->json([
                'status'  => 'false',
                'message' => 'Incorrect email or password!',
            ], 401);
        }

        // Check if account is activated
        /** @var User $user */
        $user = Auth::user();
        if ($user->status == 0) {
            Auth::logout();
            return response()->json([
                'status'  => 'inactive',
                'message' => 'Your account is not activated. Please confirm your email first!',
            ], 403);
        }

        // (Optional) Attach any session‐based cart to this user
        if (Session::has('session_id')) {
            $sessionId = Session::get('session_id');
            if ($sessionId) {
                Cart::where('session_id', $sessionId)
                    ->update(['user_id' => $user->id]);
            }
        }

        return response()->json([
            'status'  => 'true',
            'message' => 'Logged in successfully.',
            'user'    => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'mobile' => $user->mobile,
            ],
            // 'token' => $user->createToken('API Token')->plainTextToken, // if using Sanctum/Passport
        ]);
    }


    /**
     * Update user account details (API).
     * - Requires authenticated user (apply auth middleware in routes).
     * - Accepts JSON payload:
     *   {
     *     "name":    "required|string|max:100",
     *     "city":    "required|string|max:100",
     *     "state":   "required|string|max:100",
     *     "address": "required|string|max:255",
     *     "country": "required|string|max:100",
     *     "mobile":  "required|numeric|digits:10",
     *     "pincode": "required|numeric|digits:5"
     *   }
     *
     * If called via GET, returns a list of countries and current user details.
     */
    public function updateAccount(Request $request)
    {
        // If it’s a GET request, return user data + countries (for dropdown)
        if ($request->isMethod('get')) {
            $user = Auth::user();
            $countries = Country::where('status', 1)->get(['id', 'name'])->toArray();

            return response()->json([
                'status'   => 'true',
                'user'     => [
                    'id'      => $user->id,
                    'name'    => $user->name,
                    'mobile'  => $user->mobile,
                    'city'    => $user->city,
                    'state'   => $user->state,
                    'country' => $user->country,
                    'pincode' => $user->pincode,
                    'address' => $user->address,
                ],
                'countries' => $countries,
            ]);
        }

        // Otherwise, it’s a POST (or PUT/PATCH) to update
        $data = $request->all();
        $validator = Validator::make(
            $data,
            [
                'name'    => 'required|string|max:100',
                'city'    => 'required|string|max:100',
                'state'   => 'required|string|max:100',
                'address' => 'required|string|max:255',
                'country' => 'required|string|max:100',
                'mobile'  => 'required|numeric|digits:10',
                'pincode' => 'required|numeric|digits:5',
            ],
            [
                'name.required'     => 'Name is required.',
                'name.string'       => 'Name must be a valid string.',
                'name.max'          => 'Name may not be greater than 100 characters.',

                'city.required'     => 'City is required.',
                'city.string'       => 'City must be a valid string.',
                'city.max'          => 'City may not be greater than 100 characters.',

                'state.required'    => 'State is required.',
                'state.string'      => 'State must be a valid string.',
                'state.max'         => 'State may not be greater than 100 characters.',

                'address.required'  => 'Address is required.',
                'address.string'    => 'Address must be a valid string.',
                'address.max'       => 'Address may not be greater than 255 characters.',

                'country.required'  => 'Country is required.',
                'country.string'    => 'Country must be a valid string.',
                'country.max'       => 'Country may not be greater than 100 characters.',

                'mobile.required'   => 'Mobile number is required.',
                'mobile.numeric'    => 'Mobile must be numeric.',
                'mobile.digits'     => 'Mobile must be exactly 10 digits.',

                'pincode.required'  => 'Pincode is required.',
                'pincode.numeric'   => 'Pincode must be numeric.',
                'pincode.digits'    => 'Pincode must be exactly 5 digits.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();
        User::where('id', $user->id)->update([
            'name'    => $data['name'],
            'mobile'  => $data['mobile'],
            'city'    => $data['city'],
            'state'   => $data['state'],
            'country' => $data['country'],
            'pincode' => $data['pincode'],
            'address' => $data['address'],
        ]);

        return response()->json([
            'status'  => 'true',
            'message' => 'Your contact details were successfully updated.',
        ]);
    }


    /**
     * Update (change) user password (API).
     * - Requires authenticated user.
     * - Expects JSON payload:
     *   {
     *     "current_password": "required",
     *     "new_password":     "required|min:6",
     *     "confirm_password": "required|min:6|same:new_password"
     *   }
     */
    public function updatePassword(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make(
            $data,
            [
                'current_password' => 'required|string',
                'new_password'     => 'required|string|min:6',
                'confirm_password' => 'required|string|min:6|same:new_password',
            ],
            [
                'current_password.required' => 'Current password is required.',
                'current_password.string'   => 'Current password must be a valid string.',

                'new_password.required'     => 'New password is required.',
                'new_password.string'       => 'New password must be a valid string.',
                'new_password.min'          => 'New password must be at least 6 characters.',

                'confirm_password.required' => 'Confirm password is required.',
                'confirm_password.string'   => 'Confirm password must be a valid string.',
                'confirm_password.min'      => 'Confirm password must be at least 6 characters.',
                'confirm_password.same'     => 'Confirm password must match new password.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();
        if (! Hash::check($data['current_password'], $user->password)) {
            return response()->json([
                'status'  => 'incorrect',
                'message' => 'Your current password is incorrect.',
            ], 403);
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        return response()->json([
            'status'  => 'true',
            'message' => 'Your password has been updated successfully.',
        ]);
    }


    /**
     * Send “forgot password” email (API).
     * Expects JSON payload:
     * {
     *   "email": "required|email|exists:users,email"
     * }
     */
    public function forgotPassword(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make(
            $data,
            [
                'email' => 'required|email:rfc,dns|max:150|exists:users,email',
            ],
            [
                'email.required' => 'Email is required.',
                'email.email'    => 'Email must be a valid email address.',
                'email.max'      => 'Email may not be greater than 150 characters.',
                'email.exists'   => 'This email is not registered.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Generate a temporary random password (16 chars)
        $newPassword = Str::random(16);

        // Update user’s password (hashed)
        User::where('email', $data['email'])->update([
            'password' => Hash::make($newPassword),
        ]);

        // Retrieve user’s name (for email template)
        $userDetails = User::where('email', $data['email'])->first();
        $email       = $data['email'];

        // Send reset‐password email
        $messageData = [
            'name'     => $userDetails->name,
            'email'    => $email,
            'password' => $newPassword,
        ];
        Mail::send('emails.user_forgot_password', $messageData, function ($message) use ($email) {
            $message->to($email)
                    ->subject('Your New Password from Web Hat Developer');
        });

        return response()->json([
            'status'  => 'true',
            'message' => 'A new temporary password has been emailed to you.',
        ]);
    }


    /**
     * Logout the currently authenticated user (API).
     * - If you are using session‐based auth, simply flush session.
     * - If you are using token‐based (e.g. Sanctum), you might revoke the token here.
     */
    public function logout(Request $request)
    {
        // If using session‐based:
        Auth::logout();
        Session::flush();

        // If using token‐based (Sanctum/Passport), you could do:
        // $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'true',
            'message' => 'Logged out successfully.',
        ]);
    }


    /**
     * Confirm a newly registered user by “code” (base64‐encoded email).
     * Route example: GET /api/user/confirm/{code}
     */
    public function confirmUser($code)
    {
        $email = base64_decode($code);
        $userCount = User::where('email', $email)->count();

        if ($userCount == 0) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Invalid confirmation code or user does not exist.',
            ], 404);
        }

        $user = User::where('email', $email)->first();

        if ($user->status == 1) {
            return response()->json([
                'status'  => 'already_active',
                'message' => 'Your account is already activated; you may log in.',
            ], 200);
        }

        // Activate user
        $user->status = 1;
        $user->save();

        // Send welcome mail
        $messageData = [
            'name'   => $user->name,
            'mobile' => $user->mobile,
            'email'  => $email,
        ];
        Mail::send('emails.register', $messageData, function ($message) use ($email) {
            $message->to($email)
                    ->subject('Welcome to Web Hat Developer');
        });

        return response()->json([
            'status'  => 'true',
            'message' => 'Your account has been activated. You can now log in.',
        ]);
    }
}
