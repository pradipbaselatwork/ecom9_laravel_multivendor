@extends('admin.layout.layout')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h3 class="font-weight-bold">Edit Shipping Charges</h3>
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

            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $title }}</h4>
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
                            <form class="forms-sample" @if (empty($shipping['id'])) action="{{ url('admin/add-edit-shipping-charges') }}" @else action="{{ url('admin/add-edit-shipping-charges/'.$shipping['id']) }}" @endif
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="country">Country Name</label>
                                    <input type="text" class="form-control" id="country" name="country" class="country" placeholder="Enter country name"
                                    @if(!empty($shipping['country'])) value="{{ $shipping['country'] }}" @else value="{{ old('country') }}" @endif readonly>
                                </div>

                                <div class="form-group">
                                    <label for="0_500g">Rate(0g to 500g)</label>
                                    <input type="text" class="form-control" id="0_500g" name="0_500g" class="0_500g" placeholder="Enter 0_500g"
                                    @if(!empty($shipping['0_500g'])) value="{{ $shipping['0_500g'] }}" @else value="{{ old('0_500g') }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="501_1000g">Rate(501g to 1000g)</label>
                                    <input type="text" class="form-control" id="501_1000g" name="501_1000g" class="501_1000g" placeholder="Enter 501_1000g"
                                    @if(!empty($shipping['501_1000g'])) value="{{ $shipping['501_1000g'] }}" @else value="{{ old('501_1000g') }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="1001_2000g">Rate(1001g to 2000g)</label>
                                    <input type="text" class="form-control" id="1001_2000g" name="1001_2000g" class="1001_2000g" placeholder="Enter 1001_2000g"
                                    @if(!empty($shipping['1001_2000g'])) value="{{ $shipping['1001_2000g'] }}" @else value="{{ old('1001_2000g') }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="2001_5000g"> Rate(2001g to 5000g)</label>
                                    <input type="text" class="form-control" id="2001_5000g" name="2001_5000g" class="2001_5000g" placeholder="Enter 2001_5000g"
                                    @if(!empty($shipping['2001_5000g'])) value="{{ $shipping['2001_5000g'] }}" @else value="{{ old('2001_5000g') }}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="above_5000g">Rate(Above 5000g)</label>
                                    <input type="text" class="form-control" id="above_5000g" name="above_5000g" class="above_5000g" placeholder="Enter above_5000g"
                                    @if(!empty($shipping['above_5000g'])) value="{{ $shipping['above_5000g'] }}" @else value="{{ old('above_5000g') }}" @endif>
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                <button class="btn btn-light">Cancel</button>
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
