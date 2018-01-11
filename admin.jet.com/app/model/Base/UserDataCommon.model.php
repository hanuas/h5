<?php
/**
 * 美德数据库访问基类
 * @author qiaochenglei
 * 2015-12-02
 *
 */
 
 
 
 use Doris\DApp,
 Doris\DDB;
 
 
class Base_UserDataCommonModel{
	
	public function __construct(){
		
	 
	}
	protected function db($uid = null){
		if(empty($uid))
			return DDB::db();
		return DDB::dbInGroup("userdb_main");
	}
	protected function execute($sql, $uid = null){
		if(empty($uid))
			return DDB::execute($sql);
		else
			return DDB::execute($sql, DDB::pdoInGroup("userdb_main") );
	}
	protected function fetch($sql,$paras=[], $uid = null ){
		$pdo = null;
		if( !empty($uid)  )
			$pdo = 	DDB::pdoInGroup("userdb_slave");	
		return DDB::fetch($sql , $paras ,$pdo);
	}
	protected function fetchAll($sql,$paras=[], $uid = null){
		$pdo = null;
		if( !empty($uid)  )
			$pdo = 	DDB::pdoInGroup("userdb_slave");	
		return DDB::fetchAll($sql , $paras ,$pdo);
		
	}
	
}