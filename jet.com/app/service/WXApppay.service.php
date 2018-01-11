<?php
class WXApppayService extends WXpayService{


    public function __construct(){
        parent::__construct();
        require_once _THIRD_DIR_.'pay/wxpay/WxPay.AppPay.php';
    }

    //下单，返回支付参数
    public function unifiedOrder($order_id,$amount,$productSubject){
        $input = new WxPayUnifiedOrder();
        $appPay = new AppPay();
        $input->SetBody($productSubject);
        $input->SetAttach($productSubject);
        $input->SetOut_trade_no($order_id);
        $input->SetTotal_fee($amount*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url($this->notify_url);
        $input->SetTrade_type("APP");
        $result = $appPay->GetPayArgs($input);
        if(@$result['return_code'] == 'SUCCESS') {
            return $result;
        }else{
            return false;

        }
    }


    public function appSign($prepay_id,$nonce_str,$timeStamp){
        $appPay = new AppPay();
        return $appPay->getAppSign($prepay_id,$nonce_str,$timeStamp);
    }

    

}