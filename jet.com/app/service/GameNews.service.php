<?php
class GameNewsService{

    //获取资讯列表
    public function getNewsList($game_id,$page_no = 1,$page_size = 15){
        $model = new GameNewsModel();
        $fields = "id,game_id,title,add_time,read_count,thumb_count";
        $limit = ($page_no-1)*$page_size;
        $limit = $limit>=0?$limit:0;
        $where = "game_id = {$game_id} and status=1 limit {$limit},{$page_size}";
        $list = $model->getNewsList($where,$fields);
        return $list;
    }

    //获取资讯详情
    public function getNewsInfo($news_id){
        $model = new GameNewsModel();
        $info = $model->getNewsInfoById($news_id);
        return $info;
    }

    //获取用户未读资讯的数量
    public function getUnReadNewsCount($user_id,$game_id){
        $userReadMsgModel = new UserReadMsgModel();
        $where = "user_id = {$user_id} and game_id = {$game_id} and msg_type='news'";
        $lastReadTimeInfo = $userReadMsgModel->readInfo('last_read_time',$where);
        if(!$lastReadTimeInfo){
            return $this->getNewsCount($game_id);
        }
        $lastReadTime = $lastReadTimeInfo['last_read_time'];

        $gameNewsModel = new GameNewsModel();
        $where = "game_id = {$game_id} and status = 1 and add_time>'{$lastReadTime}'";
        return $gameNewsModel->getCount($where);

    }

    //获取资讯数量
    public function getNewsCount($game_id){
        $gameNewsModel = new GameNewsModel();
        $where = " game_id = {$game_id} and status = 1";
        return $gameNewsModel->getCount($where);
    }


}