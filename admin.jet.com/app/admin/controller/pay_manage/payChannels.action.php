<?php
    	
use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Mjoin,
DataTables\Editor\Validate;



/**
 * @Description 充值渠道管理
 * 
 */


class payChannelsController extends commonController{
	public function indexAction(){
		$this->assign("js", "pay_manage/pay_channels_list.js");	
		#$this->assign("js_privilege", json_encode(array("privilege_code"=> 4 )));
		$this->assign("title", _lan('BackgroundUserManagement','充值渠道管理'));
		$this->render(false,"common_list.tpl");
	}
	
	public function index_ajaxAction(){
		$db = Doris\DApp::loadDT();
 		$out = Editor::inst( $db, 'pay_channel' ,"channel_id" )
		->fields(
			Field::inst( 'channel_id' ),
			Field::inst( 'channel_name' ),
			Field::inst( 'channel_intro' ),
			Field::inst( 'channel_seque' ),
			Field::inst( 'is_enable' ),
			Field::inst( 'channel_tips' )
		)->process($_POST)
        ->data(); 
     
	    echo json_encode($out);  
	}
}