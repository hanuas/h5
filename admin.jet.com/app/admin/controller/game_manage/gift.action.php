<?php
    	
use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Mjoin,
DataTables\Editor\Validate;



/**
 * @Description 礼包管理
 * 
 */


class giftController extends commonController{
	public function indexAction(){
        _load( "Admin_GameModel");
        $game_id = @$_GET['game_id']+0; 
        $game_info = Admin_GameModel::readGameById($game_id);
        if(!$game_info){$this->error('页面未找到','index.php?m=game_manage&c=game');}
        $gift_type = array(
            ""=>array("全部"),
            "normal"=>array("普通礼包"),
            "qq_group_num"=>array("QQ群礼包"),
            "point"=>array("积分礼包"),
            "vip"=>array("VIP礼包"),
            "union_code"=>array("统一码礼包"),
          
        );
        $gift_status = array(
            "1"=>array("启用"),
            "0"=>array("停用"),
          
        );
        self::simplifyColumnGroup($order_state);
        
        $this->assign("js_para", json_encode([
            "gift_type"=>$gift_type,
            "gift_status"=>$gift_status,
            "game_id"=>$game_id,
		]));

		$this->assign("js", "game_manage/gift_list.js");		
		$this->assign("js_privilege", json_encode(array("privilege_code"=> 0 )));
		$this->assign("second_menu", "/game_manage/gift/gift_second_menu.tpl"); 
		$this->assign("title", _lan('BackgroundUserManagement','礼包管理'));
		$this->assign("sub_title", $game_info['game_name'].'—'.$game_info['appid']);
		$this->render(false,"common_list.tpl");
	}
	
	public function index_ajaxAction(){
		$db = Doris\DApp::loadDT();
        foreach($_POST['columns'] as $k=>$v){
            if($v['data'] == 'gift.game_id' && $v['search']['value'] === '0'){
                $_POST['columns'][$k]['search']['value'] = '';
            }
        }

 		$editor = Editor::inst( $db, 'gift' ,"gift_id" )
		->fields(
			Field::inst( 'gift.gift_id' ),
			Field::inst( 'gift.gift_title' ),
			Field::inst( 'gift.get_type' ),
			Field::inst( 'gift.brief_intro' ),
			Field::inst( 'gift.total' ),
			Field::inst( 'gift.start_time' ),
			Field::inst( 'gift.end_time' ),
			Field::inst( 'gift.gift_status' ),
            Field::inst( 'gift.gift_weight' ),
			Field::inst( 'gift.point_gift_auto_send' )
		)->where('game_id',$_GET['game_id']);

        $out = $editor->process($_POST)->data();
	

        _load( "Admin_PrivilegeModel");
        $privilegeModel = new Admin_PrivilegeModel();
        #print_r($out['data']);
        foreach($out['data'] as $k=>$v){
            $out['data'][$k]['operation'] = '';
            if($privilegeModel->checkAuth('game_manage','gift','updateGift')){
                $out['data'][$k]['operation'] .= '<a href="?m=game_manage&c=gift&a=updateGift&gift_id='.$v['gift']['gift_id'].'">修改礼包</a>&nbsp;&nbsp;';
            }
            if($privilegeModel->checkAuth('game_manage','card','index')){
                if($v['gift']['get_type'] == 'normal' || ($v['gift']['get_type'] == 'point' && $v['gift']['point_gift_auto_send'] == 0 )) {
                    $out['data'][$k]['operation'] .= '<a href="?m=game_manage&c=card&a=index&gift_id=' . $v['gift']['gift_id'] . '">查看礼包码</a>&nbsp;&nbsp;';
                }
            }
            if($privilegeModel->checkAuth('game_manage','gift','delGift')){
                $out['data'][$k]['operation'] .= '<a href="?m=game_manage&c=gift&a=delGift&gift_id='.$v['gift']['gift_id'].'" onclick= "return confirm(\'确定要删除此礼包吗?\');">删除礼包</a>&nbsp;&nbsp;';
            }
        }
        
	    echo json_encode($out);  
	}
    //删除礼包
    public function delGiftAction(){
        $gift_id = $_GET['gift_id']+0;
        if(!$gift_id){$this->error('页面未找到','index.php?m=game_manage&c=game');}
        _load( "Admin_GiftModel");
        $gift_info = Admin_GiftModel::readGiftByGiftId($gift_id);
        if(!$gift_info){$this->error('页面未找到','index.php?m=game_manage&c=game');}

        $delGiftRes = Admin_GiftModel::delGiftById($gift_id);
        if(!$delGiftRes){
            $this->error("删除失败","/index.php?m=game_manage&c=gift&a=index&game_id=".$gift_info['game_id']);
        }else{
            $this->success("删除成功","/index.php?m=game_manage&c=gift&a=index&game_id=".$gift_info['game_id']);
        }
    }

