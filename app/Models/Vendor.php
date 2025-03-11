<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    public function vendorBusinessDetails(){
        return $this->belongsTo('App\Models\VendorBusinessDetail','id','vendor_id');
    }

    public static function getVendorShop($vendorid){
        // $getVendorShop = VendorBusinessDetail::select('shop_name')->where('vendor_id',$vendorid)->first()->toArray();
        $getVendorShop = VendorBusinessDetail::select('shop_name')->where('vendor_id',$vendorid)->first()->toArray();
        // dd($getVendorShop);
        return $getVendorShop['shop_name'];
    }

    public static function getVendorCommission($vendorid){
        $getVendorCommission = Vendor::select('commission')->where('id',$vendorid)->first()->toArray();
        // dd($getVendorCommission);
        return $getVendorCommission['commission'];
    }
}
