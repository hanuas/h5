<?php
class WXLoginService extends WXService{

    private $callBackUrl = "";


    public function __construct(){
        parent::__construct();
        require_once _THIRD_DIR_.'MPWechat/MPWechatLogin.class.php';
        $this->callBackUrl = getHttpType().$_SERVER['HTTP_HOST'].'/api/login/wxAuth';
    }

    /**
    *   获取跳转登陆url
    */
    public function getAuthUrl(){
        $wxlogin = new MPWechatLogin(self::APP_ID,self::APP_SECRET);
        $state = md5(uniqid(rand(), TRUE));
        $_SESSION['wx_third_state'] = $state;
        $_SESSION['wx_query_string'] = $_SERVER['QUERY_STRING'];
        $authUrl = $wxlogin->getAuthorizeURL(urlencode($this->callBackUrl),urlencode($state),"snsapi_userinfo");
        return $authUrl;
    }

    //检查state参数的合法性
    public function checkState($state){
        if($_SESSION['wx_third_state'] != $state){
            return false;
        }else{
            return true;
        }
    }

    //获得access token
    public function getUserAuthAccessToken($code){
        $wxlogin = new MPWechatLogin(self::APP_ID,self::APP_SECRET);
        $access_token = $wxlogin->getUserAuthAccessToken($code);
        return $access_token;
    }

    //获取unionid
    public function getUnionID($open_id,$access_token){
        $wxlogin = new MPWechatLogin(self::APP_ID,self::APP_SECRET);
        $user_info = $wxlogin->getUserInfo($open_id,$access_token);
        if(!$user_info || !@$user_info['unionid']){
            return false;
        }
        return $user_info['unionid'];
    }
 
}