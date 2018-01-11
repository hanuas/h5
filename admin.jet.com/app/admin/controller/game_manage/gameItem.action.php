<?php
//游戏商品管理
class gameItemController extends commonController{
	public function indexAction(){
        if($_POST){
            if(count($_POST['item_id']) != count(array_unique($_POST['item_id']))){
                $this->error('item_id 不能重复 !');
            }
            $this->doUpdateGameItem();exit;
        }
        $game_id = @$_GET['game_id']+0; 
        _load( "Admin_GameModel");
        $game_info = Admin_GameModel::readGameById($game_id);
        if(!$game_info){$this->error('页面未找到','index.php?m=game_manage&c=game');}
        _load( "Admin_PayChannelsModel");
        $pay_channels = Admin_PayChannelsModel::readList();
        _load( "Admin_GameItemModel");
        $gameItem = Admin_GameItemModel::readGameItemByGameId($_GET['game_id']);
        $this->assign("menu", "game/menu.tpl");
        $this->assign("title", '游戏商品管理');
        $this->assign("sub_title","游戏ID:{$game_info['appid']}&nbsp;{$game_info['game_name']}");
        $this->assign('game_info',$game_info);
        $this->assign('game_item',$gameItem);
        $this->assign('pay_channels',$pay_channels);
        $this->render("/game_manage/gameItem/updateGameItem.tpl","main.tpl");
	} 
    

    /**
     * @Description 执行修改游戏商品
     */
    private function doUpdateGameItem(){

        $game_id = @$_GET['game_id']+0; 
        _load( "Admin_GameModel");
        $gameInfo = Admin_GameModel::readGameById($game_id);
        if(!$gameInfo){
            $this->error("游戏不存在");
        }
        //修改package_id
        $game = Doris\DDB::db()->game[$game_id];
        $game['package_id'] = trim($_POST['package_id']);
        $game->update();

        _load( "Admin_GameItemModel");
        $camera = Admin_GameItemModel::getCamera($game_id);//未修改之前的定价表信息
        
        //清空game_item数据
        Admin_GameItemModel::deleteByGameId($game_id);
        foreach($_POST['channel_ids'] as $k=>$v){
            $channelids = explode(',',$v);
            $_POST['channel_ids'][$k] = implode(',',$channelids);
        }        
        foreach($_POST['reference_name'] as $key=>$value){
            $pricedata['reference_name'] = $value;
            $pricedata['type'] = $_POST['type'][$key];
            $pricedata['game_id'] = $_GET['game_id'];
            $pricedata['channel_id'] = $_POST['channel_ids'][$key];
            $pricedata['value'] = $_POST['value'][$key];
            $pricedata['coin'] = $_POST['coin'][$key];
            $pricedata['coin_unit'] = $_POST['coin_unit'][$key];
            $pricedata['rmbprice'] = $_POST['rmbprice'][$key];
            $pricedata['dolarprice'] = $_POST['dolarprice'][$key];
            $pricedata['tire'] = $_POST['tire'][$key];
            $pricedata['month'] = @$_POST['month'][$key]?$_POST['month'][$key]:0;
            $pricedata['week'] = @$_POST['week'][$key]?$_POST['week'][$key]:0;
            $pricedata['item_id'] = $_POST['item_id'][$key];
            $pricedata['display_name'] = $_POST['display_name'][$key]?$_POST['display_name'][$key]:'';
            $pricedata['description'] = $_POST['description'][$key]?$_POST['description'][$key]:'';

            $insert_id = Doris\DDB::db()->game_item()->insert($pricedata);
        }

        //记录操作日志
        _load("Admin_ActionLogModel");
        $log = array();
        $log[] = '修改PackageId:'.$_POST['package_id'].',原为:'.$gameInfo['package_id'];

        $log['newcamera'] = Admin_GameItemModel::getCamera($_GET['game_id']);
        $log['camera'] = $camera;
        Admin_ActionLogModel::logging('修改游戏商品,游戏名:'.$gameInfo['game_name'].',游戏ID'.$gameInfo['appid'],$log,'GameItem');            
        $this->success("操作成功");
    }


}