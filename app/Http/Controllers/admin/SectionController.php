<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\Session;

class SectionController extends Controller
{
    public function sections(){
        Session::put('page','sections');
        $sections = Section::get()->toArray();
        // echo "<pre>"; print_r($sections); die;
        return view('admin.sections.sections')->with(compact('sections'));
    }

    public function updateSectionStatus(Request $request){
        if($request->ajax()){
           $data = $request->all();
           // echo "<pre>"; print_r($data); die;
           if($data['status']=="Active"){
               $status = 0;
           }else{
               $status = 1;
           }
           Section::where('id',$data['section_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'section_id'=>$data['section_id']]);
        }
   }

   public function addEditSection(Request $request, $id=null){
    Session::put('page','sections');
    if($id==null){
        $title = "Add Section";
        $section = new Section;
        $message = "Section added successfulley!";
    }else{
        $title = "Edit Section";
        $section = Section::find($id);
        $message = "Section Updaed successfulley!";
    }

    if($request->isMethod('post')){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $rules = [
            'section_name' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
        ];
        $customMessages = [
            'section_name.required' => 'Name is required',
            'section_name.regex' => 'Valid Name is required',
        ];
        $this->validate($request, $rules, $customMessages);

        $section->name = $data['section_name'];
        $section->status = 1;
        $section->save();
        return redirect()->route('admin.sections')->with('success_message',$message);
    }
    return view('admin.sections.add_edit_section')->with(compact('title','section'));
   }

   public function deleteSection($id){
     Section::where('id',$id)->delete();
     $message = "Section has been deleted successfulley!";
     return redirect()->back()->with('success_message',$message);
   }
}