    //修改礼包
    public function updateGiftAction(){
        $gift_id = $_GET['gift_id']+0;
        if(!$gift_id){$this->error('页面未找到','index.php?m=game_manage&c=game');}
        _load( "Admin_GiftModel");
        $gift_info = Admin_GiftModel::readGiftByGiftId($gift_id);
        if(!$gift_info){$this->error('页面未找到','index.php?m=game_manage&c=game');}
        $this->assign('gift_info',$gift_info);
        _load( "Admin_GameModel");
        $game_info = Admin_GameModel::readGameById($gift_info['game_id']);
        if(!$game_info){$this->error('页面未找到','index.php?m=game_manage&c=game');}
        $this->assign("sub_title","&nbsp;&nbsp;游戏APPID:{$game_info['appid']}&nbsp;{$game_info['game_name']}");
        $navs = array(
            array("url"=>"/index.php?m=game_manage&c=game","title"=>"游戏管理"),
            array("url"=>"/index.php?m=game_manage&c=gift&a=index&game_id=".$gift_info['game_id'],"title"=>"礼包管理"),
        );
        $this->assign('navs',$navs);
        $this->assign('navs_tpl','/navs.tpl');


        if($gift_info['get_type'] == 'normal' || $gift_info['get_type'] == 'qq_group_num' || $gift_info['get_type'] == 'union_code' ){
            //普通礼包
            $this->updateNormalGift($gift_info['get_type']);
        }elseif($gift_info['get_type'] == 'point'){
            //积分礼包
            $this->updatePointGift($gift_info['point_gift_auto_send']);
        }else{
            $items = json_decode($gift_info['vip_gift_content'],true);
            foreach($items as $k=>$v){
                $icon = rtrim(_ROOT_DIR_,'/').'/admin/'.$v['icon'];
                $icon_info = array();
                if(is_file($icon) && file_exists($icon)){
                    $icon_info = array(
                        'name'=>$v['title'].'icon',
                        'size'=>filesize($icon),
                        'path'=>$v['icon']
                    );
                }
                $items[$k]['icon_info'] = $icon_info;
            }
            $gift_info['vip_gift_content'] = json_encode($items);
            $this->assign('gift_info',$gift_info);
            $this->updateVipGift($gift_info['game_id'],$game_info['appid']);
        }
    }
    //修改vip礼包
    private function updateVipGift($game_id,$app_id){
        if($_POST){
            $gift_id = $_GET['gift_id']+0;
            $gift_title = trim($_POST['gift_title']);
            $vip_get_condition = trim($_POST['vip_get_condition']);
            $vip_get_condition_val = $_POST['vip_get_condition_val']+0;
            $vip_get_condition_desc = trim($_POST['vip_get_condition_desc']);
            $vip_is_get_code = $_POST['vip_is_get_code']+0;
            $brief_intro = trim($_POST['brief_intro']);
            $gift_weight = $_POST['gift_weight']+0;
            $start_time = date('Y-m-d H:i:s',strtotime($_POST['start_time']));
            $end_time = date('Y-m-d H:i:s',strtotime($_POST['end_time']));
            $gift_status = $_POST['gift_status']+0;
            if(!$brief_intro || !$gift_title || !$vip_get_condition || !$vip_get_condition_desc || !$_POST['item_title'][0] ){
                $this->error('信息填写不完整');
            }

            $updateInfo = array(
                'gift_title'=>$gift_title,
                'vip_get_condition'=>$vip_get_condition,
                'vip_get_condition_val'=>$vip_get_condition_val,
                'vip_get_condition_desc'=>$vip_get_condition_desc,
                'vip_is_get_code'=>$vip_is_get_code,
                'brief_intro'=>$brief_intro,
                'gift_weight'=>$gift_weight,
                'start_time'=>$start_time,
                'end_time'=>$end_time,
                'gift_status'=>$gift_status,
            );
            $item_arr = array();
            foreach($_POST['item_title'] as $k=>$v){
                $icon = $_POST['item_icon'][$k];
                $title = $v;
                $num = $_POST['item_num'][$k]+0;
                $suffix = strrchr($icon,'.');
                if($suffix != '.png'){
                    $this->error("请上传PNG图片");
                }
                if(!$icon || !$title || !$num || $suffix != '.png'){
                    $this->error("礼包道具信息不完整");
                }
                $to_dir =  rtrim(_ROOT_DIR_,'/').'/admin/Resources/vipGift/'.$app_id.'/';
                if(!file_exists($to_dir)) {
                    mkdir($to_dir, 0777, true);
                }
                $img_id = $gift_id*20+$k+1;
                $newFileName = 'icon_'.$img_id.$suffix;
                $relativePath = 'Resources/vipGift/'.$app_id.'/'.$newFileName;
                #echo $icon;exit;
                if($icon != $relativePath){
                    $res = copy($icon,$to_dir.$newFileName);
                    if(!$res){
                        $this->error("拷贝图片文件出错");
                    }
                }
                $itemInfo = array(
                    'title'=>$title,
                    'num'=>$num,
                    'icon'=>$relativePath
                );
                $item_arr[] = $itemInfo;
            }

            $updateInfo['vip_gift_content'] = addslashes(json_encode($item_arr));
            $res = Admin_GiftModel::updateGift($updateInfo,$gift_id);
            #var_dump($res);exit;
            $res = (new Service_Picture())->syncPicture();
            $this->success('操作成功','/index.php?m=game_manage&c=gift&a=updateGift&gift_id='.$gift_id);exit;
        }
        $this->assign("menu", "game/menu.tpl");
        $this->assign("title", '修改VIP礼包');
        $this->render("/game_manage/gift/updateVipGift.tpl","main.tpl");
    }

