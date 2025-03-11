<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductsFilter;
use App\Models\ProductsFiltersValue;
use App\Models\Section;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class FilterController extends Controller
{
    public function filters(){
        Session::put('page','filters');
        $filters = ProductsFilter::get()->toArray();
        // echo "<pre>"; print_r($filters); die;
        return view('admin.filters.filters')->with(compact('filters'));
    }

    public function filtersValues(){
        Session::put('page','filters');
        $filters_values = ProductsFiltersValue::get()->toArray();
        // echo "<pre>"; print_r($filters_values); die;
        return view('admin.filters.filters_values')->with(compact('filters_values'));
    }

    public function updateFilterStatus(Request $request){
        if($request->ajax()){
           $data = $request->all();
           // echo "<pre>"; print_r($data); die;
           if($data['status']=="Active"){
               $status = 0;
           }else{
               $status = 1;
           }
           ProductsFilter::where('id',$data['filter_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'filter_id'=>$data['filter_id']]);
        }
   }
   public function updateFilterValuesStatus(Request $request){
    if($request->ajax()){
       $data = $request->all();
       // echo "<pre>"; print_r($data); die;
       if($data['status']=="Active"){
           $status = 0;
       }else{
           $status = 1;
       }
       ProductsFiltersValue::where('id',$data['filter_id'])->update(['status'=>$status]);
        return response()->json(['status'=>$status,'filter_id'=>$data['filter_id']]);
    }
}

   public function addEditFilter(Request $request, $id=null){
    Session::put('page','filters');
    if($id==null){
        $title = "Add Product Filters";
        $filter = new ProductsFilter;
        $message = "Products filters added successfulley!";
    }else{
        $title = "Edit Product filters";
        $filter = ProductsFilter::find($id);
        $message = "Products filters Updaed successfulley!";
    }

    if($request->isMethod('post')){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $rules = [
            'filter_name' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
        ];
        $customMessages = [
            'filter_name.required' => 'Filter Name is required',
            'filter_name.regex' => 'Valid Filter Name is required',
        ];
        $this->validate($request, $rules, $customMessages);
        $cat_ids = implode(',', $data['cat_ids']);

        //Save filter columns in products_filters table
        $filter->cat_ids = $cat_ids;
        $filter->filter_name = $data['filter_name'];
        $filter->filter_column = $data['filter_column'];
        $filter->status = 1;
        $filter->save();

        //Add filter columns in products table
        DB::statement('Alter table products add '.$data['filter_column'].' varchar(255) after description');
        return redirect()->route('admin.filters')->with('success_message',$message);
    }

        //get sections with categories and subcategories
    $categories = Section::with(['categories'])->get()->toArray();
    return view('admin.filters.add_edit_filter')->with(compact('title','filter','categories'));
   }

   public function addEditFilterValue(Request $request, $id=null){
    Session::put('page','filters');
    if($id==null){
        $title = "Add Product Filters Value";
        $filter = new ProductsFiltersValue;
        $message = "Products filters Value added successfulley!";
    }else{
        $title = "Edit Product filters Value";
        $filter = ProductsFiltersValue::find($id);
        $message = "Products filters Value Updaed successfulley!";
    }

    if($request->isMethod('post')){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $rules = [
            'filter_value' => 'required',
        ];
        $customMessages = [
            'filter_value.required' => 'Filter Values is required',
        ];
        $this->validate($request, $rules, $customMessages);

        //Save filter columns Values in products_filters_values table
        $filter->filter_id = $data['filter_id'];
        $filter->filter_value = $data['filter_value'];
        $filter->status = 1;
        $filter->save();
        return redirect()->route('admin.filters-values')->with('success_message',$message);
    }
      //get filter id and value form product_filters table
      $filters = ProductsFilter::where('status',1)->get()->toArray();
    return view('admin.filters.add_edit_filter_value')->with(compact('title','filter','filters'));
   }

   public function deleteFilter($id){
    ProductsFilter::where('id',$id)->delete();
     $message = "Products Filters has been deleted successfulley!";
     return redirect()->back()->with('success_message',$message);
   }

   public function deleteFilterValue($id){
    ProductsFiltersValue::where('id',$id)->delete();
     $message = "Products Filters has been deleted successfulley!";
     return redirect()->back()->with('success_message',$message);
   }

   public function categoryFilters(Request $request){
    if($request->ajax()){
        $data = $request->all();
        // dd($data);
        $category_id = $data['category_id'];
        return response()->json([
            'view'=>(String)View::make('admin.filters.category_filters')->with(compact('category_id'))->render()
        ]);
     }
   }
}
