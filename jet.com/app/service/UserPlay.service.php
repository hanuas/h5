<?php
class UserPlayService{
    
    
    //add
    public function addPlayLog($game_id,$user_id,$cps_id = '',$sub_cps_id = ''){
        $model = new UserPlayModel();

        $args = array(
            'game_id'=>$game_id,
            'user_id'=>$user_id,
            'cps_id'=>$cps_id,
            'sub_cps_id'=>$sub_cps_id,
            'ip'=>getClientIP(),
        );
        $time = strtotime(date('Y-m-d H:0:0')); //时间只记录到小时
        $args['time'] = $time;
        $res = $model->add($args);
        return $res;
    }

   

}