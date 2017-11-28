<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // 首页
    public function home()
    {
        return view('home');
    }
}