    //修改积分礼包
    private function updatePointGift($point_gift_auto_send){
        if($_POST){
            $gift_title = trim($_POST['gift_title']);
            $total = @$_POST['total']+0;
            $brief_intro = trim($_POST['brief_intro']);
            $gift_weight = $_POST['gift_weight']+0;
            $start_time = date('Y-m-d H:i:s',strtotime($_POST['start_time']));
            $end_time = date('Y-m-d H:i:s',strtotime($_POST['end_time']));
            $gift_status = $_POST['gift_status']+0;
            $gift_id = $_GET['gift_id']+0;
            $point = $_POST['point']+0;

            if(!$brief_intro || !$gift_title ){
                $this->error('信息填写不完整');
            }

            if ($point_gift_auto_send == 0 && @$_FILES['card_file']['error'] != 4 && $_FILES['card_file']['type'] != 'text/plain') {
                $this->error('请上传txt格式的文件');
            }

            $updateInfo = array(
                'brief_intro'=>$brief_intro,
                'gift_weight'=>$gift_weight,
                'start_time'=>$start_time,
                'end_time'=>$end_time,
                'gift_status'=>$gift_status,
                'gift_title'=>$gift_title,
                'point'=>$point,
            );
            if($point_gift_auto_send == 1){
                $updateInfo['total'] = $total;
            }
            //修改
            _load("Admin_GiftModel");
            $res = Admin_GiftModel::updateGift($updateInfo, $gift_id);
            if ($res === false) {
                $this->error('操作失败');
            }
            if (@$_FILES['card_file']['error'] != 4 && $point_gift_auto_send == 0) {
                $gift_info = Admin_GiftModel::readGiftByGiftId($gift_id);
                //导入礼包码
                $this->importGiftCard($gift_id, $gift_info['game_id']);
                //刷新数据库总礼包码数量
                _load("Service_Gift");
                $giftService = new Service_Gift();
                //刷新总礼包码数量和清除redis缓存
                $giftService->refreshCardCount($gift_id);
            }
            $this->success('操作成功','/index.php?m=game_manage&c=gift&a=updateGift&gift_id='.$gift_id);exit;
        }
        $this->assign("menu", "game/menu.tpl");
        $this->assign("title", '修改积分礼包');
        $this->render("/game_manage/gift/updatePointGift.tpl","main.tpl");
    }

