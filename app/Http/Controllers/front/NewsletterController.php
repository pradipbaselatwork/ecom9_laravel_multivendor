<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function addSubscriberEmail(Request $request){
        if($request->ajax()){
            $data = $request->all();
            $subscriberCount = NewsletterSubscriber::where('email',$data['subscriber_email'])->count();
            if ($subscriberCount > 0) {
                return "exists";
            }else{
                //Add Newsletter email in newsletter_subscribers table
                $subscriber = new NewsletterSubscriber;
                $subscriber->email =$data['subscriber_email'];
                $subscriber->status = 1;
                $subscriber->save();
                return "saved";
            }
        }
    }
}
