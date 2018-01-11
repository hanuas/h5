<?php
/**
 *
 * @name PublicPlugin
 * @author qiaochenglei
 *
*/


function _lan($key,$default){return $default;}


function _new($className , $initPara=null, $relativePathOfApp ='' )
{
	return Doris\DApp::newCLass($className , $initPara, $relativePathOfApp);
}

function _load($className , $relativePathOfApp ='' )
{
	return Doris\DApp::loadClass($className , $relativePathOfApp);
}

class PublicPlugin  {

	public function routerStartup( &$requestUri ) {
	
		//启动SESSION,如果客户断点传了sessionid,则以客户端为准  ——QCL
		//$sid=$request->getParam("sessionid", 0);
		
		if ( !defined("CONSOLE_MODE") ){
			$sid=@$_GET["sessionid"];
			if($sid){
				session_id($sid);  
			}
			session_start(); 
		
		}
		//TODO 加载函数库
		
		
		return $requestUri ;
	}

	public function routerShutdown( &$routeinfo ) {
		//var_dump($routeinfo);exit;
		return $routeinfo;
	}


	public function preDispatch( &$controller,&$dispatch ,&$routeinfo ) {
		Doris\DApp::loadController("","common");
		Doris\DApp::loadController("","mobApiCommon");
	}

	public function postDispatch( &$controller,&$dispatch,&$routeinfo ) {
		
	
	}


}