    //修改普通礼包
    private function updateNormalGift($get_type){
        if($_POST){
            switch($get_type){
                case 'normal':
                    $this->doUpdateNormalGift();
                    break;
                case 'union_code':
                    $this->doUpdateUnionCodeGift();
                    break;
                case 'qq_group_num':
                    $this->doUpdateQQGroupNumGift();
                    break;
                default:
                    $this->error('礼包类型出错');
            }
        }
        $this->assign("menu", "game/menu.tpl");
        $this->assign("title", '修改普通礼包');
        $this->render("/game_manage/gift/updateNormalGift.tpl","main.tpl");
    }
    //执行修改普通礼包的操作
    private function doUpdateNormalGift()
    {
        $gift_id = $_GET['gift_id'] + 0;
        $brief_intro = trim($_POST['brief_intro']);
        $gift_weight = $_POST['gift_weight'] + 0;
        $start_time = date('Y-m-d H:i:s', strtotime($_POST['start_time']));
        $end_time = date('Y-m-d H:i:s', strtotime($_POST['end_time']));
        $gift_status = $_POST['gift_status'] + 0;
        $gift_title = trim($_POST['gift_title']);

        if (!$brief_intro || !$gift_title) {
            $this->error('信息填写不完整');
        }
        if ($_FILES['card_file']['error'] != 4 && $_FILES['card_file']['type'] != 'text/plain') {
            $this->error('请上传txt格式的文件');
        }

        $updateInfo = array(
            'brief_intro' => $brief_intro,
            'gift_weight' => $gift_weight,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'gift_status' => $gift_status,
            'gift_title' => $gift_title,
        );
        //修改
        _load("Admin_GiftModel");
        $res = Admin_GiftModel::updateGift($updateInfo, $gift_id);
        if ($res === false) {
            $this->error('操作失败');
        }
        if ($_FILES['card_file']['error'] != 4) {
            $gift_info = Admin_GiftModel::readGiftByGiftId($gift_id);
            //导入礼包码
            $this->importGiftCard($gift_id, $gift_info['game_id']);
            //刷新数据库总礼包码数量
            _load("Service_Gift");
            $giftService = new Service_Gift();
            //刷新总礼包码数量和清除redis缓存
            $giftService->refreshCardCount($gift_id);
        }
        $this->success('操作成功','/index.php?m=game_manage&c=gift&a=updateGift&gift_id='.$gift_id);exit;

    }

    //执行修改统一码礼包的操作
    private function doUpdateUnionCodeGift(){
        $brief_intro = trim($_POST['brief_intro']);
        $gift_weight = $_POST['gift_weight']+0;
        $union_code = trim($_POST['union_code']);
        $start_time = date('Y-m-d H:i:s',strtotime($_POST['start_time']));
        $end_time = date('Y-m-d H:i:s',strtotime($_POST['end_time']));
        $gift_status = $_POST['gift_status']+0;
        $gift_title = trim($_POST['gift_title']);
        $gift_id = $_GET['gift_id']+0;
        if(!$brief_intro || !$gift_title || !$union_code ){
            $this->error('信息填写不完整');
        }
        $updateInfo = array(
            'brief_intro'=>$brief_intro,
            'gift_weight'=>$gift_weight,
            'start_time'=>$start_time,
            'union_code'=>$union_code,
            'end_time'=>$end_time,
            'gift_status'=>$gift_status,
            'gift_title'=>$gift_title,
        );
        //修改
        _load("Admin_GiftModel");
        $res = Admin_GiftModel::updateGift($updateInfo, $gift_id);
        if ($res === false) {
            $this->error('操作失败');
        }
        $this->success('操作成功','/index.php?m=game_manage&c=gift&a=updateGift&gift_id='.$gift_id);exit;
    }

