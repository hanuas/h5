<?php
use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Validate;

/**
 * @Description 权限管理
 * 
 */

class privilegeController extends commonController{
	public function indexAction(){
		$this->assign("menu", "sysuser/menu");
		$this->assign("js", "sysuser/privilege_list.js");
		$this->assign("title", _lan('PrivilegeManagement','权限管理'));
		$this->assign("js_para", $this->get_segment_list());
		$this->render(false,"common_list.tpl");
	}
	
	public function index_ajaxAction(){
	//echo  json_encode($_POST);exit;
	
		if(isset($_POST['action'])){
		
		
			
			foreach($_POST['data'] as $key => &$data){//1.5.0变化，要取第一个元素做行数据
			
				if(($_POST['action'] == 'create' || $_POST['action'] == 'edit') && $data['authtype'] =='mcab'){
					if(!isset($data['branch'])){
						echo '{"id":-1,"error":"","fieldErrors":[{"name":"branch","status":"This field is required"}],"data":[]}';exit;
					}else{
						if(!in_array('read',$data['branch'])){
							$data['branch'][] = 'read';
						}
					}//$_POST['data'][$key]['branch'][] = 'read';
				}
			}//END FOR
			
			
			
		}
		
		$db = Doris\DApp::loadDT();
		$editor = Editor::inst( $db, 'sys_privilege' )    
						->field(
							Field::inst( 'the_group' ),
							Field::inst( 'id' ),
	    					Field::inst( 'privilege_name' )->validator( 'Validate::required' ),
							Field::inst( 'authtype' ),
							Field::inst( 'url' ),
							Field::inst( 'name' ),
							Field::inst( 'm' ),
							Field::inst( 'c' ),
							Field::inst( 'a' ),
							Field::inst('branch')
							->setFormatter(function ($val, $data, $field) {
								$str =  implode(',',$data["branch"]); 
								return $str;}
							)
							->getFormatter(function ($val, $data, $field) {return explode(',',$val);})
						);
		//cache_f("datableEdituser_asks_list_ajax","test",var_export($out,true));
		
		$out = @$editor->process($_POST)->data();
		echo json_encode($out);
    	
	}
	public function get_segment_list(){
		$r=array();
		$rs = Doris\DDB::pdo()->query('select `the_group` from sys_privilege group by  `the_group`',PDO::FETCH_ASSOC);
		while($row = $rs->fetch()){
			$r[]=$row['the_group'];
		}
		return json_encode($r);
	}
	
}