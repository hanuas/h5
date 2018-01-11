<?php
class activityController extends icommonController{
    public function indexAction(){
        if(!in_array(@$_GET['cmd'],array("info","prize","log"))){
            json_exit(4,"非法参数");
        }

        switch($_GET['cmd']){
            case 'info':
                $this->info();
            break;
            case 'prize':
                $this->prize(); //领取vip礼包
            break;
            case 'log':
                $this->log();   //获取vip礼包领取记录
        }

    }   
    

    private function info(){
        $game_id = @$_GET['gameid']+0;
        $token = trim(@$_GET['token']);
        if(!$game_id || !$token){json_exit(2,"缺少必要参数");}
        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($game_id);
        if(!$gameInfo){json_exit(60001,"游戏不存在");}

        $authService = new AuthService();
        $access_token_info = $authService->getTokenInfo($token);
        if(!$access_token_info){    //token校验失败
            json_exit(3,"token失效");
        }
        $user_id = $access_token_info['user_id'];
        //获取vip礼包
        $giftService = new GiftService();
        $giftList = $giftService->getVipGiftList($gameInfo['id']); //获取非积分礼包列表
        if(!$giftList){
            echo json_encode(array("groupList"=>array(),"prizeList"=>array(),"rmb"=>0));exit;
        }
        $gift_list = array();
        foreach($giftList as $k=>$v){
            $gift_list[$v['vip_get_condition']][$v['vip_get_condition_val']][] = $v;
        }
        //重新排序,为的是和/ic/api/getGameVipGift 接口的礼包列表接口顺序相同。
        $gifts = array();
        foreach($gift_list as $v){
            foreach($v as $val){
                foreach($val as $value){
                    $gifts[] = $value;
                }
            }
        }
        //获取今日用户充值
        $payService = new PayService();
        $start_time = date("Y-m-d 00:00:00");
        $end_time = date("Y-m-d 23:59:59");
        $payAmount = $payService->getPayAmount($user_id,$game_id,$start_time,$end_time);
        $rmb = $payAmount;
        //获取vip等级
        $userService = new LocalUserService();
        $user_info = $userService->getUserInfoByUserId($user_id);
        if(!$user_info){
            $vip_level = 0;
        }else{
            $vip_level = $user_info['vip'];
        }
        $groupList = array();
        $prizeList = array();
        foreach($gifts as $value){

            $group = array(
                'gameid'=>$game_id,
                'requirement'=>$value['vip_get_condition_val'],
                'leftChance'=>$this->giftHasGetChance($payAmount,$vip_level,$value['gift_id'],$value['vip_get_condition'],$value['vip_get_condition_val'],$user_id)?1:0,
            );
            $items = json_decode($value['vip_gift_content'],true);
            $desc = '';
            foreach($items as $itemVal){
                $desc.=$itemVal['title'].'*'.$itemVal['num'].',';
            }
            $desc = trim($desc,',');
            $prize = array(
                'id'=>$value['gift_id'],
                'des'=>$desc
            );
            $groupList[$value['gift_id']] = $group;
            $prizeList[$value['gift_id']] = $prize;
        }
        $result = array(
            'groupList'=>$groupList,
            'prizeList'=>$prizeList,
            'rmb'=>$rmb
        );

        echo json_encode($result);
    }

