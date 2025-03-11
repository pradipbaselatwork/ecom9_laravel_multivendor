@extends('admin.layout.layout')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">

                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h3 class="font-weight-bold">View Vendor Details</h3>
                            <a href="{{ url('admin/admins/vendor') }}">
                                <h6 class="font-weight-bold">Back To Vendor</h6>
                            </a>

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

            {{-- <a href="{{ url('admin/admins/vendor') }}">Back To Vendor</a> --}}

            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Personal Information</h4>
                            <div class="form-group">
                                <label for="exampleInputUsername1">Vendor Username/Email</label>
                                <input type="text" class="form-control" value="{{ $adminDetails['vendor_personal']['email'] }}" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_name">Name</label>
                                <input type="text" class="form-control"
                                    value="{{ $adminDetails['vendor_personal']['name'] }}" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_address">Address</label>
                                <input type="text" class="form-control"
                                    value="{{ $adminDetails['vendor_personal']['address'] }}" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_city">City</label>
                                <input type="text" class="form-control"
                                    value="{{ $adminDetails['vendor_personal']['city'] }}" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_state">State</label>
                                <input type="text" class="form-control"
                                    value="{{ $adminDetails['vendor_personal']['state'] }}" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_country">Country</label>
                                <input type="text" class="form-control"
                                    value="{{ $adminDetails['vendor_personal']['country'] }}" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_pincode">Pincode</label>
                                <input type="text" class="form-control"
                                    value="{{ $adminDetails['vendor_personal']['pincode'] }}" readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_mobile">Mobile</label>
                                <input type="text" class="form-control"
                                    value="{{ $adminDetails['vendor_personal']['mobile'] }}" readonly="">
                            </div>

                            @if (!empty($adminDetails['image']))
                                <div class="form-group">
                                    <label for="vendor_image">Vendor Image</label>
                                    <br>
                                    <img style="width: 200px;"
                                        src="{{ url('admin/images/admin_photos/' . $adminDetails['image']) }}"><br>View Image
                                    {{-- {{ asset('admin/images/proofs_photos/'.$vendorDetails['address_proof_image']) }  --}}
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Business Information</h4>
                            <div class="form-group">
                                <label for="exampleInputUsername1">Shop Name</label>
                                <input type="text" class="form-control"
                                @if(!empty($adminDetails['vendor_business']['shop_name'])) value="{{ $adminDetails['vendor_business']['shop_name'] }}" @endif readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_name">Shop Address</label>
                                <input type="text" class="form-control"
                                @if(!empty($adminDetails['vendor_business']['shop_address'])) value="{{ $adminDetails['vendor_business']['shop_address'] }}" @endif readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_name">Shop City</label>
                                <input type="text" class="form-control"
                                @if(!empty($adminDetails['vendor_business']['shop_city'])) value="{{ $adminDetails['vendor_business']['shop_city'] }}" @endif readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_name">Shop State</label>
                                <input type="text" class="form-control"
                                @if(!empty($adminDetails['vendor_business']['shop_state'])) value="{{ $adminDetails['vendor_business']['shop_state'] }}" @endif readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_name">Shop Country</label>
                                <input type="text" class="form-control"
                                @if(!empty($adminDetails['vendor_business']['shop_country'])) value="{{ $adminDetails['vendor_business']['shop_country'] }}" @endif readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_name">Shop Pincode</label>
                                <input type="text" class="form-control"
                                @if(!empty($adminDetails['vendor_business']['shop_pincode'])) value="{{ $adminDetails['vendor_business']['shop_pincode'] }}" @endif readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_name">Shop Mobile</label>
                                <input type="text" class="form-control"
                                @if(!empty($adminDetails['vendor_business']['shop_mobile'])) value="{{ $adminDetails['vendor_business']['shop_mobile'] }}" @endif readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_address">Shop Website</label>
                                <input type="text" class="form-control"
                                @if(!empty($adminDetails['vendor_business']['shop_website'])) value="{{ $adminDetails['vendor_business']['shop_website'] }}" @endif readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_city">Shop Email</label>
                                <input type="text" class="form-control"
                                @if(!empty($adminDetails['vendor_business']['shop_email'])) value="{{ $adminDetails['vendor_business']['shop_email'] }}" @endif readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_pincode">Shop Licence Number</label>
                                <input type="text" class="form-control"
                                @if(!empty($adminDetails['vendor_business']['business_license_number'])) value="{{ $adminDetails['vendor_business']['business_license_number'] }}"
                                    @endif readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_mobile">Shop Number</label>
                                <input type="text" class="form-control"
                                @if(!empty($adminDetails['vendor_business']['get_number'])) value="{{ $adminDetails['vendor_business']['get_number'] }}" @endif readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_mobile">Shop Pan Number</label>
                                <input type="text" class="form-control"
                                @if(!empty($adminDetails['vendor_business']['pan_number'])) value="{{ $adminDetails['vendor_business']['pan_number'] }}" @endif readonly="">
                            </div>

                            <div class="form-group">
                                <label for="vendor_state">Shop Address Proof</label>
                                <input type="text" class="form-control"
                                @if(!empty($adminDetails['vendor_business']['address_proof'])) value="{{ $adminDetails['vendor_business']['address_proof'] }}" @endif readonly="">
                            </div>

                            @if (!empty($adminDetails['vendor_business']['address_proof_image']))
                                <div class="form-group">
                                    <label for="vendor_image">Shop Address Image</label>
                                    <br>
                                    <img style="width: 200px;"
                                        src="{{ url('admin/images/proofs/' . $adminDetails['vendor_business']['address_proof_image']) }}"><br>View
                                    Image
                                </div>
                            @endif
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Bank Information</h4>
                                <div class="form-group">
                                    <label for="account_holder_name">Account Holder Name</label>
                                    <input type="text" class="form-control"
                                @if(!empty($adminDetails['vendor_bank']['account_holder_name'])) value="{{ $adminDetails['vendor_bank']['account_holder_name'] }}" @endif readonly="">
                                </div>

                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" class="form-control"
                                       @if(!empty($adminDetails['vendor_bank']['bank_name'])) value="{{ $adminDetails['vendor_bank']['bank_name'] }}" @endif readonly="">
                                </div>

                                <div class="form-group">
                                    <label for="account_number">Account Number</label>
                                    <input type="text" class="form-control"
                                       @if(!empty($adminDetails['vendor_bank']['account_number'])) value="{{ $adminDetails['vendor_bank']['account_number'] }}" @endif readonly="">
                                </div>

                                <div class="form-group">
                                    <label for="bank_ifsc_code">Bank IFSC Code</label>
                                    <input type="text" class="form-control"
                                       @if(!empty($adminDetails['vendor_bank']['bank_ifsc_code'])) value="{{ $adminDetails['vendor_bank']['bank_ifsc_code'] }}" @endif readonly="">
                                </div>

                            </div>
                        </div>

                        <div class="card">
                            @if (Session::has('success_message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success: </strong>{{ Session::get('success_message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif
                            <div class="card-body">
                                <h4 class="card-title">Commission Information</h4>
                            <form class="forms-sample" action="{{ url('admin/update-vendor-commission') }}" method="post">
                                    @csrf
                                <div class="form-group">
                                    <input type="hidden" name="vendor_id" class="form-control" value="{{ $adminDetails['vendor_personal']['id'] }}">
                                    <label for="commission">Commission per order item (%)</label>
                                    <input type="text" name="commission" class="form-control" @if(isset($adminDetails['vendor_personal']['commission'])) value="{{ $adminDetails['vendor_personal']['commission'] }}" @endif>
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">Update</button>
                            </form>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                @include('admin.layout.footer')
                <!-- partial -->
            </div>
        @endsection
