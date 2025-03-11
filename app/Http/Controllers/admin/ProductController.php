<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Section;
use App\Models\ProductAttribute;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Models\ProductsFilter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function products(){
        Session::put('page','products');
        // $products = Product::with(['section','category'])->get()->toArray();
        // echo "<pre>"; print_r($products); die;
        $adminType = Auth::guard('admin')->user()->type;
        $vendor_id = Auth::guard('admin')->user()->vendor_id;

        if($adminType=="vendor"){
            $vendorStatus = Auth::guard('admin')->user()->status;
            if($vendorStatus==0){
                return redirect('admin/update-vendor-details/personal')->with('error_message','Your Vendor Account is not approved yet. Please make sure to fill your valid personal, business and bank details');
            }
        }
        $products = Product::with(['section'=>function($query){
              $query->select('id','name');
        },'category'=>function($query){
             $query->select('id','category_name');
        }]);
        if($adminType=="vendor"){
            $products = $products->where('vendor_id',$vendor_id);
        }

        $products = $products->get()->toArray();
        return view('admin.products.products')->with(compact('products'));
    }

    public function updateProductStatus(Request $request){
        if($request->ajax()){
           $data = $request->all();
           if($data['status']=="Active"){
               $status = 0;
           }else{
               $status = 1;
           }
           Product::where('id',$data['product_id'])->update(['status'=>$status]);
           return response()->json(['status'=>$status,'product_id'=>$data['product_id']]);
        }
   }

   public function deleteProduct($id){
       Product::where('id',$id)->delete();
       $message = "Product has been deleted successfulley!";
       return redirect()->back()->with('success_message',$message);
  }

   public function addEditProduct(Request $request,$id=null){
    Session::put('page','products');
    if($id==""){
        $title = "Add Product";
        $products = new Product;
        $message = "Product added successfulley!";

     }else{
        $title = "Edit Product";
        $products = Product::find($id);
        $message = "Product Updaed successfulley!";
     }

     if($request->isMethod('post')){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        // dd($data);
        $rules = [
            'category_id' =>'required',
            'product_name' => 'required|regex:^[A-ZÀÂÇÉÈÊËÎÏÔÛÙÜŸÑÆŒa-zàâçéèêëîïôûùüÿñæœ0-9_.,()]+$^',
            'product_code' => 'required|regex:/^[\w-]*$/',
            'product_price' => 'required|numeric',
            'product_color' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
        ];
        $customMessages = [
            'category_id.required' => 'Category ID is required',
            'product_name.required' => 'Product name is required',
            'product_name.regex' => 'Valid Product name is required',
            'product_code.required' => 'Product code is required',
            'product_code.regex' => 'Valid Product code is required',
            'product_price.required' => 'Product price is required',
            'product_price.regex' => 'Valid Product price is required',
            'product_color.required' => 'Product color is required',
            'product_color.regex' => 'Valid Product color is required',
        ];
    $this->validate($request, $rules, $customMessages);

        //Upload Product Image after Resize small: 250X250 medium:500X500 large:1000X1000
    if($request->hasFile('product_image')){
        $image_tmp = $request->file('product_image');
        if($image_tmp->isValid()){
              // get image extension
            $extension = $image_tmp->getClientOriginalExtension();
              //Generate New Image name
            $imageName = rand(111,99999).'.'.$extension;
            $largeImagePath ='front/images/product_images/large/'.$imageName;
            $mediumImagePath ='front/images/product_images/medium/'.$imageName;
            $smallImagePath ='front/images/product_images/small/'.$imageName;

            Image::make($image_tmp)->resize(1000,1000)->save($largeImagePath);
            Image::make($image_tmp)->resize(500, 500)->save($mediumImagePath);
            Image::make($image_tmp)->resize(250, 250)->save($smallImagePath);
            $products->product_image = $imageName;
        }
    }

    // Upload Product Video
    if($request->hasFile('product_video')){
        $video_tmp = $request->file('product_video');
        if($video_tmp->isValid()){
              // get Video extension
            $extension = $video_tmp->getClientOriginalExtension();
              //Generate New Video name
            $videoName = rand(111,99999).'.'.$extension;
            $videoPath ='front/videos/product_videos/';
            $video_tmp->move($videoPath,$videoName);
            //Inset Video name in products table
            $products->product_video = $videoName;
        }
    }

        if($data['product_discount']==""){
            $data['product_discount']=0;
         }


        if($data['product_weight']==""){
            $data['product_weight']=0;
         }

        if($data['description']==""){
            $data['description']="";
         }

        if($data['group_code']==""){
        $data['group_code']="";
        }

         //Save Product Details in products table
        $categoryDetails = Category::find($data['category_id']);
        $products->section_id = $categoryDetails['section_id'];
        $products->category_id = $data['category_id'];
        $products->brand_id = $data['brand_id'];
        $products->group_code = $data['group_code'];

        $productFilters = ProductsFilter::productFilters();
        foreach($productFilters as $filter){
            $filterAvailable = ProductsFilter::filterAvailable($filter['id'],$data['category_id']);
            if($filterAvailable=="Yes"){
                if(isset($filter['filter_column']) && $data[$filter['filter_column']]){
                    $products->{$filter['filter_column']} = $data[$filter['filter_column']];
                }
            }
        }

        $adminType = Auth::guard('admin')->user()->type;
        $vendor_id = Auth::guard('admin')->user()->vendor_id;
        $admin_id = Auth::guard('admin')->user()->id;

        $products->admin_type = $adminType;
        $products->admin_id = $admin_id;
        if($adminType=="vendor"){
            $products->vendor_id =$vendor_id;
        }else{
            $products->vendor_id =0;
        }

        $products->product_name = $data['product_name'];
        $products->product_code = $data['product_code'];
        $products->product_color = $data['product_color'];
        $products->product_price = $data['product_price'];
        $products->product_discount = $data['product_discount'];
        $products->product_weight = $data['product_weight'];
        $products->description = $data['description'];
        $products->meta_title = $data['meta_title'];
        $products->meta_description = $data['meta_description'];
        $products->meta_keywords = $data['meta_keywords'];
        if(!empty($data['is_featured'])){
             $products->is_featured = $data['is_featured'];
        }else{
             $products->is_featured = "No";
        }
        if(!empty($data['is_bestseller'])){
            $products->is_bestseller = $data['is_bestseller'];
       }else{
            $products->is_bestseller = "No";
       }
        $products->status = 1;
        $products->save();
        return redirect()->route('admin.products')->with('success_message',$message);
    }
    //get sections with categories and subcategories
     $categories = Section::with(['categories'])->get()->toArray();
     //get all the brands
     $brands = Brand::where('status',1)->get()->toArray();
     return view('admin.products.add_edit_product')->with(compact('title','categories','brands','products'));
   }

   public function deleteProductImage($id){
    //Get Product Image
    $productImage = Product::select('product_image')->where('id',$id)->first();

    //get Product Imgae Path
    $small_image_path = 'front/images/product_images/small/';
    $medium_image_path = 'front/images/product_images/medium/';
    $large_image_path = 'front/images/product_images/large/';

    //Delete Product small image if exists in small folder
    if(file_exists($small_image_path.$productImage->product_image)){
        unlink($small_image_path.$productImage->product_image);
    }
       //Delete Product medium image if exists in small folder
       if(file_exists($medium_image_path.$productImage->product_image)){
        unlink($medium_image_path.$productImage->product_image);
    }

           //Delete Product Large image if exists in small folder
        if(file_exists($large_image_path.$productImage->product_image)){
        unlink($large_image_path.$productImage->product_image);
    }

    //Delete Product Image form products Table
        Product::where('id',$id)->update(['product_image'=>'']);
        $message = "Product Image has been deleted successfulley!";
        return redirect()->back()->with('success_message',$message);

   }

   public function deleteProductVideo($id){
    //Get Product Image
    $productVideo = Product::select('product_video')->where('id',$id)->first();

    //get Product Video Path
    $product_video_path = 'front/videos/product_videos/';

    //Delete Product Video s if exists in small folder
    if(file_exists($product_video_path.$productVideo->product_video)){
        unlink($product_video_path.$productVideo->product_video);
    }
    //Delete Product Video form products Table
        Product::where('id',$id)->update(['product_video'=>'']);
        $message = "Product Video has been deleted successfulley!";
        return redirect()->back()->with('success_message',$message);
   }

   public function addAttributes(Request $request,$id){
    Session::put('page','products');
      $products = Product::select('id','product_name','product_code','product_color','product_price','product_image')->with('attributes')->find($id);
    //   dd($products);
      $products = json_decode(json_encode($products),true);

      if($request->isMethod('post')){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;

            foreach($data['sku'] as $key => $value){
            if(!empty($value)){
                // SKU duplicate check
            $skuCount = ProductAttribute::where('sku',$value)->count();
            if($skuCount>0){
                return redirect()->back()->with('error_message','SKU already exists! Plz add another SKU!');
            }

            // $sizeCount = ProductAttribute::where(['product_id'=>$id],'size',$data['size'][$key])->count();
            // if($sizeCount>0){
            //     return redirect()->back()->with('error_message','Size already exists! Plz add another size!');
            // }

            $attribute = new ProductAttribute;
            $attribute->product_id = $id;
            $attribute->sku = $value;
            $attribute->size = $data['size'][$key];
            $attribute->price = $data['price'][$key];
            $attribute->stock = $data['stock'][$key];
            $attribute->status = 1;
            $attribute->save();
        }
      }
      return redirect()->back()->with('success_message','Product attributes added successfulley!');
    }
      return view('admin.attributes.add_edit_attributes')->with(compact('products'));
   }

   public function updateAttributeStatus(Request $request){
    if($request->ajax()){
       $data = $request->all();
       if($data['status']=="Active"){
           $status = 0;
       }else{
           $status = 1;
       }
       ProductAttribute::where('id',$data['attribute_id'])->update(['status'=>$status]);
        return response()->json(['status'=>$status,'attribute_id'=>$data['attribute_id']]);
    }
}

