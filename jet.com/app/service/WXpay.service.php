<?php
class WXpayService{


    protected $notify_url = "";
    protected $return_url = "";

    public function __construct(){
        #require_once _THIRD_DIR_.'pay/alipay/AopSdk.php';
        $http_type = getHttpType();
        $this->notify_url = $http_type.$_SERVER['HTTP_HOST'].'/pay/weixin/payNotify';
        $this->return_url = $http_type.$_SERVER['HTTP_HOST'].'/pay/weixin/payReturn';
        #$this->notify_url = 'http://www.noaindustry.com/en/product/lala';
    }

    //检查notify参数
    public function checkNotify(){
        require_once _THIRD_DIR_.'pay/wxpay/lib/WxPay.Api.php';
        $args = WxPayApi::checkNotifyArgs();
        return $args;
    }

    
    

}