<?php
class WXNativepayService extends WXpayService{


    public function __construct(){
        parent::__construct();
        require_once _THIRD_DIR_.'pay/wxpay/WxPay.NativePay.php';
    }

    //下单，返回二维码地址
    public function unifiedOrder($order_id,$amount,$item_id,$productSubject){
        $input = new WxPayUnifiedOrder();
        $notify = new NativePay();
        $input->SetBody($productSubject);
        $input->SetAttach($productSubject);
        $input->SetOut_trade_no($order_id);
        $input->SetTotal_fee($amount*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        #$input->SetGoods_tag("test");
        $input->SetNotify_url($this->notify_url);
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id($item_id);
        $result = $notify->GetPayUrl($input);
        if($result['return_code'] == 'SUCCESS') {
            return $result["code_url"];
        }else{
            return false;
        }
    }

    

}