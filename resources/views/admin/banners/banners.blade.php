@extends('admin.layout.layout')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Home Page Banners</h4>

                            <a style="max-width: 170px; float:right; display:inline-block;" href="{{ route('admin.add-edit-banner') }}" class="btn btn-block btn-primary">Home Page Banners</a>

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
                                                Banner ID
                                            </th>
                                            <th>
                                                Type
                                            </th>
                                            <th>
                                                Image
                                            </th>
                                            <th>
                                                Link
                                            </th>
                                            <th>
                                                Title
                                            </th>
                                            <th>
                                                Alt
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
                                        @foreach ($banners as $banner)
                                            <tr>
                                                <td>
                                                    {{ $banner['id'] }}
                                                </td>
                                                <td>
                                                    {{ $banner['type'] }}
                                                </td>
                                                <td>
                                                    @if(!empty($banner['image']))
                                                    <img style="height: 80px; width: 80px" src="{{ url('front/images/banner_images/'.$banner['image']) }}">
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $banner['link'] }}
                                                </td>
                                                <td>
                                                    {{ $banner['title'] }}
                                                </td>
                                                <td>
                                                    {{ $banner['alt'] }}
                                                </td>
                                                <td>
                                                    @if ($banner['status'] == 1)
                                                        <a class="updateBannerStatus" id="banner-{{ $banner['id'] }}"
                                                            banner_id="{{ $banner['id'] }}" href="javascript:void(0)">
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-check"
                                                                status="Active"></i></a>
                                                    @else
                                                        <a class="updateBannerStatus" id="banner-{{ $banner['id'] }}"
                                                            banner_id="{{ $banner['id'] }}" href="javascript:void(0)">
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-outline"
                                                                status="Inactive"></i></a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('admin/add-edit-banner/'.$banner['id']) }}"><i
                                                            style="font-size: 25px" class="mdi mdi-pencil-box"></i></a>

                                                    <a href="javascript:void(0)" class="confirmDelete" module="banner" moduleid="{{ $banner['id'] }}" href="{{ url('admin/delete-banner/'.$banner['id']) }}"><i
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
