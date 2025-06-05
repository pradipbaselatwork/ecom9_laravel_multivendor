<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsletterSubscriber;
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

}
