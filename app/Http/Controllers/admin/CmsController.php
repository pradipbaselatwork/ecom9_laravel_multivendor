<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Illuminate\Support\Facades\Session;

class CmsController extends Controller
{
       public function cmsPages(){
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
}
