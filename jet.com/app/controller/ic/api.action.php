<?php
class apiController extends icommonController{
    
    /**
    *
    */
    public function indexAction(){
        if(!in_array(@$_GET['cmd'],array("getUserInfo","getGameInfo","getGameVipGift","drawJh","getSomRecoAndNewGames","getRemindDot","delRemindDot"))){
            json_exit(4,"非法参数");
        }

        switch($_GET['cmd']){
            case "getUserInfo":
                $this->getUserInfo();
            break;

            case "getGameInfo":
                $this->getGameInfo();
            break;

            case "getGameVipGift":
                $this->getGameVipGift();
            break;

            case "drawJh":
                $this->drawJh(); //领礼包
            break;

            case "getSomRecoAndNewGames":
                $this->getSomRecoAndNewGames(); //获取游戏列表
            break;

            case "getRemindDot":
                $this->getRemindDot(); //是否有礼包
            break;

            case "delRemindDot":
                $this->delRemindDot(); //清除未读礼包消息

        }

    }

    //清除未读礼包消息
    private function delRemindDot(){
        $token = getSafeStr($_GET['token']);
        $game_id = $_GET['gameid']+0;
        if(!$token || !$game_id){json_exit(10010,"参数缺失");}
        $authService = new AuthService();
        $tokenInfo = $authService->getTokenInfo($token);
        if(!$tokenInfo){
            json_exit(402,"token校验失败");
        }
        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($game_id);
        if(!$gameInfo){json_exit(10010,"游戏不存在");}

        $userReadMsgService = new UserReadMsgService();
        $userReadMsgService->logReadMsgLastTime($tokenInfo['user_id'],$gameInfo['id'],'gift');

        json_exit(0,'success');
    }

    //是否有礼包
    private function getRemindDot(){
        $token = getSafeStr($_GET['token']);
        $game_id = $_GET['gameid']+0;
        if(!$token || !$game_id){json_exit(10010,"参数缺失");}
        $authService = new AuthService();
        $tokenInfo = $authService->getTokenInfo($token);
        if(!$tokenInfo){
            json_exit(402,"token校验失败");
        }
        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($game_id);
        if(!$gameInfo){echo json_encode(array('remind'=>array(),'hasJh'=>0));exit;}
        //检查是否有礼包
        $giftService = new GiftService();
        $count = $giftService->getNotPointVipGiftCount($gameInfo['id']);
        $hasJh = $count>0?1:0;
        //是否有未读的新礼包
        $unReadCount = $giftService->getUnReadNormalGiftCount($tokenInfo['user_id'],$gameInfo['id']);
        $remind = $unReadCount>0?array(1=>1):array();
        echo json_encode(array('hasJh'=>$hasJh,'remind'=>$remind));
    }

    /**
     * 获取游戏信息
     * 实际主要获取的是礼包信息
     * 参考：https://web.11h5.com/api?cmd=getGameInfo&id=123&token=828264fb9f7275f021bda328e38b92bc&gameid=123&v=1509069080622
     *
     */
    private function getGameInfo(){
        $id = @$_GET['id']+0;
        $gameid = @$_GET['gameid']+0;
        if(!$id && !$gameid){json_exit(2,"缺少必要参数");}
        $game_id = $id?$id:$gameid;
        $token = trim(@$_GET['token']);
        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($game_id);
        if(!$gameInfo){json_exit(60001,"游戏不存在");}

        $authService = new AuthService();
        $access_token_info = $authService->getTokenInfo($token);
        if($access_token_info){    //token校验失败
            $user_id = $access_token_info['user_id'];
        }else{
            $user_id = null;
        }
        $giftService = new GiftService();
        $giftList = $giftService->getNotPointVipGiftList($gameInfo['id']); //获取非积分礼包列表
        foreach($giftList as $k=>$v){
            if($v['get_type'] == 'normal'){ //需要获取礼包码已领取的数量和总量
                $usedCount = $giftService->getGiftCardUsedCount($v['gift_id']); //获取已领取礼包码数量
                $giftList[$k]['getcount'] = $usedCount+0;
                $giftList[$k]['sum'] = $v['total'];
                //获取当前用户领取的礼包码
                $giftList[$k]['getCode'] = $user_id?$giftService->getUserUsedCard($user_id,$v['gift_id']):'';
                $giftList[$k]['getCode'] = $giftList[$k]['getCode']?$giftList[$k]['getCode']:'';
            }else{
                $giftList[$k]['getcount'] = 0;
                $giftList[$k]['sum'] = 0;
                $giftList[$k]['getCode'] = '';
            }
            $giftList[$k]['type'] = $v['gift_id'];
            $giftList[$k]['title'] = $v['gift_title'];
        }


        $result = array(
            "gift"=>$giftList,
            "data"=>array(
                "title"=>$gameInfo['game_name']
                //...
            ),
            "error"=>0
        );
        echo json_encode($result);


    }

