<?php
require_once "lib/WxPay.Api.php";

/**
 * 
 * APP支付实现类
 * @author widyhu
 *
 */
class AppPay
{
	
	/**
	 * 
	 */
	public function GetPayArgs($input)
	{
            $result = WxPayApi::unifiedOrder($input);
			return $result;
	}

    public function getAppSign($prepay_id,$nonce_str,$timeStamp){
        $args = array(
            'appid'=>WxAppPayConfig::APPID,
            'partnerid'=>WxAppPayConfig::MCHID,
            'prepayid'=>$prepay_id,
            'package'=>'Sign=WXPay',
            'noncestr'=>$nonce_str,
            'timestamp'=>$timeStamp
        );

        ksort($args);
        foreach($args as $k=>$v){
            if(!$v){unset($args[$k]);}
        }
        $meta = '';
        foreach($args as $k=>$v){
            $meta.=$k.'='.$v.'&';
        }
        $meta = rtrim($meta,'&');
        $meta .='&key='.WxAppPayConfig::KEY;
        $sign = strtoupper(md5($meta));
        return $sign;
    }
}