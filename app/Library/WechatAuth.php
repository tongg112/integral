<?php
/**
 * Created by PhpStorm.
 * User: xtong
 * Date: 17-11-26
 * Time: 下午4:06
 */

namespace App\Library;
/**
 * 微信登录检测操作类
 * @author    Xtong <tongg112@126.com>
 * @version   WechatAuth.class.php  2016年1月13日 15:58:52
 */
class WechatAuth {
    public $appid = 'wx6fdb8cd3ce989859';
    public $appsecret = 'd4624c36b6795d1d99dcf0547af5443d';
    protected $code;//code码，用以获取openid
    protected $openid;//用户的openid
    protected $curl_timeout;//curl超时时间
    public $state = 123;
    public $scope = "snsapi_base"; //snsapi_base或者snsapi_userinfo

    function __construct()
    {
        //设置curl超时时间
        $this->curl_timeout = 30;//curl超时时间
    }

    function get_url() {
        //获取来源地址

        $URL['PHP_SELF'] = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : (isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['ORIG_PATH_INFO']);   //当前页面名称
        $URL['DOMAIN'] = $_SERVER['SERVER_NAME'];  //域名(主机名)
        $URL['QUERY_STRING'] = $_SERVER['REQUEST_URI'];   //URL 参数
        $URL['URI'] = $URL['PHP_SELF'].($URL['QUERY_STRING'] ? "?".$URL['QUERY_STRING'] : "");
        $myurl  = "http://".$URL['DOMAIN'].$URL['PHP_SELF'].($URL['QUERY_STRING'] ? "_".$URL['QUERY_STRING'] : ""); //完整URL地址
print_r($URL);die;
        return $myurl;
    }

    /**
     * 	作用：生成可以获得code的url
     */
    function createOauthUrlForCode($redirectUrl)
    {
        $state = $this->state;
        $urlObj["appid"] = $this->appid;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = $this->scope;
        $urlObj["state"] = $state."#wechat_redirect";
        $bizString = $this->formatBizQueryParaMap($urlObj, false);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }

    /**
     * 	作用：生成可以获得openid的url
     */
    function createOauthUrlForOpenid()
    {
        $urlObj["appid"] = $this->appid;
        $urlObj["secret"] = $this->appsecret;
        $urlObj["code"] = $this->code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->formatBizQueryParaMap($urlObj, false);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
    }


    /**
     * 	作用：通过curl向微信提交code，以获取openid
     */
    function getOpenid()
    {
        $url = $this->createOauthUrlForOpenid();
        // 初始化curl
        $ch = curl_init();
        // 设置超时
        curl_setopt($ch, CURLOP_TIMEOUT, $this->curl_timeout);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // 运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        // 取出openid
        $data = json_decode($res,true);
        $this->openid = $data['openid'];
        return $this->openid;
    }

    /**
     * 	作用：格式化参数，签名过程需要使用
     */
    function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            if($urlencode)
            {
                $v = urlencode($v);
            }
            //$buff .= strtolower($k) . "=" . $v . "&";
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }

    /**
     * 	作用：设置code
     */
    function setCode($code_)
    {
        $this->code = $code_;
    }

    // 在当前网址获取openid
    function myopenid(){
        //通过code获得openid
        if (!isset($_GET['code']))
        {
            //触发微信返回code码
            $url = $this->createOauthUrlForCode($this->get_url());
            Header("Location: $url");
        }else
        {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $this->setCode($code);
            return $this->getOpenId();
        }
    }


    /**
     * 	作用：获取用户基本信息
     */
    function GetUserMsg()
    {
        $this->scope = "snsapi_userinfo";
        if (!isset($_GET['code']))
        {
            // 触发微信返回code码
            $url = $this->createOauthUrlForCode($this->get_url());
            Header("Location: $url");
        }else
        {
            // 获取code码，以获取openid
            $code = $_GET['code'];
            $this->setCode($code);
            $url = $this->createOauthUrlForOpenid();
            $data = $this->curlto($url);
            $msgurl = "https://api.weixin.qq.com/sns/userinfo?access_token=".$data['access_token']."&openid=".$data['openid'];
            $msgdata = $this->curlto($msgurl);
            return $msgdata;
        }
    }

    function curlto($url){
        // 初始化curl
        $ch = curl_init();
        // 设置超时
        curl_setopt($ch, CURLOP_TIMEOUT, $this->curl_timeout);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // 运行curl，结果以json形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        // 取出openid
        $data = json_decode($res,true);
        return $data;
    }
}
