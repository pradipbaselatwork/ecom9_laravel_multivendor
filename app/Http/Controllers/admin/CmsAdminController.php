<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Illuminate\Support\Facades\Session;

class CmsAdminController extends Controller
{
    public function cmsPages(){
      Session::put('page','cmspages');
      $cmspages = CmsPage::get();
      return view('admin.pages.cms_page')->with(compact('cmspages'));
    }

    public function updateCmsPagesStatus(Request $request){
        if($request->ajax()){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        if($data['status']=="Active"){
            $status = 0;
        }else{
            $status = 1;
        }
        CmsPage::where('id',$data['cmspage_id'])->update(['status'=>$status]);
        return response()->json(['status'=>$status,'cmspage_id'=>$data['cmspage_id']]);
    }
   }

    public function addEditCmsPages(Request $request,$id=null){
    Session::put('page','cmspages');
    if($id==""){
        $title = "Add Cmspage";
        $cmspages = new CmsPage;
        $message = "Cmspage added successfulley!";

    }else{
        $title = "Edit Cmspage";
        $cmspages = CmsPage::find($id);
        $message = "Cmspage Updaed successfulley!";
    }

    if($request->isMethod('post')){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $rules = [
            'title' => 'required',
            'url' => 'required',
            'description' => 'required',
        ];
        $customMessages = [
            'title.required' => 'Cmspage title is required',
            'url.required' => 'Cmspage url is required',
            'url.required' => 'Cmspage description is required',
        ];

        $this->validate($request, $rules, $customMessages);

        $cmspages->title = $data['title'];
        $cmspages->url = $data['url'];
        $cmspages->description = $data['description'];
        $cmspages->meta_title = $data['meta_title'];
        $cmspages->meta_description = $data['meta_description'];
        $cmspages->meta_keywords = $data['meta_keywords'];
        $cmspages->status = 1;
        $cmspages->save();
        return redirect()->route('admin.cms-pages')->with('success_message',$message);
    }
     return view('admin.pages.add_edit_cms_page')->with(compact('title','cmspages'));
   }

    public function deleteCmsPages($id){
     CmsPage::where('id',$id)->delete();
     $message = "CmsPage has been deleted successfulley!";
     return redirect()->back()->with('success_message',$message);
   }

}
