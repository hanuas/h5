<?php 

use Doris\DApp;

class commonController extends Doris\DAction{
	protected $admin;
	protected $privilege;
	protected $menuConf;
	public function __construct(){ 
		$this->ensureAdmin($_SERVER["REQUEST_URI"]);
	 	$this->privilege = DApp::newClass('Admin_PrivilegeModel',$_SESSION['admin']['id']);//$_SESSION['admin']['id'];
	 
        $this->setWebFrontUrl();
		$editorPrivilegeCode = $this->getEditorPrivilegeCode();
		//标识editor权限code
		$this->assign("js_privilege", json_encode(array("privilege_code"=>$editorPrivilegeCode)));
		$this->admin=$this->ensureAdmin($this->cur_url());
		//检查访问权限
		if(!$this->checkAuth()){
		    if(strtolower(substr(@$_GET['a'],-4)) !='ajax'){
              //  $this->getGoPrivilegeUrl();
              //exit("asd");
 			    $this->error('对不起，您没有权限进行此操作','index.php');
			}
		}
		//检查editor权限
		if(isset($_POST['action'])){
			if(!$this->checkEditorAuth($_POST['action'])){
				echo '{"error":"对不起，您没有权限进行此操作"}';exit;
			}
		}
		$leftMenuTemplate=@$_SESSION['admin']['leftMenuTemplate'];
		if(!$leftMenuTemplate){
			//生成系统菜单树
			$menuClass = DApp::newClass("Admin_Menu");
			$this->menuConf = Doris\DConfig::register("admin.menu.conf.php","admin.menu");
			$allPrivilege = $this->privilege->getAllPrivilege();
			list($leftMenuTemplate,$firstPrivilegedMenu) = $menuClass->generateMenu(
					$this->menuConf,
					$allPrivilege,
					false
				);
			$_SESSION['admin']['leftMenuTemplate'] = $leftMenuTemplate ;
			$_SESSION['admin']['firstPrivilegedMenu'] = $firstPrivilegedMenu ;
		}
		
		$this->assign("mainLeftMenu",$leftMenuTemplate);
		
	}
	
	
	
	public static function simplifyColumnGroup(&$arrGroups){
		if(is_array($arrGroups)) foreach($arrGroups as $id=>&$names ){
		 		$arrGroups[$id] = $names[0];
		 }
	}
	/**
	 * @Description 确认后台用户是否登录
	 * @param string $back_url 跳转地址
	 * @return boolean false or array
	 */
	public function ensureAdmin($back_url=null){
		if( empty($_SESSION['admin'])){
			$this->redirect_with_back("index.php?m=index&c=login",$back_url);
			return false;
		}else{
			return $_SESSION['admin'];
		}
	}
	public function getCurrentUser( ){
		if( empty($_SESSION['admin'])){ 
			return false;
		}else{
			return $_SESSION['admin'];
		}
	}
	public function getCurrentUserId( ){
		if( empty($_SESSION['admin']['id'])){ 
			return false;
		}else{
			return $_SESSION['admin']['id'];
		}
	}
	static function  getChildrenAndMyIds($pids_string ){ 
			if( empty($pids_string)) return "";
			$sql_find = "select GROUP_CONCAT(DISTINCT( id )) id_string  from `sys_user` where `leader` in( $pids_string ) or id in ($pids_string)";
			$id_string = Doris\DDB::fetch($sql_find)['id_string']; 
			if( empty($id_string)) return "";
			
			//Doris\debugWeb([$sql_find,$pids_string,$id_string],false);
			if(   strlen($id_string) != strlen($pids_string) ){ 
				return self::getChildrenAndMyIds($id_string );
			}
			return $id_string;
	} 
		
	/*
    * @Description SESSION存储用户登录信息
    */
    public function sessionUserInfo($userInfo){
        $_SESSION['admin'] = $userInfo;
    }

    /*
    * @Description 刷新SESSION存储用户信息
    */
    public function refreshSESSION(){
        $userinfo = Doris\DApp::newClass("Admin_AdminModel")->readAdminById($_SESSION['admin']['id']);  //取用户信息
		$_SESSION['admin'] = $this->admin = $userinfo;    
    }

	/**
	 * @Description 获取用户基本信息
	 * @param $userid  int
	 * @return boolean false or array
	 */
	public function getUserInfoById($userid){
		$stmt = Doris\DDB::pdoSlave()->prepare('select * from sys_user where id = ?');
		$stmt->bindParam(1,$userid);
        $stmt->execute();
        $userinfo = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userinfo;
	}
	
