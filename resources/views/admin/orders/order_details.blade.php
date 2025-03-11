@php
    use App\Models\Product;
    use App\Models\OrdersLog;
    use App\Models\Vendor;
@endphp
@extends('admin.layout.layout')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            @if (Session::has('success_message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success: </strong>{{ Session::get('success_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h3 class="font-weight-bold">View Order Details</h3>
                            <a href="{{ url('admin/orders') }}">
                                <h6 class="font-weight-bold">Back To Orders</h6>
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

            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Order Details</h4>
                                    <div class="form-group" style="height:15px">
                                        <label style="font-weight:550px"; for="exampleInputUsername1">Order Id</label> :
                                        <label>{{ $orderDetails['id'] }}</label>
                                    </div>
                                    <div class="form-group" style="height:15px">
                                        <label style="font-weight:550px"; for="exampleInputUsername1">Order Date</label> :
                                        <label>{{ date('d-m-Y H:i:s', strtotime($orderDetails['created_at'])) }}</label>
                                    </div>
                                    <div class="form-group" style="height:15px">
                                        <label style="font-weight:550px"; for="exampleInputUsername1">Order Status</label> :
                                        <label>{{ $orderDetails['order_status'] }}</label>
                                    </div>
                                    <div class="form-group" style="height:15px">
                                        <label style="font-weight:550px"; for="exampleInputUsername1">Order Total</label> :
                                        <label>{{ $orderDetails['grand_total'] }}</label>
                                    </div>
                                    <div class="form-group" style="height:15px">
                                        <label style="font-weight:550px"; for="exampleInputUsername1">Shipping
                                            Charges</label> :
                                        <label>{{ $orderDetails['shipping_charges'] }}</label>
                                    </div>
                                    @if (!empty($orderDetails['coupon_code']))
                                        <div class="form-group" style="height:15px">
                                            <label style="font-weight:550px"; for="exampleInputUsername1">Coupon
                                                Code</label> :
                                            <label>{{ $orderDetails['coupon_code'] }}</label>
                                        </div>
                                        <div class="form-group" style="height:15px">
                                            <label style="font-weight:550px"; for="exampleInputUsername1">Coupon
                                                Amount</label> :
                                            <label>{{ $orderDetails['coupon_amount'] }}</label>
                                        </div>
                                    @endif
                                    <div class="form-group" style="height:15px">
                                        <label style="font-weight:550px"; for="exampleInputUsername1">Payment Method</label>
                                        :
                                        <label>{{ $orderDetails['payment_method'] }}</label>
                                    </div>
                                    <div class="form-group" style="height:15px">
                                        <label style="font-weight:550px"; for="exampleInputUsername1">Payment
                                            Gateway</label> :
                                        <label>{{ $orderDetails['payment_gateway'] }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Customer Details</h4>
                                    <div class="form-group" style="height:15px">
                                        <label style="font-weight:550px"; for="exampleInputUsername1">Name</label> :
                                        <label>{{ $userDetails['name'] }}</label>
                                    </div>
                                    {{-- @if (!empty($orderDetails['address']))
                            <div class="form-group" style="height:15px">
                                <label style="font-weight:550px"; for="exampleInputUsername1">Address</label> :
                                 <label>{{ $userDetails['address'] }}</label>
                            </div>
                            @endif --}}
                                    {{-- @if (!empty($orderDetails['city']))
                            <div class="form-group" style="height:15px">
                                <label style="font-weight:550px"; for="exampleInputUsername1">City</label> :
                                 <label>{{ $userDetails['city'] }}</label>
                            </div>
                            @endif
                            @if (!empty($orderDetails['state']))
                            <div class="form-group" style="height:15px">
                                <label style="font-weight:550px"; for="exampleInputUsername1">State</label> :
                                 <label>{{ $userDetails['state'] }}</label>
                            </div>
                            @endif
                            @if (!empty($orderDetails['country']))
                            <div class="form-group" style="height:15px">
                                <label style="font-weight:550px"; for="exampleInputUsername1">Country</label> :
                                 <label>{{ $userDetails['country'] }}</label>
                            </div>
                            @endif
                            @if (!empty($orderDetails['pincode']))
                            <div class="form-group" style="height:15px">
                                <label style="font-weight:550px"; for="exampleInputUsername1">Pincode</label> :
                                <label>#{{ $userDetails['pincode'] }}</label>
                            </div>
                            @endif --}}
                                    <div class="form-group" style="height:15px">
                                        <label style="font-weight:550px"; for="exampleInputUsername1">Mobile</label> :
                                        <label>{{ $userDetails['mobile'] }}</label>
                                    </div>
                                    <div class="form-group" style="height:15px">
                                        <label style="font-weight:550px"; for="exampleInputUsername1">Email</label> :
                                        <label>{{ $userDetails['email'] }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Delivery Address</h4>
                                    <div class="form-group" style="height:15px">
                                        <label style="font-weight:550px"; for="exampleInputUsername1">Name</label> :
                                        <label>{{ $userDetails['name'] }}</label>
                                    </div>
                                    @if (!empty($orderDetails['address']))
                                        <div class="form-group" style="height:15px">
                                            <label style="font-weight:550px"; for="exampleInputUsername1">Address</label>
                                            :
                                            <label>{{ $userDetails['address'] }}</label>
                                        </div>
                                    @endif
                                    @if (!empty($orderDetails['city']))
                                        <div class="form-group" style="height:15px">
                                            <label style="font-weight:550px"; for="exampleInputUsername1">City</label> :
                                            <label>{{ $userDetails['city'] }}</label>
                                        </div>
                                    @endif
                                    @if (!empty($orderDetails['state']))
                                        <div class="form-group" style="height:15px">
                                            <label style="font-weight:550px"; for="exampleInputUsername1">State</label> :
                                            <label>{{ $userDetails['state'] }}</label>
                                        </div>
                                    @endif
                                    @if (!empty($orderDetails['country']))
                                        <div class="form-group" style="height:15px">
                                            <label style="font-weight:550px"; for="exampleInputUsername1">Country</label>
                                            :
                                            <label>{{ $userDetails['country'] }}</label>
                                        </div>
                                    @endif
                                    @if (!empty($orderDetails['pincode']))
                                        <div class="form-group" style="height:15px">
                                            <label style="font-weight:550px"; for="exampleInputUsername1">Pincode</label>
                                            :
                                            <label>#{{ $userDetails['pincode'] }}</label>
                                        </div>
                                    @endif
                                    <div class="form-group" style="height:15px">
                                        <label style="font-weight:550px"; for="exampleInputUsername1">Mobile</label> :
                                        <label>{{ $userDetails['mobile'] }}</label>
                                    </div>
                                    <div class="form-group" style="height:15px">
                                        <label style="font-weight:550px"; for="exampleInputUsername1">Email</label> :
                                        <label>{{ $userDetails['email'] }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Update Order Status</h4>
                                    @if (Auth::guard('admin')->user()->type != 'vendor')
                                        <form action="{{ route('admin.update-order-status') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $orderDetails['id'] }}">
                                            <div class="form-group form-inline">
                                                <select name="order_status" id="order_status" class="form-control"
                                                    style="color:#000; width:120px" required>
                                                    <option value="" selected>Select</option>
                                                    @foreach ($orderStatuses as $status)
                                                        <option value="{{ $status['name'] }}"
                                                            @if (!empty($orderDetails['order_status']) && $orderDetails['order_status'] == $status['name']) selected="" @endif>
                                                            {{ $status['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                <input style="margin-left:8px; width:151px;" class="form-control"
                                                    type="text" id="courier_name" name="courier_name"
                                                    placeholder="Courier Name">
                                                <input style="margin-left:8px; width:151px;" class="form-control"
                                                    type="text" id="tracking_number" name="tracking_number"
                                                    placeholder="Tracking Number">
                                                <button style="margin-left:8px;" type="submit"
                                                    class="btn btn-primary mr-2">Update</button><br>
                                            </div>
                                        </form><br>

                                        @foreach ($orderLogs as $key => $log)
                                            <strong>{{ $log['order_status'] }}</strong><br>
                                            @if (isset($log['order_item_id']) && $log['order_item_id'] > 0)
                                                @php $getItemDetails = OrdersLog::getItemDetails($log['order_item_id']) @endphp
                                                - for item: {{ $getItemDetails['product_code'] }}
                                                {{-- @dd($getItemDetails); --}}
                                                @if (!empty($getItemDetails['courier_name']))
                                                    <br><span>Courier Name: {{ $getItemDetails['courier_name'] }}</span>
                                                @endif

                                                @if (!empty($getItemDetails['tracking_number']))
                                                    <br><span>Tracking Name:
                                                        {{ $getItemDetails['tracking_number'] }}</span>
                                                @endif
                                            @endif
                                            @if ($log['order_status'] == 'Shipped')
                                                @if (!empty($orderDetails['courier_name']))
                                                    <br><span>Courier Name: {{ $orderDetails['courier_name'] }}</span>
                                                @endif
                                                @if (!empty($orderDetails['tracking_number']))
                                                    <br><span>Tracking Name: {{ $orderDetails['tracking_number'] }}</span>
                                                @endif
                                            @endif <br>
                                            {{ date('Y-m-d, h:i:s', strtotime($log['created_at'])) }}
                                            <br>
                                            <hr>
                                        @endforeach
                                        {{-- </form> --}}
                                    @else
                                        This feature is restricted to you!.
                                    @endif
                                </div>
                            </div>
                        </div>

                        <table class="table table-striped table-borderless">
                            <tr class="table-danger">
                                <th>Product Image</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Product Price</th>
                                <th>Product Qty</th>
                                <th>Total Price</th>
                                @if(Auth::guard('admin')->user()->type!="vendor")
                                <th>Product By</th>
                                @endif
                                <th>Commission</th>
                                <th>Final Amount</th>
                                <th>Product Status</th>
                            </tr>
                            @foreach ($orderDetails['orders_products'] as $product)
                            {{-- @dd($product); --}}
                                <tr>
                                    <td>
                                        @php $getProductImage = Product::getProductImage($product['product_id']) @endphp
                                        <img style="width:50px; height:50px;"
                                            src="{{ asset('front/images/product_images/small/' . $getProductImage) }}">
                                    </td>
                                    <td>{{ $product['product_code'] }}</td>
                                    <td>{{ $product['product_name'] }}</td>
                                    <td>{{ $product['product_color'] }}</td>
                                    <td>{{ $product['product_size'] }}</td>
                                    <td>{{ $product['product_price'] }}</td>
                                    <td>{{ $product['product_qty'] }}</td>
                                    <td> 
                                    @if($product['vendor_id']>0)
                                    @if($orderDetails['coupon_amount']>0)
                                      @php $couponDetails = Coupon::couponDetails($orderDetails['coupon_code']) @endphp
                                        @if ($couponDetails['vendor_id']>0)
                                      {{ $total_price = $product['product_price']*$product['product_qty']-$item_discount }}
                                         @else
                                      {{ $total_price = $product['product_price']*$product['product_qty'] }}
                                        @endif
                                    @else
                                        {{ $total_price = $product['product_price']*$product['product_qty'] }}
                                    @endif
                                    @else
                                    {{ $total_price = $product['product_price']*$product['product_qty'] }}
                                    @endif   
                                    </td>
                                    @if(Auth::guard('admin')->user()->type!="vendor")
                                    @if($product['vendor_id']>0)
                                    <td> 
                                        <a target="_blank" href="/admin/view-vendor-details/{{ $product['admin_id']}}">Vendor</a>
                                    </td>
                                         @else
                                            <td>Admin</td>
                                       @endif
                                    @endif

                                    {{-- @if(Auth::guard('admin')->user()->type=="vendor") --}}
                                    @if($product['vendor_id']>0)
                                     @php  $getVendorCommisison = Vendor::getVendorCommission($product['vendor_id']); @endphp 
                                     <td>{{ $commission = round($total_price * $getVendorCommisison/100, 2) }}</td>
                                     <td>{{ $total_price - $commission }}</td>
                                    @else
                                     <td>0</td>
                                     <td>{{ $total_price }}</td>
                                    @endif
                                    {{-- @endif --}}

                                    <td>
                                        <form action="{{ route('admin.update-order-item-status') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="item_order_id" value="{{ $product['id'] }}">
                                            <div class="form-group form-inline" style="height:15px">
                                                <select name="item_order_status" id="item_order_status"
                                                    class="form-control" style="color:#000; margin-left:6px; width:110px;"
                                                    required>
                                                    <option value="" selected>Select</option>
                                                    @foreach ($orderItemStatuses as $status)
                                                        <option value="{{ $status['name'] }}"
                                                            @if (!empty($product['item_status']) && $product['item_status'] == $status['name']) selected="" @endif>
                                                            {{ $status['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                <input style="margin-left:6px;  width:140px;" class="form-control"
                                                    type="text" id="item_courier_name" name="item_courier_name"
                                                    placeholder="Courier Name">
                                                <input style="margin-left:6px;  width:140px;" class="form-control"
                                                    type="text" id="item_tracking_number" name="item_tracking_number"
                                                    placeholder="Tracking Number">
                                                <button style="margin-left:6px;  width:80px;" type="submit"
                                                    class="btn btn-primary mr-2">Update</button>
                                            </div><br>
                                            <hr>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <!-- content-wrapper ends -->
                        <!-- partial:partials/_footer.html -->
                        @include('admin.layout.footer')
                        <!-- partial -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
