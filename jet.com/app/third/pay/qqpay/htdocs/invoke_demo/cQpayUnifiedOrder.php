<?php
/**
 * cQpayOrderQuery.php
 * Created by by HelloWorld
 * vers: v1.0.0
 * User: Tencent.com
 */

#require_once ('../include/qpay_mch_sp/qpayMchApI.class.php');
require_once ("F:/www/qqpay/include/qpay_mch_sp/qpayMchApI.class.php");
//入参
$params = array();
$params["out_trade_no"] = "20160512161914_BBC";
$params["sub_mch_id"] = "1900005911";
$params["body"] = "body_test_中文";
$params["device_info"] = "WP00000001";
$params["fee_type"] = "CNY";
$params["notify_url"] = "https://10.222.146.71:80/success.xml";
$params["spbill_create_ip"] = "127.0.0.1";
$params["total_fee"] = "1";
$params["trade_type"] = "NATIVE";

//参数检测
//实际业务中请校验参数，本demo略
//

//api调用
$qpayApi = new QpayMchAPI('https://qpay.qq.com/cgi-bin/pay/qpay_unified_order.cgi', null, 10);
$ret = $qpayApi->reqQpay($params);

print_r(QpayMchUtil::xmlToArray($ret));