    //执行修改QQ群礼包操作
    private function doUpdateQQGroupNumGift(){
        $brief_intro = trim($_POST['brief_intro']);
        $gift_weight = $_POST['gift_weight']+0;
        $qq_group_link = trim($_POST['qq_group_link']);
        $qq_group_num = $_POST['qq_group_num']+0;
        $start_time = date('Y-m-d H:i:s',strtotime($_POST['start_time']));
        $end_time = date('Y-m-d H:i:s',strtotime($_POST['end_time']));
        $gift_status = $_POST['gift_status']+0;
        $gift_title = trim($_POST['gift_title']);
        $gift_id = $_GET['gift_id']+0;
        if(!$brief_intro || !$gift_title || !$qq_group_link || !$qq_group_num ){
            $this->error('信息填写不完整');
        }
        $updateInfo = array(
            'brief_intro'=>$brief_intro,
            'gift_weight'=>$gift_weight,
            'start_time'=>$start_time,
            'qq_group_link'=>$qq_group_link,
            'qq_group_num'=>$qq_group_num,
            'end_time'=>$end_time,
            'gift_status'=>$gift_status,
            'gift_title'=>$gift_title,
        );

        //修改
        _load("Admin_GiftModel");
        $res = Admin_GiftModel::updateGift($updateInfo, $gift_id);
        if ($res === false) {
            $this->error('操作失败');
        }
        $this->success('操作成功','/index.php?m=game_manage&c=gift&a=updateGift&gift_id='.$gift_id);exit;
    }

    //新建礼包
    public function addGiftAction(){
        $type = trim($_GET['type']);
        $game_id = $_GET['game_id']+0;
        if(!$game_id || !in_array($type,array('point','vip','normal'))){$this->error('页面未找到','index.php?m=game_manage&c=game');}
        _load( "Admin_GameModel");
        $game_info = Admin_GameModel::readGameById($game_id);
        if(!$game_info){$this->error('页面未找到','index.php?m=game_manage&c=game');}
        $this->assign("sub_title","&nbsp;&nbsp;游戏APPID:{$game_info['appid']}&nbsp;{$game_info['game_name']}");
        $this->assign('game_info',$game_info);
        $navs = array(
            array("url"=>"/index.php?m=game_manage&c=game","title"=>"游戏管理"),
            array("url"=>"/index.php?m=game_manage&c=gift&a=index&game_id=".$game_id,"title"=>"礼包管理"),
        );
        $this->assign('navs',$navs);
        $this->assign('navs_tpl','/navs.tpl');
        switch($type){
            case 'normal':
                $this->addNormalGift();
            break;
            case 'vip':
                $this->addVipGift($game_info['appid']);
            break;
            case 'point':
                $this->addPointGift();
            break;
        }
    }