    //获取礼包是否有领取机会
    private function giftHasGetChance($payAmount,$vip_level,$gift_id,$condition,$condition_val,$user_id){
        if($condition == 'other'){
            return false;
        }
        if( ($condition == "today_recharge" && $payAmount>=$condition_val) || ($condition == "vip_level" && $vip_level>=$condition_val) ){
            //检查用户是否已经领取
            $giftService = new GiftService();
            if($giftService->getUserUsedCard($user_id,$gift_id)){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }

    }

    //领取会员礼包
    private function prize(){
        $gift_id = $_GET['id']+0;
        $role = getSafeStr($_GET['role']);
        $server = getSafeStr($_GET['zone']);
        $phone = $_GET['phone']+0;
        $token = getSafeStr($_GET['token']);
        $game_id = $_GET['gameid']+0;
        if(!$gift_id || !$role || !$server || !$phone || !$token) {
            json_exit(2,"缺少必要参数");
        }

        //id=63&role=角色昵称&zone=服务器1&phone=18911555199&token=819f8926bcfa891774de6c377944c047&gameid=123&v=1509959566942
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
        //获取礼包信息
        $giftInfo = $giftService->getGiftInfoById($gift_id);

        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($game_id);

        if(!$giftInfo || !$gameInfo){
            json_exit(5,"礼包不存在");
        }
        $payAmount = 0;$vip_level = 0;
        if($giftInfo['vip_get_condition'] == 'today_recharge'){
            //获取今日用户充值
            $payService = new PayService();
            $start_time = date("Y-m-d 00:00:00");
            $end_time = date("Y-m-d 23:59:59");
            $payAmount = $payService->getPayAmount($access_token_info['user_id'],$gameInfo['appid'],$start_time,$end_time);
        }elseif($giftInfo['vip_get_condition'] == 'vip_level'){
            //获取vip等级
            $userService = new LocalUserService();
            $user_info = $userService->getUserInfoByUserId($access_token_info['user_id']);
            if(!$user_info){
                json_exit(3,'请重新登录');
            }
            $vip_level = $user_info['vip'];
        }else{
            json_exit(5,"礼包不存在");
        }
        $left_chance = $this->giftHasGetChance($payAmount,$vip_level,$gift_id,$giftInfo['vip_get_condition'],$giftInfo['vip_get_condition_val'],$access_token_info['user_id']);
        if(!$left_chance){
            json_exit(6,"没有领取次数");
        }


        $res = $giftService->getVipGift($access_token_info['user_id'],$gift_id,$giftInfo['game_id'],$role,$server,$phone);
        if(!$res){
            json_exit(1,"插入数据库领取记录失败");
        }else{
            json_exit(0,"success");
        }



    }

    //获取领取会员礼包记录
    private function log(){
        //token=819f8926bcfa891774de6c377944c0471&gameid=123&v=1510018690221
        $token = getSafeStr($_GET['token']);
        $game_id = $_GET['gameid']+0;
        if(!$token || !$game_id){
            json_exit(2,"缺少必要参数");
        }
        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($game_id);
        if(!$gameInfo){
            echo json_encode(array('logList'=>array(),'error'=>0));exit;
        }
        //检查用户token
        $authService = new AuthService();
        $com_args = $this->getCommonArgs($_GET);
        $access_token_info = $authService->getTokenInfo($token);

        if(!$access_token_info){    //token校验失败
            json_exit(3,"请重新登录");
        }
        //检查用户游戏token
        $userService = new UserService($game_id);
        if(!$userService->checkUserToken($access_token_info['user_id'],$access_token_info['user_token'],$com_args)){
            json_exit(3,"请重新登录");
        }

        $giftService = new GiftService();
        //获取vip礼包列表
        $vipGiftList = $giftService->getVipGiftList($gameInfo['id']);
        if(!$vipGiftList){
            echo json_encode(array('logList'=>array(),'error'=>0));exit;
        }
        $vipGifts = array();
        $vipGiftIds = array();
        foreach($vipGiftList as $v){
            $vipGifts[$v['gift_id']] = $v;
            $vipGiftIds[] = $v['gift_id'];
        }

        $getList = $giftService->getUserVipCards($access_token_info['user_id'],$gameInfo['id'],implode(',',$vipGiftIds));
        if(!$getList){
            echo json_encode(array('logList'=>array(),'error'=>0));exit;
        }
        $list = array();
        foreach($getList as $k=>$v){
            $list[] = array(
                "fetchTime"=>$v['create_time'],
                "status"=>$v['vip_gift_send'],
                "desc"=>$vipGifts[$v['gift_id']]['brief_intro'],
            );
        }
        echo json_encode(array('logList'=>$list,'error'=>0));exit;
    }
}