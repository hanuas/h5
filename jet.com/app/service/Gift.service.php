<?php
//礼包service
class GiftService{
    const GIFT_CARD_USED_COUNT_REDIS_KEY = "giftcard_usedcount_"; //已领取礼包码数量的redis key 例如giftcard_usedcount_1 表示礼包ID为1的礼包码领取的数量
    const GIFT_CARD_LEFT_COUNT_REDIS_KEY = "giftcard_leftcount_"; //未领取礼包码数量的redis key 例如giftcard_leftcount_1 表示礼包ID为1的礼包码未领取的数量
    const GIFT_USER_USED_CARDNUM_REDIS_KEY = "usercardnum_";//用户领取过的礼包码redis key 格式:usercardnum_{gift_id}_{user_id}
    const GIFT_REDIS_EXPIRE_TIME = 86400; //redis key过期时间

    //获取redis
    protected static function getRedis(){
        return Cache::redis("game");
    }

    //获取非积分非会员礼包列表
    public function getNotPointVipGiftList($game_id){
        $giftModel = new GiftModel();
        $time = date("Y-m-d H:i:s");
        $where = "game_id = {$game_id} and get_type  in ('normal','qq_group_num','union_code') and start_time <'{$time}' and end_time > '{$time}' and gift_status = 1 order by start_time desc";
        $giftList = $giftModel->getGiftList($where);
        return $giftList;
    }

    //获取普通礼包数量
    public function getNotPointVipGiftCount($game_id){
        $giftModel = new GiftModel();
        $time = date("Y-m-d H:i:s");
        $where = "game_id = {$game_id} and get_type in ('normal','qq_group_num','union_code') and start_time <'{$time}' and end_time > '{$time}' and gift_status = 1";
        return $giftModel->getGiftCount($where);
    }

    //获取未读普通礼包的数量
    public function getUnReadNormalGiftCount($user_id,$game_id){
        $userReadMsgModel = new UserReadMsgModel();
        $where = "user_id = {$user_id} and game_id = {$game_id} and msg_type='gift'";
        $lastReadTimeInfo = $userReadMsgModel->readInfo('last_read_time',$where);
        if(!$lastReadTimeInfo){
            return $this->getNotPointVipGiftCount($game_id);
        }
        $lastReadTime = $lastReadTimeInfo['last_read_time'];

        $giftModel = new GiftModel();
        $time = date('H-m-d H:i:s');
        $where = "game_id = {$game_id} and get_type in ('normal','qq_group_num','union_code') and gift_status = 1 and start_time>'{$lastReadTime}' and start_time <'{$time}' and end_time > '{$time}'";
        return $giftModel->getGiftCount($where);
    }

    //获取未读积分礼包的数量
    public function getUnReadPointGiftCount($user_id,$game_id){
        $userReadMsgModel = new UserReadMsgModel();
        $where = "user_id = {$user_id} and game_id = {$game_id} and msg_type='gift'";
        $lastReadTimeInfo = $userReadMsgModel->readInfo('last_read_time',$where);
        if(!$lastReadTimeInfo){
            return $this->getPointGiftCount($game_id);
        }
        $lastReadTime = $lastReadTimeInfo['last_read_time'];

        $giftModel = new GiftModel();
        $where = "game_id = {$game_id} and get_type ='point' and gift_status = 1 and start_time>'{$lastReadTime}'";
        return $giftModel->getGiftCount($where);
    }

    //获取积分礼包列表
    public function getPointGiftList($game_id){
        $giftModel = new GiftModel();
        $time = date("Y-m-d H:i:s");
        $where = "game_id = {$game_id} and get_type = 'point' and start_time <'{$time}' and end_time > '{$time}' and gift_status = 1 order by gift_weight desc,start_time desc";
        $giftList = $giftModel->getGiftList($where);
        return $giftList;
    }

