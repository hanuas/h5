<?php 
date_default_timezone_set('Asia/Shanghai');


/* 是否开启调试模式 */
ini_set("display_errors", 1);
error_reporting(E_ALL );



define("CONSOLE_MODE",true);
 
require_once(dirname(__FILE__) . '/../doris/Startup.php');
 
// Doris\DConfig::register("init_config.conf.php"	,"init_config");
// Doris\DConfig::register("rediskeys.conf.php"	,"rediskeys");

//复用 public 模块的配置. 并改成console模块及修改配置路由规则为 supervar
Doris\DConfig::register("public.conf.php"  );
Doris\DConfig::set("web_module", "console");
Doris\DConfig::set("dispatch/route_list",[ [ "type"=>"supervar", "schema"=>"r" ] ]);
		

$_GET = @Doris\DApp::getParasByConsoleArgs($argv );
$_SERVER['REQUEST_URI'] = "/?" . http_build_query($_GET);


$app = Doris\DApp::getInstance("public.conf.php");//加载父类 
function _app(){global $app;return $app;}



_app()->run();

