<?php
    	
use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Mjoin,
DataTables\Editor\Validate;



/**
 * @Description 游戏管理
 * 
 */


class gameController extends commonController{
	public function indexAction(){
		$this->assign("js", "game_manage/games_list.js");		
		$this->assign("js_privilege", json_encode(array("privilege_code"=> 0 )));
		$this->assign("second_menu", "/game_manage/game/game_second_menu.tpl"); 
		$this->assign("title", _lan('BackgroundUserManagement','游戏管理'));
		$this->render(false,"common_list.tpl");
	}
	
	public function index_ajaxAction(){
		$db = Doris\DApp::loadDT();
 		$editor = Editor::inst( $db, 'game' ,"id" )
		->fields(
			Field::inst( 'id' ),
			Field::inst( 'appid' ),
			Field::inst( 'game_name' )
		);

        $out = $editor->process($_POST)->data();
	

        _load( "Admin_PrivilegeModel");
        $privilegeModel = new Admin_PrivilegeModel();
        foreach($out['data'] as $k=>$v){
            $out['data'][$k]['operation'] = '';
            if($privilegeModel->checkAuth('game_manage','game','viewGame')){
                $out['data'][$k]['operation'] .= '<a href="?m=game_manage&c=game&a=viewGame&id='.$v['id'].'">查看游戏信息</a>&nbsp;&nbsp;';
            }
            if($privilegeModel->checkAuth('game_manage','game','updateGame')){
                $out['data'][$k]['operation'] .= '<a href="?m=game_manage&c=game&a=updateGame&id='.$v['id'].'">修改游戏</a>&nbsp;&nbsp;';
            }
            if($privilegeModel->checkAuth('game_manage','game','delGame')){
                $out['data'][$k]['operation'] .= '<a href="?m=game_manage&c=game&a=delGame&id='.$v['id'].'" onclick= "return confirm(\'确定要删除此游戏吗?\');">删除游戏</a>&nbsp;&nbsp;';
            }
            if($privilegeModel->checkAuth('game_manage','gameItem')){
                $out['data'][$k]['operation'] .= '<a href="?m=game_manage&c=gameItem&a=index&game_id='.$v['id'].'">商品管理</a>&nbsp;&nbsp;';
            }

            if($privilegeModel->checkAuth('game_manage','gift')){
                $out['data'][$k]['operation'] .= '<a href="?m=game_manage&c=gift&a=index&game_id='.$v['id'].'">礼包管理</a>&nbsp;&nbsp;';
            }
            if($privilegeModel->checkAuth('game_manage','gameNews')){
                $out['data'][$k]['operation'] .= '<a href="?m=game_manage&c=gameNews&a=index&game_id='.$v['id'].'">资讯管理</a>';
            }
        }
	    echo json_encode($out);  
	}

