<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brandsRecords = [
            ['id'=>1,'name'=>'Arrow','status'=>'1'],
            ['id'=>2,'name'=>'Grap','status'=>'1'],
            ['id'=>3,'name'=>'Lee','status'=>'1'],
            ['id'=>4,'name'=>'Samsung','status'=>'1'],
            ['id'=>5,'name'=>'LG','status'=>'1'],
            ['id'=>6,'name'=>'lenevo','status'=>'1'],
            ['id'=>7,'name'=>'MI','status'=>'1'],
            ['id'=>8,'name'=>'Iphone','status'=>'1'],
            
        ];
        Brand::insert($brandsRecords);
    }
}