    //获取会员礼包列表
    public function getVipGiftList($game_id){
        $giftModel = new GiftModel();
        $time = date("Y-m-d H:i:s");
        $where = "game_id = {$game_id} and get_type = 'vip'  and start_time <'{$time}' and end_time > '{$time}' and gift_status = 1 order by gift_weight desc,start_time desc";
        #echo $where;;exit;
        $giftList = $giftModel->getGiftList($where);
        return $giftList;
    }
    //获取积分礼包数量
    public function getPointGiftCount($game_id){
        $giftModel = new GiftModel();
        $time = date("Y-m-d H:i:s");
        $where = "game_id = {$game_id} and get_type = 'point'  and start_time <'{$time}' and end_time > '{$time}' and gift_status = 1";
        $count = $giftModel->getGiftCount($where);
        return $count;
    }





    //获取已领取礼包码数量
    public function getGiftCardUsedCount($gift_id){
        $redis_key = self::GIFT_CARD_USED_COUNT_REDIS_KEY.$gift_id;
        $redis = self::getRedis();
        $usedCount = $redis->get($redis_key);
        if($usedCount === false){
            //从数据库取
            $giftCardModel = new GiftCardModel();
            $usedCount = $giftCardModel->getGiftCardUsedCount($gift_id);
            if($usedCount === false){
                return false;
            }
            $redis->setex($redis_key,self::GIFT_REDIS_EXPIRE_TIME,$usedCount);
        }
        return $usedCount;
    }

    //获取未领取礼包码数量(库存) $is_point_gift_auto_send 是否为积分礼包并且直接发送到游戏
    public function getGiftCardLeftCount($gift_id,$is_point_gift_auto_send){
        $redis_key = self::GIFT_CARD_LEFT_COUNT_REDIS_KEY.$gift_id;
        $redis = self::getRedis();
        $leftCount = $redis->get($redis_key);
        if($leftCount === false){
            $giftCardModel = new GiftCardModel();
            if($is_point_gift_auto_send){ //是积分礼包，并且直接发送到游戏 需要查已经发送的礼包数量，用总数量减已发送得到库存
                $gift_info = $this->getGiftInfoById($gift_id);
                if(!$gift_info){return false;}
                $usedCount = $this->getGiftCardUsedCount($gift_id);
                if($usedCount === false){return false;}
                $leftCount = $gift_info['total']-$usedCount;
            }else {
                $leftCount = $giftCardModel->getGiftCardLeftCount($gift_id);
            }
            if($leftCount === false){
                return false;
            }
            if($leftCount<0){$leftCount = 0;}

            $redis->setex($redis_key,self::GIFT_REDIS_EXPIRE_TIME,$leftCount);
        }
        return $leftCount;
    }

    public function getVipGift($user_id,$gift_id,$game_id,$role,$server,$phone){
        //添加领取记录
        $cardInfo = array();
        $cardInfo['user_id'] = $user_id;
        $cardInfo['role_name'] = $role;
        $cardInfo['server_name'] = $server;
        $cardInfo['gift_id'] = $gift_id;
        $cardInfo['card_no'] = uniqid();
        $cardInfo['card_status'] = 1;
        $cardInfo['create_time'] = time();
        $cardInfo['game_id'] = $game_id;
        $cardInfo['is_used'] = 1;
        $user_ip = ip2long(getClientIP());
        $cardInfo['user_ip'] = $user_ip;
        $cardInfo['used_time'] = time();
        $cardInfo['card_remark'] = 'vip礼包';
        $cardInfo['phone'] = $phone;

        $cardModel = new GiftCardModel();
        $res = $cardModel->add($cardInfo);
        if(!$res){
            return false;
        }
        return true;

    }



    //增加redis记录的 已经使用礼包码数量
    private function incrRedisGiftCardUsedCount($gift_id,$add_count){
        $redis_key = self::GIFT_CARD_USED_COUNT_REDIS_KEY.$gift_id;
        $usedCount = $this->getGiftCardUsedCount($gift_id);
        if($usedCount === false){return false;}
        $usedCount = $usedCount+$add_count;
        $redis = self::getRedis();
        return $redis->setex($redis_key,self::GIFT_REDIS_EXPIRE_TIME,$usedCount);
    }

    //减少redis 礼包码库存数量  //$is_point_gift_auto_send是否为积分礼包且自动兑换
    private function reduceRedistGiftCardLeftCount($gift_id,$reduce_count,$is_point_gift_auto_send){
        $redis_key = self::GIFT_CARD_LEFT_COUNT_REDIS_KEY.$gift_id;
        $leftCount = $this->getGiftCardLeftCount($gift_id,$is_point_gift_auto_send);
        if($leftCount === false){return false;}
        $leftCount = $leftCount-$reduce_count;
        $redis = self::getRedis();
        return $redis->setex($redis_key,self::GIFT_REDIS_EXPIRE_TIME,$leftCount);
    }