    //检查appid是否占用 true 已占用  false 未占用
    private function checkAppidRepeat($appid,$except_game_id = ''){
        _load( "Admin_GameModel");
        $GameModel = new Admin_GameModel();
        $game_info = $GameModel->readGameByAppId($_POST['appid']);
        if($game_info && $game_info['id'] != $except_game_id){
            return true;
        }else{
            return false;
        }
    }

    
    public function addGameAction(){
        if($_POST){
            //提交
            if(empty($_POST['game_name']) || empty($_POST['appid']) || empty($_POST['game_url']) || empty($_POST['brief_intro']) || empty($_POST['td_appid']) ||  empty($_POST['content_url']) || empty($_POST['file_path']) || empty($_POST['icon']) ){
                $this->error('信息填写不完整');
            }
            #print_r($_POST);exit;
            #echo 2;exit;
            
            if($this->checkAppidRepeat($_POST['appid'])){
                $this->error('APPID重复!');
            }

            $info['game_name'] = trim($_POST['game_name']);
            $info['appid'] = trim($_POST['appid']);
            #$info['url'] = strtolower(substr($_POST['url'],0,4)) != 'http'? 'http://'.$_POST['url']:$_POST['url'];
            //$info['token_type'] = trim($_POST['token_type']);
            //$info['type'] = trim($_POST['type']);
            //$info['dc_appid'] = trim($_POST['dc_appid']);
            $info['td_appid'] = trim($_POST['td_appid']);
            //$info['entry_url'] = strtolower(substr($_POST['entry_url'],0,4)) != 'http'? 'http://'.$_POST['entry_url']:$_POST['entry_url'];
            $info['game_url'] = strtolower(substr($_POST['game_url'],0,4)) != 'http'? 'http://'.$_POST['game_url']:$_POST['game_url'];
            $info['content_url'] = strtolower(substr($_POST['content_url'],0,4)) != 'http'? 'http://'.$_POST['content_url']:$_POST['content_url'];
            $info['orientation'] = $_POST['orientation']+0;
            $info['wx_option'] = $_POST['wx_option']+0;
            #$info['use_vuconpon'] = trim($_POST['use_vuconpon']);
            $info['is_exclusive'] = $_POST['is_exclusive']+0;
            $info['is_gift'] = $_POST['is_gift']+0;
            $info['is_new'] = $_POST['is_new']+0;
            $info['is_hot'] = $_POST['is_hot']+0;
            $info['weight'] = $_POST['weight']+0;
            $info['brief_intro'] = trim($_POST['brief_intro']);

            $insert_id = Doris\DDB::db()->game()->insert($info);
            if(!$insert_id){
                $this->error('操作失败');
            }

            $to_dir =  rtrim(_ROOT_DIR_,'/').'/admin/Resources/game/'.$insert_id.'/picture/';
            if(!file_exists($to_dir)){
                mkdir($to_dir,0777,true);
            }
            $suffix = strrchr($_POST['file_path'],'.');
            $newFileName = 'desktopicon_date_'.date('Y-m-dHis').$suffix;
            $res = copy($_POST['file_path'],$to_dir.$newFileName);
            if($res){
                $game = Doris\DDB::db()->game[$insert_id];
                $game['desktop_icon'] = 'Resources/game/'.$insert_id.'/picture/'.$newFileName;
                $game->update();
            }
            
            $icon = strrchr($_POST['icon'],'.');
            $newFileName = 'icon_date_'.date('Y-m-dHis').$suffix;
            $res = copy($_POST['icon'],$to_dir.$newFileName);
            if($res){
                $game = Doris\DDB::db()->game[$insert_id];
                $game['icon'] = 'Resources/game/'.$insert_id.'/picture/'.$newFileName;
                $game->update();
            }
            //同步图片
            (new Service_Picture())->syncPicture();

            $this->success('操作成功','/index.php?m=game_manage&c=game');exit;
        }

        $navs = array(
            array('url'=>'index.php?m=game_manage&c=game','title'=>'游戏管理'),
        );
		$this->assign("js_para",array(
				"session_id"=>session_id(),
		));
        $this->assign("menu", "game/menu.tpl");
        $this->assign("title", '新建游戏');
        $this->render("/game_manage/game/addGame.tpl","main.tpl");
    }

    /**
    *   @Description 上传附件到临时目录
    */
    public function upload_ajaxAction(){
        set_time_limit(0);
        \Doris\DApp::loadSysLib('Upload/FileUpload.php');
	    $toDir = rtrim(_ROOT_DIR_,'/').'/admin/cache/game/';
        $newFileName = uniqid();
        mkdir($toDir,0777,true);
        $suffix = strrchr($_FILES['Filedata']['name'],'.');
        $fileUp = new Upload_FileUpload(array('savepath'=>$toDir, 'israndname'=>false, 'givenfilename'=>$newFileName,'allowtype'=>array('jpg','jpeg','png','gif'),'maxsize'=>'524288000') );
        if(!$_FILES['Filedata']){
			$result = array('code'=>0,'message'=>'上传文件超过配置文件的大小');
            echo json_encode($result);exit;        
        }
		$fileUp->uploadFile('Filedata');

        if($fileUp->getErrorMsg()){
            $errorMsg = $fileUp->getErrorMsg();
			$result = array('code'=>0,'message'=>$errorMsg);
            echo json_encode($result);exit;
		}

        $result = array('code'=>1,'url'=>'cache/game/'.$newFileName.$suffix);
        echo json_encode($result);
    }