public function editAttribute(Request $request){
     if($request->isMethod('post')){
        $data = $request->all();
        foreach($data['attributeId'] as $key => $attribute){
            ProductAttribute::where(['id'=>$data['attributeId'][$key]])->update(['price'=>$data['price'][$key],'stock'=>$data['stock'][$key]]);
        }
        return redirect()->back()->with('success_message','Product attributes updated successfulley!');
     }
}

public function addImages(Request $request,$id){
    Session::put('page','products');
    $products = Product::select('id','product_name','product_code','product_color','product_price','product_image')->with('images')->find($id);
    // dd($products);
    if($request->isMethod('post')){
        $data = $request->all();
        // dd($data);
        if($request->hasFile('images')){
            $images = $request->file('images');
            foreach($images as $key => $image){
                //Generate Temp Image
                $image_tmp = Image::make($image);
                   //Get Image Name
                   $image_name = $image->getClientOriginalName();
                    // get image extension
                  $extension = $image->getClientOriginalExtension();
                    //Generate New Image name
                  $imageName = $image_name.rand(111,99999).'.'.$extension;
                  $largeImagePath ='front/images/product_images/large/'.$imageName;
                  $mediumImagePath ='front/images/product_images/medium/'.$imageName;
                  $smallImagePath ='front/images/product_images/small/'.$imageName;

                  Image::make($image_tmp)->resize(1000,1000)->save($largeImagePath);
                  Image::make($image_tmp)->resize(500, 500)->save($mediumImagePath);
                  Image::make($image_tmp)->resize(250, 250)->save($smallImagePath);

                //Insert Image Name in products Table
                $image = new ProductImage;
                $image->image = $imageName;
                $image->product_id = $id;
                $image->status = 1;
                $image->save();
            }
        }
        return redirect()->back()->with('success_message','Product images has been added successfulley!');

        }
    return view('admin.images.add_images')->with(compact('products'));

}

