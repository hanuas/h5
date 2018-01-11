<?php
use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Validate;

/**
 * @Description 操作日志管理
 * 
 */

class action_logController extends commonController {
	public function indexAction(){
		$this->assign("js", "admin/action_log_list.js");		
		$this->assign("menu", "index/sys_menu");
		$this->assign("js_para",json_encode( [ "type_list" => $this->get_type_list()] ) );
		$this->assign("title", _lan('OperationLog','操作日志')	);
		$this->render(false,"common_list.tpl");
	}
	
	public function index_ajaxAction(){
        $db = Doris\DApp::loadDT();
    	$editor = Editor::inst( $db, 'sys_action_log' )
    	->field(
    			Field::inst( 'id' ),
    			Field::inst( 'user_name' ),
    			Field::inst( 'category'),
    			Field::inst( 'type' ),
    			Field::inst( 'title'), 
    			Field::inst( 'time') 
    	);
    	$out = $editor->process($_POST)->data();
    	
    	//cache_f("datableEdituser_asks_list_ajax","test",var_export($out,true));
    	echo json_encode($out);
    
	}

    public function browseLogDetailAction(){
        $logInfo = Doris\DApp::newClass( "Admin_ActionLogModel")->readActionLogById($_GET['id']);
        if(!$logInfo){$this->error('未找到相关数据','?m=action_log');}
        $this->assign("title", '查看操作日志详情' );
        $this->assign('loginfo',$logInfo);
        
		$this->render("browseLogDetail.tpl","main.tpl"); 
        
    }

    public function delLogAction(){
        $issuc = (new Admin_action_logModel())->delLog($_GET['id']);
        $this->success("操作成功",-1);
        
    }

    public function get_type_list(){ 
    
    	
		$sql = "select DISTINCT type, type from sys_action_log" ;
		$results = Doris\DDB::pdo()->query( $sql )->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
		self::simplifyColumnGroup($results ); 
		return $results ;
		 
	}

 

	
}