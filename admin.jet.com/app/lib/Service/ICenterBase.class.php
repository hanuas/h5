<?php 
/**
 * 访问ICenter接口基类
 * @author qiaochenglei
 * 2017-04-29
 * 
 *
 */ 
 
use Doris\DApp,
 Doris\DCache,
 Doris\DLog,
 Doris\DConfig;
 




class Service_ICenterBase{
	protected $KEY;
	protected $SECRET;
	
	
	protected $codeNameOnPost ;
	protected $msgNameOnPost ;
	protected $dataNameOnPost ;
	
	
	private $icenter_conf;

	
	public function __construct($codeNameOnPost="status" ,$msgNameOnPost = "message", $dataNameOnPost ="data" ){ 
        $this->setRecvInfoOnREST($codeNameOnPost, $msgNameOnPost,  $dataNameOnPost);
        
       	$this->icenter_conf = DConfig::get("icenter");
	}
	
	//当测试的是 POST 时,要设置返回码 code、msg、data的名称
	public function setRecvInfoOnREST($codeName,$msgName,$dataName){
		$this->codeNameOnPost=$codeName;
		$this->msgNameOnPost=$msgName;
		$this->dataNameOnPost=$dataName;
	}
	
	protected function _getSign( $arrFields, $plat="cps"){
		
		$rawString = "";
		foreach($arrFields as $fName => $fValue)
		{
			$rawString .= "{$fName}={$fValue}";
		} 
		$resign = md5($rawString . "secret={$this->SECRET}");
		 
		
		return $resign;
	}
	protected function commonSignData($exData = [],$notSignData=[],$useUidToken = false){
		$data = $exData ;
		if($useUidToken){
			$data = array_merge([
				"user_id"=>$this->uid,
				"token"=>$this->token ,
			], $exData);
		}
		$data["plat"] = "cps";
		$data["key"] = $this->KEY;
		$data["sign"] = $this->_getSign($data);
		//之后字段不参与签名
		$data = array_merge($data, $notSignData);
		return $data;
	} 
	public function sendICenter($key_index, $interface, $method , $sign_arr,$not_sign_arr = []){
		$this->KEY = $this->icenter_conf["keys"][$key_index][0];
	 	$this->SECRET = $this->icenter_conf["keys"][$key_index][1];
	 	//Doris\debugWeb($this->icenter_conf["host"]."/". $interface);
	 	
	 	$reqData = $this->commonSignData($sign_arr , $not_sign_arr);
		$r = $this->sendCommon(
			$this->icenter_conf["host"]."/". $interface,
			$reqData ,  
			$method   
		);
		self::log($r[1],$r[2],$r[3], $interface,$reqData);
		return $r; 
	}
	//TODO:要记录LOG
	protected function sendCommon($url,$para,$deliverType){
		list($raw,$real_url) =self::send($url,$para, $deliverType );
		
		$r=$raw ? json_decode($raw,true):array();
		//var_dump($r);
		$recvedOK=!empty($raw);
		
		$recvCode=$raw? (isset($r[$this->codeNameOnPost])?$r[$this->codeNameOnPost]: 700 ):-1;
		$recvMsg= $raw? (isset($r[$this->msgNameOnPost])?$r[$this->msgNameOnPost]:"lost field: $this->msgNameOnPost "):"net unreachable!";
		$recvData=$raw? (isset($r[$this->dataNameOnPost])?$r[$this->dataNameOnPost]:null):null;
		
		//var_dump($recvCode);
		return [$recvedOK,$recvCode,$recvMsg,$recvData,$raw];
	}
	
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
	
	//
	 
	public static function log( $code,$msg,$data,$interface, $reqData ){
		_load( "Admin_ActionLogModel"); 
		$newcamera = [];
		if(  !empty($data['root']) &&  is_array($data['root'])  ){
	 		$newcamera=  ["返回的是数组，COUNT为"=>count($data['root'])];
		}else{
			$newcamera =  $data ; 
		} 
		$log=[$msg];
		Admin_ActionLogModel::log(-1 ,"console_auto","自动调用".$interface,$reqData,$newcamera,$log , $interface , "ICenterCall") ;
	}
}


