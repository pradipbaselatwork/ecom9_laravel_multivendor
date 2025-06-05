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
                                            {{-- </th>
                                            <th>
                                                Status
                                            </th> --}}
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
                                                {{-- <td>
                                                    @if ($Subscriber['status'] == 1)
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
                                                </td> --}}
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
