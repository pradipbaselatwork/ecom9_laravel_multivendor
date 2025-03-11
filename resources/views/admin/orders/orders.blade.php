@extends('admin.layout.layout')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Orders</h4>
                            <div class="table-responsive pt-3">
                                <table id="bootstrap-4-datatables" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>
                                                Order ID
                                            </th>
                                            <th>
                                                Order Date
                                            </th>
                                            <th>
                                                Customer Name
                                            </th>
                                            <th>
                                                Customer Email
                                            </th>
                                            <th>
                                                Ordered Products
                                            </th>
                                            <th>
                                                Order Amount
                                            </th>
                                            <th>
                                                Order Status
                                            </th>
                                            <th>
                                                Payment Method
                                            </th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>
                                                    {{ $order['id'] }}
                                                </td>
                                                <td>
                                                    {{ $order['created_at'] }}
                                                </td>
                                                <td>
                                                    {{ $order['name'] }}
                                                </td>
                                                <td>
                                                    {{ $order['email'] }}
                                                </td>
                                                <td>
                                                    @foreach ($order['orders_products'] as $product)
                                                        {{ $product['product_code'] }} ({{ $product['product_qty'] }}) <br>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    Rs.{{ number_format($order['grand_total']) }} <br>
                                                </td>
                                                <td>
                                                    {{ $order['order_status'] }}
                                                </td>
                                                <td>
                                                    {{ $order['payment_method'] }}
                                                </td>
                                                <td>
                                                    <a title="View Order Details"
                                                        href="{{ url('admin/orders/' . $order['id']) }}"><i
                                                            style="font-size: 25px" class="mdi mdi-file-document"></i></a>
                                                    &nbsp;&nbsp;
                                                    <a target="_blank" title="View Order Invoice"
                                                        href="{{ url('admin/orders-invoice/' . $order['id']) }}"><i
                                                            style="font-size: 25px" class="mdi mdi-printer"></i></a>
                                                    &nbsp;&nbsp;
                                                    <a target="_blank" title="View Order PDF Invoice"
                                                        href="{{ url('admin/orders-invoice/pdf/' . $order['id']) }}"><i
                                                            style="font-size: 25px" class="mdi mdi-file-pdf"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
