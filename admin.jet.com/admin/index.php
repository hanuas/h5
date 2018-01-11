<?php 
date_default_timezone_set('Asia/Shanghai');


/* 是否开启调试模式 */
ini_set("display_errors", 1);
error_reporting(E_ALL );




require '../doris/Startup.php';

$app = Doris\DApp::getInstance("admin.conf.php");

Doris\DConfig::register("init_config.conf.php"	,"init_config");
Doris\DConfig::register("rediskeys.conf.php"	,"rediskeys");		
Doris\DConfig::register("rediskeys.conf.php","rediskeys");			
		
function _app(){global $app;return $app;}

$app->run();

