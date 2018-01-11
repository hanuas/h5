<?php
class WXService{
    //const APP_ID = 'wxfb7a886d5e56d43e';  //APP_ID
    //const APP_SECRET = "359676fbe0154eae31c2067b043c8e5e"; //APP_SECRET

    const APP_ID = 'wxfb7a886d5e56d43e';
    const APP_SECRET = '359676fbe0154eae31c2067b043c8e5e';


    const WX_ACCESSTOKEN_REDIS_KEY = 'wx_accesstoken';

    public function __construct(){
        require_once _THIRD_DIR_.'MPWechat/MPWechat.class.php';
    }

    //获取redis
    protected static function getRedis(){
        return Cache::redis("code");
    }

    //获取全局的access token
    protected function getAccessToken(){
        $redis_key = self::WX_ACCESSTOKEN_REDIS_KEY;
        $redis = self::getRedis();
        $access_token = $redis->get($redis_key);
        if($access_token){
           return $access_token;
        }
        $MPWechat = new MPWechat(self::APP_ID,self::APP_SECRET);
        $access_token = $MPWechat->getAccessToken();

        if(!$access_token){
            return false;
        }
        $redis->setex($redis_key,7000,$access_token);
    }


}