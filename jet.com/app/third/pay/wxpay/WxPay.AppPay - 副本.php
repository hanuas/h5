<?php

class AppPay{
    const app_id = 'wx7aa77270bab1aa76';
    const mch_id = '1416931402';  //商户号
    const pay_url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    const notify_url = 'http://psvc.netkingol.com/pay/wechatpayback'; //回调地址
    const secret_key = '1qaz2wsx3edc4rfv5tgb6yhn7ujm8ikl';


    public function __construct(){

    }
    /**
    * 统一下单
    */
    public function unifiedorder($params){
        $nonce_str = uniqid();
        $args = array();
        $args['appid'] = self::app_id;
        $args['mch_id'] = self::mch_id;
        $args['device_info'] = 'WEB';
        $args['nonce_str'] = $nonce_str;
        $args['sign_type'] = 'MD5';
        $args['body'] = $params['body'];
        $args['out_trade_no'] = $params['out_trade_no'];
        $args['fee_type'] = 'CNY';
        $args['total_fee'] = $params['total_fee'];
        $args['spbill_create_ip'] = $params['spbill_create_ip'];
        $args['notify_url'] = $params['notify_url'];
        $args['trade_type'] = 'APP';
        $args['sign'] = $this->createSign($args);
        $xml = $this->arrayToXml($args);
        $con = $this->send_post(self::pay_url,$xml);
        $res = $this->xmlToArray($con);
        if($res['return_code'] == 'SUCCESS'){
            $res['nonce_str'] = $nonce_str;
            return $res;
        }else{
            //error_log('time:'.date('Y-m-d H:i:s').'send:'.json_encode($args).',return:'.json_encode($res) . "\n", 3, _ROOT_DIR_ . '/log/apimob/pay/wechatpay_error-' . date('Ymd') . '.log' . '.' . getServerIp());
            return false;
        }

    }
    /**
        <xml>
          <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
          <attach><![CDATA[支付测试]]></attach>
          <bank_type><![CDATA[CFT]]></bank_type>
          <fee_type><![CDATA[CNY]]></fee_type>
          <is_subscribe><![CDATA[Y]]></is_subscribe>
          <mch_id><![CDATA[10000100]]></mch_id>
          <nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
          <openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>
          <out_trade_no><![CDATA[1409811653]]></out_trade_no>
          <result_code><![CDATA[SUCCESS]]></result_code>
          <return_code><![CDATA[SUCCESS]]></return_code>
          <sign><![CDATA[B552ED6B279343CB493C5DD0D78AB241]]></sign>
          <sub_mch_id><![CDATA[10000100]]></sub_mch_id>
          <time_end><![CDATA[20140903131540]]></time_end>
          <total_fee>1</total_fee>
          <trade_type><![CDATA[JSAPI]]></trade_type>
          <transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id>
        </xml>
    **/
    public function notifyVerify($xml){
        $notify_con = $this->xmlToArray($xml);
        $sign = $this->createSign($notify_con);
        if($sign != $notify_con['sign']){
            return false;
        }
        return $notify_con;
    }

    private function createSign($args){
        unset($args['sign']);
        ksort($args);
        foreach($args as $k=>$v){
            if(!$v){unset($args[$k]);}
        }
        $meta = '';
        foreach($args as $k=>$v){
            $meta.=$k.'='.$v.'&';
        }
        $meta = rtrim($meta,'&');
        $meta .='&key='.self::secret_key;
        $sign = strtoupper(md5($meta));
        return $sign;
    }

    //数组转XML
    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            $xml.="<".$key.">".$val."</".$key.">";
            /*
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
            */
        }
        $xml.="</xml>";
        return $xml;
    }

    //将XML转为array
    public function xmlToArray($xml)
    {    
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $values;
    }

    /**
	 * 发送post请求
	 * @param string $url 请求地址
	 * @param string $postdata 数据
	 * @return string
	 */
	public function send_post($url, $postdata) {
	
		$options = array(
				'http' => array(
						'method' => 'POST',
						'header' => 'Content-type:application/x-www-form-urlencoded',
						'content' => $postdata,
						'timeout' => 15 * 60 // 超时时间（单位:s）
				)
		);
		$context = stream_context_create($options);
		$result = @file_get_contents($url, false, $context);
		return $result;
	}
}
