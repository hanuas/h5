<?php
class weixinController extends payCommonController{
    
    /**
    *   微信扫码支付
    */
    public function native_call_qrcodeAction(){
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
        $res = $payService->createOrder($uid,$accessToken['user_token'],'wxpay',$product_id,$itemInfo['reference_name'],$itemInfo['value'],$areaID,$serverID,$serverName,$roleID,$roleName,$roleLevel,$accountID,$extendbox,$payext,$com_args);
        if($res['state'] != 1){
            #echo $res['msg'];exit;
            //错误
            echo json_encode(array('url'=>'','msg'=>$res['msg']));exit;
        }
        //调微信获取二维码
        $wxNativePayService = new WXNativepayService();
        $code_url = $wxNativePayService->unifiedOrder($res['data']['order_id'],$itemInfo['value'],$product_id,$itemInfo['reference_name']);
        if(!$code_url){
            //下单错误
            echo json_encode(array('url'=>'','trans_id'=>$res['data']['order_id']));exit;
        }
        //{"url":"weixin:\/\/wxpay\/bizpayurl?pr=w8hvsf8","trans_id":55952798}
        echo json_encode(array('url'=>$code_url,'trans_id'=>$res['data']['order_id']));
    }

    //充值回调通知
    public function payNotifyAction(){
        logPayNotifyArgs('wxpay');
        $service = new WXpayService();
        $args = $service->checkNotify();
        if(!$args){
            $this->exitNotify('FAIL','sign error');
        }
        //商户订单号
        $out_trade_no = $args['out_trade_no'];
        //微信交易号
        $trade_no = $args['transaction_id'];
        //交易状态
        $trade_status = $args['result_code'];
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

    public function h5Action(){
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
            echo $checkParamRes['msg'];exit;
        }
        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($game_id);
        if(!$gameInfo){
            echo json_encode(array('error'=>'1','msg'=>'游戏不存在'));exit;
        }
        $itemService = new GameItemService();
        $itemInfo = $itemService->getItemInfoByItemId($product_id,$gameInfo['id']);
        if(!$itemInfo){
            echo json_encode(array('error'=>'1','msg'=>'游戏商品不存在'));exit;
        }
        $accessToken = (new AuthService())->getTokenInfo($token);
        //下订单
        //$ktuid,$token,$gateway,$productID,$productName,$amount,$areaID,$serverID,$serverName,$roleID,$roleName,$roleLevel,$accountID,$extendbox,$kt_payext,$com_args
        $res = $payService->createOrder($uid,$accessToken['user_token'],'wxpay',$product_id,$itemInfo['reference_name'],$itemInfo['value'],$areaID,$serverID,$serverName,$roleID,$roleName,$roleLevel,$accountID,$extendbox,$payext,$com_args);
        if($res['state'] != 1){
            //错误
            echo json_encode(array('error'=>'1','msg'=>$res['msg']));exit;
        }
        //调微信获取二维码
        $WXH5payService = new WXH5payService();
        $mweb_url = $WXH5payService->unifiedOrder($res['data']['order_id'],$itemInfo['value'],$product_id,$itemInfo['reference_name']);
        if(!$mweb_url){
            //下单错误
            echo json_encode(array('error'=>'1','msg'=>'下单错误，请稍后再试'));exit;
        }
        echo json_encode(array('mweb_url'=>$mweb_url,'trans_id'=>$res['data']['order_id']));
    }


    //输出内容相应微信notify
    private function exitNotify($code,$msg){
        echo '<xml><return_code><![CDATA['.$code.']]></return_code><return_msg><![CDATA['.$msg.']]></return_msg></xml>';exit;
    }

}

