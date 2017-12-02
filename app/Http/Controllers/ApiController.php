<?php

namespace App\Http\Controllers;

use App\Integral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
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

    public function home(){

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

        return response()->json($data);
    }
}
