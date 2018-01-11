<?php
class gameController extends icommonController{
    
    /**
    *
    */
    public function indexAction(){
        if(!in_array(@$_GET['cmd'],array("getRoleList","hasShop","getGameGoods","buyGoods"))){
            json_exit(4,"非法参数");
        }

        switch($_GET['cmd']){
            case "getRoleList":
                $this->getRoleList();  //获取角色列表
            break;
            case "hasShop":
                $this->hasShop();  //是否有积分礼包
            break;
            case "getGameGoods":
                $this->getGameGoods(); //获取积分礼包列表
            break;
            case "buyGoods":
                $this->buyGoods();  //积分礼包兑换
            break;

        }

    }

    //积分礼包兑换
    private function buyGoods(){
        $goodsId = @$_GET['goodsId']+0;
        $token = trim(@$_GET['token']);
        $game_id = @$_GET['gameid']+0;
        $serverid = @$_GET['serverid']+0;
        $roleid = @$_GET['roleid']+0;
        $rolename = @$_GET['rolename']?@$_GET['rolename']:'';
        $servername = @$_GET['servername']?@$_GET['servername']:'';
        if(!$token || !$goodsId){
            json_exit(2,"缺少必要参数");
        }
        $authService = new AuthService();
        $com_args = $this->getCommonArgs($_GET);
        $access_token_info = $authService->getTokenInfo($token);

        if(!$access_token_info){    //token校验失败
            json_exit(3,"本地登陆信息失效,请重新登录");
        }
        //检查用户token
        $userService = new UserService($game_id);
        if(!$userService->checkUserToken($access_token_info['user_id'],$access_token_info['user_token'],$com_args)){
            json_exit(3,"游戏内登陆信息失效,请重新登录");
        }

        $localUserService = new LocalUserService();
        $userInfo =$localUserService->getUserInfoByUserId($access_token_info['user_id']);
        if(!$userInfo){json_exit(3,"获取用户信息失败,请重新登录");}

        //获取礼包信息
        $giftService = new GiftService();
        $giftInfo = $giftService->getGiftInfoById($goodsId);
        if(!$giftInfo || $giftInfo['get_type'] != 'point'){json_exit(5,"礼包不存在");}
        if($giftInfo['point_gift_auto_send'] == 1 && (!$serverid || !$roleid)){  //自动兑换到游戏的礼包需要服务器id和角色id
            json_exit(2,"缺少必要参数");
        }
        $is_get = $giftService->getUserUsedCard($access_token_info['user_id'],$goodsId); //检查用户是否领取过
        if($is_get){json_exit(7,"已经领取过");}

        $leftCount = $giftService->getGiftCardLeftCount($goodsId,$giftInfo['point_gift_auto_send']);
        if(!$leftCount){json_exit(9,"礼包不足");}
        if($userInfo['lv'] < $giftInfo['point']){json_exit(8,"积分不足");}

        if($giftInfo['point_gift_auto_send'] != 1){
            $cardSn = $giftService->getPonitGiftCard($goodsId,$access_token_info['user_id']);
            if(!$cardSn){
                json_exit(1,"礼包领取失败");
            }
        }else{
            $res = $giftService->sendPonitGift($goodsId,$access_token_info['user_id'],$serverid,$roleid,$servername,$rolename);
            if(!$res){
                json_exit(1,"礼包领取失败");
            }
        }
        $userInfo = $localUserService->getUserInfoByUserId($access_token_info['user_id']);
        $leftScore = $userInfo['lv'];

        $data = array("leftScore"=>$leftScore,"error"=>0);
        if(@$cardSn){$data['cardSn'] = $cardSn;}
        echo json_encode($data);


    }

    //获取角色列表
    private function getRoleList(){
        $token = trim(@$_GET['token']);
        $goodsId = @$_GET['goodsId']+0;
        $game_id = @$_GET['gameid']+0;
        if(!$token || !$goodsId){
            json_exit(2,"缺少必要参数");
        }
        $authService = new AuthService();
        $com_args = $this->getCommonArgs($_GET);
        $access_token_info = $authService->getTokenInfo($token);

        if(!$access_token_info){    //token校验失败
            json_exit(3,"请重新登录");
        }
        //检查用户token
        $userService = new UserService($game_id);
        if(!$userService->checkUserToken($access_token_info['user_id'],$access_token_info['user_token'],$com_args)){
            json_exit(3,"请重新登录");
        }


        //TODO:获取角色列表 调游戏

        $role_list = array(
            array(
                "roleid"=>1,
                "rolename"=>"角色1",
                "serverid"=>1,
                "servername"=>"1区",
            ),
            array(
                "roleid"=>2,
                "rolename"=>"角色2",
                "serverid"=>2,
                "servername"=>"2区",
            ),
        );
        $result = array(
            "roleList"=>$role_list,
            "error"=>0
        );
        echo json_encode($result);


    }

    //是否有积分礼包
    public function hasShop(){
        $game_id = @$_GET['gameid']+0;
        $token = getSafeStr(@$_GET['token']);
        if(!$token || !$game_id){json_exit(10010,"参数缺失");}
        $authService = new AuthService();
        $tokenInfo = $authService->getTokenInfo($token);
        if(!$tokenInfo){
            json_exit(402,"token校验失败");
        }
        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($game_id);
        if(!$gameInfo){json_exit(60001,"游戏不存在");}


        $giftService = new GiftService();
        $count = $giftService->getPointGiftCount($gameInfo['id']); //获取积分礼包数量

        $new = false; //是否有新积分礼包
        if($count){
            $new = $giftService->getUnReadPointGiftCount($tokenInfo['user_id'],$gameInfo['id']);
        }
        $result = array(
            "count"=>$count,
            "new"=>$new?1:0,
            "error"=>0
        );
        echo json_encode($result);

    }

    //获取积分礼包列表
    private function getGameGoods(){
        $gameid = @$_GET['gameid']+0;
        if(!$gameid){json_exit(2,"缺少必要参数");}
        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($gameid);
        if(!$gameInfo){json_exit(60001,"游戏不存在");}
        $giftService = new GiftService();
        $giftList = $giftService->getPointGiftList($gameInfo['id']); //获取积分礼包数量
        $gift_list = array();
        foreach($giftList as $k=>$v){
            $goods_num = $giftService->getGiftCardLeftCount($v['gift_id'],$v['point_gift_auto_send']);
            $gift_list[$k] = array(
                'hide'=>0,
                'goods_name'=>$v['gift_title'],
                'goods_brief'=>$v['brief_intro'],
                'goods_number'=>$goods_num,
                'goods_id'=>$v['gift_id'],
                'point'=>$v['point'],
                'is_auto_send'=>$v['point_gift_auto_send'],
            );
        }
        echo json_encode(
            array(
                'goodsList'=>$gift_list,
                'error'=>0
            )
        );
    }


    


    


}

