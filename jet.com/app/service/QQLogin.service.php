<?php
class QQLoginService{

    const APP_ID = 101432559;  //web app_key
    const APP_KEY = "d3ac3bbc472bad4c13f29c4f29ae59e4"; //web app secret

    private $callBackUrl = "";
    private $scop = "get_user_info";


    public function __construct(){
        require_once _THIRD_DIR_.'qq/qqConnectAPI.php';
        //http://login.vutimes.com/account/api.php?c=new_login&d=sinaAuth&pf=sina&gameid=123&code=5162752159cec6a74358e486b255af53
        $this->callBackUrl = getHttpType().$_SERVER['HTTP_HOST'].'/api/login/qqAuth';
    }

    /**
    *   获取跳转登陆url
    */
    public function getAuthUrl(){
        $qc = new QC(self::APP_ID,self::APP_KEY);
        $state = md5(uniqid(rand(), TRUE));
        $_SESSION['qq_third_state'] = $state;
        $_SESSION['qq_query_string'] = $_SERVER['QUERY_STRING'];
        $authUrl = $qc->getAuthorizeURL($this->callBackUrl,$this->scop,urlencode($state));
        return $authUrl;
    }

    //检查state参数的合法性
    public function checkState($state){
        if($_SESSION['qq_third_state'] != $state){
            return false;
        }else{
            return true;
        }
    }

    //获得access token
    public function getAccessToken($code){
        $qc = new QC(self::APP_ID,self::APP_KEY);
        $access_token = $qc->getAccessToken($code,$this->callBackUrl);
        //echo $qc->get_openid();


        return $access_token;
    }

    //获得open id
    public function getOpenId($access_token){
        $qc = new QC(self::APP_ID,self::APP_KEY);
        $open_id = $qc->getOpenid($access_token);
        return $open_id;
    }

    //获取用户信息
    public function getUserInfo($access_token,$openid){
        $qc = new QC(self::APP_ID,self::APP_KEY);
        $userInfo = $qc->getUserInfo($access_token,$openid);
        return $userInfo;
    }
    

 
}