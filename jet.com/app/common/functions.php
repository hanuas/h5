<?php
// 从NKBOSS里拷贝来的
/**
 * 用户分表HASH算法
 *
 * @param string $key 字符串
 * @param int $n 分表数
 * @return string 00-63
 */
function getHash(&$key, $n = 64) {
    $hash = crc32($key) >> 16 & 0xffff;
    return sprintf("%02s", $hash % $n);
}

function isAttack($ca, $key, $expire = 3600, $num = 10) {
    //$ca = CCache::Instance();
    $loadnum = $ca->Get($key);
    
    if (empty($loadnum)) {
        $loadnum = 1;
        $ca->Set($key, $loadnum, false, $expire);
    }

    $ca->Inc($key, 1);

    if (!empty($loadnum) && is_numeric($loadnum)) {
        if (intval($loadnum) > intval($num)) {
            return false;
        }
    }

    return true;
}

function day_num($year,$month)   //获取某年某月的天数   
{  
	$big_month=array(1,3,5,7,8,10,12);  
	$sm_month=array(4,6,9,11);  
	if(in_array($month,$big_month))  
	{     
		$day_num=31;  
	}  
	else if(in_array($month,$sm_month))  
	{ 
		$day_num=30;  
	}  
	else  
	{        
		if($year%4==0 && ($year%100!=0 || $year%400==0))//闰年    
		{       
			$day_num=29;         
		}   
		else    
		{      
			$day_num=28;        
		}  
	}  
	return $day_num;   
}


//接口输出规范
//乔成磊 20140522 检查是不是ticket安全传输的，是的话就用ticket封装返回
function json_display($code, $message, $data = array()) {
	
	if(isset($_POST['ticket'])){
		json_display_safed($code, $message, $data);
	}else{
		json_display_direct($code, $message, $data);
	}
	
}
function json_display_direct($code, $message, $data ) {

    $display = array(
        'code' => $code,
        'message' => $message,
    );
    if (!empty($data)) {
        $display['data'] = $data;
    }
    die(json_encode($display));
}

function json_exit($error,$message,$data = array()){
    $json_arr = array(
        'error'=>$error,
        'message'=>$message
    );
    if (!empty($data)) {
        $json_arr['data'] = $data;
    }
    die(json_encode($json_arr));
}

function json_flag_exit($error,$flag,$message,$data = array()){
    $json_arr = array(
        'error'=>$error,
        'flag'=>$flag,
        'message'=>$message
    );
    if (!empty($data)) {
        $json_arr['data'] = $data;
    }
    die(json_encode($json_arr));
}

function json_display_safed($code, $message, $data ) {

	global $_apimobTicketTraslator;
	//$_apimobTicketTraslator->reply($_POST);
	if($_apimobTicketTraslator)
		$_apimobTicketTraslator->reply($data,$code, $message);
}



function encryptPassword($password) {
    return md5(sha1($password) . SITE_ENCRYPT_KEY);
}

function encryptPassword2($password,$user_id) {
	return md5(md5(trim($password)).$user_id);
}

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

function args_addslashes() {
    if (!get_magic_quotes_gpc()) {
        str_addslashes($_GET);
        str_addslashes($_POST);
        str_addslashes($_COOKIE);
    }
}

//用于获取用户IP
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

//用于服务端IP检验
function getClientIp2() {
    if (isset($_SERVER)) {
        $realip = $_SERVER["REMOTE_ADDR"];
    } else {
        $realip = getenv("REMOTE_ADDR");
    }

    return addslashes($realip);
}

//用于服务端IP检验
function getServerIp() {
    $realip = @file_get_contents('/data/ip.txt');
    $realip = trim($realip);
    if(!$realip){
        if (isset($_SERVER)) {
            $realip = $_SERVER["SERVER_ADDR"];
        } else {
            $realip = getenv("SERVER_ADDR");
        }
    }
    return addslashes($realip);
}

// EMAIL 是否合法
function isEmail($vStr) {
    return preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $vStr);
}

// 昵称 是否合法
function isNickName($nick_name) {
    if (!isNickNameFormat($nick_name))
        return false;
    return !!isNotFilter($nick_name);
}

