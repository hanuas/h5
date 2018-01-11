<?php
class userController extends icommonController{
    
    /**
    *   @Desc 申请好友
    *   参考:https://api.11h5.com/wechat?cmd=applyFriend&token=0f804668620c013e699fec93eba3a616&fuid=426529253
    */
    public function wechatAction($paras){
        if(!in_array(@$_GET['cmd'],array("applyFriend"))){
            json_exit(10001,"参数错误");   
        }

        switch($_GET['cmd']){
            case "applyFriend":
                $this->applyFriend();
        }

    }
    
    //加好友
    private function applyFriend(){
        if(!@$_GET['token'] || !@$_GET['fuid']){//参数缺失
            json_exit(10001,"参数缺失");  
        }
        $token = trim($_GET['token']);
        $fuid = $_GET['fuid']+0;
        $service = new AuthService();
        $access_token_info = $service->getTokenInfo($token);
        if(!$access_token_info){    //token校验失败
            json_exit(10001,"token校验失败");  
        }

        //TODO: 加好友

        echo json_encode(array("error"=>0));    
    
    }

    


}