    //加载个人信息
    private function getUserInfo(){
        $token = trim(@$_GET['token']);
        $game_id = @$_GET['gameid']+0;
        if(!$token){
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

        $localUserService = new LocalUserService();
        $userInfo = $userInfo = $localUserService->getUserInfoByUserId($access_token_info['user_id']);
        if(!$userInfo){
            json_exit(3,"请重新登录");
        }

        $result = array(
            'uid'=>$userInfo['user_id'],
            'headimgurl'=>$userInfo['headimgurl'],
            'lv'=>$userInfo['lv'],
            'vip'=>$userInfo['vip'],
            'error'=>0
        );
        echo json_encode($result);

    }


    //获取vip礼包信息 https://web.11h5.com/api?cmd=getGameVipGift&token=e75626482980c8a1b46bde0133af08ab&gameid=170&v=1509328687422
    private function getGameVipGift(){
        $game_id = @$_GET['gameid']+0;
        if(!$game_id){json_exit(2,"缺少必要参数");}
        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($game_id);
        if(!$gameInfo){json_exit(60001,"游戏不存在");}

        #echo '<meta charset="utf-8">';
        //获取vip礼包
        $giftService = new GiftService();
        $giftList = $giftService->getVipGiftList($gameInfo['id']); //获取非积分礼包列表

        $gift_list = array();
        if(!$giftList){
            echo json_encode(array());exit;
        }
        foreach($giftList as $k=>$v){
            $gift_list[$v['vip_get_condition']][$v['vip_get_condition_val']][] = $v;
        }

       # print_r($gift_list);
        //每日充值满500元|每日充值满1000元|每日充值满2000元|每日充值满3000元|每日充值满5000元|每日充值满8000元|每日充值满10000元
        $level = "";
        //90,礼包一,羽毛骑术礼盒*5*1,培养石*100*2,超·进化石*50*3,守护兽升级石*150*4,守护兽进阶石*50*5
        $gift_content = "";
        $vip_is_get_code = 0;
        foreach($gift_list as $v){
            foreach($v as $val){
                $level = $level.$val[0]['vip_get_condition_desc'].'|';
                $vip_is_get_code = $val[0]['vip_is_get_code']; //是否领礼包码
                foreach($val as $value){
                    $gift_content.=$value['gift_id'].','.$value['gift_title'].',';
                    $gifts = json_decode($value['vip_gift_content'],true);
                    foreach($gifts as $giftVal){
                        $imgName = substr($giftVal['icon'],strripos($giftVal['icon'],'_')+1);
                        $suffix = strstr($imgName,'.');
                        $icon_key = trim($imgName,$suffix);
                        $gift_content.=$giftVal['title'].'*'.$giftVal['num'].'*'.$icon_key.',';
                    }
                    $gift_content = trim($gift_content,',');
                    $gift_content.='&';
                }
                $gift_content = trim($gift_content,'&');
                $gift_content.="\r\n";
            }
        }
        $gift_content = trim($gift_content,"\r\n");
        $level = trim($level,'|');
        $title = $gameInfo['appid'].$gameInfo['game_name'].'|'.$gameInfo['appid'].'|'.trim($suffix,'.').'|'.$vip_is_get_code;
        $gift_content = $level."\r\n".$gift_content;
        $result = array(
            'gameid'=>$game_id,
            'gift_content'=>$gift_content,
            'title'=>$title,
        );
        echo json_encode($result);


    }


    //领礼包  领普通礼包和统一码礼包
    private function drawJh(){
        $token = trim(@$_GET['token']);
        $game_id = @$_GET['gameid']+0;
        $gift_id = @$_GET['type']+0;
        if(!$token || !$gift_id){
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

        $giftService = new GiftService();
        $giftInfo = $giftService->getGiftInfoById($gift_id);
        #var_dump($giftInfo);exit;
        if(!$giftInfo || ($giftInfo['get_type'] != 'normal' && $giftInfo['get_type'] != 'union_code' )){
            json_exit(60001,"礼包不存在");
        }
        //获取用户已经领取的礼包码
        $activation_code = $giftService->getUserUsedCard($access_token_info['user_id'],$gift_id);
        if($activation_code){
            echo json_encode(array("activation_code"=>$activation_code,"error"=>0));exit;
        }

        //领礼包
        if($giftInfo['get_type'] == 'normal'){
            $leftNum = $giftService->getGiftCardLeftCount($gift_id,false);
            if(!$leftNum){json_exit(9,'礼包不足');}
            $activation_code = $giftService->getNormalCard($access_token_info['user_id'],$gift_id);
        }else{
            $activation_code = $giftService->getUnionCard($access_token_info['user_id'],$gift_id);
        }

        if(!$activation_code){
            json_exit(1,'领取礼包失败');
        }
        //{"activation_code":"979e4adaaadfa9gf","get_time":1509417645}

        echo json_encode(
            array("activation_code"=>$activation_code,"error"=>0)
        );

    }

    //获取游戏列表
    private function getSomRecoAndNewGames(){
        $gameService = new GameService();

        $newGameList = $gameService->getNewGameList();
        $hotGameList = $gameService->getHotGameList();

        $new = array();
        foreach($newGameList as $v){
            $labels = [];
            if($v['is_exclusive']){$labels[] = 5;}
            if($v['is_gift']){$labels[] = 4;}
            $new[] = array(
                'title'=>$v['game_name'],
                'brief_intro'=>$v['brief_intro'],
                'icon'=>'/static/image/'.$v['icon'],
                'id'=>$v['appid'],
                'labels'=>$labels,
                ///'game_url'=>$v['game_url']
                'game_url'=>'/game/?gameid='.$v['appid']
            );
        }
        $recos = array();
        foreach($hotGameList as $v){
            $labels = [];
            if($v['is_exclusive']){$labels[] = 5;}
            if($v['is_gift']){$labels[] = 4;}
            $recos[] = array(
                'title'=>$v['game_name'],
                'brief_intro'=>$v['brief_intro'],
                'icon'=>'/static/image/'.$v['icon'],
                'id'=>$v['appid'],
                'labels'=>$labels,
                'game_url'=>'/game/?gameid='.$v['appid']
            );
        }

        $result = array(
            'new'=>$new,
            'recos'=>$recos,
            'error'=>0
        );

        echo json_encode($result);

    }

    


    


}

