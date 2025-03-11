<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VendorBusinessDetail;

class VendorBusinessDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendorBusinessDetailRecords = [
            ['id'=>1,'vendor_id'=>'1','shop_name'=>'Web Electronic','shop_address'=>'Jagwal Chowk','shop_city'=>'ktm','shop_state'=>'Bagmati','shop_country'=>'Nepal','shop_pincode'=>'lane 2001','shop_mobile'=>"9761629115",'shop_website'=>"www.webelectronic.com",'shop_email'=>"webelectronic@gmail.com",'address_proof'=>"Passport",'address_proof_image'=>"test.jpg",'business_license_number'=>"12345",'get_number'=>"54321",'shop_mobile'=>"04-12345454",'pan_number'=>"0031"]
        ];
        VendorBusinessDetail::insert($vendorBusinessDetailRecords);
    }
}
