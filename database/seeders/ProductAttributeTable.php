<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;

class ProductAttributeTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productAttributeTableRecords = [
            ['id'=>1,'product_id'=>2, 'size'=>'Small', 'price'=>1000, 'stock'=>10, 'sku'=>'RCOO1-S','status'=>'1'],
            ['id'=>2,'product_id'=>2, 'size'=>'Medium', 'price'=>1500, 'stock'=>15, 'sku'=>'RCOO1-M','status'=>'1'],
            ['id'=>3,'product_id'=>2, 'size'=>'Large', 'price'=>2000, 'stock'=>20, 'sku'=>'RCOO1-L','status'=>'1'],
        ];
        ProductAttribute::insert($productAttributeTableRecords);
    }
}