public function updateImageStatus(Request $request){
    if($request->ajax()){
       $data = $request->all();
       if($data['status']=="Active"){
           $status = 0;
       }else{
           $status = 1;
       }
       ProductImage::where('id',$data['image_id'])->update(['status'=>$status]);
        return response()->json(['status'=>$status,'image_id'=>$data['image_id']]);
    }
}

public function deleteImage($id){
    //Get Product Image
    $productImage = ProductImage::select('image')->where('id',$id)->first();

    //get Product Imgae Path
    $small_image_path = 'front/images/product_images/small/';
    $medium_image_path = 'front/images/product_images/medium/';
    $large_image_path = 'front/images/product_images/large/';

    //Delete Product small image if exists in small folder
    if(file_exists($small_image_path.$productImage->image)){
        unlink($small_image_path.$productImage->image);
    }
       //Delete Product medium image if exists in small folder
       if(file_exists($medium_image_path.$productImage->image)){
        unlink($medium_image_path.$productImage->image);
    }

           //Delete Product Large image if exists in small folder
        if(file_exists($large_image_path.$productImage->image)){
        unlink($large_image_path.$productImage->image);
    }

    //Delete Product Image form products Table
    ProductImage::where('id',$id)->delete();
        $message = "Product Image has been deleted successfulley!";
        return redirect()->back()->with('success_message',$message);

   }

}