    //添加vip礼包
    private function addVipGift($app_id){
        if($_POST){
            $gift_title = trim($_POST['gift_title']);
            $vip_get_condition = trim($_POST['vip_get_condition']);
            $vip_get_condition_val = $_POST['vip_get_condition_val']+0;
            $vip_get_condition_desc = trim($_POST['vip_get_condition_desc']);
            $vip_is_get_code = $_POST['vip_is_get_code']+0;
            $brief_intro = trim($_POST['brief_intro']);
            $gift_weight = $_POST['gift_weight']+0;
            $start_time = date('Y-m-d H:i:s',strtotime($_POST['start_time']));
            $end_time = date('Y-m-d H:i:s',strtotime($_POST['end_time']));
            $gift_status = $_POST['gift_status']+0;
            $game_id = $_GET['game_id']+0;
            #echo '<pre>';
            #print_r($_POST);
            if(!$brief_intro || !$gift_title || !$vip_get_condition || !$vip_get_condition_desc || !$_POST['item_title'][0] ){
                $this->error('信息填写不完整');
            }

            $info = array(
                'gift_title'=>$gift_title,
                'vip_get_condition'=>$vip_get_condition,
                'vip_get_condition_val'=>$vip_get_condition_val,
                'vip_get_condition_desc'=>$vip_get_condition_desc,
                'vip_is_get_code'=>$vip_is_get_code,
                'brief_intro'=>$brief_intro,
                'gift_weight'=>$gift_weight,
                'start_time'=>$start_time,
                'end_time'=>$end_time,
                'gift_status'=>$gift_status,
                'game_id'=>$game_id,
                'get_type'=>'vip',
            );

            Doris\DDB::db()->gift()->insert($info);
            $gift_id = Doris\DDB::db()->gift()->insert_id();
            if(!$gift_id){
                $this->error("添加数据库错误!");
            }

            $item_arr = array();
            #print_r($_POST);
            foreach($_POST['item_title'] as $k=>$v){
                $icon = $_POST['item_icon'][$k];
                $title = $v;
                $num = $_POST['item_num'][$k]+0;
                $suffix = strrchr($icon,'.');
                if(!$icon || !$title || !$num || $suffix != '.png'){
                    continue;
                }
                $to_dir =  rtrim(_ROOT_DIR_,'/').'/admin/Resources/vipGift/'.$app_id.'/';
                if(!file_exists($to_dir)) {
                    mkdir($to_dir, 0777, true);
                }
                $img_id = $gift_id*20+$k+1;
                $newFileName = 'icon_'.$img_id.$suffix;
                $res = copy($icon,$to_dir.$newFileName);
                if(!$res){
                    continue;
                }
                $itemInfo = array(
                    'title'=>$title,
                    'num'=>$num,
                    'icon'=>'Resources/vipGift/'.$app_id.'/'.$newFileName
                );
                $item_arr[] = $itemInfo;
            }
            if($item_arr){
                _load( "Admin_GiftModel");
                $update = array(
                    'vip_gift_content'=>addslashes(json_encode($item_arr))
                );
                Admin_GiftModel::updateGift($update,$gift_id);
            }
            $res = (new Service_Picture())->syncPicture();
            $this->success('操作成功','/index.php?m=game_manage&c=gift&a=index&game_id='.$_GET['game_id']);exit;
        }
        $this->assign("menu", "game/menu.tpl");
        $this->assign("title", '新建VIP礼包');
        $this->render("/game_manage/gift/addVipGift.tpl","main.tpl");
    }
    //添加积分礼包
    private function addPointGift(){
        if($_POST){
            $gift_title = trim($_POST['gift_title']);
            $point_gift_auto_send = $_POST['point_gift_auto_send']+0;
            $total = @$_POST['total']+0;
            $brief_intro = trim($_POST['brief_intro']);
            $gift_weight = $_POST['gift_weight']+0;
            $start_time = date('Y-m-d H:i:s',strtotime($_POST['start_time']));
            $end_time = date('Y-m-d H:i:s',strtotime($_POST['end_time']));
            $gift_status = $_POST['gift_status']+0;
            $game_id = $_GET['game_id']+0;
            $point = $_POST['point']+0;

            if(!$brief_intro || !$gift_title ){
                $this->error('信息填写不完整');
            }

            if ($point_gift_auto_send == 0 && $_FILES['card_file']['error'] != 4 && $_FILES['card_file']['type'] != 'text/plain') {
                $this->error('请上传txt格式的文件');
            }

            $info = array(
                'brief_intro'=>$brief_intro,
                'gift_weight'=>$gift_weight,
                'start_time'=>$start_time,
                'end_time'=>$end_time,
                'gift_status'=>$gift_status,
                'gift_title'=>$gift_title,
                'game_id'=>$game_id,
                'get_type'=>'point',
                'point_gift_auto_send'=>$point_gift_auto_send,
                'point'=>$point,
            );
            if($point_gift_auto_send == 1){
                $info['total'] = $total;
            }
            $insert_id = Doris\DDB::db()->gift()->insert($info);
            if(!$insert_id){
                $this->error('操作失败');
            }
            if ($_FILES['card_file']['error'] != 4 && $point_gift_auto_send == 0) {
                //导入礼包码
                $this->importGiftCard($insert_id, $game_id);
                //刷新数据库总礼包码数量
                _load("Service_Gift");
                $giftService = new Service_Gift();
                //刷新总礼包码数量和清除redis缓存
                $giftService->refreshCardCount($insert_id);
            }
            $this->success('操作成功','/index.php?m=game_manage&c=gift&a=index&game_id='.$_GET['game_id']);exit;

        }
        $this->assign("menu", "game/menu.tpl");
        $this->assign("title", '新建积分礼包');
        $this->render("/game_manage/gift/addPointGift.tpl","main.tpl");
    }
    
