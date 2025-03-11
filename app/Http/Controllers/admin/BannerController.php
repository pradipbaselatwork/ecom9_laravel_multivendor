<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class BannerController extends Controller
{
    public function banners(){
        Session::put('page','banners');
        $banners = Banner::get()->toArray();
        // echo "<pre>"; print_r($banners); die;
        return view('admin.banners.banners')->with(compact('banners'));
    }

    public function updateBannerStatus(Request $request){
        if($request->ajax()){
           $data = $request->all();
           // echo "<pre>"; print_r($data); die;
           if($data['status']=="Active"){
               $status = 0;
           }else{
               $status = 1;
           }
           Banner::where('id',$data['banner_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'banner_id'=>$data['banner_id']]);
        }
   }

   public function addEditBanner(Request $request, $id=null){
    Session::put('page','banners');
    if($id==null){
        $title = "Add Banner";
        $banner = new Banner;
        $message = "Home Page Banner added successfulley!";
    }else{
        $title = "Edit Banner";
        $banner = Banner::find($id);
        $message = "Home Page Banner Updaed successfulley!";
    }

    if($request->isMethod('post')){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $rules = [
            'title' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
        ];
        $customMessages = [
            'title.required' => 'Title is required',
            'title.regex' => 'Valid Name is required',
        ];
        $this->validate($request, $rules, $customMessages);

            if($data['type']=="Slider"){
               $width = "1920";
               $height = "720";
            }else if($data['type']=="Fix"){
                $width = "1920";
                $height = "450";
            }
            //Upload Banner Photo
            if($request->hasFile('image')){
            $image_tmp = $request->file('image');
            if($image_tmp->isValid()){
                // get extension
                $extension = $image_tmp->getClientOriginalExtension();
                //Generate New Image name
                $imageName = rand(111,99999).'.'.$extension;
                $imagePath ='front/images/banner_images/'.$imageName;
                Image::make($image_tmp)->resize($width,$height)->save($imagePath);
                $banner->image = $imageName;
            }
        }

        $banner->type = $data['type'];
        $banner->link = $data['link'];
        $banner->title = $data['title'];
        $banner->alt = $data['alt'];
        $banner->status = 1;
        $banner->save();
        return redirect()->route('admin.banners')->with('success_message',$message);
    }
    return view('admin.banners.add_edit_banner')->with(compact('title','banner'));
   }

   public function deleteBanner($id){
     Banner::where('id',$id)->delete();
     $message = "Home Page Banner has been deleted successfulley!";
     return redirect()->back()->with('success_message',$message);
   }
}
