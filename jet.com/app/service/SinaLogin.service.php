<?php
class SinaLoginService{

    #const WEB_APP_KEY = "2215467368";  //web app_key
    #const WEB_APP_SECRET = "b7b48ccb80d510b02bacdc37dd6fc520"; //web app secret

    const WEB_APP_KEY = "1797120790";  //web app_key
    const WEB_APP_SECRET = "b6e056f9ea3284452c547867f3a47122"; //web app secret


    private $callBackUrl = "";


    public function __construct(){
        require_once _THIRD_DIR_.'weibo/saetv2.ex.class.php';
        //http://login.vutimes.com/account/api.php?c=new_login&d=sinaAuth&pf=sina&gameid=123&code=5162752159cec6a74358e486b255af53
        $this->callBackUrl = getHttpType().$_SERVER['HTTP_HOST'].'/api/login/sinaAuth';
    }

    /**
    *   获取跳转登陆url
    */
    public function getAuthUrl(){
        $saeTOAuthV2 = new SaeTOAuthV2(self::WEB_APP_KEY,self::WEB_APP_SECRET);
        $state = md5(uniqid(rand(), TRUE));
        $_SESSION['sina_third_state'] = $state;
        $_SESSION['sina_query_string'] = $_SERVER['QUERY_STRING'];
        $authUrl = $saeTOAuthV2->getAuthorizeURL($this->callBackUrl,'code',$state);
        return $authUrl;
    }

    //检查state参数的合法性
    public function checkState($state){
        if($_SESSION['sina_third_state'] != $state){
            return false;
        }else{
            return true;
        }
    }


    public function getAccessToken($code){
        $saeTOAuthV2 = new SaeTOAuthV2(self::WEB_APP_KEY,self::WEB_APP_SECRET);
        $keys = array();
        $keys['code'] = trim($code);
        $keys['redirect_uri'] = $this->callBackUrl;
        try{
            $access_token = $saeTOAuthV2->getAccessToken('code',$keys);
        }catch(OAuthException $e){
            return false;
        }

        return $access_token;
    }

    public function getUserInfo($access_token,$uid){
        $url = "https://api.weibo.com/2/users/show.json?access_token={$access_token}&uid={$uid}";
        $res = @file_get_contents($url);
        if(!$res){
            return false;
        }
        return json_decode($res,true);

    }
    

 
}