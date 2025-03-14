<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function section(){
        return $this->belongsTo('App\Models\Section','section_id')->select('id','name','status');
    }

    public function category(){
        return $this->belongsTo('App\Models\Category','category_id');
    }

    public function brand(){
        return $this->belongsTo('App\Models\Brand','brand_id');
    }

    public function vendor(){
        return $this->belongsTo('App\Models\Vendor','vendor_id')->with('vendorBusinessDetails');
    }

    public function attributes(){
        return $this->hasMany('App\Models\ProductAttribute');
    }

    public function images(){
        return $this->hasMany('App\Models\ProductImage');
    }

    public static function getDiscountPrice($product_id){
        $proDetails = Product::select('product_price','product_discount','category_id')->where('id',$product_id)->first();
        $proDetails = json_decode(json_encode($proDetails), true);

        $catDetails = Category::select('category_discount')->where('id',$proDetails['category_id'])->first();
        $catDetails = json_decode(json_encode($catDetails), true);

        if($proDetails['product_discount']>0){
            //if product discount is added from the admin panel
            $discounted_price = $proDetails['product_price'] - ($proDetails['product_price'] * $proDetails['product_discount']/100);
        }else if($catDetails['category_discount']>0){
            //if product discount is not added but category discount is added form the admin panel
            $discounted_price = $proDetails['product_price'] - ($proDetails['product_price'] * $catDetails['category_discount']/100);
        }else{
             $discounted_price = 0;
        }
        return $discounted_price;
    }

    public static function getDiscountAttributePrice($product_id,$size){
        $proAttrPrice = ProductAttribute::where(['product_id'=>$product_id,'size'=>$size])->first()->toArray();
        $proDetails = Product::select('product_price','product_discount','category_id')->where('id',$product_id)->first();
        $proDetails = json_decode(json_encode($proDetails), true);

        $catDetails = Category::select('category_discount')->where('id',$proDetails['category_id'])->first();
        $catDetails = json_decode(json_encode($catDetails), true);

        if($proDetails['product_discount']>0){
            //if product discount is added from the admin panel
            $final_price = $proAttrPrice['price'] - ($proAttrPrice['price'] *$proDetails['product_discount']/100);
            $discount = $proAttrPrice['price']-$final_price;
        }else if($catDetails['category_discount']>0){
            //if product discount is not added but category discount is added form the admin panel
            $final_price = $proAttrPrice['price'] - ($proAttrPrice['price'] *$catDetails['category_discount']/100);
            $discount = $proAttrPrice['price']-$final_price;
        }else{
             $final_price = $proAttrPrice['price'];
             $discount =0;
        }
        return array('product_price'=>$proAttrPrice['price'],'final_price'=>$final_price,'discount'=>$discount);
    }

    public static function isProductNew($product_id){
        //Get Last 3 Products
        $productIds = Product::select('id')->where('status',1)->orderby('id','Desc')->limit(3)->pluck('id');
        $productIds = json_decode(json_encode($productIds),true);
        // dd($productIds);
        if(in_array($product_id,$productIds)){
            $isProductNew = "Yes";
        }else{
            $isProductNew = "No";
        }
        return $isProductNew;
    }

    public static function getProductImage($product_id){
        $getProductImage = Product::select('product_image')->where('id',$product_id)->first()->toArray();
        return $getProductImage['product_image'];
    }

    public static function getProductStatus($product_id){
       $getProductStatus = Product::select('status')->where('id',$product_id)->first();
       return $getProductStatus->status;
    }

    public static function deleteCartProduct($product_id){
        Cart::where('product_id',$product_id)->delete();
     }
 
}
