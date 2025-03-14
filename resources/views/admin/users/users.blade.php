@extends('admin.layout.layout')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">users</h4>

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
                                                User ID
                                            </th>
                                            <th>
                                                Name
                                            </th>
                                            <th>
                                                Address
                                            </th>
                                            <th>
                                                City
                                            </th>
                                            <th>
                                                State
                                            </th>
                                            <th>
                                                Country
                                            </th>
                                            <th>
                                                Pincode
                                            </th>
                                            <th>
                                                Mobile
                                            </th>
                                            <th>
                                                Email
                                            </th>
                                            <th>
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>
                                                    {{ $user['id'] }}
                                                </td>
                                                <td>
                                                    {{ $user['name'] }}
                                                </td>
                                                <td>
                                                    {{ $user['address'] }}
                                                </td>
                                                <td>
                                                    {{ $user['city'] }}
                                                </td>
                                                <td>
                                                    {{ $user['state'] }}
                                                </td>
                                                <td>
                                                    {{ $user['country'] }}
                                                </td>
                                                <td>
                                                    {{ $user['pincode'] }}
                                                </td>
                                                <td>
                                                    {{ $user['mobile'] }}
                                                </td>
                                                <td>
                                                    {{ $user['email'] }}
                                                </td>
                                                <td>
                                                    @if ($user['status'] == 1)
                                                        <a class="updateUsersStatus" id="user-{{ $user['id'] }}"
                                                            user_id="{{ $user['id'] }}" href="javascript:void(0)">
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-check"
                                                                status="Active"></i></a>
                                                    @else
                                                        <a class="updateUsersStatus" id="user-{{ $user['id'] }}"
                                                            user_id="{{ $user['id'] }}" href="javascript:void(0)">
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-outline"
                                                                status="Inactive"></i></a>
                                                    @endif
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