function isNickNameFormat($nick_name) {
    if (!is_string($nick_name))
        return false;
    return !!isValidWord($nick_name, 4, 25);
}

// 字符串长度 是否在制定范围内
function isValidWord($vStr, $vMinLength, $vMaxLength) {
    $vLength = strLength($vStr);
    if ($vLength < $vMinLength || $vLength > $vMaxLength) {
        return false;
    } else {
        return preg_match('/^([\x80-\xff\w])+$/', $vStr);
    }
}

// 字符串 长度
function strLength($vString) {
    $vLength = 0;

    for ($i = 0; $i < strlen($vString);) {
        $ascNum = Ord($vString{$i});

        if ($ascNum >= 224) {
            $vLength += 2;
            $i += 3;
        } elseif ($ascNum >= 192) {
            $vLength += 2;
            $i += 2;
        } else {
            $vLength += 1;
            $i += 1;
        }
    }

    return $vLength;
}

// 字符串是否 不包含屏蔽词
function isNotFilter($word) {
    if (empty($GLOBALS['filterReg'])) {
        include_once(CONFIG_PATH . 'filter.inc.php');
        $filterWordsArray = array_unique($filterWordsArray);
        $GLOBALS['filterReg'] = '/' . implode('|', array_map('preg_quote', $filterWordsArray)) . '/is';
    }
    return preg_match($GLOBALS['filterReg'], $word) === 0;
}

// 密码 格式、长度 是否合法
function isPassword($password) {
    //return !!preg_match('/^[\w]{6,20}$/', $password);
    #return !!preg_match('/^[^\'"]{6,20}$/', $password);
    #return !!preg_match('/[a-zA-Z\w]{6,20}$/', $password);
    return !!preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,10}$/',$password);

}

// 用户名 是否合法。
function isUserName($user_name) {
	if (in_array($user_name,array('客服01','客服02','客服03','客服05','客服06','客服07'))) return true;
    if (isEmail($user_name))
        return true;
    return !!preg_match('/^[a-zA-Z][\w]{6,50}$/', $user_name);
}

// 手机号码 是否合法。
function isMobile($mobile) {
	
//    return !!preg_match('/^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/', $mobile);
    //return !!preg_match('/^\d{8,16}$/', $mobile);
    return !!preg_match("/1[345789]{1}\d{9}$/",$mobile);
}
//手机短信验证码
function isSmsCode($code){
     return !!preg_match('/^[1-9]\d{6}$/', $code);
}
/**
 * 分页:1 2 3 4 5
 * @param $total 总页数
 * @param $page 当前页码
 * @param $count 每页显示条数
 * @param $pagelong 显示的可点击页数
 * @param $prefix 页码链接
 * @param $text 页码前的文字说明
 * */
function getPageNav($total, $page, $count, $pagelong, $prefix, $suffix = '', $text = '') {
    //总页数
    $pages = ceil($total / $count);
    $link = '{$prefix}&page={$i}{$suffix}';

    $middle_number = ceil($pagelong / 2); //middle number
    if ($page <= $middle_number) {
        $first_page = 1;
        $end_page = & $pagelong;
    } else {
        $first_page = $page - ($middle_number - 1);
        $end_page = $page + ($middle_number - 1);
    }

    $previous_page = $page - 1;
    $next_page = $page + 1;

    $pagelist = '<div class="page"><font style="margin-right: 2px;">' . $text . '</font>';
    //$pagelist = '<div class="page"><font style="float: left; margin-right: 2px;">' . $text . '</font>';

    if($page > 1)
        $pagelist .='<a href="' . substr($prefix,0,-1) .$suffix. '">首页</a>'; //shangyiye
        //$pagelist .='<a href="' . $prefix .'&page='. ($page-1) .$suffix. '">首页</a>'; //shangyiye
    else
        $pagelist .='<a href="javascript:void(0);">首页</a>';//shangyiye
    //分页列表
    $str = $link;
    for ($i = $first_page; $i <= $end_page; $i++) {
        eval("\$str = \"$str\";");
        $i == $page ? $pagelist .= '<a href="' . $str . '"  class="on">' . $i . '</a>' : $pagelist .= '<a href="' . $str . '">' . $i . '</a>';

        $str = $link;
        if ($i >= $pages)
            break;
    }
    if(($page)*$count <= $total)
        $pagelist .='<a href="' . $prefix .'&page='. $pages .$suffix. '">尾页</a>';
        //$pagelist .='<a href="' . $prefix .'&page='. ($page+1) .$suffix. '">尾页</a>'; //xiayiye
    else
        $pagelist .='<a href="javascript:void(0);">尾页</a>'; //xiayiye
    $pagelist .= '</div>';

    return $pagelist;
}

