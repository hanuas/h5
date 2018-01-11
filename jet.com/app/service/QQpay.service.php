<?php
class QQpayService{


    protected $notify_url = "";
    protected $return_url = "";

    public function __construct(){
        require_once _THIRD_DIR_.'pay/qqpay/include/qpay_mch_sp/qpayMchAPI.class.php';
        $this->notify_url = getHttpType().$_SERVER['HTTP_HOST'].'/pay/qpay/payNotify';
        //$this->notify_url = 'http://www.noaindustry.com/en/product/lala';
    }

    public function unifiedOrder($order_id,$amount,$productSubject,$trade_type = 'JSAPI'){
        $params = array();
        $params["out_trade_no"] = $order_id;
        $params["body"] = $productSubject;
        $params["device_info"] = '';
        $params["fee_type"] = "CNY";
        $params["notify_url"] = $this->notify_url;
        $params["spbill_create_ip"] = getClientIP();
        $params["total_fee"] = $amount*100;
        $params["trade_type"] = $trade_type;

        $qpayApi = new QpayMchAPI('https://qpay.qq.com/cgi-bin/pay/qpay_unified_order.cgi', null, 10);
        $ret = $qpayApi->reqQpay($params);
        if(!$ret){
            return false;
        }
        $res = QpayMchUtil::xmlToArray($ret);
        if(@$res['result_code'] != 'SUCCESS'){
            return false;
        }
        return $res;
    }

    //检查notify参数
    public function checkNotify(){
        $qpayApi = new QpayMchAPI('', null, 10);
        $args = $qpayApi->checkNotify();
        return $args;
    }

    
    

}