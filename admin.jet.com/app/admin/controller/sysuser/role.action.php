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

class roleController extends commonController{

	public function indexAction(){

		$this->assign("menu", "sysuser/menu");
		$this->assign("js", "sysuser/role_list.js");
		$this->assign("title", '角色管理');
		//$this->render(_TEMPLATE_DIR_."index/index/index.tpl","main.tpl");exit;
		$this->render(false,"/common_list.tpl");
	}

    public function index_ajaxAction(){
    	$db = Doris\DApp::loadDT();
    	
    	//TODO: 创建新条目时有个警告，暂时屏蔽了。可能是升级Editor所致
    	ini_set("display_errors", 0);
    	
    	$editor = Editor::inst( $db, 'sys_role' )
    	->field(
    			Field::inst( 'role_name' )->validator( 'Validate::required' ),
    			Field::inst( 'id' )
    	)->join(
    			Join::inst( 'sys_privilege', 'array' )
    			->join(
    					array( 'id', 'role_id' ),
    					array( 'id', 'privilege_id' ),
    					'sys_role_privilege'
    			)
    			->field(
    					Field::inst( 'privilege_name' ),
    					Field::inst( 'id' )
    			)
    	
    	);
    	
    	// The "process" method will handle data get, create, edit and delete
    	// requests from the client
    	$out = $editor->process($_POST)->data();
    	if ( !isset($_POST['action']) ) {
    		// Get department details
    		$out['sys_privilege'] =  $db->select( 'sys_privilege', 'id as value, privilege_name as label' )
    		->fetchAll();
    	}
    	//cache_f("datableEdituser_asks_list_ajax","test",var_export($out,true));
    	echo json_encode($out);
    	
    	
    }

    /**
    *@Description 给角色关联权限
    *
    */
    public function relatedPrivilegeAction(){
        $role_id = $_GET['id'];
        $form_action="?m=sysuser&c=role&a=relatedPrivilege&id=".$role_id;
        $default = $this->getDefaultPrivilege($role_id);
        $roleModel = Doris\DApp::newClass( "Admin_RoleModel");//_model_ex('Role','/privilege/');
        
        //var_dump( $roleModel );
        $roleInfo = $roleModel->readRoleById($role_id);
        if(!$roleInfo){
            $this->error('未找到相关页面','index.php');
        }
        if($_POST){
            if(!$role_id){
                $this->error("参数不全");
                return false;
            }
            $arrIds=explode(",", $_POST['the_sels']);
            $strSearchIds="";
            $roleModel->delRoleById($role_id);
            foreach ($arrIds as $privilege_id) {
                $info=array(
                        "role_id"=>$role_id,
                        "privilege_id"=>$privilege_id
                );
                $ret=Doris\DDB::db()->sys_role_privilege()->insert($info);
            }
            $this->success('操作成功',-1);
            exit;
        }
        $this->privilegeListAction($default , "选择相关权限 &nbsp;<small>角色名:{$roleInfo['role_name']}</small>" , $form_action);


    }
    /**
    *@Description 获取角色原有的权限。
    */

    private function getDefaultPrivilege($role_id){
    	$arrDef=array();
    	$privileges=Doris\DDB::pdoSlave()->query("select role_name,privilege_name,the_group,authtype,p.id as privilegeid from sys_role as r left join sys_role_privilege as rp on r.id = rp.role_id left join sys_privilege as p on p.id = rp.privilege_id where r.id={$role_id}")->fetchAll(PDO::FETCH_ASSOC);
    	foreach ($privileges as $value) {
            if(!$value['privilegeid']){continue;}
    		$arrDef[]=$value["privilegeid"];
    	}
    	$default=implode(",", $arrDef);
    	return $default; 
   }

   private function privilegeListAction($default ,$title, $form_action){
    	////_load_class("FormBS");
		$this->assign("js_para", $this->get_segment_list());
    	$this->assign("js", "sysuser/privilege_list_sel.js");
    	$this->assign("title", $title);
    	$this->assign("form_action", $form_action);//
    	$this->assign("js_para2", '{default:"'.$default.'"}');
        //$this->assign("menu","sysuser/menu");
    	$this->render("privilege_list.tpl");
   }

    public function privilege_list_ajaxAction() {
//     	$default=isset($_GET["default"])? $_GET["default"]:"";
//     	_model("DTTool")->dealRead( array( 'id', 'name','intro', 'type' ), "place","id",
//     		array("type"=>"check",	"data"=>array("name"=>"ids[]","text"=>"请选择","default"=>$default )	)
//     	);
    	
    	$db = Doris\DApp::loadDT();
    	$content=Editor::inst($db, 'sys_privilege')
    	->fields(
    		Field::inst( 'id' ),
    		Field::inst( 'the_group' ),
    		Field::inst( 'authtype' ),
    		Field::inst( 'privilege_name' )
        )
    	->process($_POST)
    	->data();
    	echo json_encode($content);
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