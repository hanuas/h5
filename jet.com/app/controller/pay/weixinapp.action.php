<?php
class weixinappController extends payCommonController{
    
    /**
    *   微信内h5支付
    */
    public function js_api_callAction(){
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
        $res = $payService->createOrder($uid,$accessToken['user_token'],'wxpay',$product_id,$itemInfo['reference_name'],$itemInfo['value'],$areaID,$serverID,$serverName,$roleID,$roleName,$roleLevel,$accountID,$extendbox,$payext,$com_args);

        if($res['state'] != 1){
            #echo $res['msg'];exit;
            //错误
            echo json_encode(array("error"=>$res['msg']));exit;
        }
        $wxApppayService = new WXApppayService();
        $payParams = $wxApppayService->unifiedOrder($res['data']['order_id'],$itemInfo['value'],$itemInfo['reference_name']);
        if(!$payParams){
            echo json_encode(array("error"=>"微信下单错误"));exit;
        }
        $timeStamp = time();
        $sign = $wxApppayService->appSign($payParams['prepay_id'],$payParams['nonce_str'],$timeStamp);


        $payData = array(
            'prepayId'=>$payParams['prepay_id'],
            'nonceStr'=>$payParams['nonce_str'],
            'timestamp'=>$timeStamp,
            'appsign'=>$sign,
        );
        echo json_encode($payData);
    }

}

