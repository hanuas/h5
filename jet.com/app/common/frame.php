<?php
/**
 * @name 主框架中的几个类：DBTool, Config, Func
 * @author qiaochenglei
 *
 */

define('_ROOT_DIR_'			,realpath(__DIR__.'/../').'/'); 
define('_MODEL_DIR_'		,_ROOT_DIR_		."model/");
define('_SERVICE_DIR_'		,_ROOT_DIR_		."service/");
define('_LIB_DIR_'			,_ROOT_DIR_		."lib/");
define('_THIRD_DIR_'		,_ROOT_DIR_		."third/"); //放三方库
define('_CACHE_DIR_'		,_ROOT_DIR_		."cache/"); //文件缓存里用到
define('_LOG_DIR_'		,_ROOT_DIR_		."log/"); //log文件
define('_PAY_LOG_DIR_'		,_LOG_DIR_		."pay/"); //支付log文件


define("_ACTION_POSTFIX_"	,"Action");
define("_CONTROLLER_POSTFIX_","Controller");


/************************************************
**	DBTable
************************************************/
class DBTable  {
	protected $tableName = false;
	protected $cur_pdo = null; 
	public function __construct( $tableName ,$pdo ){
		$this->tableName = $tableName;
		$this->cur_pdo = $pdo; 
	}
	function  execute($sql){
		return DBTool::execute($sql, $this->cur_pdo);
	}
	function  insertByArray(&$arrData ){ 
		return DBTool::insertByArray($this->tableName, $arrData, $this->cur_pdo);
	}
	public function fetch($sql,$paras=[] ){
		return DBTool::fetch($sql,$paras, $this->cur_pdo);
	}
	public function fetchAll($sql,$paras=[]){
		return DBTool::fetchAll($sql,$paras, $this->cur_pdo);
	}
	public function fetchAllGroup($sql,$paras=[] ){
		return DBTool::fetchAllGroup($sql,$paras, $this->cur_pdo);
	}
	
	//为兼容NKBOSS
	public function MyInsert( &$arrData ){
		$result = $this->insertByArray( $arrData );
		if($result){ 
			$result = $this->cur_pdo ->lastInsertId ( );
		}
		return $result;
	}
	public function MyGetAll($fields ,$where,$paras = []){
		$sql = "SELECT $fields FROM `".$this->tableName."` WHERE $where";
		return DBTool::fetchALL($sql,$paras, $this->cur_pdo);
	}
	public function MyGetRow($fields ,$where,$paras = [] ){
		$sql = "SELECT $fields FROM `".$this->tableName."` WHERE $where";
		return DBTool::fetch($sql,$paras, $this->cur_pdo);
	}
	public function MyUpdate(&$arrUpData ,$where ){
		$setSentence = DBTool::getSqlSetSentenceFromArray($arrUpData);
		$sql = "UPDATE `".$this->tableName."` SET $setSentence WHERE $where";
		return $this->execute($sql);
	}
	public function MyDelete($where){
		$sql = "DELETE FROM `".$this->tableName."`  WHERE $where";
		return $this->execute($sql);
	}
}

/************************************************
**	DBTool
************************************************/
class DBTool{
	
	protected static $pdos=[];
	public static function getPdoByString($link_number, $conf_string){
	
		$a = explode(",", $conf_string);  
		//echo $conf_string;
		return self::getPdo($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $link_number);
	}
	
