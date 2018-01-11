<?php 
/**
*	乔成磊 20130827
*/
namespace Doris;

//防注入，添加反斜杠
function str_addslashes(&$_value) {
    if (!empty($_value)) {
        if (is_array($_value)) {
            foreach ($_value as $_key => $_val) {
                str_addslashes($_value[$_key]);
            }
        } else {
            $_value = addslashes($_value);
        }
    }
}


function append_url($url,$key,$value,$encodeValue=true){
	if($encodeValue){
		$value = urlencode($value);
	}
	$jump="{$key}={$value}";
	if(strpos($url,"?")<0)
		$url.='?'.$jump;
	else if(substr($url,-1)=='?'){
		$url.=$jump;
	}else{	
		$url.='&'.$jump;
	}
	return $url;
}

function create_guid($namespace = '',$simple=true) {     
    static $guid = '';
    $uid = uniqid("", true);
    $data = $namespace;
    $data .= $_SERVER['REQUEST_TIME'];
    $data .= $_SERVER['HTTP_USER_AGENT'];
   // $data .= $_SERVER['LOCAL_ADDR'];
    //$data .= $_SERVER['LOCAL_PORT'];
    $data .= $_SERVER['REMOTE_ADDR'];
    $data .= $_SERVER['REMOTE_PORT'];
    $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
	if($simple){
		$guid = $hash;
	}else{
		$guid = '{' .   
            substr($hash,  0,  8) . 
            '-' .
            substr($hash,  8,  4) .
            '-' .
            substr($hash, 12,  4) .
            '-' .
            substr($hash, 16,  4) .
            '-' .
            substr($hash, 20, 12) .
            '}';
	}
    return $guid;
}

function session($name,$val=''){
	if($val===''){//get
		if(!isset($_SESSION[$name]))return false;
		return  $_SESSION[$name];
	}else if($val===null){//del
		 unset( $_SESSION[$name]);
		 return null;
	}else{//set
		$_SESSION[$name]=$val;
		return true;
	}
	return false;
}

function env($name,$val=''){
	if($val===''){//get
		if(empty($_ENV[$name]))return false;
		return  $_ENV[$name];
	}else if($val===null){//del
		 unset( $_ENV[$name]);
		 return null;
	}else{//set
		$_ENV[$name]=$val;
	}
	return false;
}
function mkdirs($dir)  
{	
	if(!file_exists($dir))
		return @mkdir($dir,0777,true);
	return true;
}
/*
function rmdirs($dir)
{
	$d = dir($dir);
	while (false !== ($child = $d->read())){
		if($child != '.' && $child != '..'){
			if(is_dir($dir.'/'.$child))rmdirs($dir.'/'.$child);
			else unlink($dir.'/'.$child);
		}
	}
	$d->close();
	rmdir($dir);
}
*/
//删除目录
function rmdirs($dir) {
	@$dh=opendir($dir);
	while (@$file=readdir($dh)) {
		if($file!="." && $file!="..") {
			$fullpath=$dir."/".$file;
			if(!is_dir($fullpath)) {
				unlink($fullpath);
			} else {
				rmdirs($fullpath);
			}
		}
	}
	@closedir($dh);
	if(@rmdir($dir)) {
		return true;
	} else {
		return false;
	}
}


