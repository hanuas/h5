<?php
class GameService{
    
    
    //获取游戏信息
    public function getGameInfoByAppId($appid){
        $model = new GameModel();
        $game_info = $model->getGameInfoByAppId($appid);
        return $game_info;
    }

    //获取新游尝鲜
    public function getNewGameList(){
        $model = new GameModel();
        $where = " is_new = 1";
        return $model->getGameList($where);

    }

    //获取必玩爆款
    public function getHotGameList(){
        $model = new GameModel();
        $where = " is_hot = 1";
        return $model->getGameList($where);
    }

   

}