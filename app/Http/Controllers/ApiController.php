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

        // 提取参数
        $changePoint = $request['point'];

        // 插入数据
        DB::transaction(function () use ($changePoint, $uid) {
            $integral = Integral::where('uid', $uid);
            $integral->increment('total', $changePoint);
            $integral->increment('available', $changePoint);

            $integralLog = new IntegralLog();
            $insertData = [
                'uid' => $uid,
                'changes' => $changePoint,
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
}
