<?php 
/**
* 	后台主配置
*	乔成磊 20150816
*/

return [ //BEGIN CONFIG!!!

/*
* 	继承自哪个配置文件
*
*/
"extend_from" => "public.conf.php",

/*
*	本配置文件代表的web模块。比如：用admin表示后台模块，则：
*			前端虚拟机指向：_ROOT_DIR_/admin
*			控制器根目录为：_ROOT_DIR_/ctl_root/admin/controller
*			控制器模板目录为：_ROOT_DIR_/ctl_root/admin/tpl
*
*/
"web_module"=>"admin",
"site_title"=>"H5平台",


/*
*	钩子插件：
*		
*/
"plugin"=>"AdminPlugin",


/*
*	路由配置
*/
"dispatch"=>[
	"dispatch_level"=>3, 
	/*
	*	路由：按顺序从上往下执行
	*		类型有：simple、supervar、static、rewrite、regex 
	*		
	*/
 	"route_list"=>[
 		"simple"	=>	[ "type"=>"simple"	,	"schema"=>["m"=>"m","c"=>"c","a"=>"a"] ],

	]//end route_list
],//end dispatch


/*
*	插件：
*		字符串类型, 插件
*/
"plugin"=>"AdminPlugin",



];//END CONFIG!!!