	/**
		$link_number 如果提供该值，程序会利用链接
	**/
	public static function getPdo($dbname, $username, $password,$host,$port=3306, $socket=false ,$charset = "utf8", $link_number=false){
		if( !empty( $link_number) && isset(self::$pdos[ $link_number ]) ){
			return self::$pdos[ $link_number ]; 
		} 
		
		$link_options=[PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '$charset';"];
		$dsn="mysql:"
			.($host	? "host=".$host.";" :"")
			.($port	? "port=".$port.";" :"")
			.($socket? "unix_socket=".$socket.";"  : "" )
			."dbname=".$dbname;
				
		$pdo = new PDO($dsn, $username, $password,$link_options);
		if( !empty( $link_number ) ){
			self::$pdos[ $link_number ] = $pdo;
		}
		return $pdo;
	}

	
	static function  execute($sql, $pdo){
		if(!is_object($pdo)){
			$pdo = self::$pdos[ $pdo ];
		} 
		return $pdo->exec($sql);
	}
	static function  insertByArray($table, &$arrData, $pdo){
		list($fields, $values) = self::getSqlFieldsValuesFromArray($arrData);
		$sql = "INSERT INTO `$table`($fields) VALUES($values)";
 
		// Func::debugWeb($sql ,false,__method__,__line__ );
		 
		return self::execute($sql, $pdo);
	}
	public static function fetch($sql,$paras=[],$pdo ){
		if(!is_object($pdo)){
			$pdo = self::$pdos[ $pdo ];
		}
		$query= $pdo->prepare($sql);
		$exeres = $query->execute($paras);
	
		$row=null;
		if($exeres)$row =$query->fetch(PDO::FETCH_ASSOC);
        
		return $row;
	}
	/**
		$pdo变量 即可以是PDO对象，也可以是$link_number（此时会从缓存里取PDO对象）
	**/
	public static function fetchAll($sql,$paras=[], $pdo){
		if(!is_object($pdo)){
			$pdo = self::$pdos[ $pdo ];
		}
		$query= $pdo->prepare($sql);
		$exeres = $query->execute($paras);

		$rows=null;
		if($exeres)$rows =$query->fetchAll(PDO::FETCH_ASSOC);
		return $rows;
	}
	public static function fetchAllGroup($sql,$paras=[], $pdo ){
		if(!is_object($pdo)){
			$pdo = self::$pdos[ $pdo ];
		}
		$query= $pdo->prepare($sql);
		$exeres = $query->execute($paras);

		$rows=null;
		if($exeres)$rows =$query->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
		return $rows;
	}
	
	//将二维数组中行中的某一列做为二维数组的key
	public static function transIndexByColumn( &$datas, $index){
		$newdatas = [];
		foreach( $datas as $key => &$data ){ 
			$newdatas[$data[ $index]] = $data;
		}
		return $newdatas;
	}
	
	/*
	*	将数组转换成 SQL里的Fields和Values中的子句
		传入 数组，如：
			[
				'user_id'=> 123, 
				'user_name'=> 'qcl', 
				'gender'=> 1, 
				'expand_user_name'=> null, 
				'data'=> ["qq","asd"], 
				'info'=>['爱好'=>'游泳'] 
			]
		返回（可用元组接入，如 list($fields, $values) = getSqlFieldsValuesFromArray($arrData);  ）：
			[
				"`user_id`, `user_name`, `gender`, `expand_user_name`, `data`, `info`" ,
				"'123', 'qcl', '1', null, '["qq","asd",23,false,null]', '{"\u7231\u597d":"\u6e38\u6cf3"}'"
			]
		
	*/
	static function getSqlFieldsValuesFromArray( &$arrData ){
		$fields = "";
		$values = "";
	
		foreach( $arrData as $key=>$value){
			if(!empty($fields))
				$fields .= ", ";
			$fields .= "`$key`";
		
			if(!empty($values))
				$values .= ", ";
			
			//参见	settype()、is_array()、is_bool()、is_float()、is_integer()、
			//		is_null()、is_numeric()、is_object()、is_resource()、is_scalar() 和 is_string()。
			if(	   is_string($value) 	
				|| is_integer($value)
				|| is_float($value)
				|| is_numeric($value)
				){
				$values .= "'$value'";
			}else if( is_array($value)  || is_object($value)){
				$values .= "'".json_encode($value)."'";
			}else if( is_null($value)  ){
				$values .= "null";
			}else{ 
				$values .= "$value";
			}
		}
		return [$fields, $values];
	}

	/*
	*	将数组转换成 SQL里的Fields和Values中的子句
		传入 数组，如：
			[
				'user_id'=> 123, 
				'user_name'=> 'qcl', 
				'gender'=> 1, 
				'expand_user_name'=> null, 
				'data'=> ["qq","asd"], 
				'info'=>['爱好'=>'游泳'] 
			]
		返回（ 如 $setSentence = getSqlSetSentenceFromArray($arrData);  ）：
			`user_id` = '123', `user_name` = 'qcl', `gender` = '1', `expand_user_name` = null, `data` = '["qq","asd",23,false,null]', `info` = '{"\u7231\u597d":"\u6e38\u6cf3"}'

		
	*/
	static function getSqlSetSentenceFromArray( &$arrData ){
		$setSentence = "";
		foreach( $arrData as $key=>$value){
			if(!empty($setSentence))
				$setSentence .= ", ";
		
			$setSentence .= "`$key` = ";
			if(	   is_string($value) 	
				|| is_integer($value)
				|| is_float($value)
				|| is_numeric($value)
				){
				$setSentence .= "'$value'";
			}else if( is_array($value)  || is_object($value)){
				$setSentence .= "'".json_encode($value)."'";
			}else if( is_null($value)  ){
				$setSentence .= "null";
			}else{ 
				$setSentence .= "$value";
			}
		
		}
		return $setSentence;
	}


}




/************************************************
**	Config
**
************************************************/


class Config{
	static $confs=[];

