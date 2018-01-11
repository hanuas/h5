<?php 
use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Validate;


class systemController extends commonController {
// 	
// 	public function __construct(){
// 		parent::__construct();
// 		
// 	}
	public function indexAction(){
		$this->assign("menu", "index/sys_menu");
		$this->assign("js", "admin/system_config.js");
		$this->assign("title", _lan('SystemVariables','系统变量')	);
		$this->assign("js_para", $this->get_segment_list());
		//_admin()->display("common_list");
		$this->render(false,"/common_list.tpl");
	}
	public function index_ajaxAction(){
		$db = Doris\DApp::loadDT();
		//cache_f("datableEdituser_POST","test",var_export($_POST,true));
		//cache_f("datableEdituser_DB","test",var_export($db,true));
		//	global  $db;
		// Build our Editor instance and process the data coming from _POST
		ob_start();
		Editor::inst( $db, 'sys_config' )
			->fields(
			//Field::inst( 'id' )->set(false),
			Field::inst( 'segment' ),
			Field::inst( 'name' ),
			Field::inst( 'value' )
				->setFormatter( function ($val, $data, $field) {
					
					switch (@$data['type']) {
						case "int":		if(is_int(  $val*1))				return $val*1;	return 0;
						case "float":	if(is_float($val*1)||is_int($val*1))return $val*1;	return 0;
						case "json":	{
							$json=json_decode($val);
							if($json!==null)return $val;	return '{}';
						}
						default:return $val;
					}	
				} )
			,
			Field::inst( 'type' )
			
			
			
			)
			->process( $_POST )
			->json();
		$content  = ob_get_contents();
	    ob_end_clean();
	
	    echo $content;
	}
	
	public function segment_list_ajaxAction(){
		echo $this->get_segment_list(); 
	}
	public function get_segment_list(){
		$r=array();
		$rs = Doris\DDB::pdoSlave()->query('select segment from sys_config group by segment',PDO::FETCH_ASSOC);
		while($row = $rs->fetch()){
			$r[]=$row['segment'];
		}
		return json_encode($r);
	}
    
}