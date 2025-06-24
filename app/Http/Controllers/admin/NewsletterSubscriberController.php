<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsletterSubscriber;
use App\Exports\SubscribersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class NewsletterSubscriberController extends Controller
{
    public function subscribers(){
        Session::put('page','subscribers');
        $subscribers = NewsletterSubscriber::get()->toArray();
        // echo "<pre>"; print_r($subscribers); die;
        return view('admin.subscribers.subscribers')->with(compact('subscribers'));
    }

    public function updateSubscribersStatus(Request $request){
        if($request->ajax()){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        if($data['status']=="Active"){
            $status = 0;
        }else{
            $status = 1;
        }
        NewsletterSubscriber::where('id',$data['subscriber_id'])->update(['status'=>$status]);
        return response()->json(['status'=>$status,'subscriber_id'=>$data['subscriber_id']]);
    }
   }

    public function deleteSubscriber($id){
     NewsletterSubscriber::where('id',$id)->delete();
     $message = "Subscriber has been deleted successfulley!";
     return redirect()->back()->with('success_message',$message);
   }

    public function exportSubscribers()
    {
        return Excel::download(new SubscribersExport, 'subscribers.xlsx');
    }

}
