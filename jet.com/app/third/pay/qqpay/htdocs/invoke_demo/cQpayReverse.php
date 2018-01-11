<?php
/**
 * cCQpayCloseOrder.php
 * Created by by HelloWorld
 * vers: v1.0.0
 * User: Tencent.com
 */

require_once ('../include/qpay_mch_sp/qpayMchApI.class.php');

//入参
$params = array();
$params["out_trade_no"] = "20160512161914_BBC";
$params["sub_mch_id"] = "1900005911";

//参数检测
//实际业务中请校验参数，本demo略
//

//api调用
$qpayApi = new QpayMchAPI('https://qpay.qq.com/cgi-bin/pay/qpay_reverse.cgi', null, 10);
$ret = $qpayApi->reqQpay($params);

print_r(QpayMchUtil::xmlToArray($ret));