    //增加普通礼包
    private function addNormalGift(){
        if($_POST){
            $get_type = $_POST['get_type'];
            switch($get_type){
                case 'normal':
                    $this->doAddNormalGift();
                break;
                case 'union_code':
                    $this->doAddUnionCodeGift();
                break;
                case 'qq_group_num':
                    $this->doAddQQGroupNumGift();
                break;
                default:
                    $this->error('礼包类型出错');
            }
        }
        $this->assign("menu", "game/menu.tpl");
        $this->assign("title", '新建普通礼包');
        $this->render("/game_manage/gift/addNormalGift.tpl","main.tpl");
    }
    
    //执行添加普通礼包操作
    private function doAddNormalGift(){
        $brief_intro = trim($_POST['brief_intro']);
        $gift_weight = $_POST['gift_weight']+0;
        $start_time = date('Y-m-d H:i:s',strtotime($_POST['start_time']));
        $end_time = date('Y-m-d H:i:s',strtotime($_POST['end_time']));
        $gift_status = $_POST['gift_status']+0;
        $gift_title = trim($_POST['gift_title']);
        $game_id = $_GET['game_id']+0;
        
        if(!$brief_intro || !$gift_title ){
            $this->error('信息填写不完整');  
        }

        if ($_FILES['card_file']['error'] != 4 && $_FILES['card_file']['type'] != 'text/plain') {
            $this->error('请上传txt格式的文件');
        }
        
        $info = array(
            'brief_intro'=>$brief_intro,  
            'gift_weight'=>$gift_weight,  
            'start_time'=>$start_time,  
            'end_time'=>$end_time,  
            'gift_status'=>$gift_status,  
            'gift_title'=>$gift_title,  
            'game_id'=>$game_id, 
            'get_type'=>'normal',
        );
        $insert_id = Doris\DDB::db()->gift()->insert($info);
        if(!$insert_id){
            $this->error('操作失败');
        }
        if ($_FILES['card_file']['error'] != 4) {
            //导入礼包码
            $this->importGiftCard($insert_id, $game_id);
        }
        //刷新数据库总礼包码数量
        _load("Service_Gift");
        $giftService = new Service_Gift();
        //刷新总礼包码数量和清除redis缓存
        $giftService->refreshCardCount($insert_id);
        $this->success('操作成功','/index.php?m=game_manage&c=gift&a=index&game_id='.$_GET['game_id']);exit;

    }

