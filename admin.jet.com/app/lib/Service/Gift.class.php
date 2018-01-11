<?php 
/**
 * 
 *
 */ 
 
use Doris\DApp,
 Doris\DCache,
 Doris\DLog,
 Doris\DConfig;
 




class Service_Gift{
    const GIFT_CARD_USED_COUNT_REDIS_KEY = "giftcard_usedcount_"; //已领取礼包码数量的redis key 例如giftcard_usedcount_1 表示礼包ID为1的礼包码领取的数量
    const GIFT_CARD_LEFT_COUNT_REDIS_KEY = "giftcard_leftcount_"; //未领取礼包码数量的redis key 例如giftcard_leftcount_1 表示礼包ID为1的礼包码未领取的数量

    private static function getRedis(){
        return Doris\DApp::redis('game');

    }
	//刷新数据库中总礼包码数量     //刷新redis缓存
    public function refreshCardCount($gift_id){
        _load( "Admin_GiftModel");
        $count = Admin_GiftModel::getCardCountByGiftId($gift_id);
        /*
        $gift = Doris\DDB::db()->gift[$gift_id];
        $gift['total'] = $count;
        $gift->update();
        */
        $update['total'] = $count;
        Admin_GiftModel::updateGift($update,$gift_id);
        $this->clearRedisCountCache($gift_id);
    }
    //刷新redis缓存
    private function clearRedisCountCache($gift_id){
        $redis = self::getRedis();
        $redis_key = self::GIFT_CARD_LEFT_COUNT_REDIS_KEY.$gift_id;
        $redis->del($redis_key);
    }
}


