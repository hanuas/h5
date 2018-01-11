<?php
class MPWechatLogin{
    private $app_id;
    private $app_secret;

    private $authorizeURL = "https://open.weixin.qq.com/connect/oauth2/authorize";
    private $userAuthAccessTokenUrl = "https://api.weixin.qq.com/sns/oauth2/access_token";
    private $userInfoURL = "https://api.weixin.qq.com/sns/userinfo";


    public function __construct($app_id,$app_secret){
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
    }


    public function getAuthorizeURL($callBackUrl,$state,$scope = "snsapi_base"){
        $authorizeURL = $this->authorizeURL."?appid=".$this->app_id."&redirect_uri={$callBackUrl}&response_type=code&scope={$scope}&state={$state}#wechat_redirect";        
        return $authorizeURL;
    }


    //获取用户授权的access token
    public function getUserAuthAccessToken($code){
        $oauth2Url = $this->userAuthAccessTokenUrl."?appid=".$this->app_id."&secret=".$this->app_secret."&code=".$code."&grant_type=authorization_code";
        $accessToken = $this->send_get($oauth2Url);
        return json_decode($accessToken,true);
       # return $accessToken;
    }

    //获取用户信息
    public function getUserInfo($open_id,$access_token){
        $url = $this->userInfoURL.'?access_token='.$access_token.'&openid='.$open_id;
        $json = $this->send_get($url);
        if(!$json){return false;}
        $arr = json_decode($json,true);
        return $arr;
    }


    private function send_get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}