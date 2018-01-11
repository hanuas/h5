<?php
class WXH5payService extends WXpayService{


    public function __construct(){
        parent::__construct();
        require_once _THIRD_DIR_.'pay/wxpay/WxPay.H5Pay.php';
    }

    //下单，返回跳转地址
    public function unifiedOrder($order_id,$amount,$item_id,$productSubject){
        $input = new WxPayUnifiedOrder();
        $notify = new H5Pay();
        $input->SetBody($productSubject);
        $input->SetAttach($productSubject);
        $input->SetOut_trade_no($order_id);
        $input->SetTotal_fee($amount*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        #$input->SetGoods_tag("test");
        $input->SetNotify_url($this->notify_url);
        $input->SetTrade_type("MWEB");
        $input->SetProduct_id($item_id);
        $scene_info = array(
            'h5_info'=>array(
                'type'=>'Wap',
                'wap_url'=>getHttpType().$_SERVER['HTTP_HOST'],
                'wap_name'=>'h5',
            )
        );
        $scene_info = json_encode($scene_info);
        $input->setScene_info($scene_info);
        $result = $notify->GetPayUrl($input);
        if($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS' ) {
            return $result["mweb_url"];
        }else{
            return false;
        }
    }

    

}