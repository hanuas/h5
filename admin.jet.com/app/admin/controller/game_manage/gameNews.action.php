<?php
    	
use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Mjoin,
DataTables\Editor\Validate;



/**
 * @Description 游戏资讯管理
 * 
 */


class gameNewsController extends commonController{
    public function indexAction(){
        _load( "Admin_GameModel");
        $game_id = @$_GET['game_id']+0; 
        $game_info = Admin_GameModel::readGameById($game_id);
        if(!$game_info){$this->error('页面未找到','index.php?m=game_manage&c=game');}
        $status = array(
            "1"=>array("显示"),
            "0"=>array("隐藏"),
        );
        self::simplifyColumnGroup($status);
        $this->assign("js_para", json_encode([
            "n_status"=>$status,
            "game_id"=>$game_id
		]));

        $this->assign("second_menu", "/game_manage/gameNews/game_news_menu.tpl");
        $this->assign("js", "game_manage/game_news_list.js");
		$this->assign("js_privilege", json_encode(array("privilege_code"=> 0 )));
		$this->assign("title", _lan('BackgroundUserManagement','资讯管理'));
        $this->assign("sub_title", $game_info['game_name'].'—'.$game_info['appid']);
		$this->render(false,"common_list.tpl");
    }

    public function index_ajaxAction(){
        $db = Doris\DApp::loadDT();

        $editor = Editor::inst( $db, 'game_news' ,"id" )
            ->fields(
                Field::inst( 'id' ),
                Field::inst( 'game_id' ),
                Field::inst( 'title' ),
                Field::inst( 'add_time' ),
                #Field::inst( 'read_count' ),
                #Field::inst( 'thumb_count' ),
                Field::inst( 'weight' ),
                Field::inst( 'status' )
            )->where('game_id',$_GET['game_id']);

        $out = $editor->process($_POST)->data();


        _load( "Admin_PrivilegeModel");
        $privilegeModel = new Admin_PrivilegeModel();
        #print_r($out['data']);
        foreach($out['data'] as $k=>$v){
            $out['data'][$k]['operation'] = '';
            if($privilegeModel->checkAuth('game_manage','gameNews','updateGameNews')){
                $out['data'][$k]['operation'] .= '<a href="?m=game_manage&c=gameNews&a=updateGameNews&id='.$v['id'].'">修改资讯</a>&nbsp;&nbsp;';
            }
            if($privilegeModel->checkAuth('game_manage','gameNews','delGameNews')){
                $out['data'][$k]['operation'] .= '<a href="?m=game_manage&c=gameNews&a=delGameNews&id='.$v['id'].'" onclick= "return confirm(\'确定要删除此资讯吗?\');">删除资讯</a>&nbsp;&nbsp;';
            }
        }

        echo json_encode($out);
    }

    //新建资讯
    public function addGameNewsAction(){
        _load( "Admin_GameModel");
        $game_id = @$_GET['game_id']+0;
        $game_info = Admin_GameModel::readGameById($game_id);
        if(!$game_info){$this->error('页面未找到','index.php?m=game_manage&c=game');}
        if($_POST){
            $info = array();
            $info['title'] = trim($_POST['title']);
            $info['status'] = $_POST['status'] == 1?1:0;
            $info['weight'] = $_POST['weight']+0;
            $info['content'] = $_POST['content'];
            $info['add_time'] = date('Y-m-d H:i:s');
            $info['game_id'] = $game_id;
            if(!$info['title'] || !$info['content']){
                $this->error("信息不完整");
            }
            $insert_id = Doris\DDB::db()->game_news()->insert($info);
            if(!$insert_id){
                $this->error('操作失败');
            }
            $this->success('操作成功','/index.php?m=game_manage&c=gameNews&a=index&game_id='.$game_id);exit;
        }
        $navs = array(
            array("url"=>"/index.php?m=game_manage&c=game","title"=>"游戏管理"),
            array("url"=>"/index.php?m=game_manage&c=gameNews&a=index&game_id=".$game_id,"title"=>"资讯管理"),
        );
        $this->assign("sub_title","&nbsp;&nbsp;游戏APPID:{$game_info['appid']}&nbsp;{$game_info['game_name']}");
        $this->assign('navs',$navs);
        $this->assign('navs_tpl','/navs.tpl');
        $this->assign("menu", "gameNews/menu.tpl");
        $this->assign("title", '新建资讯');
        $this->render("/game_manage/gameNews/addNews.tpl","main.tpl");

    }

    //修改资讯
    public function updateGameNewsAction(){
        _load( "Admin_GameNewsModel");
        _load( "Admin_GameModel");
        $id = @$_GET['id']+0;
        $news_info = Admin_GameNewsModel::readGameNewsById($id);
        if(!$news_info){$this->error('页面未找到','index.php?m=game_manage&c=game');}
        $game_info = Admin_GameModel::readGameById($news_info['game_id']);
        if(!$game_info){$this->error('页面未找到','index.php?m=game_manage&c=game');}
        if($_POST){
            $game_news = Doris\DDB::db()->game_news[$_GET['id']];
            $game_news['title'] = trim($_POST['title']);
            $game_news['status'] = $_POST['status'] == 1?1:0;
            $game_news['weight'] = $_POST['weight']+0;
            $game_news['content'] = $_POST['content'];
            if(!trim($_POST['title']) || !$_POST['content']){
                $this->error("信息不完整");
            }
            $game_news->update();
            $this->success('操作成功');exit;
        }
        $navs = array(
            array("url"=>"/index.php?m=game_manage&c=game","title"=>"游戏管理"),
            array("url"=>"/index.php?m=game_manage&c=gameNews&a=index&game_id=".$news_info['game_id'],"title"=>"资讯管理"),
        );
        $this->assign("news_info",$news_info);
        $this->assign("sub_title","&nbsp;&nbsp;游戏APPID:{$game_info['appid']}&nbsp;{$game_info['game_name']}");
        $this->assign('navs',$navs);
        $this->assign('navs_tpl','/navs.tpl');
        $this->assign("menu", "gameNews/menu.tpl");
        $this->assign("title", '修改资讯');
        $this->render("/game_manage/gameNews/updateNews.tpl","main.tpl");
    }

    //删除资讯
    public function delGameNewsAction(){
        _load( "Admin_GameNewsModel");
        $id = @$_GET['id']+0;
        $news_info = Admin_GameNewsModel::readGameNewsById($id);
        if(!$news_info){$this->error('资讯未找到','index.php?m=game_manage&c=game');}

        $res = Admin_GameNewsModel::delGameNewsById($id);
        if(!$res){
            $this->error("操作失败","index.php?m=game_manage&c=gameNews&a=index&game_id=".$news_info['game_id']);
        }else{
            $this->success("操作成功","index.php?m=game_manage&c=gameNews&a=index&game_id=".$news_info['game_id']);
        }
    }


}