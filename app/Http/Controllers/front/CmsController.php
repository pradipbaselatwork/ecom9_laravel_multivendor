<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class CmsController extends Controller
{
    public function cmsPage(){
        $currentRoute =url()->current();
        $currentRoute =str_replace("http://www.ecom9laravelmultivendor.local.com/","",$currentRoute);
        $cmsRoutes =CmsPage::select('url')->where('status',1)->get()->pluck('url')->toArray();
        if(in_array($currentRoute,$cmsRoutes)){
            $cmsPageDetails = CmsPage::where('url',$currentRoute)->first()->toArray();
            $meta_title =  $cmsPageDetails['meta_title'];
            $meta_keywords =  $cmsPageDetails['meta_keywords'];
            $meta_description =  $cmsPageDetails['meta_description'];
            return view('front.pages.cms_page')->with(compact('cmsPageDetails','meta_title','meta_description','meta_keywords'));
        }else{
            abort(404);
        }
    }

    public function contact(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
    
            $rules = [
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:150',
                'subject' => 'required|max:200',
                'message' => 'required|string|max:1000',
            ];
    
            $customMessages = [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.email' => 'Please enter a valid Email address',
                'subject.required' => 'Subject is required',+
                'message.required' => 'Message is required',
            ];
    
            $this->validate($request, $rules, $customMessages);
    
            $email = "hawakoadmin@gmail.com";
            $messageData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'subject' => $data['subject'],
                'comment' => $data['message'],
            ];
    
            Mail::send('emails.enquiry', $messageData, function($message) use($email) {
                $message->to($email)->subject('Enquiry form - webhatDevelopers.pvt.ltd');
            });
    
            $message = "Thanks for your query. We will get back to you soon.";
            return redirect()->back()->with('success_message', $message);
        }
    
        return view('front.pages.contact');
    }
    
}
