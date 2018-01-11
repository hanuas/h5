<?php
/**
 * cQpayRefund.php
 * Created by by HelloWorld
 * vers: v1.0.0
 * User: Tencent.com
 */

require_once ('../include/qpay_mch_sp/qpayMchApI.class.php');

//入参
$params = array();
$params["out_trade_no"] = "20160512161914_BBC";
$params["sub_mch_id"] = "1900005911";
$params["out_refund_no"] = "20160512161914_BBC_out_refund_1";
$params["refund_fee"] = "99999";
$params["op_user_id"] = "1900005911";
$params["op_user_passwd"] = "";

//参数检测
//实际业务中请校验参数，本demo略
//

//api调用
$qpayApi = new QpayMchAPI('https://qpay.qq.com/cgi-bin/pay/qpay_refund.cgi', true, 10);
$ret = $qpayApi->reqQpay($params);

print_r(QpayMchUtil::xmlToArray($ret));