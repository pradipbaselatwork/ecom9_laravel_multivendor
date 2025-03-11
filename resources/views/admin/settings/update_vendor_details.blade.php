@extends('admin.layout.layout')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">

                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h3 class="font-weight-bold">Update Vendor Details</h3>
                        </div>
                        <div class="col-12 col-xl-4">
                            <div class="justify-content-end d-flex">
                                <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                    <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button"
                                        id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="true">
                                        <i class="mdi mdi-calendar"></i> Today (10 Jan 2021)
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                                        <a class="dropdown-item" href="#">January - March</a>
                                        <a class="dropdown-item" href="#">March - June</a>
                                        <a class="dropdown-item" href="#">June - August</a>
                                        <a class="dropdown-item" href="#">August - November</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            @if ($slug == 'personal')
                <div class="row">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Update Personal Information</h4>

                                @if (Session::has('error_message'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Error: </strong>{{ Session::get('error_message') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if (Session::has('success_message'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success: </strong>{{ Session::get('success_message') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form class="forms-sample"
                                    action="{{ route('admin.update-vendor-details', ['slug' => 'personal']) }}"
                                    method="post" name="" id="" enctype="multipart/form-data">
                                    @csrf
                                    {{-- {{ dd($vendorDetails['email']); }} --}}
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Vendor Username/Email</label>
                                        <input type="text" class="form-control" value="{{ $vendorDetails['email'] }}"
                                            id="vendor_email" name="vendor_email" placeholder="Username" readonly="">
                                    </div>

                                    <div class="form-group">
                                        <label for="vendor_name">Name</label>
                                        <input type="text" class="form-control" value="{{ $vendorDetails['name'] }}"
                                            id="vendor_name" name="vendor_name" placeholder="Enter Vendor Name"
                                            required="">
                                    </div>

                                    <div class="form-group">
                                        <label for="vendor_address">Address</label>
                                        <input type="text" class="form-control" value="{{ $vendorDetails['address'] }}"
                                            id="vendor_address" name="vendor_address" placeholder="Enter Vendor Address"
                                            required="">
                                    </div>

                                    <div class="form-group">
                                        <label for="vendor_city">City</label>
                                        <input type="text" class="form-control" value="{{ $vendorDetails['city'] }}"
                                            id="vendor_city" name="vendor_city" placeholder="Enter Vendor City"
                                            required="">
                                    </div>

                                    <div class="form-group">
                                        <label for="vendor_state">State</label>
                                        <input type="text" class="form-control" value="{{ $vendorDetails['state'] }}"
                                            id="vendor_state" name="vendor_state" placeholder="Enter Vendor State"
                                            required="">
                                    </div>

                                    <div class="form-group">
                                        <label for="vendor_country">Country</label>
                                            <select name="vendor_country" id="vendor_country" class="form-control" style="color:#000">
                                                <option value="">Select Country</option>
                                                 @foreach($countries as $country)
                                                <option value="{{ $country['country_name'] }}" @if ($country['country_name'] == $vendorDetails['country']) selected @endif >{{ $country['country_name'] }}</option>
                                                 @endforeach
                                            </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="vendor_pincode">Pincode</label>
                                        <input type="text" class="form-control"
                                            value="{{ $vendorDetails['pincode'] }}" id="vendor_pincode"
                                            name="vendor_pincode" placeholder="Enter Vendor Pincode" required="">
                                    </div>

                                    <div class="form-group">
                                        <label for="vendor_mobile">Mobile</label>
                                        <input type="text" class="form-control"
                                            value="{{ $vendorDetails['mobile'] }}" id="vendor_mobile"
                                            name="vendor_mobile" placeholder="Enter Vendor Mobile" required="">
                                    </div>

                                    <div class="form-group">
                                        <label for="vendor_image">Photo</label>
                                        <input type="file" class="form-control" value="" id="vendor_image"
                                            name="vendor_image">

                                        @if (!empty(Auth::guard('admin')->user()->image))
                                            <a target="_blank"
                                                href="{{ asset('admin/images/admin_photos/' . Auth::guard('admin')->user()->image) }}">View
                                                Image</a>
                                            <input type="hidden" name="current_vendor_image"
                                                value="{{ Auth::guard('admin')->user()->image }}">
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                    <button type="reset" class="btn btn-light">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($slug == 'business')
                <div class="row">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Update Business Information</h4>

                                @if (Session::has('error_message'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Error: </strong>{{ Session::get('error_message') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if (Session::has('success_message'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success: </strong>{{ Session::get('success_message') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form class="forms-sample"
                                    action="{{ route('admin.update-vendor-details', ['slug' => 'business']) }}"
                                    method="post" name="updateVendorDetailsForm" id="updateVendorDetailsForm"
                                    enctype='multipart/form-data'>
                                    @csrf
                                    <div class="form-group">
                                        <label for="account_holder_name">Vendor Username/Email</label>
                                        <input class="form-control" value="{{ Auth::guard('admin')->user()->email }}" readonly="">
                                    </div>
                                    <div class="form-group">
                                        <label for="shop_name">Shop Name</label>
                                        <input type="text" class="form-control"
                                        @if(!empty($vendorDetails['shop_name'])) value="{{ old('shop_name', $vendorDetails['shop_name']) }}" @else value="{{ old('shop_name') }}" @endif id="shop_name" name="shop_name"
                                            placeholder="Enter Shop Name" required="">
                                    </div>
                                    <div class="form-group">
                                        <label for="shop_address">Shop Address</label>
                                        <input type="text" class="form-control"
                                        @if(!empty($vendorDetails['shop_address'])) value="{{ old('shop_address', $vendorDetails['shop_address']) }}" @else value="{{ old('shop_address') }}" @endif id="shop_address"
                                            name="shop_address" placeholder="Enter Shop Address" required="">
                                    </div>
                                    <div class="form-group">
                                        <label for="shop_city">Shop City</label>
                                        <input type="text" class="form-control"
                                        @if(!empty($vendorDetails['shop_city'])) value="{{ old('shop_city', $vendorDetails['shop_city']) }}" @else value="{{ old('shop_city') }}" @endif id="shop_city" name="shop_city"
                                            placeholder="Enter Shop City" required="">
                                    </div>
                                    <div class="form-group">
                                        <label for="shop_state">Shop State</label>
                                        <input type="text" class="form-control"
                                        @if(!empty($vendorDetails['shop_state'])) value="{{ old('shop_state', $vendorDetails['shop_state']) }}" @else value="{{ old('shop_state') }}" @endif id="shop_state" name="shop_state"
                                            placeholder="Enter Shop State" required="">
                                    </div>
                                    <div class="form-group">
                                        <label for="shop_country">Shop Country</label>
                                            <select name="shop_country" id="shop_country" class="form-control" style="color:#000">
                                                <option value="">Select Country</option>
                                                 @foreach($countries as $country)
                                                <option value="{{ $country['country_name'] }}" @if(!empty($vendorDetails['shop_state']) && $country['country_name'] == $vendorDetails['shop_country']) selected @endif >{{ $country['country_name'] }}</option>
                                                 @endforeach
                                            </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="get_number">Get Number</label>
                                        <input type="text" class="form-control"
                                        @if(!empty($vendorDetails['get_number'])) value="{{ old('get_number', $vendorDetails['get_number']) }}" @else value="{{ old('get_number') }}" @endif id="get_number"
                                            name="get_number" placeholder="Enter Get Number" required="">
                                    </div>

                                    <div class="form-group">
                                        <label for="pan_number">Pan Number</label>
                                        <input type="text" class="form-control"
                                        @if(!empty($vendorDetails['pan_number'])) value="{{ old('pan_number', $vendorDetails['pan_number']) }}" @else value="{{ old('pan_number') }}" @endif id="pan_number"
                                            name="pan_number" placeholder="Enter Pan Number" required="">
                                    </div>


                                    <div class="form-group">
                                        <label for="shop_pincode">Shop Pincode</label>
                                        <input type="text" class="form-control"
                                        @if(!empty($vendorDetails['shop_pincode'])) value="{{ old('shop_pincode', $vendorDetails['shop_pincode']) }}" @else value="{{ old('shop_pincode') }}" @endif id="shop_pincode"
                                            name="shop_pincode" placeholder="Enter Shop Pincode" required="">
                                    </div>

                                    <div class="form-group">
                                        <label for="shop_mobile">Shop Mobile</label>
                                        <input type="text" class="form-control"
                                        @if(!empty($vendorDetails['shop_mobile'])) value="{{ old('shop_mobile', $vendorDetails['shop_mobile']) }}" @else value="{{ old('shop_mobile') }}" @endif id="shop_mobile"
                                            name="shop_mobile" placeholder="Enter Shop Mobile" required="">
                                    </div>

                                    <div class="form-group">
                                        <label for="shop_website">Shop Website</label>
                                        <input type="text" class="form-control"
                                        @if(!empty($vendorDetails['shop_website'])) value="{{ old('shop_website', $vendorDetails['shop_website']) }}" @else value="{{ old('shop_website') }}" @endif id="shop_website"
                                            name="shop_website" placeholder="Enter Shop Mobile" required="">
                                    </div>

                                    <div class="form-group">
                                        <label for="business_license_number">Business Licence Number</label>
                                        <input type="text" class="form-control"
                                        @if(!empty($vendorDetails['business_license_number'])) value="{{ old('business_license_number', $vendorDetails['business_license_number']) }}" @else value="{{ old('business_license_number') }}" @endif
                                            id="business_license_number" name="business_license_number"
                                            placeholder="Enter Vendor Mobile" required="">
                                    </div>

                                    <div class="form-group">
                                        <label for="pan_number">Pan Number</label>
                                        <input type="text" class="form-control"
                                        @if(!empty($vendorDetails['pan_number'])) value="{{ old('pan_number', $vendorDetails['pan_number']) }}" @else value="{{ old('pan_number') }}" @endif id="pan_number"  name="pan_number"
                                            placeholder="Enter Vendor Mobile" required="">
                                    </div>

                                    <div class="form-group">
                                        <label for="address_proof">Address Proof</label>
                                        <select class="form-control" name="address_proof" id="address_proof" style="color:#000">
                                            <option value="Passport" @if(!empty($vendorDetails['address_proof']) && $vendorDetails['address_proof'] == 'Passport') selected @endif>Passport</option>
                                            <option value="Voting Card" @if(!empty($vendorDetails['address_proof']) && $vendorDetails['address_proof'] == 'Voting Card') selected @endif>Voting Card</option>
                                            <option value="PAN" @if(!empty($vendorDetails['address_proof']) && $vendorDetails['address_proof'] == 'PAN') selected @endif>PAN</option>
                                            <option value="Driving License" @if(!empty($vendorDetails['address_proof']) && $vendorDetails['address_proof'] == 'Driving License') selected @endif>Driving License</option>
                                            <option value="Adhar Card" @if(!empty($vendorDetails['address_proof']) && $vendorDetails['address_proof'] == 'Adhar Card') selected @endif>Adhar Card</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="address_proof_image">Address Proof Image</label>
                                        <input type="file" class="form-control" id="address_proof_image"
                                            name="address_proof_image">
                                        @if (!empty($vendorDetails['address_proof_image']))
                                            <a target="_blank"
                                                href="{{ asset('admin/images/proofs_photos/' . $vendorDetails['address_proof_image']) }}">View
                                                Image</a>
                                            <input type="hidden" name="current_address_proof"
                                                value="{{ $vendorDetails['address_proof_image'] }}">
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                    <button type="reset" class="btn btn-light">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($slug == 'bank')
                <div class="row">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Update Bank Information</h4>

                                @if (Session::has('error_message'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Error: </strong>{{ Session::get('error_message') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if (Session::has('success_message'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success: </strong>{{ Session::get('success_message') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form class="forms-sample"
                                    action="{{ route('admin.update-vendor-details', ['slug' => 'bank']) }}" method="post"
                                    name="updateVendorDetailsForm" id="updateVendorDetailsForm"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="account_holder_name">Vendor Username/Email</label>
                                        <input class="form-control" value="{{ Auth::guard('admin')->user()->email }}" readonly="">
                                    </div>

                                    <div class="form-group">
                                        <label for="account_holder_name">Account Holder Name</label>
                                        <input type="text" class="form-control"
                                           @if(!empty($vendorDetails['account_holder_name'])) value="{{ $vendorDetails['account_holder_name'] }}" @endif id="account_holder_name"
                                            name="account_holder_name" placeholder="Enter Account Holder Name"
                                            required="">
                                    </div>
                                    <div class="form-group">
                                        <label for="bank_name">Bank Name</label>
                                        <input type="text" class="form-control"
                                        @if(!empty($vendorDetails['bank_name'])) value="{{ $vendorDetails['bank_name'] }}" @endif id="bank_name" name="bank_name"
                                            placeholder="Enter Shop Address" required="">
                                    </div>
                                    <div class="form-group">
                                        <label for="account_number">Account Name</label>
                                        <input type="text" class="form-control"
                                        @if(!empty($vendorDetails['account_number'])) value="{{ $vendorDetails['account_number'] }}" @endif id="account_number"
                                            name="account_number" placeholder="Enter Shop City" required="">
                                    </div>
                                    <div class="form-group">
                                        <label for="bank_ifsc_code">Bank Ifsc Code</label>
                                        <input type="text" class="form-control"
                                        @if(!empty($vendorDetails['bank_ifsc_code'])) value="{{ $vendorDetails['bank_ifsc_code'] }}" @endif id="bank_ifsc_code"
                                            name="bank_ifsc_code" placeholder="Enter Shop Pincode" required="">
                                    </div>
                                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                    <button type="reset" class="btn btn-light">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        @include('admin.layout.footer')
        <!-- partial -->
    </div>
@endsection
