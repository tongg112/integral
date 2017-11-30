<?php

namespace App\Http\Controllers;

use App\Integral;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 获取积分信息
        $uid = Auth::id();
        /** @var $integral Integral */
        $integral = new Integral();
        $result = $integral->where('uid',$uid)->first();
        // 没有积分信息则插入
        if(empty($result)){
            $available = 0;
            $integral->uid = $uid;
            $integral->total = 0;
            $integral->available = 0;
            $integral->save();
        }else{
            $available= $result->available;
        }

        // 展示积分信息
        $data = [
            'available' => $available
        ];

        return view('home',$data);
    }

    /**
     * 增加积分
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function integralIncrease(){

        return view('home');
    }

    /**
     * 扣除积分
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function integralDecrease(){

        return view('home');
    }
}
