<?php
class alipayController extends payCommonController{
    
    /**
    *   支付宝支付
    */
    public function indexAction(){
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

