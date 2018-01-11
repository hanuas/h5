<?php
    	
use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Mjoin,
DataTables\Editor\Validate;



/**
 * @Description 后台用户管理
 * 
 */

class adminIndexController extends commonController{
	public function indexAction(){
		$this->assign("menu", "sysuser/menu");
		$this->assign("js", "sysuser/admin_user_list.js");
		//$this->assign("js_para", json_encode(array("privilege_code"=>"n")));
		
		$this->assign("title", _lan('BackgroundUserManagement','后台用户管理'));
		$this->render(false,"common_list.tpl");
	}
	
	public function index_ajaxAction(){
		//var_dump($db);
		$db = Doris\DApp::loadDT();
    	$editor = Editor::inst( $db, 'sys_user' )
    	->field(
    			Field::inst( 'user_name' )->validator( 'Validate::required' ),
    			Field::inst( 'id' ),
    			Field::inst( 'email'),
    			Field::inst( 'phone'),
    			Field::inst('leader'),
    			Field::inst('nick_name'),
    			Field::inst( 'user_pwd')->validator( 'Validate::required' )
    			->setFormatter( function ($val, $data, $field) {
    				$userinfo = Doris\DApp::newClass( "Admin_AdminModel")->readAdminByName($data['user_name']);
    				$old_md5_pass=$userinfo['user_pwd'];
    				$new_md5_pass= $val ?md5(md5($val)): $val;
    				if($old_md5_pass==$val){
    					return $val;//密码未做更改
    				}else{
    					return $new_md5_pass;
    				}
    			} 
    			),

    			Field::inst( 'gender')
    	)
    	->join(
    			Join::inst( 'sys_user', 'object' )      // Read from 'users' table
    			->aliasParentTable( 'manager' )  // i.e. FROM users as manager
    			->name( 'manager' )              // JSON / POST field
    			->join( 'id', 'leader' )        // Join parent `id`, to child `manager`
    			->set( false )                   // Used for read-only (change the 'manager' on the parent to change the value)
    			->field(
    					Field::inst( 'manager.user_name', 'user_name' )
    			)
    	)
    	//以下两种JOIN的方法均可(后者为官方新方式，所以采用后者)
    	->join(
			Mjoin::inst( 'sys_role' )
				->link( 'sys_user.id', 'sys_user_role.user_id' )
				->link( 'sys_role.id', 'sys_user_role.role_id' )
				->fields(
					Field::inst( 'id' )
						->validator( 'Validate::required' )
						->options( 'sys_role', 'id', 'role_name' ),
					Field::inst( 'role_name' )
				)
		)
//     	->join(
//     			Join::inst( 'sys_role', 'array' )
//     			->join(
//     					array( 'id', 'user_id' ),
//     					array( 'id', 'role_id' ),
//     					'sys_user_role'
//     			)
//     			->field(
//     					Field::inst( 'role_name' ),
//     					Field::inst( 'id' )
//     			)
//     	
//     	)
    	;
    	
    	// The "process" method will handle data get, create, edit and delete
    	// requests from the client
    	$out = $editor->process($_POST)->data();
    	if ( !isset($_POST['action']) ) {
    		// Get department details
    		$out['sys_role'] =$db->select( 'sys_role', 'id as value, role_name as label' )->fetchAll();
    		$out['userlist']  = Doris\DDB::pdo()->query( 'select id as value,user_name as label from sys_user' )->fetchAll(PDO::FETCH_ASSOC);
    	}
    	//cache_f("datableEdituser_asks_list_ajax","test",var_export($out,true));
    	echo json_encode($out);
	}

    /**
    *@Description 关联地区权限
    *
    */
    public function relatedAreaPrivilegeAction(){
        $user_id = @$_GET['id'];
        $form_action="?m=sysuser&c=index&a=relatedAreaPrivilege&id=".$user_id;
        $userInfo = Doris\DDB::pdo()->query("select * from sys_user where id = {$user_id}")->fetch(PDO::FETCH_ASSOC);
        if(!$userInfo){
            $this->error('未找到相关页面','index.php');
        }
        if($_POST){
            if(!$user_id){
                $this->error("参数不全");
                return false;
            }
            _pdo_m0()->query("update sys_user set area_privilege = '{$_POST['the_sels']}' where id = {$user_id}");
            $this->success('操作成功','index.php?m=sysuser&c=admin');
            exit;
        }
        $this->areaListAction($userInfo['area_privilege'] , "选择地区权限 &nbsp;<small>用户名:{$userInfo['username']}</small>" , $form_action);        
    }
    

    private function areaListAction($default ,$title, $form_action){
        //_load_class("FormBS");
    	$this->assign("js", "area_list_sel.js");
    	$this->assign("title", $title);
    	$this->assign("form_action", $form_action);//
    	$this->assign("js_para", '{default:"'.$default.'"}');
        $this->assign("menu","../sysuser/menu");
    	$this->display("../sysuser/privilege_list");
    
    }

    public function area_list_ajaxAction() {
    	$db = Doris\DApp::loadDT();
    	$content=Editor::inst($db, 'lma_area')
    	->fields(
    		Field::inst( 'id' ),
    		Field::inst( 'name' ),
    		Field::inst( 'db_name' ),
    		Field::inst( 'admin_url' ),
    		Field::inst( 'server_ip' ),
    		Field::inst( 'status' )
        )
    	->process($_POST)
    	->data();
    	echo json_encode($content);
    }

	
}