    /**
    *   @Description 上传附件到临时目录
    */
    public function upload_ajax_dropzonAction(){
        set_time_limit(0);
        \Doris\DApp::loadSysLib('Upload/FileUpload.php');
	    $toDir = rtrim(_ROOT_DIR_,'/').'/admin/cache/game/';
        $newFileName = uniqid();
        mkdir($toDir,0777,true);
        $suffix = strrchr($_FILES['Filedata']['name'],'.');
        $fileUp = new Upload_FileUpload(array('savepath'=>$toDir, 'israndname'=>false, 'givenfilename'=>$newFileName,'allowtype'=>array('jpg','jpeg','png','gif'),'maxsize'=>'524288000') );
        if(!$_FILES['Filedata']){
			$result = array('code'=>0,'message'=>'上传文件超过配置文件的大小');
            echo json_encode($result);exit;        
        }
		$fileUp->uploadFile('Filedata');

        if($fileUp->getErrorMsg()){
            $errorMsg = $fileUp->getErrorMsg();
			#$result = array('code'=>0,'message'=>$errorMsg);
            #echo json_encode($result);exit;
            echo $errorMsg; 
            Header("HTTP/1.1 500");exit;
		}

        $result = array('code'=>1,'url'=>'cache/game/'.$newFileName.$suffix);
        echo json_encode($result);
    }

