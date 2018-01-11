<?php
/**
 * @name DDB 数据库类
 * @author qiaochenglei
 *
 */
namespace Doris;

class DDB{
	//index=-1 表示随机选取,真实值会在 realIndex 里返回。 lazyload
	public static function pdo($index= 0, &$realIndex=-1){
		return self::getPDO("main", $index, $realIndex);
	}
	public static function db($index= 0, &$realIndex=-1){
		$pdo=self::pdo($index,$realIndex);	
		return self::dbNotORM("main",$pdo,$realIndex);
	}
	
	public static function pdoSlave($index= -1 , &$realIndex=-1){
		return self::getPDO("slave", $index, $realIndex);
	}
	public static function dbSlave($index= -1 , &$realIndex=-1){
		$pdo=self::pdoSlave($index,$realIndex);	
		return self::dbNotORM("slave",$pdo,$realIndex);
	}
	
	public static function pdoInGroup($groupName,$index= -1 , &$realIndex=-1){
		return self::getPDO( $groupName , $index, $realIndex);
	}
	
	public static function dbInGroup($groupName,$index= -1 , &$realIndex=-1){
		$pdo=self::getPDO($groupName , $index,$realIndex);	
		return self::dbNotORM($groupName  , $pdo,$realIndex);
	}
	
	//===========
	static function  execute($sql, $pdo = null){
		$ret=array();
		if(!$pdo)$pdo=self::pdo();
		
		if(!is_object($pdo)){
			$pdo = self::$pdos[ $pdo ];
		} 
		return $pdo->exec($sql);
	}
	
	public static function fetch($sql,$paras=[],$pdo=null){
		if(!$pdo)$pdo=self::pdoSlave();
		$query= $pdo->prepare($sql);
		$exeres = $query->execute($paras);
	
		$row=null;
		if($exeres)$row =$query->fetch(\PDO::FETCH_ASSOC);
		return $row;
	}	
	
	public static function fetchAll($sql,$paras=[],$pdo=null){
		if(!$pdo)$pdo=self::pdoSlave();
		$query= $pdo->prepare($sql);
		$exeres = $query->execute($paras);
	
		$rows=null;
		if($exeres)$rows =$query->fetchAll(\PDO::FETCH_ASSOC);
		return $rows;
	}

    //批量插入数据
    public static function insertAll($table,$datas, $pdo = null) {
        #print_r($datas);
        if(!is_array($datas[0])) return false;
        $fields = array_keys($datas[0]);
        $values  =  array();
        foreach ($datas as $data){
            $value   =  array();
            foreach ($data as $key=>$val){
                #if(is_scalar($val)) { // 过滤非标量数据
                    $value[]   =  "'".$val."'";
                #}
            }
            $values[]    = '('.implode(',', $value).')';
        }
        $sql = 'INSERT INTO '.$table.' ('.implode(',', $fields).') VALUES '.implode(',',$values);
        return self::execute($sql, $pdo);
    }
	

	
	private static $groupPDOs=[];		//二维:groupName,index
	private static $groupNotORMs=[];	//二维:groupName,index
	private static $groupPDOsJust=[];	//一维:groupName
	private static $indexJust=[];		//一维:groupName

