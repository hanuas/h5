<?php
class confController extends icommonController{
    
    /**
    *   @Desc getCpsConfig    
    */
    public function getCpsConfigAction($paras){
        if(!@$_GET['chid']){    //参数缺失
            json_exit(406,"参数缺失");
        }
        $cps_id = $_GET['chid']+0;
        $service = new CpsConfigService();
        $cps_info = $service->getCpsInfo($cps_id);
        if(!$cps_info){
            json_exit(406,"渠道信息查询失败");
        }        
        //最后一个为手机登录
        $loginHideOption = "{$cps_info['wxqr_login']},{$cps_info['qq_login']},{$cps_info['sina_login']},{$cps_info['jet_login']},{$cps_info['jet_login']}";
        
        $info = array();
        $info['loginHideOption'] = $loginHideOption;
        $info['wxh5pay'] = $cps_info['wxh5pay'];
        $info['wxPay'] = $cps_info['wxpay'];
        $info['alih5pay'] = $cps_info['alih5pay'];
        $info['hideWxPay'] = $cps_info['wxpay']?0:1;
        $info['replaceQrcodeUrl'] = $cps_info['replaceQrcodeUrl'];
        $info['hideStruct'] = $cps_info['hideStruct'];

        echo json_encode(array("config"=>$info,"error"=>0));exit;
    }

    /**
     * 获取商品信息
     */
    public function getProductInfoAction(){
        $pid = @$_GET['pid'];
        $game_id = @$_GET['game_id'];
        if(!$pid || !$game_id){
            json_exit(405,"参数错误");
        }
        $gameService = new GameService();
        $game_info = $gameService->getGameInfoByAppId($game_id);
        if(!$game_info){
            json_exit(405,"游戏不存在");
        }
        $service = new GameItemService();
        $itemInfo = $service->getItemInfoByItemId($pid,$game_info['id']);
        if(!$itemInfo){
            json_exit(405,"商品不存在");
        }
        if($itemInfo['value']>=1){
            $itemInfo['value'] = intval($itemInfo['value']);
        }
        $res = array(
            "productName"=>$itemInfo['display_name'],
            "des"=>$itemInfo['description'],
            "cost"=>$itemInfo['value'],
        );
        echo json_encode($res);


    }

   

}

