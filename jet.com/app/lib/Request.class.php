<?php 
class Request{
	
	public static function send($url, $data,$method='POST') {
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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        //启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
        curl_setopt($ch, CURLOPT_POST, true);
        #curl_setopt($ch, CURLOPT_CUSTOMREQUEST, true);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,$method);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验2
        curl_setopt($ch, CURLOPT_TIMEOUT,10);  
        curl_exec($ch);
        $result = curl_multi_getcontent($ch);
        return [$result ,$url];
        
    }
	
}


