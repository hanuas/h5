<?php
/* PHP SDK
 * @version 2.0.0
 * @author connect@qq.com
 * @copyright © 2013, Tencent Corporation. All rights reserved.
 */

require_once(CLASS_PATH."Recorder.class.php");
require_once(CLASS_PATH."URL.class.php");
require_once(CLASS_PATH."ErrorCase.class.php");

class Oauth{

    const VERSION = "2.0";
    const GET_AUTH_CODE_URL = "https://graph.qq.com/oauth2.0/authorize";
    const GET_ACCESS_TOKEN_URL = "https://graph.qq.com/oauth2.0/token";
    const GET_OPENID_URL = "https://graph.qq.com/oauth2.0/me";
    const GET_USERINFO_URL = "https://graph.qq.com/user/get_user_info";

    protected $recorder;
    public $urlUtils;
    protected $error;

    protected $app_id;
    protected $app_key;
    

    function __construct($app_id,$app_key){
        $this->recorder = new Recorder();
        $this->urlUtils = new URL();
        $this->error = new ErrorCase();
        $this->app_id = $app_id;
        $this->app_key = $app_key;
    }

    //原qq_login
    public function getAuthorizeURL($callBackUrl,$scop,$state){
        $appid = $this->app_id;
        $callback = $callBackUrl;
        $scope = $scop;


        //-------构造请求参数列表
        $keysArr = array(
            "response_type" => "code",
            "client_id" => $appid,
            "redirect_uri" => $callback,
            "state" => $state,
            "scope" => $scope
        );

        $login_url =  $this->urlUtils->combineURL(self::GET_AUTH_CODE_URL, $keysArr);

        return $login_url;
    }

    //原 qq_callback
    public function getAccessToken($code,$callBackUrl){
        //-------请求参数列表
        $keysArr = array(
            "grant_type" => "authorization_code",
            "client_id" => $this->app_id,
            "redirect_uri" => urlencode($callBackUrl),
            "client_secret" => $this->app_key,
            "code" => $code
        );

        //------构造请求access_token的url
        $token_url = $this->urlUtils->combineURL(self::GET_ACCESS_TOKEN_URL, $keysArr);
        $response = $this->urlUtils->get_contents($token_url);

        if(strpos($response, "callback") !== false){

            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);

            if(isset($msg->error)){
                //$this->error->showError($msg->error, $msg->error_description);
                return false;
            }
        }

        $params = array();
        parse_str($response, $params);

        //$this->recorder->write("access_token", $params["access_token"]);
        return $params["access_token"];

    }

    //原 get_openid
    public function getOpenid($access_token){

        //-------请求参数列表
        $keysArr = array(
            "access_token" => $access_token
        );

        $graph_url = $this->urlUtils->combineURL(self::GET_OPENID_URL, $keysArr);
        $response = $this->urlUtils->get_contents($graph_url);

        //--------检测错误是否发生
        if(strpos($response, "callback") !== false){

            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos -1);
        }

        $user = json_decode($response);
        if(isset($user->error)){
            //$this->error->showError($user->error, $user->error_description);
            return false;
        }

        //------记录openid
        //$this->recorder->write("openid", $user->openid);
        return $user->openid;

    }

    public function getUserInfo($access_token,$open_id){
        //-------请求参数列表
        $keysArr = array(
            "access_token" => $access_token,
            "openid"=>$open_id,
            "oauth_consumer_key" => $this->app_id,
        );

        $graph_url = $this->urlUtils->combineURL(self::GET_USERINFO_URL, $keysArr);
        $response = $this->urlUtils->get_contents($graph_url);

        //--------检测错误是否发生
        if(strpos($response, "callback") !== false){

            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos -1);
        }

        $user = json_decode($response,true);
        if(isset($user['error'])){
            return false;
        }

        return $user;
    }
}
