<?php
class busiController extends icommonController{
    
    /**
    *   @Desc checkNotify?
    *   参考:https://api.11h5.com/game?cmd=getNotice&gameid=157
    */
    public function getNoticeAction($paras){
        $game_id = @$_GET['gameid']+0;
        if(!$game_id){//参数缺失
            json_exit(60001,"参数缺失");
        }
        
        //todo 
        echo json_encode(array("error"=>0));
    }

    //记录用户最后一次玩的游戏
    public function userPlayAction(){
        $game_id = @$_GET['gameid']+0;
        $token = trim(@$_GET['token']);
        $cps_id = trim(@$_GET['chid']);
        $sub_cps_id = trim(@$_GET['subchid']);
        $from = trim(@$_GET['from']);
        
        if(!$game_id && !$token ){  //参数缺失
            json_exit(10001,"参数缺失");
        }

        $service = new AuthService();
        $access_token_info = $service->getTokenInfo($token);
        if(!$access_token_info){    //token校验失败
            json_exit(10001,"token校验失败");
        }

        $userPlayService = new UserPlayService();
        $res = $userPlayService->addPlayLog($game_id,$access_token_info['user_id'],$cps_id,$sub_cps_id);

        //todo 入库

        echo json_encode(array("error"=>0));


    }

    


}

