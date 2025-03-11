<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendorRecords = [
            ['id'=>1,'name'=>'John Cena','address'=>'Europe Canada','city'=>'Ottawa','state'=>'Bagmati','country'=>'Canada','pincode'=>'9001','mobile'=>'9761629115','email'=>"johncena@gmail.com",'status'=>1]
        ];
        Vendor::insert($vendorRecords);
    }
}
