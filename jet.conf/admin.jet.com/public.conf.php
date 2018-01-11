<?php 
/**
*	前台主配置
*
*	乔成磊 20150816
*/

return [ //BEGIN CONFIG!!!

/*
可选参数，用于跑测试接口
*/
"can_test_action"=>true,

/*
*	本配置文件代表的web模块。比如：用admin表示后台模块，则：
*			前端虚拟机指向：_ROOT_DIR_/admin
*			控制器根目录为：_ROOT_DIR_/ctl_root/admin/controller
*			控制器模板目录为：_ROOT_DIR_/ctl_root/admin/tpl
*
*/
"web_module"=>"public",


/*
*	钩子插件：
*		
*/
"plugin"=>"PublicPlugin",


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
	"dispatch_level"=>2,
	
	//"base_uri"=>"/www.imback.net/public",
	
	/*
	*	路由：按顺序从上往下执行
	*		类型有：simple、supervar、static、rewrite、regex 
	*		
	*/
 	"route_list"=>[
 		//"static"	=>	[ "type"=>"static" ], //默认会自动添加一条static路由
	]//end route_list
],//end dispatch


/*
*	插件：
*		字符串类型, 插件
*/
"plugin"=>"PublicPlugin",


/*
*	日志配置
*		root:	日志根目录，为空或false时取默认值： _ROOT_DIR_/data/log
*		folder:	子目录,null时表示没有子目录，写入当前目录；不空时写入子目录
*		type_path_prefix： 日志类型及日志存储的数组
*			key: 日志类型，例如：DLog::log("testLog",DLOG_INFO);   DLOG_INFO即为日志类型
*			value（字符串时）: 	最后一个“/”之前的内容与folder一起组成日志所在文件夹；最后一个 “/” 之后的字串为日志文件名前缀；
*			value（数组时）: 数据第一项与 value为字符串时含意一样，第二个元素表示文件后缀的日期格式，如Ymd、Ym，好处是方便日志按时间粒度拆分文件	
*		type_path_prefix_default 当没有在type_path_prefix中找到合适的日志类型时，该值充值默认配置。
*/

"log"=>array(
	//"root"=>'', 
	"folder"=>"public",
	"type_path_prefix_default"=>"common/common_",
	"type_path_prefix"=>[
		DLOG_BUSINESS	=>["business/business_","Ym"],//若为数组，则第二元素是文件日期后缀的dateFromat
		"pay"			=>"pay/pay_",
		"consume"		=>"pay/pay_",
		"paymark"		=>"pay/paymark_",
		DLOG_ERROR		=>"common/common_",
		DLOG_EXCEPTION	=>"common/common_",
		DLOG_FATAL		=>"common/common_",
		DLOG_WARNING	=>"common/common_",
		DLOG_DATA		=>["data/data_","Ym"],
		"click"			=>["data/click_","Ymd"],
		"login"			=>"login/login_",
		DLOG_RPC		=>["business/business_","Ym"],//远程调用
	],
),


/*
*	Memcache配置
*	is_use:	是否启用
*	user 用户名，字符串
*	pass 密码，字符串
*	servers 主机，格式：	host:port:weight
*/
"memcache"	=>	array (
	'is_use'=>0,
	'use_memcached'=>1,
	//"store_session"=>FALSE,
	'user'=>null,
	'pass'=>null,
	"servers"=>array (
		'192.168.1.167:11211:100',
		'192.168.1.183:11211:100'
	)
),

/*
*	Redis 配置
*	store_session:	是否存储 session （暂不支持）
*	server格式： host:port:db
*/
"redis"	=>	array (
	"store_session"=>FALSE,
	"time_out"=>0 ,
	"servers"=>array (
		"rUser"			=>'127.0.0.1:6379:0',
		"rVirtue"		=>'127.0.0.1:6379:1',
		"rIpLimits"		=>'127.0.0.1:6379:2'
	)
),


/*
*	DB配置
*		host 主机，数组类型，表示该组内的所有主机
*		username、password、port 即可为数组，也可以为字符串
*			当为数组时：数组长度与host一致，且一一对应
*			当为字符串时表示：针对本组内所有host，在该项上的配置一致（比如，本组内所有主机密码一样时，password 则可配置成密码字符串）
*		name 数据库名称，字符串类型
*		charset 字符集，字符串类型
*/
"db"=>array (
	"main"=>array(
		'name'=>"h5sdk",
		'host'=>['gonghui-netkingol-com.mysql.rds.aliyuncs.com' , 'gonghui-netkingol-com.mysql.rds.aliyuncs.com'],
		'username' => 'wr_user',
		'password' => 'Aa123456',
		'port'=>"",
		'charset' => 'utf8'
	), 
	"slave"=>array( 'point_to'=>"main" ),
	"userdb_main"=>array( 'point_to'=>"main" ),
	"userdb_slave"=>array( 'point_to'=>"main" ),
),

/*
*	目录配置
*	
*/
"dir" => array(
	"public"	=> _ROOT_DIR_ . "public",
	"admin"		=> _ROOT_DIR_ . "admin",
),


"site" => array( 
	"admin"		=> "http://opentool.netkingol.com",
),


/**
*	ICenter配置
**/
"icenter" => array( 
	"host"  => "http://icenter.netkingol.com",
	"keys"	=> array(
		"iuserplat" => ["8U21OQ3C","JIP7XSRYCTA8V46FSVY0"],
		"iorder" 	=> ["B26A749G","PEN7EVTHK8UBRWXCWSB2"],
		"iunion" 	=> ["B36L638X","WSN56VQHKDUBSWCCWSB1"],
		
	),
),//end icenter
 

 
];//END CONFIG!!!