//生成订单ID
function generateOrderId() {
    return date('ymdHis') . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
}

function vCode($num = 4, $size = 20, $width = 0, $height = 0) {
    !$width && $width = $num * $size * 4 / 5 + 5;
    !$height && $height = $size + 10;
    // 去掉了 0 1 O l 等
    $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVW";
    $code = '';
    for ($i = 0; $i < $num; $i++) {
        $code .= $str[mt_rand(0, strlen($str) - 1)];
    }
    // 画图像
    $im = imagecreatetruecolor($width, $height);
    // 定义要用到的颜色
    $back_color = imagecolorallocate($im, 235, 236, 237);
    $boer_color = imagecolorallocate($im, 118, 151, 199);
    $text_color = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
    // 画背景
    imagefilledrectangle($im, 0, 0, $width, $height, $back_color);
    // 画边框
    imagerectangle($im, 0, 0, $width - 1, $height - 1, $boer_color);
    // 画干扰线
    for ($i = 0; $i < 5; $i++) {
        $font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        imagearc($im, mt_rand(- $width, $width), mt_rand(- $height, $height), mt_rand(30, $width * 2), mt_rand(20, $height * 2), mt_rand(0, 360), mt_rand(0, 360), $font_color);
    }
    // 画干扰点
    for ($i = 0; $i < 50; $i++) {
        $font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $font_color);
    }
    // 画验证码
    @imagefttext($im, $size, 0, 5, $size + 3, $text_color, ROOT_PATH . '/web/static/font/tahoma.ttf', $code);
    $_SESSION["VerifyCode"] = $code;
    header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
    header("Content-type: image/png;charset=gb2312");
    imagepng($im);
    imagedestroy($im);
}

