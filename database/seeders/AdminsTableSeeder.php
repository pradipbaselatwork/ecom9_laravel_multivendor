<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRecords = [
            ['id'=>1,'name'=>'First Vendor', 'type'=>'admin','vendor_id'=>0,'mobile'=>'9761629115','email'=>"admin@admin.com",'password'=>'7c4a8d09ca3762af61e59520943dc26494f8941b','image'=>'','status'=>1],
            ['id'=>2,'name'=>'First Vendor', 'type'=>'vendor','vendor_id'=>1,'mobile'=>'9761629115','email'=>"johncena@gmail.com",'password'=>'7c4a8d09ca3762af61e59520943dc26494f8941b','image'=>'','status'=>1]
        ];
        Admin::insert($adminRecords);
    }
}
