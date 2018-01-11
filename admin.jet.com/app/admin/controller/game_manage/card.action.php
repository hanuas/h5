<?php
    	
use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Mjoin,
DataTables\Editor\Validate;



/**
 * @Description 礼包码管理
 * 
 */


class cardController extends commonController{
	public function indexAction(){
        _load( "Admin_GiftModel");
        $gift_id = @$_GET['gift_id']+0; 
        $gift_info = Admin_GiftModel::readGiftByGiftId($gift_id);
        if(!$gift_info){$this->error('页面未找到','index.php?m=game_manage&c=game');}
        $card_used = array(
            "1"=>array("已领取"),
            "0"=>array("未领取"),
        );
        self::simplifyColumnGroup($order_state);
        
        $this->assign("js_para", json_encode([
            "card_used"=>$card_used,
            "gift_id"=>$gift_id
		]));
        $navs = array(
            array("url"=>"/index.php?m=game_manage&c=game","title"=>"游戏管理"),
            array("url"=>"/index.php?m=game_manage&c=gift&a=index&game_id=".$gift_info['game_id'],"title"=>"礼包管理"),
        );
        $this->assign('navs',$navs);
        $this->assign('navs_tpl','/navs.tpl');
		$this->assign("js", "game_manage/card_list.js");
		$this->assign("js_privilege", json_encode(array("privilege_code"=> 0 )));
		$this->assign("title", _lan('BackgroundUserManagement','礼包码'));
		$this->assign("sub_title", $gift_info['gift_title']);
		$this->render(false,"common_list.tpl");
	}
	
	public function index_ajaxAction(){
		$db = Doris\DApp::loadDT();

 		$editor = Editor::inst( $db, 'gift_card' ,"id" )
		->fields(
			Field::inst( 'id' ),
			Field::inst( 'card_no' ),
			Field::inst( 'card_status' ),
			Field::inst( 'create_time' ),
			Field::inst( 'user_id' ),
			Field::inst( 'role_id' ),
			Field::inst( 'server_id' ),
			Field::inst( 'server_name' ),
			Field::inst( 'role_name' ),
			Field::inst( 'is_used' ),
			Field::inst( 'used_time' ),
			Field::inst( 'card_remark' ),
			Field::inst( 'user_ip' ),
			Field::inst( 'phone' ),
			Field::inst( 'gift_id' ),
			Field::inst( 'vip_gift_send' )
		)->where('gift_id',$_GET['gift_id']);

        $out = $editor->process($_POST)->data();
	
        #print_r($out);exit;
        /*
        _load( "Admin_PrivilegeModel");
        $privilegeModel = new Admin_PrivilegeModel();
        #print_r($out['data']);
        foreach($out['data'] as $k=>$v){
            $out['data'][$k]['operation'] = '';
            if($privilegeModel->checkAuth('game_manage','gift','updateGift')){
                #$out['data'][$k]['operation'] .= '<a href="?m=game_manage&c=game&a=viewGame&id='.$v['gift']['gift_id'].'">查看礼包</a>&nbsp;&nbsp;';
                $out['data'][$k]['operation'] .= '<a href="?m=game_manage&c=gift&a=updateGift&gift_id='.$v['gift']['gift_id'].'">修改礼包</a>&nbsp;&nbsp;';
            }
            if($privilegeModel->checkAuth('game_manage','card','index')){
                if($v['gift']['get_type'] == 'normal' || ($v['gift']['get_type'] == 'point' && $v['gift']['point_gift_auto_send'] == 0 )) {
                    $out['data'][$k]['operation'] .= '<a href="?m=game_manage&c=card&a=index&gift_id=' . $v['gift']['gift_id'] . '">查看礼包码</a>&nbsp;&nbsp;';
                }
            }
        }
        */
	    echo json_encode($out);  
	}




}