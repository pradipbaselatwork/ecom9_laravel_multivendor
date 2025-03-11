<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productRecords = [
            ['id'=>1,'section_id'=>2,'category_id'=>9,'brand_id'=>9,'vendor_id'=>1,'admin_type'=>'vendor','product_name'=>'Iphone11',
            'product_code'=>'CMIP01','product_color'=>'White','product_price'=>15000,'product_discount'=>10,'product_weight'=>500,'product_image'=>'','product_video'=>'','description'=>'is descrlption',
            'meta_title'=>'','meta_description'=>'','meta_keywords'=>'','is_featured'=>'Yes','status'=>1],

            ['id'=>2,'section_id'=>1,'category_id'=>1,'brand_id'=>1,'vendor_id'=>0,'admin_type'=>'superadmin','product_name'=>'Red-Casual T-Shirt',
            'product_code'=>'RCTSP02','product_color'=>'RED','product_price'=>2000,'product_discount'=>20,'product_weight'=>100,'product_image'=>'','product_video'=>'','description'=>'red tshirt desc',
            'meta_title'=>'red tshirt meta title','meta_description'=>'red tshirt meta desc','meta_keywords'=>'red tshirt meta keywords','is_featured'=>'Yes','status'=>1],
        ];
        Product::insert($productRecords);
    }
}
