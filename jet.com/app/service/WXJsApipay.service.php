<?php
class WXJsApipayService extends WXpayService{


    public function __construct(){
        parent::__construct();
        require_once _THIRD_DIR_.'pay/wxpay/WxPay.JsApiPay.php';
    }

    //下单，返回支付参数
    public function unifiedOrder($order_id,$amount,$item_id,$productSubject,$openId){

        $tools = new JsApiPay();
        $input = new WxPayUnifiedOrder();
        $input->SetBody($productSubject);
        $input->SetAttach($productSubject);
        $input->SetOut_trade_no($order_id);
        $input->SetTotal_fee($amount*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        #$input->SetGoods_tag("test");
        $input->SetNotify_url($this->notify_url);
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);
        $jsApiParameters = $tools->GetJsApiParameters($order);
        return json_decode($jsApiParameters,true);
    }

    

}