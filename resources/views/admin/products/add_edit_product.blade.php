@extends('admin.layout.layout')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h3 class="font-weight-bold">Products</h3>
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
                            <form class="forms-sample"
                                @if (empty($products['id'])) action="{{ url('admin/add-edit-product') }}" @else action="{{ url('admin/add-edit-product/' . $products['id']) }}" @endif
                                method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="category_id">Select Category</label>
                                    <select name="category_id" id="category_id" class="form-control text-dark">
                                        <option value="">Select Section</option>
                                        @foreach ($categories as $section)
                                         <optgroup label="{{ $section['name'] }}"></optgroup>
                                            @foreach ($section['categories'] as $category)
                                                <option value="{{ $category['id'] }}" @if(!empty($products['category_id'] == $category['id'])) selected="" @endif>&nbsp;&nbsp;&nbsp; --&nbsp;
                                                    {{ $category['category_name'] }}</option>
                                                @foreach ($category['subcategories'] as $subcategory)
                                                    <option value="{{ $subcategory['id'] }}" @if(!empty($products['category_id'] == $subcategory['id'])) selected="" @endif>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; --&nbsp;
                                                        {{ $subcategory['category_name'] }} </option>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>

                                <div class="loadFilters">
                                    @include('admin.filters.category_filters')
                                </div>

                                <div class="form-group">
                                    <label for="brand_id">Select Brand</label>
                                    <select name="brand_id" id="brand_id" class="form-control text-dark">
                                        <option value="">Select</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand['id'] }}" @if(!empty($products['brand_id'] == $brand['id'])) selected="" @endif>{{ $brand['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div id="appendProductsLevel">
                                        @include('admin.products.append_products_level')
                                </div> --}}

                                <div class="form-group">
                                    <label for="product_name">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name"
                                        placeholder="Enter product name"
                                        @if (!empty($products['product_name'])) value="{{ $products['product_name'] }}" @else value="{{ old('product_name') }}" @endif>
                                </div>


                                <div class="form-group">
                                    <label for="product_code">Product Code</label>
                                    <input type="text" class="form-control" id="product_code" name="product_code"
                                        placeholder="Enter product code"
                                        @if (!empty($products['product_code'])) value="{{ $products['product_code'] }}" @else value="{{ old('product_code') }}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="product_color">Product Color</label>
                                    <input type="text" class="form-control" id="product_color" name="product_color"
                                        placeholder="Enter product color"
                                        @if (!empty($products['product_color'])) value="{{ $products['product_color'] }}" @else value="{{ old('product_color') }}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="product_price">Product Price</label>
                                    <input type="text" class="form-control" id="product_price" name="product_price"
                                        placeholder="Enter product price"
                                        @if (!empty($products['product_price'])) value="{{ $products['product_price'] }}" @else value="{{ old('product_price') }}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="product_discount">Product Discount</label>
                                    <input type="text" class="form-control" id="product_discount" name="product_discount"
                                        placeholder="Enter product weight"
                                        @if (!empty($products['product_discount'])) value="{{ $products['product_discount'] }}" @else value="{{ old('product_discount') }}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="product_weight">Product Weight</label>
                                    <input type="number" class="form-control" id="product_weight"
                                        name="product_weight" placeholder="Enter product weight"
                                        @if (!empty($products['product_weight'])) value="{{ $products['product_weight'] }}" @else value="{{ old('product_weight') }}" @endif>
                                </div>


                                <div class="form-group">
                                    <label for="group_code">Group Code</label>
                                    <input type="text" class="form-control" id="group_code"
                                        name="group_code" placeholder="Enter Group Code"
                                        @if (!empty($products['group_code'])) value="{{ $products['group_code'] }}" @else value="{{ old('group_code') }}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="product_image">Product Image (Recommend size: 1000x1000)</label>
                                    <input type="file" class="form-control" value="" id="product_image" name="product_image">
                                    @if (!empty($products['product_image']))
                                        <a target="_blank" href="{{ url('front/images/product_images/large/'.$products['product_image']) }}">View Image</a>&nbsp;&nbsp;
                                        <a href="javascript:void(0)" class="confirmDelete" module="product-image" moduleid="{{ $products['id'] }}" href="">Delete Image</a>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="product_video">Product Video (Recommend Size: less then  2 MB)</label>
                                    <input type="file" class="form-control" value="" id="product_video" name="product_video">
                                    @if (!empty($products['product_video']))
                                        <a target="_blank" href="{{ url('front/videos/product_videos/'.$products['product_video']) }}">View Video</a>&nbsp;&nbsp;
                                        <a href="javascript:void(0)" class="confirmDelete" module="product-video" moduleid="{{ $products['id'] }}" href="">Delete Video</a>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" cols="30" rows="10" class="form-control"
                                        placeholder="Write something..">{{ $products['description'] }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title"
                                        placeholder="Enter meta title"
                                        @if (!empty($products['meta_title'])) value="{{ $products['meta_title'] }}" @else value="{{ old('meta_title') }}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <input type="text" class="form-control" id="meta_description"
                                        name="meta_description" placeholder="Enter meta description"
                                        @if (!empty($products['meta_description'])) value="{{ $products['meta_description'] }}" @else value="{{ old('meta_description') }}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="meta_keywords">Meta Keywords</label>
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                        placeholder="Enter meta keywords"
                                        @if (!empty($products['meta_keywords'])) value="{{ $products['meta_keywords'] }}" @else value="{{ old('meta_keywords') }}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="meta_keywords">Featured Item</label>
                                    <input type="checkbox" name="is_featured" id="is_featured" value="Yes" @if(!empty($products['is_featured'] == $products['is_featured'])) checked="" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="meta_keywords">Best Selling Item</label>
                                    <input type="checkbox" name="is_bestseller" id="is_bestseller" value="Yes" @if(!empty($products['is_bestseller'] == $products['is_bestseller'])) checked="" @endif>
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