	/*
	*	获取PDO对象
	*	$groupName 指哪个DB组，如 main、slave
	*	$index		指同组内哪个配置，当-1时会随机选择
	*	&$realIndex 指实际使用了哪个index
	*
	*	返回值		pdo对象
	*/
	public static function getPDO($groupName , $index, &$realIndex=-1) {//lazy load
		if(!isset(self::$groupPDOs[$groupName]))
			self::$groupPDOs[$groupName]=[];
			
		//同一个用户请求中，如果多次使用DB 则用同一个DB连接
		if( !empty(self::$groupPDOsJust[$groupName])  ){
			$realIndex=self::$indexJust[$groupName];
			return self::$groupPDOsJust[$groupName];
		}
		
		$pdos=self::$groupPDOs[$groupName];
		
		list($host , $dbname , $username , $password , $port , $socket, $charset )
			=self::getDbConfig($groupName , $index, $realIndex);
		
		if(!isset($pdos[$index])){
			
			$port = empty($dbconf['port'])	? "" : $dbconf["port"]  ;
			$port = is_array($port) ? $port[$index]	:	$port;
			
			$dsn="mysql:"
				.($host	? "host=".$host.";" :"")
				.($port	? "port=".$port.";" :"")
				.($socket? "unix_socket=".$dbconf["socket"].";"  : "" )
				."dbname=".$dbname;

			$link_options=[\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '$charset';"];
			
			$pdos[$index] = new \PDO($dsn, $username, $password,$link_options);
			$pdos[$index]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
			//$pdos[$index]->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);//会自动转换成小写
		}
		self::$groupPDOsJust[$groupName]=$pdos[$index];
		self::$indexJust[$groupName]=$index;
		return $pdos[$index];
	}
	
	private static function getDbConfig($groupName , $index, &$realIndex=-1){
		$dbconf = DConfig::get("db/".$groupName);
		
		//如果是指向型，没读取对应配置
		if ( !empty($dbconf["point_to"]) ){
			$dbconf = DConfig::get("db/".$dbconf["point_to"]);
		}
		if($index==-1){
			//var_dump($dbconf);exit;
			$hostCount= count($dbconf["host"]);
			if( $index < 0  ||  $index >= $hostCount ) $index=mt_rand(0, $hostCount-1 );
			
		}	
		$realIndex=$index;
		
		$host = $dbconf["host"][$index]	;
		$username = is_array($dbconf["username"]) ? $dbconf["username"][$index]	:	$dbconf["username"];
		$password = is_array($dbconf["password"]) ? $dbconf["password"][$index]	:	$dbconf["password"];
		$port = empty($dbconf['port'])	? false : $dbconf["port"]  ;
		$port = is_array($port) ? $port[$index]	:	$port;
		$socket = empty($dbconf['socket'])	? false : $dbconf["socket"];
		$dbname = $dbconf["name"];
		$charset = $dbconf["charset"];
		return [$host , $dbname , $username , $password , $port , $socket , $charset ];
	}
	
	public static function getDatatablesConfig($groupName , $index=-1, &$realIndex=-1){
		list($host , $dbname , $username , $password , $port , $socket, $charset )
			=self::getDbConfig($groupName , $index, $realIndex);
		
		$sql_details = array(
			"type" => "Mysql",
			"user" =>  $username,
			"pass" =>  $password,
			"host" =>  $host,
			"port" =>  $port,
			"db"   =>  $dbname,
			"socket"   =>  $socket,	//自加
			"charset"   =>  $charset,	//自加
		);
		return $sql_details;

	}
	private static function dbNotORM($groupName,$conn,$index) {
		//include_once  _DTHIRD_DIR_."NotORM/NotORM.php";
		//include_once  _DTHIRD_DIR_."notorm-201512/NotORM.php";
		if(!isset(self::$groupNotORMs[$groupName]))
			self::$groupNotORMs[$groupName]=[];
		$dbs=self::$groupNotORMs[$groupName];
		
		if(!isset($dbs[$index])){
			//include_once  _DTHIRD_DIR_."NotORM/NotORM.php";
			include_once  _DTHIRD_DIR_."notorm-201512/NotORM.php";
			$dbs[$index] = new \NotORM($conn);
		}
		return $dbs[$index];
	}
	static function notOrmRows2NormalArray($db_rows){
		$a=array();
		foreach ($db_rows as $key=>$db_row) {
			$a[$key]=array();
			foreach ($db_row as $name=>$value) {
				$a[$key][$name]=$value;
			}
		}
		return $a;
	}
	
	static function notOrmRow2NormalArray($db_row){
		$a=array();
		foreach ($db_row as $name=>$value) {
			$a[$name]=$value;
		}
		return $a;
	}
	
	

}
///////////////////////////// DB相关: 对性能有要求的直接使用PDO，其它情况可以使用NotORM //////////////////////////
// 
// function _pdo($index=-1, &$realIndex=-1){return DDB::pdoMain( $index, $realIndex);}
// function _db($index=-1, &$realIndex=-1){return DDB::dbMain( $index, $realIndex);}
// function _pdoSlave($index=-1, &$realIndex=-1){return DDB::pdoSlave( $index, $realIndex);}
// function _dbSlave($index=-1, &$realIndex=-1){return DDB::dbSlave( $index, $realIndex);}
// function _fetch($sql,$paras,$pdo=null){		return DDB::fetch($sql,$paras,$pdo);}
// function _fetchAll($sql,$paras,$pdo=null){return DDB::fetchAll($sql,$paras,$pdo);}

// function orm_rows2array($db_rows){
// 	$a=array();
// 	foreach ($db_rows as $key=>$db_row) {
// 		$a[$key]=array();
// 		foreach ($db_row as $name=>$value) {
// 			$a[$key][$name]=$value;
// 		}
// 	}
// 	return $a;
// }
// function orm_row2array($db_row){
// 	$a=array();
// 	foreach ($db_row as $name=>$value) {
// 		$a[$name]=$value;
// 	}
// 	return $a;
// }
// 

//本地化（多语言支持）
// function _lan_byid($domId,$defaultDisplayName){return "<span id='LAN_$domId'>$defaultDisplayName</span>"; }
// function _lan($domClass,$defaultDisplayName){return "<span class='LAN_$domClass'>$defaultDisplayName</span>"; }
// function _lan_serial($arrDomClassAndDefaultDisplayName,$addSpace=false){
// 	$ret='';
// 	foreach($arrDomClassAndDefaultDisplayName as $k=>$v)$ret.=_lan($k,$v).($addSpace?' ':'');
// 	return $ret; 
// }
