@extends('admin.layout.layout')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Cms Pages</h4>

                            <a style="max-width: 150px; float:right; display:inline-block;" href="{{ route('admin.add-edit-cms-pages') }}" class="btn btn-block btn-primary">Add Cms Page</a>

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
                                                Cms ID
                                            </th>
                                            <th>
                                                Title
                                            </th>
                                            <th>
                                                Url
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
                                        @foreach ($cmspages as $cmspage)
                                            <tr>
                                                <td>
                                                    {{ $cmspage['id'] }}
                                                </td>
                                                 <td>
                                                    {{ $cmspage['title'] }}
                                                </td>
                                                <td>
                                                    {{ $cmspage['url'] }}
                                                </td>
                                                <td>
                                                    @if ($cmspage['status'] == 1)
                                                        <a class="updateCmsAdminStatus" id="cmspage-{{ $cmspage['id'] }}"
                                                            cmspage_id="{{ $cmspage['id'] }}" href="javascript:void(0)">
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-check"
                                                                status="Active"></i></a>
                                                    @else
                                                        <a class="updateCmsAdminStatus" id="cmspage-{{ $cmspage['id'] }}"
                                                            cmspage_id="{{ $cmspage['id'] }}" href="javascript:void(0)">
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-outline"
                                                                status="Inactive"></i></a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('admin/add-edit-cms-pages/'.$cmspage['id']) }}"><i style="font-size: 25px" class="mdi mdi-pencil-box"></i></a>

                                                    <a href="javascript:void(0)" class="confirmDelete" module="cms-pages" moduleid="{{ $cmspage['id'] }}" href="{{ url('admin/delete-cms-pages/'.$cmspage['id']) }}"><i
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