    //获取用户领取过的礼包码
    public function getUserUsedCard($user_id,$gift_id){
        $redis_key = self::GIFT_USER_USED_CARDNUM_REDIS_KEY.$gift_id.'_'.$user_id;
        $redis = self::getRedis();
        $card_num = $redis->get($redis_key);
        if($card_num === false){
            //从数据库取
            $giftCardModel = new GiftCardModel();
            $card_num = $giftCardModel->getUserUsedCard($user_id,$gift_id);
            if($card_num === false){
                return false;
            }
            $redis->setex($redis_key,self::GIFT_REDIS_EXPIRE_TIME,$card_num);
        }
        return $card_num;
    }

    //获取礼包信息
    public function getGiftInfoById($gift_id){
        $giftModel = new GiftModel();
        $giftInfo = $giftModel->getGiftInfoById($gift_id);
        return $giftInfo;
    }



    //领普通礼包
    public function getNormalCard($user_id,$gift_id){
        //获取剩余礼包码数量
        $leftNum = $this->getGiftCardLeftCount($gift_id,false);
        if(!$leftNum){
            return false;
        }
        //获取一条未使用的礼包码
        $cardInfo = $this->getUnUsedCard($gift_id,$leftNum);
        if(!$cardInfo){return false;}
        //删除未使用的礼包码
        $delRes = $this->delUnUsedCardById($cardInfo['id']);
        if(!$delRes){return false;}
        $this->reduceRedistGiftCardLeftCount($gift_id,1,false); //减少redis 礼包码库存数量

        //添加已使用的礼包码
        $cardInfo['is_used'] = 1;
        $user_ip = ip2long(getClientIP());
        $cardInfo['user_ip'] = $user_ip;
        $cardInfo['used_time'] = time();
        $cardInfo['user_id'] = $user_id;
        unset($cardInfo['id']);
        $cardModel = new GiftCardModel();
        $res = $cardModel->add($cardInfo);
        if(!$res){
            return false;
        }
        $this->incrRedisGiftCardUsedCount($gift_id,1); //增加redis 已经领取礼包数量的计数
        return $cardInfo['card_no'];

    }
    //获取未使用的礼包码
    private function getUnUsedCard($gift_id,$left_num){
        $cardModel = new GiftCardModel();
        $cardInfo = $cardModel->getUnUsedCard($gift_id,$left_num);
        if(!$cardInfo || !$cardInfo['card_no']){
            return false;
        }
        return $cardInfo;
    }

    //删除未使用的礼包码
    private function delUnUsedCardById($card_id){
        $cardModel = new GiftCardModel();
        $res = $cardModel->delUnUsedCardById($card_id);
        return $res;
    }

    //领统一码礼包
    public function getUnionCard($user_id,$gift_id){
        $gift_info = $this->getGiftInfoById($gift_id);
        if(!$gift_info['union_code']){
           return false;
        }
        //插入领取记录
        $cardInfo = array();
        $cardInfo['user_id'] = $user_id;
        $cardInfo['gift_id'] = $gift_id;
        $cardInfo['card_no'] = $gift_info['union_code'];
        $cardInfo['card_status'] = 1;
        $cardInfo['create_time'] = time();
        $cardInfo['game_id'] = $gift_info['game_id'];
        $cardInfo['is_used'] = 1;
        $user_ip = ip2long(getClientIP());
        $cardInfo['user_ip'] = $user_ip;
        $cardInfo['used_time'] = time();
        $cardInfo['card_remark'] = '统一码';
        $cardModel = new GiftCardModel();
        $res = $cardModel->add($cardInfo);

        return $gift_info['union_code'];

    }

