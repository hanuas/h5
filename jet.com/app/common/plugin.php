<?php
/**
 *
 * @name PublicPlugin
 * @author qiaochenglei
 *
*/ 


class Plugin  {

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

	}

	public function postDispatch( &$controller,&$dispatch,&$routeinfo ) {
		
	
	}


}
