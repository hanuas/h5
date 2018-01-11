<?php
class qpayController extends payCommonController{
    
    /**
    *   微信内h5支付
    */
    public function payRequestAction(){
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
        //$backurl = getSafeStr(@$_GET['backurl']);
        $com_args = $this->getCommonArgs($_GET);
        $payService = new PayService();
        $checkParamRes = $payService->checkPlaceOrderParam($game_id,$uid,$token,$product_id,$areaID,$serverID,$serverName,$roleID,$roleName,$accountID,$com_args);
        if($checkParamRes['state'] != 1){
            echo json_encode(array("error"=>$checkParamRes['msg']));exit;
        }
        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($game_id);
        if(!$gameInfo){
            echo json_encode(array("error"=>"游戏不存在"));exit;
        }
        $itemService = new GameItemService();
        $itemInfo = $itemService->getItemInfoByItemId($product_id,$gameInfo['id']);
        if(!$itemInfo){
            echo json_encode(array("error"=>"游戏商品不存在"));exit;
        }
        $accessToken = (new AuthService())->getTokenInfo($token);
        //下订单
        //$ktuid,$token,$gateway,$productID,$productName,$amount,$areaID,$serverID,$serverName,$roleID,$roleName,$roleLevel,$accountID,$extendbox,$kt_payext,$com_args
        $res = $payService->createOrder($uid,$accessToken['user_token'],'qqpay',$product_id,$itemInfo['reference_name'],$itemInfo['value'],$areaID,$serverID,$serverName,$roleID,$roleName,$roleLevel,$accountID,$extendbox,$payext,$com_args);

        if($res['state'] != 1){
            //错误
            echo json_encode(array("error"=>$res['msg']));exit;
        }
        $qqPayService = new QQpayService();
        $jsPayParams = $qqPayService->unifiedOrder($res['data']['order_id'],$itemInfo['value'],$itemInfo['reference_name']);
        if(!$jsPayParams){
            echo json_encode(array("error"=>"下单错误"));exit;
        }
        $payData = array(
            'token_id'=>$jsPayParams['prepay_id'],
        );
        echo json_encode($payData);
    }

    //充值回调通知
    public function payNotifyAction(){
        logPayNotifyArgs('qqpay');
        $qqPayService = new  QQpayService();
        $args = $qqPayService->checkNotify();
        if(!$args){
            $this->exitNotify('FAIL','sign error');
        }
        //商户订单号
        $out_trade_no = $args['out_trade_no'];
        //交易号
        $trade_no = $args['transaction_id'];
        //交易状态
        $trade_status = $args['trade_state'];
        //amonut
        $amonut = $args['total_fee']/100;
        if ($trade_status == 'SUCCESS') {
            $payService = new PayService();
            $orderInfo = $payService->getLocalOrderInfoByOrderId($out_trade_no);
            if(!$orderInfo){
                $this->exitNotify('FAIL','order not exist');
            }
            if($orderInfo['payState'] == 3){
                $this->exitNotify('SUCCESS','OK');
            }
            if($orderInfo['realamount'] != $amonut){
                $this->exitNotify('FAIL','money error');
            }
            $common_args = $this->getCommonArgs($_GET);
            $payService->setAppId($orderInfo['appid']);
            $res = $payService->orderNotify($orderInfo['ktuid'],$out_trade_no,$trade_no,$args['time_end'],$amonut,$common_args);
            if($res['state'] != 1){
                $this->exitNotify('FAIL','exchange game goods error');
            }else{
                $this->exitNotify('SUCCESS','OK');
            }
        }
    }

    //输出内容相应qq钱包notify
    private function exitNotify($code,$msg){
        echo '<xml><return_code><![CDATA['.$code.']]></return_code><return_msg><![CDATA['.$msg.']]></return_msg></xml>';exit;
    }

}