	/*
	*	加载用户配置。根据最后一个文件夹后缀（.product）判断是否存在线上配置
	*	如：传入的$config = "conf/admin.conf.php"
	*		则会优先尝试加载 _ROOT_DIR_."conf.product/admin.conf.php"
	*		如果文件不存在才会加载 _ROOT_DIR_."conf/admin.conf.php"
	*/
	static function loadConf($config){
		$config=_ROOT_DIR_."$config";
		
		$confParts = explode("/", $config);
		$lastPart 	= array_pop($confParts);
		$confParts[count($confParts)-1] =end($confParts).".product";
		
		$productConfig = implode("/",$confParts). "/$lastPart ";
		
		$productConfig=trim($productConfig);//两头有空格导致判断文件不成功
		if(file_exists($productConfig)){
			$conf = require_once $productConfig;
		}else if( file_exists($config) ){
			$conf = require_once $config;
		}else{
			$conf = null;
		} 
		// 处理继承
		if ($conf && !empty( $conf["extend_from"] )  ){ 
			$fromFile = $conf["extend_from"];
			$fromConf = self::loadConf($fromFile); 
			 
			if( $fromConf ){
				$conf = array_merge( $fromConf, $conf);
			} 
			
			unset( $conf["extend_from"] );
		}
		
		
		return $conf;
	}
 
	
	/*
	*
	*
	*/
	static function register($confFile,$group="main",$forceReload = false){//根据文件后缀判断是否存在线上配置
		if ( empty(self::$confs[$group]) || $forceReload){
			self::$confs[$group] = self::loadConf($confFile);  
		}
		return self::$confs[$group];
	}
	
	static function configExt($webModule = "" ){ 
			if(!$webModule)$webModule="";
			
			define("_CTL_HOME_"			,realpath(_ROOT_DIR_.$webModule));
				define('_TEMPLATE_DIR_'		,_CTL_HOME_.'/tpl/');
				define('_LAYOUT_DIR_'		,_CTL_HOME_.'/tpl/layout/');
				define('_CONTROLLER_DIR_'	,_CTL_HOME_.'/controller/');
	}
	private static function beginWith($str, $needle) { 
		return strpos($str, $needle) === 0; 
	}
	
	/*
	*	读取配置
	*	filter为过滤器，如 db/main 表示读取主DB的配置，db/main/username 表示读取主DB的用户名
	*	如果filter 非空，且对应的键不存在则返回null
	*	
	*	如果filter 为 "to://path/to"  则读取path/to下的配置并返回
	*/
	static function get($filter=null,$group="main"){
		if(!$filter)
			return self::$confs[$group];
		
		$keys=explode("/", $filter);
		$conf = &self::$confs[$group];
		
		try {
			foreach($keys as &$key)
				$conf = &$conf[$key];
		}catch (Exception $e){
			$conf=null;
			//DLog::exception($e); 
		}
		
		if( is_string( $conf ) && strpos( $conf, "to://" ) === 0 ){
			return self::get(substr($conf ,5), $group );
		}
		return $conf;
	}
	
