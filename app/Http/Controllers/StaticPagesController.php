<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\WechatAuth;

class StaticPagesController extends Controller
{
    // home
    public function home(){
        return view('home');
    }

    // about
    public function about(){

        $wechat = new WechatAuth();
        $url = $wechat->get_url();

        print_r($url);die;

        return view('about');
    }
}