function traverse($file_arr = array(''), $path = '.') {
	$title_pa = '%<title.*?>(.*?)</title>%si';
	$domain = explode('.',SITE_SSO_DOMAIN);
	$domain = $domain[0];
	$from_arr = array("<a href='http://dede.netkinggame.net/'>主页</a> > ",
					"/a/".$domain."/'",
					"/a/".$domain
					);
	$to_arr = array("",
					"/'",
					"/article/column"
					);

	$current_dir = opendir($path);    //opendir()返回一个目录句柄,失败返回false                                       
	while (($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目                     
		$sub_dir = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径                                               
		if ($file == '.' || $file == '..' || strpos($file, 'list') !== false || strpos($file, 'index') !== false) {
			continue;
	} else if (is_dir($sub_dir)) {    //如果是目录,进行递归
			//echo 'Directory ' . $file . ':<br>';                                                                          
			$file_arr = traverse($file_arr, $sub_dir);
	} else {    //如果是文件,直接输出                                                                              
			//echo 'File in Directory ' . $path . ': ' . $file . '<br>';                                                    
			if (substr($file, -4) == 'html') {
				$dd = explode('article', $path);
				$dd = explode("/", $dd[1]);
				unset($dd[0]);
				$h['fname'] = $file;
				$str = file_get_contents($path . '/' . $file);
				preg_match_all($title_pa, $str, $match);

				$h['y'] = $dd[count($dd) - 1];
				$h['md'] = $dd[count($dd)];
				unset($dd[count($dd)]);
				unset($dd[count($dd)]);
				$h['title'] = $match[1][0];
				$h['title'] = $match[1][0];
				//$h['create_time'] = date('Y-m-d H:i:s',filectime($path.'/'.$file));
				//$h['mod_time'] = date('Y-m-d H:i:s',filemtime($path.'/'.$file));

				//文章所属分类                                                                        
				preg_match('%当前位置:</strong>(.*?)</div>%', $str, $match6);    
				$h['dir'] = $match6[1];  
				$h['dir'] = str_replace($from_arr,$to_arr,$h['dir']);
				$h['dir'] = preg_replace("/\'>(.*?\_)/si","'>",$h['dir']);//过滤<__("<"号后面带空格)

				//叶子栏目的名称 链接
				preg_match_all("%<a\shref='(.*?)'>(.*?)</a>%",$h['dir'],$match7);
				$h['col_name'] = $match7[2][count($match7[2])-1];
				$h['col_name'] = preg_replace("/\/'>/","",$h['col_name']);
				$h['col_url'] = $match7[1][count($match7[1])-1];
				//$h['dir_end'] = $match7[0][count($match7[0])-1];

				preg_match('%<span\sclass="source">(.*?)</span>%', $str, $match2);
				$h['source'] = $match2[1];
                //獲取權重 用來排序
                preg_match('%<span\sclass="weight">(.*?)</span>%', $str, $match3);
				$h['weight'] = $match3[1];
				//文章缩略图
				preg_match('%<span\sclass="litpic">(.*?)</span>%', $str, $match5);
				$h['litpic'] = $match5[1];
				//简略标题
				preg_match('%<span\sclass="stitle">(.*?)</span>%', $str, $match5);
				$h['stitle'] = $match5[1];

				preg_match('%<span\sclass="pubdate">(.*?)</span>%', $str, $match3);
				$h['pubdate'] = $match3[1];
				//if($dd[2]=="screenshot") {
				preg_match('<img.*?src="(/uploads.*?)".*?>', $str, $match4);
				$h['img_url'] = $match4[1];

				//文章推荐、头条等状态
				preg_match('%<span\sclass="flag">(.*?)</span>%', $str, $match8);
				$a_flag = explode(',',$match8[1]);
				foreach ($a_flag as $f) {
					if (trim($f) != '') $h[trim($f)] = 1;
				}

				//叶子栏目的目录
				$new_path = strstr($path, 'article');
				$h['path'] = strstr($new_path, '/');
				$h['column'] = explode("/",$h['path']);
				$h['column'] = $h['column'][count($h['column'])-3];

				$str = '$file_arr["' . implode('"]["', $dd) . '"][] = $h;';
				eval($str);
			}
		}
	}
	return $file_arr;
}
function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();
   
    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
   
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}


function build_request1($action) {
    $ts = time();
    $sign = md5($action . md5($ts . SKEY));

    return array(
        'action' => $action,
        'ts' => $ts,
        'sign' => $sign
    );
}

function request_admin($request, $url) {
    // 构造 HTTP POST 请求
    $query = http_build_query($request);

    // 用 curl 发送 HTTP POST 请求
    $curl = curl_init();
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $query,
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);

    // 解析服务器的响应
    if (curl_error($curl)) {
        $msg = sprintf("Send request to %s failed: %s\n", $url, curl_error($curl));
        return array('result' => false, 'info' => $msg);
    } else {
        $info = curl_getinfo($curl);
        if ($info['http_code'] == 200) {
            $result = json_decode($response, true);
            if (is_array($result)) {
                return $result;
            }
        }
        $msg = sprintf("code=%d, response=%s\n", $info['http_code'], $response);
        return array('result' => false, 'info' => $msg);
    }
}

function http_post($url, $post) {
    // Initialize a cURL session:
    $c = curl_init();
    // Set the URL that we are going to talk to:
    curl_setopt($c, CURLOPT_URL, $url);
    // Now tell cURL that we are doing a POST, and give it the data:
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, $post);
    // Tell cURL to return the output of the page, instead of echo'ing it:
    curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
    // Now, execute the request, and return the data that we receive:
    $output = curl_exec($c);
	// Check if any error occured
	$info = curl_getinfo($c);
	
	if ($output === false || $info['http_code'] != 200) {
	  $output = "No cURL data returned for $url [". $info['http_code']. "]";
	  if (curl_error($c))
		$output .= "\n". curl_error($c);
	}
	curl_close($c);
	return $output;
}