function isLinux() {return strtoupper(PHP_OS) === 'LINUX';}
function isWin() {return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';}

// 去除代码中的空白和注释
function strip_whitespace($content) {
    $stripStr = '';
    //分析php源码
    $tokens = token_get_all($content);
    $last_space = false;
    for ($i = 0, $j = count($tokens); $i < $j; $i++) {
        if (is_string($tokens[$i])) {
            $last_space = false;
            $stripStr .= $tokens[$i];
        } else {
            switch ($tokens[$i][0]) {
                //过滤各种PHP注释
                case T_COMMENT:
                case T_DOC_COMMENT:
                    break;
                //过滤空格
                case T_WHITESPACE:
                    if (!$last_space) {
                        $stripStr .= ' ';
                        $last_space = true;
                    }
                    break;
                case T_START_HEREDOC:
                    $stripStr .= "<<<THINK\n";
                    break;
                case T_END_HEREDOC:
                    $stripStr .= "THINK;\n";
                    for($k = $i+1; $k < $j; $k++) {
                        if(is_string($tokens[$k]) && $tokens[$k] == ';') {
                            $i = $k;
                            break;
                        } else if($tokens[$k][0] == T_CLOSE_TAG) {
                            break;
                        }
                    }
                    break;
                default:
                    $last_space = false;
                    $stripStr .= $tokens[$i][1];
            }
        }
    }
    return $stripStr;
}
function str_datetime2timestamp($strtime){
	//$strtime = "2013-02-12 16:20:35";
	$array = explode("-",$strtime);
	$year = $array[0];
	$month = $array[1];
	$array = explode(":",$array[2]);
	$minute = $array[1];
	$second = @$array[2]? $array[2]: 0;
	$array = explode(" ",$array[0]);
	$day = $array[0];
	$hour = $array[1];
	$timestamp = mktime($hour,$minute,$second,$month,$day,$year);
	//var_dump(array($hour,$minute,$second,$month,$day,$year));
	return $timestamp;
}
function str_date2timestamp($strtime){
	//$strtime = "2013-02-12";
	$array = explode("-",$strtime);
	$year = $array[0];
	$month = $array[1];
	$day = $array[2];
	$timestamp = mktime(0,0,0,$month,$day,$year);
	//var_dump(array($hour,$minute,$second,$month,$day,$year));
	return $timestamp;
}
function timestamp2str_datetime($time,$hasSeconds=true){
	$time=$time*1;
	$format = 'Y-m-d H:i:s';
	if(!$hasSeconds)
		$format = 'Y-m-d H:i';
	return date($format,$time);
}
function timestamp2str_date($time){
	$time=$time*1;
	return date('Y-m-d',$time);
}


function str_end_with($str,$subStr){
	return substr($str, -(strlen($subStr)))==$subStr;
}
function endWith($str,$subStr){
	return str_end_with($str,$subStr);
}
function beginWith($str, $needle) { 
    return strpos($str, $needle) === 0; 
}
function utf8_array_asort(&$array) {
	if(!isset($array) || !is_array($array)) {
		return false;
	}
	foreach($array as $k=>$v) {
		$array[$k] = iconv('UTF-8', 'GBK//IGNORE',$v);
	}
	asort($array);
	foreach($array as $k=>$v) {
		$array[$k] = iconv('GBK', 'UTF-8//IGNORE', $v);
	}
	return true;
}

function utf8_header() {
	header("Content-type: text/html; charset=utf-8");
}

function dump($var,$exit=true){
	header("Content-type: text/html; charset=utf-8");
	
	echo "<pre>";
	if(is_array($var))
		print_r($var);
	else	
		var_dump($var);
		
	if($exit)
		exit();
}

function getClientIP() {
    if (isset($_SERVER)) {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")) {
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }

    return addslashes($realip);
}

// xml编码
function data_to_xml($data) {
	$xml = '';
	foreach ($data as $key => $val) {
		is_numeric($key) && $key = "item id=\"$key\"";
		$xml.="<$key>";
		$xml.= ( is_array($val) || is_object($val)) ? data_to_xml($val) : $val;
		list($key, ) = explode(' ', $key);
		$xml.="</$key>";
	}
	return $xml;
}
function xml_encode($data, $encoding='utf-8', $root='root') {
	$xml = '<?xml version="1.0" encoding="' . $encoding . '"?>';
	$xml.= '<' . $root . '>';
	$xml.= data_to_xml($data);
	$xml.= '</' . $root . '>';
	return $xml;
}
function debugWeb($data, $exit = true){
	
	header("Content-type: text/html; charset=utf-8");
 	echo "<pre>";
 	if(is_array($data )){
 		print_r($data);
 	}else{
 		var_dump($data);
 	}
 	echo "</pre>";
 	if($exit)exit;
}

function send($url, $data,$method='POST') {
	$postdata = http_build_query($data);
	
	if(strtoupper($method) =="GET"){
		$jointer = "";
		if( substr($url,-1) != '?' ){//如果末尾字符不是 '?' 则继续判断
			//如果有 '?' 则添加 '&' 否则添加 '?'
			$jointer = ((strpos($url, '?') !== false) ? '&' : '?');
		}
		$url .= $jointer.$postdata;
		$postdata = "";
		
	}
	
	$options = array(
			'http' => array(
					'method' => $method,
					'header' => 'Content-type:application/x-www-form-urlencoded',
					'content' => $postdata,
					'timeout' => 15 * 60 // 超时时间（单位:s）
			)
	);
	$context = stream_context_create($options);
	$result = @file_get_contents($url, false, $context);
	//echo " $method";
	return [$result ,$url];
}

	