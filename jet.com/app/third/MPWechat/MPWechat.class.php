<?php
class MPWechat{
    private $app_id;
    private $app_secret;
    private $accessTokenURL = "https://api.weixin.qq.com/cgi-bin/token";


    public function __construct($app_id,$app_secret){
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
    }

    //获取全局access token 
    public function getAccessToken(){
        $url = $this->accessTokenURL."?grant_type=client_credential&appid=".$this->app_id."&secret=".$this->app_secret;
        $res = $this->send_get($url);
        if(!$res){return false;}

        $token = json_decode($res,true);
        $access_token = @$token["access_token"];
        if(!$access_token){return false;}
        return $access_token;
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