	/**
	 * @Description 获取用户基本信息
	 * @param $username string
	 * @return boolean false or array
	 */
	public function getUserInfoByName($username){
		$stmt = Doris\DDB::pdoSlave()->prepare('select * from lmb_user where username = ?');
		$stmt->bindParam(1,$username);
		$stmt->execute();
		$userinfo = $stmt->fetch(PDO::FETCH_ASSOC);
		return $userinfo;
	}
	
	/**
	 * @Description 
	 */
	public function hasPrivilege($m,$c="index",$a="index" ){
		return $this->privilege->checkAuth($m,$c,$a);
	}
	 
	/**
	 * @Description 检查用户操作权限
	 */
	public function checkAuth(){
		
		if(!$this->privilege->checkAuth(_MODULE_,_CONTROLLER_,_ACTION_)){
			//var_dump([_MODULE_,_CONTROLLER_,_ACTION_]);
            return $this->disPatchToPrivilegeMenu( _MODULE_ , _CONTROLLER_ , _ACTION_ );
        }else{
            return true;
        }
	}
    public function disPatchToPrivilegeMenu($m,$c,$a){
        $m = strtolower($m);
        $c = strtolower($c);
        $a = strtolower($a);
        $privilegemenu = array(
            'game'=>array('index'=>array('index'),'accesscontrol'=>array('index'),'material'=>array('index'),'channel'=>array('index'),'gamecert'=>array('index')),    
            'account'=>array('index'=>array('pubaccount','testchargeaccount')),    
            'sdk'=>array('index'=>array('androidsdklist','iossdklist')),   
            'index'=>array('index'=>array('index')),   
            'user'=>array('index'=>array('index'),'privilege'=>array('index'),'role'=>array('index'),'adminindex'=>array('index'))
        );
        if(@in_array($a,@$privilegemenu[$m][$c])){
            foreach(@$privilegemenu[$m] as $k=>$v){
                foreach($v as $value){
                    if($this->privilege->checkAuth($m,$k,$value)){
                        $this->back_or('?m='.$m.'&c='.$k.'&a='.$value);
                    }
                }
            }
        }

        return false;
    }
	
	/**
	 * @Description 检查用户editor操作权限
	 * @param string $type 操作类型
	 * 
	 */
	
	public function checkEditorAuth($type){
		if($type == 'create'){
			$type = 'add';
		}elseif($type == 'edit'){
			$type = 'update';
		}else{
			$type = 'del';
		}
		return $this->privilege->checkEditorAuth($type,_MODULE_,_CONTROLLER_,_ACTION_);
		
	}
	
	
	public function getEditorPrivilegeCode(){
		
		return $this->privilege->getEditorPrivilegeCode(_MODULE_,_CONTROLLER_,_ACTION_);
	}


    /**
    * @Description 清除登录信息
    *
    */
	public function clear_login(){
		session_destroy();
		//$_SESSION['admin']=NULL;
		//$this->clear_inc("header_s.tpl");
		return true;
	}
	
	public function go($a,$m='',$c=''){
		if(!$m)$m="index"; 	 if(!$c)$c="index";  	if(!$a)$a="index";
		$this->redirect('index.php?m='.$m.'&c='.$c.'&a='.$a);
		return true;
	}
	
	/**
	 * @Description 检查当前用户是否有修改game_num的权限
	 */
	public function checkUpdateGameNumAuth(){
		$privilege = $this->privilege->getAllPrivilege();
		foreach($privilege as $key=>$value){
			if($value['authtype'] == 'name' && $value['name'] == 'game_num'){
				return true;
			}
		}
		return false;
	}
    
    /**
    *@定义网站前台路径
    */
    private function setWebFrontUrl(){
	    $webFrontUrl = DApp::newClass('Admin_SysconfigModel')->getConfig('SiteInfo','front_host');
	    $webFrontUrl = rtrim($webFrontUrl['value'],'/');
        defined("_WEB_FRONT_URL_") or define('_WEB_FRONT_URL_',$webFrontUrl); //定义网站地址
    }


    
	static function echoData($status,$message,$data=null){
		echo json_encode([
			"status"  => $status,
			"message" => $message,
			"data"	  =>  $data ,
			"time"	  =>  date("Y-m-d H:i:s") 
		]); 
		exit;
	}

	
}