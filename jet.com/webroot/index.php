<?php

/* 是否开启调试模式 */
ini_set("display_errors", 1);
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ALL );

include_once "../app/common/frame.php";  
//$_GET = Func::getParasByConsoleArgs($argv ); //命令行时用这个语法

if(empty($_GET['env'])) {
    Config::register("../app/common/config.php");
}else {
    Config::register("../app/common/config-${env}.php");
}
Config::register("../app/common/game_config.php","game");


include_once "../app/common/dispatch.php";
include_once "../app/common/plugin.php";
include_once "../app/common/cache.php";
include_once "../app/common/functions.php";

$dispatch = new Dispatch( Config::get("dispatch") , new Plugin() );
Config::configExt("");

header("Access-Control-Allow-Origin: *");
header("Content-type:text/html;charset=utf-8");
$dispatch->dipatch();	
 