    //修改游戏
    public function updateGameAction(){
        _load( "Admin_GameModel");
        $game_info = Admin_GameModel::readGameById($_GET['id']);
        if(!$game_info){$this->error('页面未找到','index.php?m=game_manage&c=game');}
        if($_POST){
            if(empty($_POST['game_name']) || empty($_POST['appid']) || empty($_POST['game_url']) || empty($_POST['brief_intro']) || empty($_POST['td_appid']) ||  empty($_POST['content_url'])){
                $this->error('信息填写不完整');
            }

            if($this->checkAppidRepeat($_POST['appid'],$_GET['id'])){
                $this->error('APPID重复!');
            }


            $game = Doris\DDB::db()->game[$_GET['id']];
            if($_POST['file_path']){
                $toDir =  rtrim(_ROOT_DIR_,'/').'/admin/Resources/game/'.$game_info['id'].'/picture/';
                if(!file_exists($toDir)){
                    mkdir($toDir,0777,true);
                }
                $suffix = strrchr($_POST['file_path'],'.');
                $newFileName = 'desktopicon_date_'.date('Y-m-dHis').$suffix;
                $res = copy($_POST['file_path'],$toDir.$newFileName);
                if($res){
                    $game['desktop_icon'] = 'Resources/game/'.$game_info['id'].'/picture/'.$newFileName;
                    @unlink(rtrim(_ROOT_DIR_,'/').'/admin/'.$game_info['desktop_icon']);
                }else{
                    $this->error('拷贝文件出错,请重新上传');
                }
            }

            if($_POST['icon']){
                $toDir =  rtrim(_ROOT_DIR_,'/').'/admin/Resources/game/'.$game_info['id'].'/picture/';
                if(!file_exists($toDir)){
                    mkdir($toDir,0777,true);
                }
                $suffix = strrchr($_POST['icon'],'.');
                $newFileName = 'icon_date_'.date('Y-m-dHis').$suffix;
                $res = copy($_POST['icon'],$toDir.$newFileName);
                if($res){
                    $game['icon'] = 'Resources/game/'.$game_info['id'].'/picture/'.$newFileName;
                    @unlink(rtrim(_ROOT_DIR_,'/').'/admin/'.$game_info['icon']);
                }else{
                    $this->error('拷贝文件出错,请重新上传');
                }
            }
            $game['game_name'] = trim($_POST['game_name']);
            $game['appid'] = trim($_POST['appid']);
            //$game['token_type'] = trim($_POST['token_type']);
            $game['td_appid'] = trim($_POST['td_appid']);
            $game['game_url'] = strtolower(substr($_POST['game_url'],0,4)) != 'http'? 'http://'.$_POST['game_url']:$_POST['game_url'];
            $game['content_url'] = strtolower(substr($_POST['content_url'],0,4)) != 'http'? 'http://'.$_POST['content_url']:$_POST['content_url'];
            $game['orientation'] = $_POST['orientation']+0;
            $game['wx_option'] = $_POST['wx_option']+0;
            $game['is_exclusive'] = $_POST['is_exclusive']+0;
            $game['is_gift'] = $_POST['is_gift']+0;
            $game['is_new'] = $_POST['is_new']+0;
            $game['is_hot'] = $_POST['is_hot']+0;
            $game['weight'] = $_POST['weight']+0;
            $game['brief_intro'] = trim($_POST['brief_intro']);
            $game->update();
            //同步图片
            $res = (new Service_Picture())->syncPicture();
            $this->success('操作成功');exit;
            
        }

		$this->assign("js_para",array(
				"session_id"=>session_id()
		));
        $desktop_icon = rtrim(_ROOT_DIR_,'/').'/admin/'.$game_info['desktop_icon'];
        $icon = rtrim(_ROOT_DIR_,'/').'/admin/'.$game_info['icon'];
        $desktop_icon_info = array();
        $icon_info = array();
        if(is_file($desktop_icon) && file_exists($desktop_icon)){
            $desktop_icon_info = array(
                'name'=>'桌面icon',
                'size'=>filesize($desktop_icon),
                'path'=>$game_info['desktop_icon']
            );
        }
        if(is_file($icon) && file_exists($icon)){
            $icon_info = array(
                'name'=>'游戏icon',
                'size'=>filesize($icon),
                'path'=>$game_info['icon']
            );
        }
        $this->assign("menu", "game/menu.tpl");
        $this->assign("title", '修改游戏');
        $this->assign('game_info',$game_info);
        $this->assign('desktop_icon_info',$desktop_icon_info);
        $this->assign('icon_info',$icon_info);
        $this->render("/game_manage/game/updateGame.tpl","main.tpl");
    }

    
    //查看游戏信息
    public function viewGameAction(){
        _load( "Admin_GameModel");
        $game_info = Admin_GameModel::readGameById($_GET['id']);
        if(!$game_info){$this->error('页面未找到','index.php?m=game_manage&c=game');}
     
        $this->assign("menu", "game/menu.tpl");
        $this->assign("title", '查看游戏');
        $this->assign('game_info',$game_info);
        $this->render("/game_manage/game/viewGame.tpl","main.tpl");
    }
    
    //删除游戏
    public function delGameAction(){
        _load( "Admin_GameModel");
        $game_info = Admin_GameModel::readGameById($_GET['id']);
        if(!$game_info){$this->error('页面未找到','index.php?m=game_manage&c=game');}

        $res = Admin_GameModel::delGameById($_GET['id']);
        if($res){
            @unlink(rtrim(_ROOT_DIR_,'/').'/admin/'.$attachmentInfo['path']);
            $this->success('操作成功','index.php?m=game_manage&c=game');
        }else{
            $this->error('操作失败');
        }
        exit;
    
    }


	
	
	//cas_menu 的数据：游戏列表
 	// http://opentool.netkingol.com/index.php?m=cps_manage&c=cm_games&a=gameOptionsList
	public function gameOptionsListAction(){
		 
		$ret=['code'=>'fail',"msg"=>"","list"=>[]];
	
		$rootId=@$_GET['rootId'];
		$defaultId=@$_GET['defaultId'];
		if(!$rootId){
			$arrC = Doris\DDB::pdo()->query( "select id as value,game_name as label from game")->fetchAll(PDO::FETCH_ASSOC);
		
			if($arrC && count($arrC) > 0)
				array_push($ret['list'], array("pairs"=>$arrC,"def"=>$defaultId));
		}
		$ret['code']="succ";
		echo json_encode($ret);
	}

}