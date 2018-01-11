<?php
/***************************************************************************************
 *
 *
 * Description:   日志模块，所有日志通过该模块打印
 * Author:        乔成磊
 *
 ***************************************************************************************/
namespace Doris;

//日志类别
define('DLOG_BUSINESS'	, 'business');
define('DLOG_ERROR'		, 'error');
define('DLOG_EXCEPTION'	, 'exception');
define('DLOG_FATAL'		, 'fatal');
define('DLOG_WARNING'	, 'warning');
define('DLOG_INFO'		, 'info');


define('DLOG_DATA'	, 'data');
define('DLOG_CLICK'	, 'click');
define('DLOG_RPC'	, 'rpc');

class DLog 
{
	
	/**
	 * write the $msg with $type to log file
	 *
	 * @param unknown_type $type values in (DLOG_ERROR, DLOG_ATTACK, DLOG_INFO, DLOG_WARNING,DLOG_BUSINESS)
	 * @param unknown_type $msg the msg write to file
	 */
	static private function writeLog($type, $msg) 
	{
		list($dir,$prefix,$date) = self::logConf($type);
	    if(!is_dir($dir)){
			mkdir($dir, 0777,true);
			
		}
		
		$time = date("Ymd H:i:s", time());
		$file = $dir . DS . $prefix . $date . ".log";
		$content = "[{$time}][{$type}]: {$msg}\r\n";
		//echo $file;
		@ file_put_contents($file, $content, FILE_APPEND|LOCK_EX);
	}
	static private function writeOriginalLog($type,$content) 
	{
		list($dir,$prefix,$date) = self::logConf($type);
	    if(!is_dir($dir)){
			mkdir($dir, 0777,true);
		}
		$file = $dir . DS . $prefix . $date . ".log";
		@ file_put_contents($file, $content, FILE_APPEND|LOCK_EX);
	}
	
	static public function log($msg,$type=DLOG_INFO)
	{
		self::writeLog($type,$msg);
	}
	static public function error($msg)
	{
		self::writeLog(DLOG_ERROR,$msg);
	}
	
	/**
	*	content 字条串内容
	*/
	static public function dataLog($content,$type = DLOG_DATA)
	{
		self::writeOriginalLog($type,$content);
	}
	
	static public function arrayLog($array,$type = DLOG_DATA, $separator = ",")
	{
		$content = implode($separator, $array);
		$content .= "\r\n";
		self::writeOriginalLog($type,$content);
	}
	/**
	 * 程序Exception
	 * @param Exception $e
	 */
	static public function exception(\Exception $e)
	{
		self::writeLog(DLOG_EXCEPTION ,$e->getMessage().";".$e->getFile()."; on line ".$e->getLine());
	}


	/**
	 * 远程调用日志文件
	 *
	 * @param string $type
	 * @param int $return
	 * @param string $message
	 */
	static public function rpc( $return, $message, $type = DLOG_RPC) 
	{
		$time = date("Ymd H:i:s", time());
		$ip = getClientIP();
		$method = $_SERVER['REQUEST_METHOD'];
		$url = $_SERVER['REQUEST_URI'];
		$post='post:';
		if (strtoupper($method) == 'POST') {
		    //$url .= '?' . http_build_query($_POST, null, '&');
			$post .=  http_build_query($_POST, null, '&');
		}
		$content = "$time|$type|$return|$message|$ip|$method|$url|$post\r\n";
		self::writeOriginalLog($type,$content);
	}
	
	/**
	 * 记录用户登录状态日志
	 *
	 * @param unknown_type $username  用户名
	 * @param unknown_type $status	  登录成功与否
	 * @param unknown_type $info      失败原因
	 */
// 	static public function logLoginStatus($username,$status,$consume_time, $type){
// 		
// 		$real_ip = getClientIP();
// 		$date = date("Ymd", time());
// 		$time = date("Y-m-d H:i:s", time());
// 		$status_cn = ($status > 0)?"成功":"失败";
// 		$content = $username.",".$real_ip.",".$status_cn.",".$time.",".$consume_time."\r\n";
// 		self::writeOriginalLog($type,$content);
// 	}
// 	
	
	static public function logConf($type){
		try{
			$conf = DConfig::get("log");
			$root = @$conf['root'];
			if(!$root)
				$root = _LOG_DIR_;
			else
				$root .= "/";
			$logDir = $root . $conf["folder"].DS;
			$typePathPrefixes =  $conf["type_path_prefix"];
			$pathPrefix =    $conf["type_path_prefix_default"];
			if(is_array($typePathPrefixes) && array_key_exists($type,$typePathPrefixes)){
				$pathPrefix = $typePathPrefixes[$type];
			}
			$dateFormat="Ymd";
			if(is_array($pathPrefix)){
				list($pathPrefix,$dateFormat)=$pathPrefix;
			}
			$postfix = date($dateFormat, time());
			
			$file_prefix = substr($pathPrefix,strrpos($pathPrefix,'/'));
			$file_prefix = ltrim($file_prefix,'/');
			$dir = rtrim($logDir,$file_prefix) . substr($pathPrefix,0,strrpos($pathPrefix,'/'));
			
		}catch (Exception $e){
			$logDir = _LOG_DIR_;
			$file_prefix ="error_";
			$postfix = date("Ym", time());
		}
		//echo $dir;
		return [$dir,$file_prefix,$postfix];
	}
}