    //领取积分礼包码
    public function getPonitGiftCard($gift_id,$user_id){
        //库存
        $leftCount = $this->getGiftCardLeftCount($gift_id,false);
        if(!$leftCount){return false;}
        $gift_info = $this->getGiftInfoById($gift_id);
        if(!$gift_info){return false;}
        $need_point = $gift_info['point'];
        $localUserService = new LocalUserService();
        //减用户积分
        $res = $localUserService->reduceUserPoint($user_id,$need_point);
        if(!$res){return false;}
        //获取一条未使用的礼包码
        $cardInfo = $this->getUnUsedCard($gift_id,$leftCount);
        if(!$cardInfo){
            $localUserService->incrUserPoint($user_id,$need_point);
            return false;
        }
        //删除未使用的礼包码
        $delRes = $this->delUnUsedCardById($cardInfo['id']);
        if(!$delRes){
            $localUserService->incrUserPoint($user_id,$need_point);
            return false;
        }
        $this->reduceRedistGiftCardLeftCount($gift_id,1,false); //减少redis 礼包码库存数量
        //添加已使用的礼包码
        $cardInfo['is_used'] = 1;
        $user_ip = ip2long(getClientIP());
        $cardInfo['user_ip'] = $user_ip;
        $cardInfo['used_time'] = time();
        $cardInfo['user_id'] = $user_id;
        unset($cardInfo['id']);
        $cardModel = new GiftCardModel();
        $res = $cardModel->add($cardInfo);
        if(!$res){
            $localUserService->incrUserPoint($user_id,$need_point);
            return false;
        }
        $this->incrRedisGiftCardUsedCount($gift_id,1); //增加redis 已经领取礼包数量的计数
        return $cardInfo['card_no'];

    }

    //领取积分礼包，直接发送到游戏
    public function sendPonitGift($gift_id,$user_id,$serverId,$roleId,$serverName="",$roleName=""){
        //库存
        $leftCount = $this->getGiftCardLeftCount($gift_id,true);
        if(!$leftCount){return false;}
        $gift_info = $this->getGiftInfoById($gift_id);
        if(!$gift_info){return false;}
        $need_point = $gift_info['point'];
        $localUserService = new LocalUserService();
        //减用户积分
        $res = $localUserService->reduceUserPoint($user_id,$need_point);
        if(!$res){return false;}

        //TODO::调用游戏接口发礼包
        $send_res = true;
        if(!$send_res){
            $localUserService->incrUserPoint($user_id,$need_point);
            return false;
        }
        $this->incrRedisGiftCardUsedCount($gift_id,1); //增加redis 已经领取礼包数量的计数
        $this->reduceRedistGiftCardLeftCount($gift_id,1,true); //减少redis 礼包码库存数量
        //插入领取记录
        $cardInfo = array();
        $cardInfo['user_id'] = $user_id;
        $cardInfo['gift_id'] = $gift_id;
        $cardInfo['card_no'] = uniqid();
        $cardInfo['card_status'] = 1;
        $cardInfo['create_time'] = time();
        $cardInfo['game_id'] = $gift_info['game_id'];
        $cardInfo['server_id'] = $serverId;
        $cardInfo['role_id'] = $roleId;
        $cardInfo['server_name'] = $serverName;
        $cardInfo['role_name'] = $roleName;
        $cardInfo['is_used'] = 1;
        $user_ip = ip2long(getClientIP());
        $cardInfo['user_ip'] = $user_ip;
        $cardInfo['used_time'] = time();
        $cardInfo['card_remark'] = '积分礼包发送到游戏';
        $cardModel = new GiftCardModel();
        $res = $cardModel->add($cardInfo);
        return true;
    }

    //获取用户领取vip礼包的记录
    public function getUserVipCards($user_id,$game_id,$vip_gift_ids = ""){
        if(!$vip_gift_ids) {
            //获取所有vip礼包列表
            $gift_list = $this->getVipGiftList($game_id);
            if (!$gift_list) {
                return array();
            }
            $vip_gift_ids = array();
            foreach ($gift_list as $v) {
                $vip_gift_ids[] = $v['gift_id'];
            }
            $vip_gift_ids = implode(',', $vip_gift_ids);
        }

        $cardModel = new GiftCardModel();
        $where = "user_id={$user_id}";
        if($game_id){$where.=" and game_id={$game_id}";}
        $where.=" and gift_id in ({$vip_gift_ids})";
        return $cardModel->getCardsList($where);
    }
}