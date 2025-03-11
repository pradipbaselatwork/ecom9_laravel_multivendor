<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Section;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function categories(){
        Session::put('page','categories');
       $categories = Category::with(['section','parentcategory'])->get()->toArray();
       return view('admin.categories.categories')->with(compact('categories'));
    }

    public function updateCategoryStatus(Request $request){
        if($request->ajax()){
           $data = $request->all();
        //    echo "<pre>"; print_r($data); die;
           if($data['status']=="Active"){
               $status = 0;
           }else{
               $status = 1;
           }
           Category::where('id',$data['category_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'category_id'=>$data['category_id']]);
        }
   }

   public function addEditCategory(Request $request,$id=null){
    Session::put('page','categories');
    if($id==""){
        $title = "Add Category";
        $category = new Category;
        $getCategories = array();
        $message = "Category added successfulley!";
    }else{
        $title = "Edit Category";
        $category = Category::find($id);
        $getCategories = Category::with('subcategories')->where(['parent_id' =>0, 'status'=>1])->get();
        // echo "<pre>"; print_r($getCategories); die;
        $message = "Category Updaed successfulley!";
    }

    if($request->isMethod('post')){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $rules = [
            'category_name' => 'required|regex:^[A-ZÀÂÇÉÈÊËÎÏÔÛÙÜŸÑÆŒa-zàâçéèêëîïôûùüÿñæœ0-9_.,()]+$^',
            'section_id' =>'required',
            'url' => 'required',
        ];
        $customMessages = [
            'category_name.required' => 'Name is required',
            'category_name.regex' => 'Valid Name is required',
            'section_id.required' => 'Section ID is required',
            'url.required' => 'Url is required',
        ];
        $this->validate($request, $rules, $customMessages);

         //Upload Category Photo
        if($request->hasFile('category_image')){
            $image_tmp = $request->file('category_image');
            if($image_tmp->isValid()){
                // get extension
                $extension = $image_tmp->getClientOriginalExtension();
                //Generate New Image name
                $imageName = rand(111,99999).'.'.$extension;
                $imagePath ='front/images/category_images/'.$imageName;
                Image::make($image_tmp)->resize(350, 320)->save($imagePath);
                $category->category_image = $imageName;
           }
        }

        if($data['category_discount']==""){
            $data['category_discount']=0;
         }

        if($data['description']==""){
            $data['description']="";
         }

        $category->category_name = $data['category_name'];
        $category->section_id = $data['section_id'];
        $category->parent_id = $data['parent_id'];
        $category->category_discount = $data['category_discount'];
        $category->description = $data['description'];
        $category->url = $data['url'];
        $category->meta_title = $data['meta_title'];
        $category->meta_description = $data['meta_description'];
        $category->meta_keywords = $data['meta_keywords'];
        $category->status = 1;
        $category->save();
        return redirect()->route('admin.categories')->with('success_message',$message);
    }

    $getSections = Section::get()->toArray();
    return view('admin.categories.add_edit_category')->with(compact('title','category','getSections','getCategories'));
   }

   public function appendCategoryLevel(Request $request){
    if($request->ajax()){
         $data = $request->all();
         $getCategories = Category::with('subcategories')->where(['parent_id'=>0,'section_id'=>$data['section_id']])->get()->toArray();
        //  dd($getCategories);
         return view('admin.categories.append_categories_level')->with(compact('getCategories'));
     }
}

   public function deleteCategory($id){
    Category::where('id',$id)->delete();
    $message = "Category has been deleted successfulley!";
    return redirect()->back()->with('success_message',$message);
   }
}
