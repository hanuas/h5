<?php
class mmsgController extends icommonController{
    public function indexAction(){
        if(!in_array(@$_GET['cmd'],array("getMsgList","readMsg","getUnread"))){
            json_exit(4,"非法参数");
        }

        switch($_GET['cmd']){
            case "getMsgList":
                $this->getMsgList();  //获取资讯列表
            break;
            case "readMsg":
                $this->readMsg();
            break;
            case "getUnread":
                $this->getUnread();

        }

    }

    //获取未读资讯消息数量
    private function getUnread(){
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
        if(!$gameInfo){echo json_encode(array('count'=>0,'total'=>0));exit;}
        //获取总资讯数量
        $gameNewsService = new GameNewsService();
        $total = $gameNewsService->getNewsCount($gameInfo['id']);
        if(!$total){
            echo json_encode(array('count'=>0,'total'=>0));exit;
        }
        //获取未读资讯数量
        $unReadCount = $gameNewsService->getUnReadNewsCount($tokenInfo['user_id'],$gameInfo['id']);
        $readCount = $total-$unReadCount;

        echo json_encode(array('count'=>$unReadCount,'total'=>$total));exit;


    }

    //获取资讯列表
    private function getMsgList(){
        $token = getSafeStr(@$_GET['token']); //用户token
        $pageNo = @$_GET['pageNo']+0;//页码数
        $pageSize = @$_GET['pageSize']+0?$_GET['pageSize']+0:15; //每页条数
        $game_id = @$_GET['gameid']+0; //游戏id
        if(!$game_id){json_exit(10010,'游戏id不存在');}
        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($game_id);
        if(!$gameInfo){json_exit(10010,'游戏不存在');}

        $gameNewsService = new GameNewsService();
        $res = $gameNewsService->getNewsList($gameInfo['id'],$pageNo,$pageSize);

        $authService = new AuthService();
        $access_token_info = $authService->getTokenInfo($token);
        if($access_token_info){
            //记录用户最后访问资讯的时间
            $userReadMsgService = new UserReadMsgService();
            $userReadMsgService->logReadMsgLastTime($access_token_info['user_id'],$gameInfo['id'],'news');
        }

        $news_list = array();
        foreach($res as $k=>$v){
            $news_info = array(
                'id'=>$v['id'],
                'title'=>$v['title'],
                'isRead'=>0,
                'time'=>strtotime($v['add_time']),
            );
            $news_list[] = $news_info;
        }


        $result = array("msgList"=>$news_list,"error"=>0);
        
        echo json_encode($result);

    }

    //获取资讯详情
    private function readMsg(){
        $id = $_GET['id']+0;
        if(!$id){json_exit(10010,'资讯id不存在');}
        $token = getSafeStr(@$_GET['token']);
        $game_id = $_GET['gameid']+0;

        $gameNewsService = new GameNewsService();
        $news_info = $gameNewsService->getNewsInfo($id);
        if(!$news_info){
            json_exit(10010,'资讯不存在');
        }

        $info = array(
            'read'=>$news_info['read_count'],
            'thumb'=>$news_info['thumb_count'],
            'time'=>strtotime($news_info['add_time']),
            'title'=>$news_info['title'],
            'isThumb'=>0,
            'content'=>$news_info['content'],
            'error'=>0
        );

        echo json_encode($info);



    }

}