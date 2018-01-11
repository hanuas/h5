<?php
class GameItemService{
    
    
    //获取商品信息
    public function getItemInfoById($id){
        $model = new GameItemModel();
        $item_info = $model->getItemInfoById($id);
        return $item_info;
    }

    public function getItemInfoByItemId($item_id,$game_id){
        $model = new GameItemModel();
        $item_info = $model->getItemInfoItemId($item_id,$game_id);
        return $item_info;
    }

   

}