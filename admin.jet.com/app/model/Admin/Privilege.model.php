<?php 

	/**
	 * @Description 后台权限model类
	 * 
	 */
 
define("keyEditorPrivCode"  , "__EditorPrivilegeCodeKey__");
class Admin_PrivilegeModel{
	const ePriv_Add    = 1;  //editor 新建的权限
	const ePriv_Del    = 2;  //editor 删除的权限
	const ePriv_Update = 4;  //editor 修改的权限
	const ePriv_All = 7;     
	 
	
	private $adminId;
	private $allPrivilege;
	private $allEditorPrivilege;
	
	public function __construct(){
		$this->adminId = @$_SESSION['admin']['id'];
		$this->allPrivilege = $this->readAllPrivilege();  
		$this->allEditorPrivilege = $this->readAllEditorPrivilege();
	}
	
	
	
	/**
	 *	@Description 获取用户所有权限 
	 *
	 */
	private function readAllPrivilege(){
		$stmt = Doris\DDB::pdoSlave()->prepare('select * from sys_user_role as ur left join sys_user as u on u.id=ur.user_id left join sys_role as r on r.id=ur.role_id left join sys_role_privilege as rp on rp.role_id = r.id left join sys_privilege as p on p.id=rp.privilege_id where u.id=?');
		$stmt->bindParam(1 , $this->adminId);
		$stmt->execute();
		$allPrivilege = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$newAllPrivilege = array();
		//去除数组的重复值，并以privilege_id作为键名
		foreach($allPrivilege as $key => $priv){
			$newAllPrivilege[$priv['privilege_id']] = $priv;
		}
		//Doris\dump_array($newAllPrivilege);
		return $newAllPrivilege;
	}
	
	/**
	 * @Description 获取用户模块/控制器下的editor 权限
	 * 
	 */ 
	private function readAllEditorPrivilege(){
		$allPrivilege = $this->getAllPrivilege(); //所有权限
		$editor = array(); //editor权限
		foreach($allPrivilege as $key => $priv){
			$priv['branch'] = explode(',' , $priv['branch']);
			$priv['branch'] = array_combine($priv['branch'] , $priv['branch']); 
			 
			$pm = strtolower($priv["m"] ) ;
			$pc = strtolower($priv["c"] );
			$pa = strtolower($priv["a"] );
			
			switch($priv['authtype']){
				case 'm':
					$editor[ $pm ][keyEditorPrivCode] = self::ePriv_All; 
					break;
				case 'mc': 
					$editor[ $pm ][ $pc ][keyEditorPrivCode]  = self::ePriv_All; 
					break;
				case 'mca': 
					$editor[ $pm ][ $pc ][ $pa ][keyEditorPrivCode]  =  self::ePriv_All;
					break;
				case 'mcab':  
					if ( empty($editor[ $pm ][ $pc ][ $pa ][keyEditorPrivCode] ) )
						$editor[ $pm ][ $pc ][ $pa ][keyEditorPrivCode]  =  0 ;
					$ref = &$editor[ $pm ][ $pc ][ $pa ][keyEditorPrivCode] ;
					if ( isset($priv['branch']['add']) ){
						$ref |= self::ePriv_Add;
					}
					if ( isset($priv['branch']['update']) ){
						$ref |= self::ePriv_Update;
					}
					if ( isset($priv['branch']['del']) ){
						$ref |= self::ePriv_Del;
					} 
			}
		}
		return $editor;
		
	}
	
	/**
	 * @Description 检查用户访问权限
	 * @param string $m 模块名
	 * @param string $c 控制器
	 * @param string $a 方法
	 * @return bollen
	 */
	public function checkAuth($m , $c = 'index' , $a = 'index'){
	
        //echo " checkAuth:" , $m.$c.$a;
		$allPrivilege = $this->getAllPrivilege(); //所有权限
		//echo "<pre>";Doris\utf8_header();var_dump($allPrivilege);exit;
		$a = substr($a , -4) == 'ajax'? str_replace('_ajax' , '' , $a) : $a;
		$m = strtolower($m);	$c = strtolower($c);	$a = strtolower($a);
		
		//Doris\utf8_header();echo "<pre>";print_r($allPrivilege);exit;
		foreach($allPrivilege as $key => $priv){
			$priv['m'] = strtolower($priv['m']);
			$priv['c'] = strtolower($priv['c']);
			$priv['a'] = strtolower($priv['a']);
			switch($priv['authtype']){
				case 'm':
					if($priv['m'] == $m){
						return true;
					}
					break;
				case 'mc':
					if($priv['m'] == $m && $priv['c'] == $c){
						return true;
					}
					break;
				case 'mca':
					if($priv['m']==$m && $priv['c'] == $c && $priv['a'] == $a){
	
						return true;
					}
					break;
				case 'mcab':
					if($priv['m']==$m && $priv['c'] == $c && $priv['a'] == $a){
						return true;
					}
					break;
			}
		}
		return false;
	}	
	
	/**
	 * @Description 检查用户editor操作权限
	 * @param string $m 模块名
	 * @param string $c 控制器
	 * @param string $a 方法
	 * @param string $type 操作类型
	 * @return bollen
	 */	 
	public function checkEditorAuth($type , $m , $c , $a){
		
		$code =  $this->getEditorPrivilegeCode($m , $c , $a);
			switch($type){
			case "add":		return self::ePriv_All & $code > 0; 
			case "update":	return self::ePriv_All & $code > 0; 
			case "del": 	return self::ePriv_All & $code > 0; 
		} 
		return false;
		
	}
	/**
	 * @Description 获取Editor权限code
	 * @param  string 模块名 $m
	 * @param  string 控制器 $c
	 * @param  string  方法 $a
	 */ 
	public function getEditorPrivilegeCode($m , $c , $a){
		$m = strtolower($m);	$c = strtolower($c);	$a = strtolower($a);
		$a = substr($a , -4) == 'ajax'? str_replace('_ajax' , '' , $a) : $a;
	
		$e = $this->getAllEditorPrivilege();
		
		if( isset($e[ $m ][keyEditorPrivCode]) ) {
			return $e[ $m ][keyEditorPrivCode];
			
		}else if( isset($e[ $m ][ $c ][keyEditorPrivCode]) ) {
		
			return $e[ $m ][ $c ][keyEditorPrivCode];
			
		}else if( isset($e[ $m ][ $c ][ $a ][keyEditorPrivCode]) ) {
		
			return $e[ $m ][ $c ][ $a ][keyEditorPrivCode]; 
		} 
		
	}
	public function getAllPrivilege(){
		return $this->allPrivilege;
	}
	
	public function getAllEditorPrivilege(){
		return $this->allEditorPrivilege;
	}
} 


