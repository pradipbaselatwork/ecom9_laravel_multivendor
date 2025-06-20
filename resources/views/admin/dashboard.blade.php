@extends('admin.layout.layout')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-md-12 grid-margin">
          <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
              <h3 class="font-weight-bold">Welcome {{ (Auth::guard('admin')->user()->name) }}</h3>
              <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6>
            </div>
            <div class="col-12 col-xl-4">
             <div class="justify-content-end d-flex">
              <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
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
     <div class="col-md-6 grid-margin transparent">
          <div class="row">
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-tale">
                <div class="card-body">
                  <p class="mb-4">Total Sections</p>
                  <p class="fs-30 mb-2">{{ $sectionsCount }}</p>
                  {{-- <p>10.00% (30 days)</p> --}}
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-dark-blue">
                <div class="card-body">
                  <p class="mb-4">Total Categories</p>
                  <p class="fs-30 mb-2">{{ $categoriesCount }}</p>
                  {{-- <p>22.00% (30 days)</p> --}}
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
              <div class="card card-light-blue">
                <div class="card-body">
                  <p class="mb-4">Total Orders</p>
                  <p class="fs-30 mb-2">{{ $ordersCount }}</p>
                  {{-- <p>2.00% (30 days)</p> --}}
                </div>
              </div>
            </div>
            <div class="col-md-6 stretch-card transparent">
              <div class="card card-light-danger">
                <div class="card-body">
                  <p class="mb-4">Total Coupons</p>
                  <p class="fs-30 mb-2">{{ $couponsCount }}</p>
                  {{-- <p>0.22% (30 days)</p> --}}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 grid-margin transparent">
          <div class="row">
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-tale">
                <div class="card-body">
                  <p class="mb-4">Total Products</p>
                  <p class="fs-30 mb-2">{{ $productsCount }}</p>
                  {{-- <p>10.00% (30 days)</p> --}}
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-dark-blue">
                <div class="card-body">
                  <p class="mb-4">Total Brands</p>
                  <p class="fs-30 mb-2">{{ $brandsCount }}</p>
                  {{-- <p>22.00% (30 days)</p> --}}
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
              <div class="card card-light-blue">
                <div class="card-body">
                  <p class="mb-4">Total Users</p>
                  <p class="fs-30 mb-2">{{ $usersCount }}</p>
                  {{-- <p>2.00% (30 days)</p> --}}
                </div>
              </div>
            </div>
            <div class="col-md-6 stretch-card transparent">
              <div class="card card-light-danger">
                <div class="card-body">
                  <p class="mb-4">Total Subscribers</p>
                  <p class="fs-30 mb-2">{{ $newsletterSubscribersCount }}</p>
                  {{-- <p>0.22% (30 days)</p> --}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      {{-- <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <p class="card-title">Order Details</p> 
              <p class="font-weight-500">The total number of sessions within the date range. It is the period time a user is actively engaged with your website, page or app, etc</p>
              <div class="d-flex flex-wrap mb-5">
                <div class="mr-5 mt-3">
                  <p class="text-muted">Order value</p>
                  <h3 class="text-primary fs-30 font-weight-medium">12.3k</h3>
                </div>
                <div class="mr-5 mt-3">
                  <p class="text-muted">Orders</p>
                  <h3 class="text-primary fs-30 font-weight-medium">14k</h3>
                </div>
                <div class="mr-5 mt-3">
                  <p class="text-muted">Users</p>
                  <h3 class="text-primary fs-30 font-weight-medium">71.56%</h3>
                </div>
                <div class="mt-3">
                  <p class="text-muted">Downloads</p>
                  <h3 class="text-primary fs-30 font-weight-medium">34040</h3>
                </div>
              </div>
              <canvas id="order-chart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
             <div class="d-flex justify-content-between">
              <p class="card-title">Sales Report</p>
              <a href="#" class="text-info">View all</a>
             </div>
              <p class="font-weight-500">The total number of sessions within the date range. It is the period time a user is actively engaged with your website, page or app, etc</p>
              <div id="sales-legend" class="chartjs-legend mt-4 mb-2"></div>
              <canvas id="sales-chart"></canvas>
            </div>
          </div>
        </div>
      </div> --}}
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    @include('admin.layout.footer')
    <!-- partial -->
  </div>
@endsection
