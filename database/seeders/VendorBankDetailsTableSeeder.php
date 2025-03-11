<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VendorBankDetail;


class VendorBankDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendorRecords = [
            ['id'=>1,'vendor_id'=>'1','account_holder_name'=>'Electronic Company','bank_name'=>'Parbhu Bank','account_number'=>'003949349349341','bank_ifsc_code'=>'0031']
        ];
        VendorBankDetail::insert($vendorRecords);
    }
}
