<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeliveryAddress;

class DeliveryAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deliveryAddress = [
            ['id'=>1,'user_id'=>'1','name'=>'Hopsin Marcus','address'=>'street 12','city'=>'Ktm','state'=>'Bagmati','country'=>'Nepal','pincode'=>'435454','mobile'=>'9848246143','status'=>'1'],
            ['id'=>2,'user_id'=>'3','name'=>'Pradip Syn','address'=>'street 40','city'=>'Damauli','state'=>'Gandaki','country'=>'Nepal Forever','pincode'=>'45353','mobile'=>'9761629115','status'=>'1'],
        ];
        DeliveryAddress::insert($deliveryAddress);
    }
}
