<?php 

defined("MandaoKey") 	or define("MandaoKey", 		"123");
defined("MandaoSecret") 	or define("MandaoSecret", 	"123"); 
// http://www.zucp.net/page/down.html
class Third_MandaoInterface{   
	
	function sentByPhone($phone,$content, $rrid = "", $stime = ""){ 
		$flag = 0; 
		//要post的数据  
		//$content = iconv( "UTF-8", "gb2312//IGNORE" ,$content);
		$argv = array( 
			 'sn'=>MandaoKey, 
			 'pwd'=>strtoupper(md5(MandaoKey.MandaoSecret)),  
			 'mobile'=>$phone,//手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
			 'content'=>urlencode( $content ),//短信内容
			 'ext'=>'',
			 'rrid'=>$rrid,//默认空 如果空返回系统生成的标识串 如果传值保证值唯一 成功则返回传入的值
			 'stime'=>$stime //定时时间 格式为2011-6-29 11:09:21
			 ); 
			 
		//构造要post的字符串 
		$params = "";
		foreach ($argv as $key=>$value) { 
			  if ($flag!=0) { 
				 $params .= "&"; 
				 $flag = 1; 
			  } 
			 $params.= $key."="; 
			 $params.= urlencode($value); 
			 $flag = 1; 
		} 
		$length = strlen($params); 
						 //创建socket连接 
		$fp = fsockopen("sdk2.entinfo.cn",8060,$errno,$errstr,10) or exit($errstr."--->".$errno); 
		//构造post请求的头 
		$header = "POST /webservice.asmx/mdSmsSend_u HTTP/1.1\r\n"; 
		$header .= "Host:sdk2.entinfo.cn\r\n"; 
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
		$header .= "Content-Length: ".$length."\r\n"; 
		$header .= "Connection: Close\r\n\r\n"; 
		//添加post的字符串 
		$header .= $params."\r\n"; 
		 //发送post的数据 
		fputs($fp,$header); 
		$inheader = 1; 
		while (!feof($fp)) { 
			$line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据 
			if ($inheader && ($line == "\n" || $line == "\r\n")) { 
				 $inheader = 0; 
			} 
			if ($inheader == 0) { 
				// echo $line; 
			} 
		} 
 
		//第三种，正则取 
		preg_match('/<string xmlns=\"http:\/\/tempuri.org\/\">(.*)<\/string>/',$line,$str);
		$result=explode("-",$str[1]);


		$status = 1;
		if(count($result)>1){
			//echo '发送失败返回值为:'.$line."请查看webservice返回值";
		}else{
			$status = 0;
			//echo '发送成功 返回值为:'.$line;  
		}
		return [$status	,$line];
		
	}
}
