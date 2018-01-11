<?php
class ShareService {
    const SHARE_LOG_NUM_REDIS_KEY = "sharelog_gid_ip_num:"; //分享 redis 计数 key值 例:"sharelog_gid_ip_num:123_192.168.0.1"
    const SHARE_LOG_REDIS_EXPIRE_TIME = 3600; // redis key 过期时间

    public static function getRedis(){
        return Cache::redis("game");
    }
  


    /**
    *   记录分享log
    */
    public function addLog($game_id,$ip){
        $model = new ShareLogModel();
        $addArgs = array(
            'game_id'=>$game_id,
            'ip'=>$ip,
            'share_time'=>date('Y-m-d H:i:s')
        );
        if($model->add($addArgs)){
            $this->addShareNum($game_id,$ip);   //redis 记录ip的分享次数
            return true;
        }else{
            return false;
        }

    }

    /**
     * 检查分享是否超过次数限制
     * true 被限制
     * false 没限制
     */
    public function checkShareNumLimit($game_id,$ip){
        $redis = self::getRedis();
        $redis_key = self::SHARE_LOG_NUM_REDIS_KEY.$game_id.':'.$ip;
        $share_num = $redis->get($redis_key);
        if($share_num>=10){
            return true;
        }
        return false;
    }

    /**
     * 分享次数+1
     */
    public function addShareNum($game_id,$ip){
        $redis = self::getRedis();
        $redis_key = self::SHARE_LOG_NUM_REDIS_KEY.$game_id.':'.$ip;
        $share_num = $redis->get($redis_key);
        $share_num = $share_num+1;
        $redis->setex($redis_key,self::SHARE_LOG_REDIS_EXPIRE_TIME,$share_num);
    }


 
}