function ys_verify ($query, $ys, $secret, $version) {
    $query .= $secret;
    $b64 = base64_encode (pack("H*", sha1($query)));
    $b64 = str_replace (array('+', '/', '='), array('.', '_', '-'), $b64);

    if ($version > 0) {
        $buf = sprintf ("%c", $version + 64);
        $b64 .= "~$buf";
    }
    if (strcmp ($ys, $b64)) {
        return 1;
    }
    return 0;
}


// You can find the following functions and more details
// on https://developers.facebook.com/docs/authentication/canvas
function parse_signed_request($signed_request, $secret) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2);

  // Decode the data
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
	error_log('Unknown algorithm. Expected HMAC-SHA256');
	return null;
  }

  // check signature
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
	error_log('Bad Signed JSON signature!');
	return null;
  }
  return $data;
}

function base64_url_decode($input) {
  return base64_decode(strtr($input, '-_', '+/'));
}

function hmacsha1($key,$data) {
    $blocksize=64;
    $hashfunc='sha1';
    if (strlen($key)>$blocksize)
        $key=pack('H*', $hashfunc($key));
    $key=str_pad($key,$blocksize,chr(0x00));
    $ipad=str_repeat(chr(0x36),$blocksize);
    $opad=str_repeat(chr(0x5c),$blocksize);
    $hmac = pack(
                'H*',$hashfunc(
                    ($key^$opad).pack(
                        'H*',$hashfunc(
                            ($key^$ipad).$data
                        )
                    )
                )
            );
    return bin2hex($hmac);
}

//生成二维码
function generateQR($chl,$widhtHeight ='150',$EC_level='L',$margin='0') 
{ 
	include ROOT_PATH."lib/qrcode/phpqrcode.php";
	$value = $chl;
	$errorCorrectionLevel = "L";
	$matrixPointSize = "4";
	QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize);
	
} 

//生成動態密碼
function getDynamicPass($phone,$ifa,$time='',$cycle=30) {
	$key = 'c7015b804d3705310934bbc119cbebce';
	if (!$time) $time = time();
	$time = $time - $time%$cycle;
	$code = md5(md5($phone.$ifa).$key.$time);
	$abstr = 'abcdefghijklmnopqrstuvwxyz';
	for ($i=0;$i<6;$i++) {
		$str .= substr($abstr,hexdec(substr($code,$i*4,4))%26,1);
	}
	return $str;
}

function writePostLog($dir,$title) {
	$loginLogFile = ROOT_PATH.'/log/'.$dir.'/'.$dir.'-' . date('Ymd') . '.log-'.date('H').'.'.getServerIp();
	if ($dir == 'apimob' && ($title == 'pay' || $title == 'paymark')) $loginLogFile = ROOT_PATH.'/log/'.$dir.'/pay_mark-' . date('Ymd') . '.log-'.date('H').'.'.getServerIp();
	
	if (!file_exists(ROOT_PATH.'/log/'.$dir))
		mkdir(ROOT_PATH.'/log/'.$dir);

	$loginLogMessage = $title.':'.date('Y-m-d H:i:s')." ".getClientIP()." Get:";
	foreach ($_GET as $key => $value) {
		$loginLogMessage .= $key . ':' . $value . ';';
	}
	$loginLogMessage .= " Post:";
	foreach ($_POST as $key => $value) {
		$loginLogMessage .= $key . ':' . $value . ';';
	}
	$loginLogMessage .= "\n";
    error_log($loginLogMessage, 3, $loginLogFile);
	
}

function writeLog($dir,$loginLogMessage) {
	$loginLogFile = ROOT_PATH.'/log/'.$dir.'/'.$dir.'-' . date('Ymd') . '.log'.'.'.getServerIp();
	
	if (!file_exists(ROOT_PATH.'/log/'.$dir))
		mkdir(ROOT_PATH.'/log/'.$dir);

	$loginLogMessage .= "\n";
    error_log($loginLogMessage, 3, $loginLogFile);
	
}

function getCookie($name, $default=null, $trim=true) 
{
    return getP($_COOKIE, $name, $default, $trim);
}

