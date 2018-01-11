<?php
/**
 *
 * @name AdminPlugin
 * @author qiaochenglei
 *
*/

/************************************************
**	自动加载
************************************************/
function __autoload($class_name) {
    settype($class_name, "string"); 
    
    if(!Doris\DDispatch::load( $class_name ) ){
    
		if(Doris\endWith($class_name, 'Controller')){
			if(!Doris\DApp::loadController( Doris\DDispatch::curModule() ,substr($class_name,0,strlen($class_name)-10))   ){
				Doris\DApp::loadController( "common" ,substr($class_name,0,strlen($class_name)-10))  ;
			}
		}
	}
	
}

/************************************************
**	加载三方
************************************************/
function include_third($ref_path, $once = true, $require = false){
	Doris\DDispatch::include_third( $ref_path, $once , $require);
}

/************************************************
**	网站标题
************************************************/
function site_title(){
	$t=Doris\DConfig::get("site_title"); 
	return $t? $t:"多纷互动";
}

function _lan($key,$default){return $default;}

//两个快捷方法

function _new($className , $initPara=null, $relativePathOfApp ='' )
{
	return Doris\DApp::newCLass($className , $initPara, $relativePathOfApp);
}

function _load($className , $relativePathOfApp ='' )
{
	return Doris\DApp::loadClass($className , $relativePathOfApp);
} 
class AdminPlugin  {

	public function routerStartup( &$requestUri ) {
	
		//启动SESSION,如果客户断点传了sessionid,则以客户端为准  ——QCL
		//$sid=$request->getParam("sessionid", 0);
		$sid=@$_GET["sessionid"];
		if($sid){
			session_id($sid); //echo "from:".$sid." to:".session_id()."<hr>";
		}
		session_start();
		// var_dump(		$request);
		// exit(0);
		
		
		//TODO 加载函数库
		
		
		return $requestUri ;
	}

	public function routerShutdown( &$routeinfo ) {
		//var_dump($routeinfo);exit;
		return $routeinfo;
	}


	public function preDispatch( &$controller,&$dispatch ,&$routeinfo ) {
		Doris\DApp::loadController("common","common");
	}

	public function postDispatch( &$controller,&$dispatch,&$routeinfo ) {
		
	
	}


}
