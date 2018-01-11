<?php
class WXShareService extends WXService{

    const WX_JSAPITICKET_REDIS_KEY = "wx_jsapiticket"; //微信jsapiticket rediskey

    public function __construct(){
        parent::__construct();
        require_once _THIRD_DIR_.'MPWechat/JSSDK.class.php';
    }

    public function getSignPackage($url){
        $access_token = $this->getAccessToken();
        if(!$access_token){return false;}

        $jsApiTicket = $this->getJsApiTicket($access_token);
        if(!$jsApiTicket){return false;}
        $jssdk = new JSSDK(self::APP_ID,self::APP_SECRET);
        return $jssdk->getSignPackage($jsApiTicket,$url);
    }

    //获取jsapiticket
    private function getJsApiTicket($access_token){
        $redis_key = self::WX_JSAPITICKET_REDIS_KEY;
        $redis = self::getRedis();
        $jsApiTicket = $redis->get($redis_key);
        if($jsApiTicket){
            return $jsApiTicket;
        }
        $jssdk = new JSSDK(self::APP_ID,self::APP_SECRET);
        $jsApiTicket = $jssdk->getJsApiTicket($access_token);
        if(!$jsApiTicket){
            return false;
        }
        $redis->setex($redis_key,7000,$jsApiTicket);
        return $jsApiTicket;
    }


}