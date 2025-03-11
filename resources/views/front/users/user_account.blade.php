@extends('front.layout.layout')
@section('content')
    <!-- Page Introduction Wrapper -->
    <div class="page-style-a">
        <div class="container">
            <div class="page-intro">
                <h2>Account</h2>
                <ul class="bread-crumb">
                    <li class="has-separator">
                        <i class="ion ion-md-home"></i>
                        <a href="index.html">Home</a>
                    </li>
                    <li class="is-marked">
                        <a href="account.html">Account</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Page Introduction Wrapper /- -->
    <!-- Account-Page -->
    <div class="page-account u-s-p-t-80">
        <div class="container">
            @if (Session::has('success_message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success: </strong>{{ Session::get('success_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (Session::has('error_message'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error: </strong>{{ Session::get('error_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error: </strong><?php echo implode('', $errors->all('<div>:message</div>')) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <div class="row">
                <!-- Update User Account -->
                <div class="col-lg-6">
                    <div class="login-wrapper">
                        <h2 class="account-h2 u-s-m-b-20" style="font-size:16px">Update Contact Details</h2>
                        <p id="account-error"></p>
                        <p id="account-success"></p>
                        <form id="accountForm" action="javascript:void(0);" method="post">
                            @csrf
                            <div class="u-s-m-b-30">
                                <label for="user-email">Email
                                    <span class="astk">*</span>
                                </label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}" id="user-email" class="text-field" readonly disabled placeholder="Username / Email" style="background-color: #e9e9e9">
                                <p id="account-email"></p>
                            </div>
                            <div class="u-s-m-b-30">
                                <label for="user-name">Name
                                    <span class="astk">*</span>
                                </label>
                                <input type="text" id="user-name" value="{{ Auth::user()->name }}" name="name" class="text-field" placeholder="User Name">
                                <p id="account-name"></p>
                            </div>
                            <div class="u-s-m-b-30">
                                <label for="user-mobile">Mobile
                                    <span class="astk">*</span>
                                </label>
                                <input type="text" id="user-mobile" value="{{ Auth::user()->mobile }}" name="mobile" class="text-field" placeholder="User Mobile">
                                <p id="account-mobile"></p>
                            </div>
                            <div class="u-s-m-b-30">
                                <label for="user-address">Address
                                    <span class="astk">*</span>
                                </label>
                                <input type="text" id="user-address" value="{{ Auth::user()->address }}" name="address" class="text-field" placeholder="User Address">
                                <p id="account-address"></p>
                            </div>
                            <div class="u-s-m-b-30">
                                <label for="user-city">City
                                    <span class="astk">*</span>
                                </label>
                                <input type="text" id="user-city" value="{{ Auth::user()->city }}" name="city" class="text-field" placeholder="User City">
                                <p id="account-city"></p>
                            </div>
                            <div class="u-s-m-b-30">
                                <label for="user-state">State
                                    <span class="astk">*</span>
                                </label>
                                <input type="text" id="user-state" value="{{ Auth::user()->state }}"  name="state" class="text-field" placeholder="User State">
                                <p id="account-state"></p>
                            </div>
                            <div class="u-s-m-b-30">
                                <label for="user-country">Country
                                    <span class="astk">*</span>
                                </label>
                                <input type="text" id="user-country" value="{{ Auth::user()->country }}"  name="country" class="text-field" placeholder="User Country">
                                <p id="account-country"></p>
                            </div>
                            {{-- <div class="u-s-m-b-30">
                                <label for="user-country">Country
                                    <span class="astk">*</span>
                                </label>
                                <select name="country" id="user-country" class="text-field" style="color:#000">
                                    <option value="">Select Country</option> --}}
                                {{-- @foreach ($countries as $key => $item)
                                    <option value="{{}}">Select Country</option>
                                @endforeach --}}
                                {{-- </select>
                                <p id="account-country"></p>
                            </div> --}}
                            <div class="u-s-m-b-30">
                                <label for="user-pincode">Pincode
                                    <span class="astk">*</span>
                                </label>
                                <input type="text" id="user-pincode" value="{{ Auth::user()->pincode }}" name="pincode" class="text-field" placeholder="User Pincode">
                                <p id="account-pincode"></p>
                            </div>
                            <div class="m-b-45">
                                <button class="button button-outline-secondary w-100">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Update Account User Pasword -->
                <div class="col-lg-6">
                    <div class="reg-wrapper">
                        <h2 class="account-h2 u-s-m-b-20" style="font-size:16px">Update Password</h2>
                            <p id="password-success"></p>
                            <p id="password-error"></p>
                        <form id="passwordForm" action="javascript:void(0);" method="POST">
                            @csrf
                            <div class="u-s-m-b-30">
                                <label for="current-password">Current Password
                                    <span class="astk">*</span>
                                </label>
                                <input type="password" id="current-password" name="current_password" class="text-field" placeholder="Current Password">
                                <p id="password-current_password"></p>
                            </div>
                            <div class="u-s-m-b-30">
                                <label for="new-password">New Password
                                    <span class="astk">*</span>
                                </label>
                                <input type="password" id="new-password" name="new_password" class="text-field" placeholder="New Password">
                                <p id="password-new_password"></p>
                            </div>
                            <div class="u-s-m-b-30">
                                <label for="confirm-password">Confirm Password
                                    <span class="astk">*</span>
                                </label>
                                <input type="password" id="confirm-password" name="confirm_password" class="text-field" placeholder="Confirm Password">
                                <p id="password-confirm_password"></p>
                            </div>
                            <div class="u-s-m-b-45">
                                <button type="submit" id="passwordButton" class="button button-primary w-100">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Register /- -->
            </div>
        </div>
    </div>
    <!-- Account-Page /- -->
@endsection