//根据session_id 得到session信息，注意：会清楚当前session_id的信息
function getSessionBySessionId($session_id){
    if($session_id == session_id()){
        return $_SESSION;
    }
    session_destroy();
    session_id($session_id);
    session_start();
    return $_SESSION;
}

function getSession($name, $default=null, $trim=true) 
{
    return getP($_SESSION, $name, $default, $trim);
}

function getPost($name, $default=null, $trim=true) 
{
    return getP($_POST, $name, $default, $trim);
}

function getGet($name, $default=null, $trim=true) 
{
    return getP($_GET, $name, $default, $trim);
}

function getParam($name, $default=null, $trim=true) 
{
    return getP(getParams(), $name, $default, $trim);
}

function getParams() 
{
    return array_merge($_GET, $_POST);
}

function hasPost($name) 
{
    return isset($_POST[$name]);
}

function hasGet($name) 
{
    return isset($_GET[$name]);
}

function hasParam($name) 
{
    $params = getParams();
    return isset($params[$name]);
}

function getP($data, $name, $default=null, $trim=true) 
{
    if(isset($data[$name])) {
        return $trim ? trimParam($data[$name]) : $data[$name];
    } else {
        return $default;
    }
}

function trimParam($param) 
{
    if(!is_array($param)) {
        return trim($param);
    }

    foreach($param as $k=>&$v) {
       if(!is_array($v)) {
            $v = trim($v);
        } else {
            $v = trimParam($v);
        } 
    }
    return $param;
}
/*
 * 生成cms_article表中的flag位
 */
function creatArticleFlag($arr){
	$str =0;
	if($arr['h'])
		$str = $str|1;
	if($arr['c'])
		$str= $str|2;
	if($arr['f'])
		$str = $str|4;
	if($arr['a'])
		$str = $str|8;
	if($arr['s'])
		$str = $str|16;
	if($arr['b'])
		$str = $str|32;
	if($arr['p'])
		$str = $str|64;
	if($arr['j'])
		$str = $str|128;
	return $str;
}
/*
 * 查询某个标志位是否为1
 * @flag 表示数据
 * @type 要返回的标志位
 */
function checkArtFlag($flag,$type){
    switch ($type){
        case 'h':
            return $flag&1;
            break;
        case 'c':
            return $flag&2;
        case 'f':
            return $flag&4;
        case 'a':
            return $flag&8;
        case 's':
            return $flag&16;
        case 'b':
            return $flag&32;
        case 'p':
            return $flag&64;
        case 'j':
            return $flag&128;
        default :
            return false;
    }
}

//版本号对比 如果v1较新，返回1；如果一样返回0
function compareVersion($v1,$v2) {
	$v1 = str_replace(array('Android', 'ios'),array('',''), $v1);
	$v1_arr = explode('.', $v1);
	$v2_arr = explode('.', $v2);
	foreach ($v1_arr as $key=>$value) {
		if ($value > $v2_arr[$key]) {
			return 1;
		}
		else if ($value < $v2_arr[$key]) {
			return -1;
		}
	}
	return 0;
}

//获取当前时间的毫秒数
function getMillisecond() {
    list($s1, $s2) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}

function clientIP(){   
  $cIP = getenv('REMOTE_ADDR');   
  $cIP1 = getenv('HTTP_X_FORWARDED_FOR');   
  $cIP2 = getenv('HTTP_CLIENT_IP');   
  $cIP1 ? $cIP = $cIP1 : null;   
  $cIP2 ? $cIP = $cIP2 : null;   
  return $cIP;   
}   
 function serverIP(){   
   return gethostbyname($_SERVER["SERVER_NAME"]);   
}  

function serverIpLinux(){ //用ifconfig读取服务器IP并输出为数组
  $ss = exec('/sbin/ifconfig | sed -n "s/^ *.*addr:\([0-9.]\{7,\}\) .*$/\1/p"',$arr);
  return $arr; 
}

