<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function brands(){
        Session::put('page','brands');
        $brands = Brand::get()->toArray();
        // echo "<pre>"; print_r($brands); die;
        return view('admin.brands.brands')->with(compact('brands'));
    }

    public function updateBrandStatus(Request $request){
        if($request->ajax()){
           $data = $request->all();
           // echo "<pre>"; print_r($data); die;
           if($data['status']=="Active"){
               $status = 0;
           }else{
               $status = 1;
           }
           Brand::where('id',$data['brand_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'brand_id'=>$data['brand_id']]);
        }
   }

   public function addEditBrand(Request $request, $id=null){
    Session::put('page','brands');
    if($id==null){
        $title = "Add Brand";
        $brand = new Brand;
        $message = "Brand added successfulley!";
    }else{
        $title = "Edit Brand";
        $brand = Brand::find($id);
        $message = "Brand Updaed successfulley!";
    }

    if($request->isMethod('post')){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $rules = [
            'brand_name' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
        ];
        $customMessages = [
            'brand_name.required' => 'Name is required',
            'brand_name.regex' => 'Valid Name is required',
        ];
        $this->validate($request, $rules, $customMessages);

        $brand->name = $data['brand_name'];
        $brand->status = 1;
        $brand->save();
        return redirect()->route('admin.brands')->with('success_message',$message);
    }
    return view('admin.brands.add_edit_brand')->with(compact('title','brand'));
   }

   public function deleteBrand($id){
     Brand::where('id',$id)->delete();
     $message = "Brand has been deleted successfulley!";
     return redirect()->back()->with('success_message',$message);
   }
}

