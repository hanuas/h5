<?php 
/**
*	乔成磊 20150820
*
*/
namespace Doris;

class DConfig{
	static $confs=[];
	/*
	*	一开始就会调用（初始化App前）
	*/
	static function config(){
		define('DS', DIRECTORY_SEPARATOR);

		//目录配置
		define('_ROOT_DIR_',realpath(dirname(__FILE__).'/../../').DS);//定义服务器的绝对路径
			define('_FRAME_DIR_',realpath(dirname(__FILE__).'/../').DS);//框架目录
				define('_SMARTY_DIR_'			,_FRAME_DIR_.'smarty/');//smarty目录(未用)
				define('_DTHIRD_DIR_'		,_FRAME_DIR_.'DCore/Third/');
				define('_DLIB_DIR_'			,_FRAME_DIR_.'DLib/');
			define('_APP_DIR_'			,_ROOT_DIR_."app/");//
				define('_MODEL_DIR_'		,_APP_DIR_		."model/");//
				define('_LIB_DIR_'			,_APP_DIR_		."lib/");//
		
				define('_CTL_ROOT_'			,_APP_DIR_);//日志文件目录
					// _CTL_HOME_	在configExt中定义
						// _TEMPLATE_DIR_	在configExt中定义，模板目录
						// _MODULE_DIR_		在configExt中定义，仅在 dispatch 是MCA时有效
						// _CONTROLLER_DIR_  在configExt中定义，仅在 dispatch 是CA时有效
	
			define('_DATA_DIR_'			,_ROOT_DIR_."data/");//
				define('_CACHE_DIR_'		,_DATA_DIR_		."cache/");//
				define('_LOG_DIR_'			,_DATA_DIR_		."log/");//
				define('_CACHE_SMARTY_DIR_'	,_DATA_DIR_.'smarty_cache/');//smarty缓存目录
				
		//其它常量配置
		define("_ACTION_POSTFIX_","Action");
		define("_CONTROLLER_POSTFIX_","Controller");
		//define("_MODEL_POSTFIX_", "Model");
	}

	/*
	*	在APP初始化完之后调用（初始化App后，构造完成之后紧接着调用）
	*/
	static function configExt($app){
			$webModule=$app->conf('web_module');
			if(!$webModule)$webModule="";
			
			define("_CTL_HOME_"			,_CTL_ROOT_.$webModule);
				define('_TEMPLATE_DIR_'		,_CTL_HOME_.'/tpl/');
				define('_LAYOUT_DIR_'		,_CTL_HOME_.'/tpl/layout/');
				define('_CONTROLLER_DIR_'	,_CTL_HOME_.'/controller/');
	}


	/*
	*	加载用户配置。根据最后一个文件夹后缀（.product）判断是否存在线上配置
	*	如：传入的$config = _ROOT_DIR_."conf/admin.conf.php"
	*		则会优先尝试加载 _ROOT_DIR_."conf.product/admin.conf.php"
	*		如果文件不存在才会加载 _ROOT_DIR_."conf/admin.conf.php"
	*/
	static function loadConf($config){
		$config=_ROOT_DIR_."conf/$config";
		
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
	*	加载用户配置。根据文件后缀（.product）判断是否存在线上配置
	*	如：传入的$config = _ROOT_DIR_."conf/admin.conf.php"
	*		则会优先尝试加载 _ROOT_DIR_."conf/admin.conf.product.php"
	*		如果文件不存在才会加载 _ROOT_DIR_."conf/admin.conf.php"
	*/
	static function loadConf2($config){//根据文件后缀判断是否存在线上配置
		$config=_ROOT_DIR_."conf/$config";
		
		$productConfig = rtrim($config,'.php').'.product.php';

		if(file_exists($productConfig)){
			$conf = require_once $productConfig;
		}else{
			$conf = require_once $config;
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
	
	/*
	*	读取配置
	*	filter为过滤器，如 db/main 表示读取主DB的配置，db/main/username 表示读取主DB的用户名
	*	如果filter 非空，且对应的键不存在则返回null
	*	
	*/
	static function get($filter=null,$group="main"){
		if(!$filter)
			return self::$confs[$group];
		
		$keys=explode("/", $filter);
		$conf = &self::$confs[$group];
		
		try {
			foreach($keys as &$key)
				$conf = &$conf[$key];
		}catch (\Exception $e){
			$conf=null;
			DLog::exception($e);
			
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
		}catch (\Exception $e){
			$conf=null;
			DLog::exception($e);
			
		}
		$conf = $value;
	}
}