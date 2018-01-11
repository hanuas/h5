<?php
class WXqrcodeLoginService{

    const APP_ID = 'wxcb57d5826252ab04';  //APP_ID
    const APP_SECRET = "1b530ed377aeeff8310145f010b267a6"; //APP_SECRET

    private $callBackUrl = "";


    public function __construct(){
        require_once _THIRD_DIR_.'wxqrcode/wxQRCode.class.php';
        $this->callBackUrl = getHttpType().$_SERVER['HTTP_HOST'].'/api/login/wxqrcodeAuth';
    }

    /**
    *   获取跳转登陆url
    */
    public function getAuthUrl(){
        $wxqrcode = new wxQRCode(self::APP_ID,self::APP_SECRET);
        $state = md5(uniqid(rand(), TRUE));
        $_SESSION['wxqrcode_third_state'] = $state;
        $_SESSION['wxqrcode_query_string'] = $_SERVER['QUERY_STRING'];
        $authUrl = $wxqrcode->getAuthorizeURL($this->callBackUrl,urlencode($state));
        return $authUrl;
    }

    //检查state参数的合法性
    public function checkState($state){
        if($_SESSION['wxqrcode_third_state'] != $state){
            return false;
        }else{
            return true;
        }
    }

    //获得access token
    public function getAccessToken($code){
        $qc = new wxQRCode(self::APP_ID,self::APP_SECRET);
        $access_token = $qc->getAccessToken($code);

        return $access_token;
    }

    //获取unionid
    public function getUnionID($open_id,$access_token){
        $qc = new wxQRCode(self::APP_ID,self::APP_SECRET);
        $user_info = $qc->getUserInfo($open_id,$access_token);
        if(!$user_info || !@$user_info['unionid']){
            return false;
        }
        return $user_info['unionid'];
    }
    
    

 
}