function validation_filter_id_card($id_card){
    if(strlen($id_card)==18){
        return idcard_checksum18($id_card);
    }elseif((strlen($id_card)==15)){
        $id_card=idcard_15to18($id_card);
        return idcard_checksum18($id_card);
    }else{
        return false;
    }
}
// 计算身份证校验码，根据国家标准GB 11643-1999
function idcard_verify_number($idcard_base){
    if(strlen($idcard_base)!=17){
        return false;
    }
    //加权因子
    $factor=array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
    //校验码对应值
    $verify_number_list=array('1','0','X','9','8','7','6','5','4','3','2');
    $checksum=0;
    for($i=0;$i<strlen($idcard_base);$i++){
        $checksum += substr($idcard_base,$i,1) * $factor[$i];
    }
    $mod=$checksum % 11;
    $verify_number=$verify_number_list[$mod];
    return $verify_number;
}
// 将15位身份证升级到18位
function idcard_15to18($idcard){
    if(strlen($idcard)!=15){
        return false;
    }else{
        // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
        if(array_search(substr($idcard,12,3),array('996','997','998','999')) !== false){
            $idcard=substr($idcard,0,6).'18'.substr($idcard,6,9);
        }else{
            $idcard=substr($idcard,0,6).'19'.substr($idcard,6,9);
        }
    }
    $idcard=$idcard.idcard_verify_number($idcard);
    return $idcard;
}
// 18位身份证校验码有效性检查
function idcard_checksum18($idcard){
    if(strlen($idcard)!=18){
        return false;
    }
    $idcard_base=substr($idcard,0,17);
    if(idcard_verify_number($idcard_base)!=strtoupper(substr($idcard,17,1))){
        return false;
    }else{
        return true;
    }
}
//判断是否为中文名
function isChineseName($name){
	if (preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/', $name)) {
        return true;
    } else {
        return false;
    }
}

//得到安全的字符串
function getSafeStr($str){
    $str = trim($str);
    if (!get_magic_quotes_gpc()) {
        $str = addslashes($str);
    }
    return strip_tags(htmlspecialchars($str,ENT_QUOTES));
}

//判断是否为身份证
function is_idcard( $id )
{
    $id = strtoupper($id);
    $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
    $arr_split = array();
    if(!preg_match($regx, $id))
    {
        return FALSE;
    }
    if(15==strlen($id)) //检查15位
    {
        $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
        @preg_match($regx, $id, $arr_split);
        //检查生日日期是否正确
        $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
        if(!strtotime($dtm_birth))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    else //检查18位
    {
        $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
        @preg_match($regx, $id, $arr_split);
        $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
        if(!strtotime($dtm_birth)) //检查生日日期是否正确
        {
            return FALSE;
        }
        else
        {
            //检验18位身份证的校验码是否正确。
            //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
            $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            $sign = 0;
            for ( $i = 0; $i < 17; $i++ )
            {
                $b = (int) $id{$i};
                $w = $arr_int[$i];
                $sign += $b * $w;
            }
            $n = $sign % 11;
            $val_num = $arr_ch[$n];
            if ($val_num != substr($id,17, 1))
            {
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
    }
}
//创建多级目录
function mkdirs($dir)
{
    if(!is_dir($dir))
    {
        if(!mkdirs(dirname($dir))){
            return false;
        }
        if(!mkdir($dir,0777)){
            return false;
        }
    }
    chmod($dir, 0777);    //给目录操作权限
    return true;
}
//记录支付notify参数log
function logPayNotifyArgs($pay_type){
    $postStr = json_encode($_POST);
    $getStr = json_encode($_GET);
    if(!file_exists(_PAY_LOG_DIR_)){
        mkdirs(_PAY_LOG_DIR_);
    }
    $logFile = _PAY_LOG_DIR_. '/paynotify.' . date('Ymd') . '.log';
    if($pay_type == 'wxpay' || $pay_type == 'qqpay'){
        $xml = file_get_contents('php://input');
        $logMessage = date('Y-m-d H:i:s') . ",{$pay_type}:POST:" . $xml . "\r\n";
    }else {
        $logMessage = date('Y-m-d H:i:s') . ",{$pay_type}:GET:" . $getStr . ',POST:' . $postStr . "\r\n";
    }
    error_log($logMessage, 3, $logFile);
}

function getHttpType(){
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    return $http_type;
}