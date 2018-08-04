<?php

namespace App\Http\Controllers;

use App\Integral;
use App\IntegralLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    /**
     * 获取当前可用积分
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function home()
    {

        // 获取积分信息
        $uid = Auth::id();
        /** @var $integral Integral */
        $integral = new Integral();
        // 没有积分信息则插入
        $default = [
            'available' => 0,
            'total' => 0
        ];
        $result = $integral->firstOrCreate(['uid' => $uid], $default);

        // 展示积分信息
        $data = [
            'available' => $result->available
        ];

        return response()->json($data);
    }

    /**
     * 积分变更
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function change_integral(Request $request)
    {

        // 获取积分信息
        $uid = Auth::id();

        // 插入数据
        DB::transaction(function () use ($request, $uid) {
            $integral = Integral::where('uid', $uid);
            // 增加的积分就增加总数，否则不变化总数
            if($request['point'] >0 ){
                $integral->increment('total', $request['point']);
            }
            $integral->increment('available', $request['point']);

            $integralLog = new IntegralLog();
            $insertData = [
                'uid' => $uid,
                'changes' => $request['point'],
                'remark' => $request['remark'],
            ];

            $integralLog->fill($insertData);
            $integralLog->save();
        });

        $result = Integral::where('uid',$uid)->first();

        $data = [
            'available' => $result->available
        ];
        return response()->json($data);
    }

    /**
     * 积分记录
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function integral_list(Request $request)
    {

        // 获取积分信息
        $uid = Auth::id();

        // 查询记录
        $integral_list = IntegralLog::where('uid', $uid)->orderBy('id','desc')->get()->toArray();

        $data = [
            'integral_list' => $integral_list
        ];

        return response()->json($data);
    }
}
