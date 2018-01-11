<?php
class alipay2Controller extends payCommonController{
    
    /**
    *   支付宝支付
    */
    public function alipayapiAction(){
        $game_id = $_GET['gameid']+0;
        $uid = $_GET['uid']+0;
        $product_id = getSafeStr($_GET['product_id']); //商品item id
        //$txid = $_GET['txid'];  //游戏订单id，不要求传，游戏可将其存到扩展字段传过来
        $areaID = $_GET['areaID']+0;    //大区id
        $serverID = $_GET['serverID']+0;//服务器id
        $serverName = getSafeStr($_GET['serverName']); //服务器名
        $roleID = $_GET['roleID']+0; //角色id
        $roleName = getSafeStr($_GET['roleName']);//角色名
        $roleLevel = $_GET['roleLevel']+0;//角色等级
        $accountID = $_GET['accountID']+0; //游戏账号id
        $token = getSafeStr($_GET['token']);
        $extendbox = getSafeStr(@$_GET['extendbox']);
        $payext = getSafeStr(@$_GET['payext']);
        $backurl = getSafeStr(@$_GET['backurl']);
        $_SESSION['pay_back_url'] = $backurl;
        $com_args = $this->getCommonArgs($_GET);
        $payService = new PayService();
        $checkParamRes = $payService->checkPlaceOrderParam($game_id,$uid,$token,$product_id,$areaID,$serverID,$serverName,$roleID,$roleName,$accountID,$com_args);
        if($checkParamRes['state'] != 1){
            echo $checkParamRes['msg'];exit;
        }
        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($game_id);
        if(!$gameInfo){
            echo '游戏不存在';exit;
        }
        $itemService = new GameItemService();
        $itemInfo = $itemService->getItemInfoByItemId($product_id,$gameInfo['id']);
        if(!$itemInfo){
            echo '游戏商品不存在';exit;
        }
        $accessToken = (new AuthService())->getTokenInfo($token);
        //下订单
        //$ktuid,$token,$gateway,$productID,$productName,$amount,$areaID,$serverID,$serverName,$roleID,$roleName,$roleLevel,$accountID,$extendbox,$kt_payext,$com_args
        $res = $payService->createOrder($uid,$accessToken['user_token'],'alipay',$product_id,$itemInfo['reference_name'],$itemInfo['value'],$areaID,$serverID,$serverName,$roleID,$roleName,$roleLevel,$accountID,$extendbox,$payext,$com_args);
        if($res['state'] != 1){
            echo $res['msg'];exit;
        }
        //调支付宝
        $alipayService = new AlipayService();
        $redirect_url = $alipayService->getMobilePayRedirectUrl($res['data']['order_id'],$itemInfo['value'],$itemInfo['reference_name']);
        if(!$redirect_url){
            echo '下单错误';exit;
        }
        header('location:'.$redirect_url);
    }

    public function payReturnAction(){
        echo <<<EOT
            <script>
                window.location.href='{$_SESSION['pay_back_url']}';
            </script>
EOT;


    }

    public function payNotifyAction(){
        #$post = '{"gmt_create":"2017-11-16 17:45:54","charset":"utf-8","seller_email":"ydnohu4769@sandbox.com","subject":"60\u4e2a\u5143\u5b9d","sign":"qtWuQ1xooCr+2a+WNlHwnQ+Cs\/zwEkpUq1glYh\/eiIWr9cnph9\/AiRbqh\/NCwMrZXuBeKM0AFeLFGM9+fMPXn1ptcltys1\/dB11Wh+2jD8ebIIip4dSHUus6Y+YKUjVF9JQ4q83MGhyF37CHXUdKAmHZG8Ey7QBYgds4gSVYBUDJhc7faZdHs\/nT02S5GXGr+UF+srG2vLwZb9oZy2tHb1D9JL2RphWFILH72dkHe9lIUI6sR56Qk7Nq+Jt8J3FRduSGtCpG5TsObdMcjGshL0PpMfQYrYQP6fZ3OiS5AjSG\/XQ9lbBpF10SnYoW8FSC6ZxBYwLeuDH80ey2N+a82A==","body":"60\u4e2a\u5143\u5b9d","buyer_id":"2088102169745362","invoice_amount":"0.01","notify_id":"96301b55c81dc65cdbe8f704c4973f8is2","fund_bill_list":"[{\"amount\":\"0.01\",\"fundChannel\":\"ALIPAYACCOUNT\"}]","notify_type":"trade_status_sync","trade_status":"TRADE_SUCCESS","receipt_amount":"0.01","app_id":"2016073000121885","buyer_pay_amount":"0.01","sign_type":"RSA2","seller_id":"2088102169080823","gmt_payment":"2017-11-16 17:45:55","notify_time":"2017-11-16 17:45:56","version":"1.0","out_trade_no":"851316255430417622","total_amount":"0.01","trade_no":"2017111621001004360200195019","auth_app_id":"2016073000121885","buyer_logon_id":"eck***@sandbox.com","point_amount":"0.00"}';
        #$post = json_decode($post,true);
        #$_POST = $post;
        logPayNotifyArgs('alipay');
        $payService = new AlipayService();
        $res = $payService->checkNotify($_POST);
        if(!$res){
            echo 'error';exit;
        }
        $out_trade_no = $_POST['out_trade_no'];
        //支付宝交易号
        $trade_no = $_POST['trade_no'];
        //交易状态
        $trade_status = $_POST['trade_status'];
        if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
            $payService = new PayService();
            $orderInfo = $payService->getLocalOrderInfoByOrderId($out_trade_no);
            if(!$orderInfo){
                echo 'order not exist';exit;
            }
            if($orderInfo['payState'] == 3){
                echo 'success';exit;
            }
            if($orderInfo['realamount'] != $_POST['total_amount']){
                echo 'money error';exit;
            }
            $common_args = $this->getCommonArgs($_GET);
            $payService->setAppId($orderInfo['appid']);
            $res = $payService->orderNotify($orderInfo['ktuid'],$out_trade_no,$_POST['trade_no'],$_POST['notify_time'],$_POST['total_amount'],$common_args);
            if($res['state'] != 1){
                echo 'exchange game goods error';
            }else{
                echo 'success';
            }
        }
    }


}

