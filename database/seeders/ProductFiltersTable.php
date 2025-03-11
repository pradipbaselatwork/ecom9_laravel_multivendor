<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductsFilter;

class ProductFiltersTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ProductFiltersTable = [
            ['id'=>1,'cat_ids'=>'1,2,3,12,15,16,17,24,19', 'filter_name'=>'Fabric', 'filter_column'=>'fabric', 'status'=>'1'],
            ['id'=>2,'cat_ids'=>'4,5,9,10,11,13,14,22,23,25,26,27,28,29,30,31', 'filter_name'=>'Ram', 'filter_column'=>'ram', 'status'=>'1'],
        ];
        ProductsFilter::insert($ProductFiltersTable);
    }
}
