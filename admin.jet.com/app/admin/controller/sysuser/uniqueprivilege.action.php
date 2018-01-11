<?php
use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Validate;

/**
 * @Description 角色管理
 * 
 */

class uniqueprivilegeController extends commonController{
	public function pubChannelAction(){
		_admin_assign($this,"menu", "sysuser/menu");
        _admin_assign($this,'second_menu','sysuser/uniquePrivilegeMenu');
		_admin_assign($this,"js", "pubchannel_privilege.js");
		_admin_assign($this,"title", _lan('SpecialPrivilege','特有权限管理'));
		_admin()->display("common_list");
	}

    public function pubChannel_ajaxAction(){
    	_loadDatables();
    	$editor = Editor::inst( $db, 'lmb_pub_channel' )
    	->field(
    			Field::inst( 'lmb_pub_channel.id' ),
    			Field::inst( 'lmb_pub_channel.name' ),
    			Field::inst( 'lmb_pub_channel.code' ),
    			Field::inst( 'lmb_game.game_num' ),
    			Field::inst( 'lmb_game.backend_name' )
    	)
        ->leftJoin('lmb_game','lmb_game.id','=','lmb_pub_channel.game_id');
    	
    	$out = $editor->process($_POST)->data();
        //print_r($out);
    	if ( !isset($_POST['action']) ) {
            foreach($out['data'] as $k=>$v){
                $channel_code = $v['lmb_pub_channel']['code'];
                $out['data'][$k]['lmb_pub_channel']['userlist'] = '';
                $limits = (new Admin_UserLimitModel())->readUserLimit('pub_channel_limit',$channel_code);
                foreach($limits as $value){
                    $out['data'][$k]['lmb_pub_channel']['userlist'] .= $value['username'].',';
                }
                 $out['data'][$k]['lmb_pub_channel']['userlist']= trim($out['data'][$k]['lmb_pub_channel']['userlist'],',');
            }
    	}
    	//cache_f("datableEdituser_asks_list_ajax","test",var_export($out,true));
        //print_r($out);
    	echo json_encode($out);
    	
    	
    }

    /**
    *@Description 给渠道关联用户
    *
    */
    public function relatedChannelPeopleAction(){
        $pubChannelId = $_GET['id'];
        $form_action="?m=sysuser&c=uniquePrivilege&a=relatedChannelPeople&id=".$pubChannelId;
        $channelInfo = _pdo_slave()->query("select * from lmb_pub_channel where id = {$pubChannelId}")->fetch(PDO::FETCH_ASSOC);
        if(!$channelInfo){
            _admin()->error('未找到相关页面','index.php');
        }
        $default = $this->getChannelDefaultUser($channelInfo['code']);
        if($_POST){
            if(!$pubChannelId){
                _admin()->error("参数不全");
                return false;
            }
            $arrIds=explode(",", $_POST['the_sels']);
            $strSearchIds="";
            _pdo_slave()->query("delete from lmb_user_limit where value={$channelInfo['code']} and module = 'pub_channel_limit'");
            foreach ($arrIds as $user_id) {
                $info=array(
                        "uid"=>$user_id,
                        "module"=>'pub_channel_limit',
                        "value"=>$channelInfo['code']
                );
                $ret=_db_m0()->lmb_user_limit()->insert($info);
            }
            _admin()->success('操作成功','index.php?m=sysuser&c=uniquePrivilege&a=pubChannel');
            exit;
        }
        _admin()->userList($default , "选择相关用户 &nbsp;<small>渠道包名:{$channelInfo['name']},Code:{$channelInfo['code']}</small>" , $form_action);


    }
    /**
    *@Description 获取角色原有的权限。
    */

    private function getChannelDefaultUser($channel_code){
    	$arrDef=array();
    	$users = (new Admin_UserLimitModel())->readUserLimit('pub_channel_limit',$channel_code);

    	foreach ($users as $value) {
    		$arrDef[]=$value["user_id"];
    	}
    	$default=implode(",", $arrDef);
    	return $default; 
   }

   private function userListAction($default ,$title, $form_action){
    	//_load_class("FormBS");
		_admin_assign($this,"js_para",json_encode(array('id'=>$_GET['id'])));
    	_admin_assign($this,"js", "user_list_sel.js");
    	_admin_assign($this,"title", $title);
    	_admin_assign($this,"form_action", $form_action);//
    	_admin_assign($this,"js_para2", '{default:"'.$default.'"}');
        _admin_assign($this,"menu","sysuser/menu");
    	_admin()->display("sysuser/user_list");
   }

    public function user_list_ajaxAction() {
        $pubChannel = (new Admin_PubChannelModel())->readPubChannelById($_GET['id']);
        $gameInfo = (new Admin_GameModel())->readGameById($pubChannel['game_id']);
        $users = (new Admin_AdminModel())->getAdminByAreaAndRole($gameInfo['area_id'],'渠道管理');
        $userIds = '';
        foreach($users as $v){
            $userIds .= $v['id'].',';
        }
        $userIds = trim($userIds,',');
        $userIds = $userIds?$userIds:0;
        //echo $userIds;
    	_loadDatables();
    	$content=Editor::inst($db, 'lmb_user')
    	->fields(
    		Field::inst( 'id' ),
    		Field::inst( 'username' ),
    		Field::inst( 'email' ),
    		Field::inst( 'nick_name' ),
    		Field::inst( 'phone' )
        )->where("id in ({$userIds}) and id",'X','!=')
    	->process($_POST)
    	->data();
    	echo json_encode($content);
    }



}