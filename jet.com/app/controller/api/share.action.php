<?php
class shareController extends icommonController{


    /**
    *   @Desc 获取分享信息
    *   参考:https://web.11h5.com/share?cmd=getShareDomain&gameid=123&token=31be63988a101cfb2c2ca2027b6e015b&1508205975833
    */
    public function getShareDomainAction(){
        $game_id = @$_GET['gameid']+0;
        $shareConf = Config::get("{$game_id}/share","game");
        if(!$shareConf){
            $shareConf = Config::get("default/share","game");
            $shareConf['error'] = 0;
            echo json_encode($shareConf);exit;
        }

        $shareConf['error'] = 0;
        echo json_encode($shareConf);exit;
    }

    /**
     * 获取微信分享参数
     * 参考 https://api.11h5.com/common?cmd=getWechatJsTicket&url=http://www.baidu.com&gameid=123
     */
    public function getWechatJsTicketAction(){
        $url = @$_GET['url'];
        if(!$url){
            json_exit(10010,"缺少url参数");
        }
        $game_id = @$_GET['gameid']+0;
        $service = new WXShareService();
        $signPackage = $service->getSignPackage($url);
        if(!$signPackage){
            json_exit(10010,"微信获取signPackage失败");
        }
        //{"error":0,"data":{"noncestr":"9q4rozw5hcrbzeej","timestamp":1508210212,"sign":"7524db6ac8eb533eb336b9418c514c8358504e77"}}
        $result = array(
            "error"=>0,
            "data"=>array(
                "appid"=>$signPackage['appId'],
                "noncestr"=>$signPackage['nonceStr'],
                "timestamp"=>$signPackage['timestamp'],
                "sign"=>$signPackage['signature']
            )
        );
        echo json_encode($result);
    }


    /*
     * 获取QQ分享参数
     * 参考 https://api.11h5.com/common?cmd=getMobileQQJsTicket&url=www.baidu.com  qq分享
     */
    public function getMobileQQJsTicketAction(){
        $url = @$_GET['url'];
        if(!$url){
            json_exit(10010,"缺少url参数");
        }
        $service = new WXShareService();
        $signPackage = $service->getSignPackage($url);
        if(!$signPackage){
            json_exit(10010,"微信获取signPackage失败");
        }
        $result = array(
            "error"=>0,
            "data"=>array(
                "noncestr"=>$signPackage['nonceStr'],
                "timestamp"=>$signPackage['timestamp'],
                "sign"=>$signPackage['signature']
            )
        );
        echo json_encode($result);
    }

    /**
     * 分享统计
     */
    public function confirmShareAction(){
        $game_id = @$_GET['gameid']+0;
        $ip = getClientIP();
        if(!$game_id){json_exit(10010,"参数缺失");}
        

        $service = new ShareService();

        $is_limit = $service->checkShareNumLimit($game_id,$ip);
        if($is_limit){
            json_exit(10010,"分享次数太多");
        }
        $res = $service->addLog($game_id,getClientIP());
        if($res){
            json_exit(0,"success");
        }else{
            json_exit(10010,"add db error");
        }

    }
    
    //获取分享二维码信息
    public function getShareQRDomainAction(){
        $game_id = @$_GET['gameid']+0;
        if(!$game_id){
            echo json_encode(array());exit;
        }
        $shareConf = Config::get("{$game_id}/share","game");
        if(!$shareConf){
            echo json_encode(array());exit;
        }

        $info = array(
            "shareURL"=>$shareConf['shareURL']   
        );
        echo json_encode($info);exit;  
    }

    


}

