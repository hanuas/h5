<?php


define("BIT_1",1<<0);
define("BIT_2",1<<1);
define("BIT_3",1<<2);
define("BIT_4",1<<3);
define("BIT_5",1<<4);
define("BIT_6",1<<5);
define("BIT_7",1<<6);
define("BIT_8",1<<7);


return [ //BEGIN CONFIG!!!
/*
*	路由配置
*/
"dispatch"=>[

	/*
	*	控制器级别：
	*		取值范围 2或3
	*		3  包含 模块、控制器、方法 示例：  index.php?m=admin&c=index&a=index,
	*			_MODULE_ 、 _CONTROLLER_ 、 _ACTION_  这三个宏取得 模块、控制器和方法
	*		
	*		2	只有 控制器和方法， 没有_MODULE_，示例：  index.php?c=index&a=index
	*/
	"dispatch_level"=>3,
	 
	
	/*
	*	路由：按顺序从上往下执行
	*		类型有：simple、supervar、static、rewrite、regex 
 	* 		"static" => [ "type"=>"static" ], //默认会自动添加一条static路由
	*		
	*/
 	"route_list"=>[
//  		 [ 
// 			"type"=>"rewrite"	,	
// 			"schema"=>"iuserplat/unionUsers/:union_id/:since", 
// 			"route"=>["c"=>"iuserplat","a"=>"unionUsers"]
//  		], 
	]//end route_list
],//end dispatch

"web_address" =>"http://www.jet.com",

/*
*	Redis 配置
*	获取实例 如：
*		$user_redis = Cache::redis("user");
*
*		store_session:	是否存储 session （暂不支持）
*		server格式: host:port:db
*				或  host:port:db:password
*/
"redis"	=>	array (
	"store_session"=>FALSE,
	"time_out"=> 0 ,
	"servers"=>array (
		"user"			=> '127.0.0.1:6379:0',
		"game"			=> '127.0.0.1:6379:1', 
		"code"			=> '127.0.0.1:6379:2',
        "auth"			=> '127.0.0.1:6379:3',
        /*
        *	WXMP_ATOKEN:$OPENID 		微信授权 access_token
        *	WXMP_RTOKEN:$OPENID		微信授权 refresh_token
        */
	)
),

/*
*	
*/
"common" => [
	"db"=> "jet,root,root,127.0.0.1,3306,,utf8",
	//"keys" => [
	//	"8U21OQ3C" => ["JIP7XSRYCTA8V46FSVY0", BIT_1 | BIT_3],
	//	],
    //"db"=> "to://user/db",
],//END user

 
];//END CONFIG!!!