    //执行添加统一码礼包操作
    private function doAddUnionCodeGift(){
        $brief_intro = trim($_POST['brief_intro']);
        $gift_weight = $_POST['gift_weight']+0;
        $union_code = trim($_POST['union_code']);
        $start_time = date('Y-m-d H:i:s',strtotime($_POST['start_time']));
        $end_time = date('Y-m-d H:i:s',strtotime($_POST['end_time']));
        $gift_status = $_POST['gift_status']+0;
        $gift_title = trim($_POST['gift_title']);
        $game_id = $_GET['game_id']+0;
        if(!$brief_intro || !$gift_title || !$union_code ){
            $this->error('信息填写不完整');
        }
        $info = array(
            'brief_intro'=>$brief_intro,
            'gift_weight'=>$gift_weight,
            'start_time'=>$start_time,
            'union_code'=>$union_code,
            'end_time'=>$end_time,
            'gift_status'=>$gift_status,
            'gift_title'=>$gift_title,
            'game_id'=>$game_id,
            'get_type'=>'union_code',
        );
        $insert_id = Doris\DDB::db()->gift()->insert($info);
        if(!$insert_id){
            $this->error('操作失败');
        }
        $this->success('操作成功','/index.php?m=game_manage&c=gift&a=index&game_id='.$_GET['game_id']);exit;
    }

    //执行添加QQ群礼包操作
    private function doAddQQGroupNumGift(){
        $brief_intro = trim($_POST['brief_intro']);
        $gift_weight = $_POST['gift_weight']+0;
        $qq_group_link = trim($_POST['qq_group_link']);
        $qq_group_num = $_POST['qq_group_num']+0;
        $start_time = date('Y-m-d H:i:s',strtotime($_POST['start_time']));
        $end_time = date('Y-m-d H:i:s',strtotime($_POST['end_time']));
        $gift_status = $_POST['gift_status']+0;
        $gift_title = trim($_POST['gift_title']);
        $game_id = $_GET['game_id']+0;
        if(!$brief_intro || !$gift_title || !$qq_group_link || !$qq_group_num ){
            $this->error('信息填写不完整');
        }
        $info = array(
            'brief_intro'=>$brief_intro,
            'gift_weight'=>$gift_weight,
            'start_time'=>$start_time,
            'qq_group_link'=>$qq_group_link,
            'qq_group_num'=>$qq_group_num,
            'end_time'=>$end_time,
            'gift_status'=>$gift_status,
            'gift_title'=>$gift_title,
            'game_id'=>$game_id,
            'get_type'=>'qq_group_num',
        );
        $insert_id = Doris\DDB::db()->gift()->insert($info);
        if(!$insert_id){
            $this->error('操作失败');
        }
        $this->success('操作成功','/index.php?m=game_manage&c=gift&a=index&game_id='.$_GET['game_id']);exit;
    }

    
    //导礼包码入库
    private function importGiftCard($gift_id,$game_id){
        if($_FILES['card_file']['error'] != 0){
            $this->error('上传文件出现错误,请联系开发.');
        }
        if ($_FILES['card_file']['type'] != 'text/plain') {
            $this->error('请上传txt格式的文件');  
        }
        $file = fopen($_FILES['card_file']['tmp_name'], "r");
        $i = 0;
        $fields=array();
        $time = time();
        while(!feof($file)){
            $value = fgets($file);
            if (!trim($value)) continue;
            $value == addslashes($value);
            //卡号导入数据库
            $value = mb_convert_encoding($value, "utf-8", "gb2312");
            $value = trim($value);
            $fields[$i]['card_no']=$value;
            $fields[$i]['gift_id']=$gift_id;
            $fields[$i]['card_status']=1;
            $fields[$i]['create_time'] = $time;
            $fields[$i]['user_id'] = 0;
            $fields[$i]['game_id'] = $game_id;
            $fields[$i]['is_used']=0;
          
            $i++;
            if ($i == 1000) {
                $res = Doris\DDB::insertAll("gift_card",$fields);
                if (!$res) {
                    $this->error("礼包码导入失败，请联系开发.");
                }
                $i = 0;
                $fields=array();
            }

        }
        if ($i != 0){
            $res = Doris\DDB::insertAll("gift_card",$fields);
            if (!$res) {
                $this->error("礼包码导入失败，请联系开发.");
            }
        }
    }



}