	/*
	*	设置配置
	*	参数同 get 接口
	*	
	*/
	static function set($filter,$value,$group="main"){
		if(!$filter){
			self::$confs[$group] = $value;
			return;
		}
		
		$keys = explode("/", $filter);
		$conf = &self::$confs[$group];
		
		try {
			foreach($keys as &$key)
				$conf = &$conf[$key];
		}catch (Exception $e){
			$conf=null;
			//DLog::exception($e);
			
		}
		$conf = $value;
	}
}


/************************************************
**	Func
**
************************************************/
class Func{ 
	//把命令行参数转换成键值对参数，并返回
	static function getParasByConsoleArgs(  $argv ) {
		if ( empty(  $argv ) ) return $_GET;
		$paras = [];
		$paras["self_script"] = $argv[ 0 ];
		$count = count($argv);
		for ($i = 1; $i< $count; $i++ ) {
			$arr = explode("=", $argv[$i] );
			if( count($arr ) > 1){
				$paras[ trim($arr [0]) ] = trim($arr [1]) ;
			}else{
				$paras[ $argv[$i] ] = "";
			}
		}
		return $paras;
	}
	
	public function isPost(){
		return $_SERVER['REQUEST_METHOD'] == "POST";
	}
	public function isGet(){
		return $_SERVER['REQUEST_METHOD'] == "GET";
	}
	public function isPut(){
		return $_SERVER['REQUEST_METHOD'] == "PUT";
	}
	public function isDelete(){
		return $_SERVER['REQUEST_METHOD'] == "DELETE";
	}
	public static function getRequestMethod(){
		return $_SERVER['REQUEST_METHOD'] ;
	}
	
	public static function requestParas(){
		parse_str(file_get_contents('php://input'), $data);
		$data = array_merge($_GET, $_POST, $data);
		return $data;
	}
	static function utf8_header() {
		header("Content-type: text/html; charset=utf-8");
	}
	static function endWith($str,$subStr){
		return substr($str, -(strlen($subStr)))==$subStr;
	}
	static function beginWith($str, $needle) { 
		return strpos($str, $needle) === 0; 
	}
	static function isMobile($phonenumber){
		//$phonenumber = '13712345678';  
		if(preg_match("/^1[34578]{1}\d{9}$/",$phonenumber)){  
			return true;  
		}
		return false;  
	}
	static function urlAppend($url_base, $data){ 
		$query_ex = http_build_query($data); 
		if(strpos($url_base,"?") > 0){
			$url = "$url_base&$query_ex";
		}else{ 
			$url = "$url_base?$query_ex";
		}
		return $url;
	}
	static function debugWeb($data, $exit = true, $file =false,$line = false ){
	
		header("Content-type: text/html; charset=utf-8");
		echo "<pre>";
		if($file !==false){
			echo "FILE:  $file";
			if($line !==false){
				echo " $line";
			}
			echo "<br>";
		}else if($line !==false){
			echo "LINE:  $line<br>";
		}
		
		
		if(is_array($data )){
			print_r($data);
		}else{
			var_dump($data);
		}
		echo "</pre>";
		if($exit)exit;
	}
	/* 
	*	encryptPassword2
	*		本函数在BOSS里登录时用了（encryptPassword或encryptPassword2只要有一个通过即可）
	*			，但在注册时只用了encryptPassword
	*	
	*		在BOSS里，user_id传的是last_name
	*/
	static function encryptPassword2($password,$user_id) { 
		return md5(md5(trim($password)).$user_id);
	}
	static function encryptPassword($password) {
		return md5(sha1($password) . Config::get('boss/SITE_ENCRYPT_KEY') );
	}
	
	/**
	 * 用户分表HASH算法
	 *
	 * @param string $key 字符串
	 * @param int $n 分表数
	 * @return string 00-63
	 */
	static function getHash(&$key, $n = 64) {
		$hash = crc32($key) >> 16 & 0xffff;
		return sprintf("%02s", $hash % $n);
	}

	static function getClientIP() {
		if (isset($_SERVER)) {
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			} else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				$realip = $_SERVER["HTTP_CLIENT_IP"];
			} else {
				$realip = $_SERVER["REMOTE_ADDR"];
			}
		} else {
			if (getenv("HTTP_X_FORWARDED_FOR")) {
				$realip = getenv("HTTP_X_FORWARDED_FOR");
			} else if (getenv("HTTP_CLIENT_IP")) {
				$realip = getenv("HTTP_CLIENT_IP");
			} else {
				$realip = getenv("REMOTE_ADDR");
			}
		}

		return addslashes($realip);
	}

}