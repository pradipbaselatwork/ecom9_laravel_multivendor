@extends('admin.layout.layout')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Subscribers</h4>

                            @if (Session::has('success_message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success: </strong>{{ Session::get('success_message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                           @endif
                            <div class="table-responsive pt-3">
                                <table id="bootstrap-4-datatables" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>
                                                Subscriber ID
                                            </th>
                                            <th>
                                                Email
                                            </th>
                                            <th>
                                                Subscribed On
                                            </th>
                                            <th>
                                                Status
                                            </th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subscribers as $subscriber)
                                            <tr>
                                                <td>
                                                    {{ $subscriber['id'] }}
                                                </td>
                                                <td>
                                                    {{ $subscriber['email'] }}
                                                </td>
                                                <td>
                                                @php
                                                    date_default_timezone_set('Asia/Kathmandu');
                                                @endphp

                                                    {{ date('F j, Y, g:i a', strtotime($subscriber['created_at'])) }}
                                                </td>
                                                <td>
                                                    @if ($subscriber['status'] == 1)
                                                        <a class="updateSubscribersStatus" id="subscriber-{{ $subscriber['id'] }}"
                                                            subscriber_id="{{ $subscriber['id'] }}" href="javascript:void(0)">
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-check"
                                                                status="Active"></i></a>
                                                    @else
                                                        <a class="updateSubscribersStatus" id="subscriber-{{ $subscriber['id'] }}"
                                                            subscriber_id="{{ $subscriber['id'] }}" href="javascript:void(0)">
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-outline"
                                                                status="Inactive"></i></a>
                                                    @endif
                                                </td>
                                                <td>
                                                 <a href="javascript:void(0)" class="confirmDelete" module="subscriber" moduleid="{{ $subscriber['id'] }}" href="{{ url('admin/delete-subscriber/'.$subscriber['id']) }}"><i
                                                            style="font-size: 25px" class="mdi mdi-file-excel-box"></i></a>
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
