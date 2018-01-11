<?php 
/**
 * 微信公众号服务
 * @author qiaochenglei
 * 2017-07-10
 * 
 * 微信网页授权文档：https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140842
 
 *	本程序补充的错误码值： 
 *		1 get access_token failed
 *		2 get access_token local invalid
 *		3 get refresh_token local invalid 
 *		10 get user_info failed 
 *		
 */  
define("SECONDS_OF_30DAYS", 3600*24*30);

class WeixinMpService {
	private $AppID;
	private $Secret; 
	
	
	function __construct(){
		$conf = Config::get("weixin_mp");
		$this->AppID = $conf['AppID'];
		$this->Secret = $conf['Secret']; 
		 
		$redis  = Cache::redis("game");
	}
	const KEY_ACCESS_TOKEN = "WXMP_ATOKEN:";
	const KEY_REFRESS_TOKEN = "WXMP_RTOKEN:";
	public function getRedis(){
		$redis  = Cache::redis("code");
		return $redis;
	}
	
	/*
		@value 
			= false 表示取
			= null 表示删除
			= 其它 表设置
	*/
	private function redisAccessString( $key, $value = false ,$expires_in = 0){
		$redis = $this->getRedis(); 

		if($value === false){
			//读取 
			return $redis->get( $key );
		}else if($value === null){
			//删除
			$redis->delete( $key  );
		}else{
			//设置
			$redis->set( $key, $value , $expires_in );
		}
	}
	
	private function accessToken( $openid, $value = false ,$expires_in = 0){
		return $this->redisAccessString(self::KEY_ACCESS_TOKEN.":$openid",  $value  ,$expires_in);
	}
	private function refreshToken($openid, $value = false ,$expires_in = 0){
		return $this->redisAccessString(self::KEY_REFRESS_TOKEN.":$openid",  $value  ,$expires_in);
	}
	public static function getUsernameByOpenid($openid){
		return "wxmp>". strtolower( $openid );
	}
	
// 	private function saveUserinfo($u){
// 	}

	/*
	*	返回通用格式
	*	@errcode 如果成功则为 0，如果失败则为微信公众号返回的 $errcode
	*	@errmsg 如果成功则为 sucess，如果失败则为微信公众号返回的 $errmsg
	*	@data 如果成功则为 解析过的数组，如果失败则返回原始返回字符串
	*/
	public static function ret($errcode, $errmsg ="",$data = null) {
		return [
			"errcode"=> $errcode,
			"errmsg" => $errmsg." IN[".__file__."]",
			"data" => $data,
		];
	}
	
	public static function send($url, $data = [] ,$method='GET') {
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
		// echo " $method";
		return [$result ,$url];
	}
	
	// ============================
	/* NOTE: 第一步：用户同意授权，获取code
		@redirect_uri  跳回的url地址，注意是url_encode之后的值
		@state = ""; 开发者自定义参数 //重定向后会带上state参数，开发者可以填写a-zA-Z0-9的参数值，最多128字节  
		@scope =	snsapi_base  或 snsapi_userinfo
			应用授权作用域，snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi_userinfo 
			（弹出授权页面，可通过openid拿到昵称、性别、所在地。并且，即使在未关注的情况下，只要用户授权，也能获取其信息）
	*/
	function getCode($redirect_uri, $state = "", $scope = "snsapi_userinfo"){ 
	
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=". $this->AppID
			."&redirect_uri=". $redirect_uri
			."&response_type=code&scope=". $scope
			."&state=".$state."#wechat_redirect";
		//list($result ,$real_url) = self::send($url);
		//echo $result ;
		//echo $url ;
		// Func::debugWeb([$result, $real_url],false );
		
		header('Location: ' . $url);
	}
	 
	function getCodeNotify(){
		//code说明 ： code作为换取access_token的票据，每次用户授权带上的code将不一样，code只能使用一次，5分钟未被使用自动过期。 
		$code = $_GET['code'];
		//在上步传过去的
		$state = $_GET['state'];
		
		return $this->getAccessToken($code, $state);
	}
	
	
	// ============================
	// NOTE:第二步：通过code换取网页授权access_token
	// @state 开发者自定义参数（在第一步传给微信公众号的state参数）,这里没用到
	function getAccessToken($code, $state){ 
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=". $this->AppID
			."&secret=". $this->Secret
			."&code=". $code
			."&grant_type=authorization_code";
		list($result ,$real_url) = self::send($url);
			 
		//Func::debugWeb([$result, $real_url],false,__method__,__line__ );
		
		$r  = $result ? json_decode($result,true):array();
		
	
		// Func::debugWeb($r,false,__method__,__line__ );
		if(empty($r)){
			return self::ret(1,"get access_token failed LINE".__line__); 
		}
		if( isset($r['access_token']) ){//请求成功
			
			$access_token = $r['access_token'];
			$expires_in = $r['expires_in'];//单位（秒）
			$refresh_token = $r['refresh_token'];
			$openid = $r['openid'];
			$scope = $r['scope']; //TODO: scope
			$this->accessToken($openid, $access_token, $expires_in);
			$this->refreshToken($openid, $refresh_token, SECONDS_OF_30DAYS );
			return self::ret(0,"success",  $r); 
		}else{// 返回错误 
			// result如：{"errcode":40029,"errmsg":"invalid code"} 
			$errcode = $r["errcode"];
			$errmsg = $r["errmsg"];
			return self::ret($errcode , $errmsg  .__line__ , $result); 
		}
	}
	
	// ============================
	// NOTE: 第三步：refreshAccessToken（如果需要）
	function refreshAccessToken( $openid ){ 
		$refresh_token =  $this->refreshToken( $openid );
		if(!$refresh_token ) 
			return self::ret(3,"get refresh_token local invalid");
			
		$url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=". $this->AppID
			."&grant_type=refresh_token&refresh_token=". $refresh_token;
			
		list($result ,$real_url) = self::send($url);
	
		//Func::debugWeb([$result, $real_url],false);
		
		$r  = $result ? json_decode($result,true):array();
		
		if(empty($r)){
			return self::ret(1,"get access_token failed LINE".__line__); 
		}
		if( isset($r['access_token']) ){//请求成功
			
			$access_token = $r['access_token'];
			$expires_in = $r['expires_in'];//单位（秒）
			$refresh_token = $r['refresh_token'];
			$openid = $r['openid'];
			$scope = $r['scope']; //TODO: scope
			
			$this->accessToken($openid, $access_token, $expires_in);
			$this->refreshToken($openid, $refresh_token, SECONDS_OF_30DAYS );
			
			return self::ret(0,"success",  $r); 
		}else{// 返回错误 
			// result如：{"errcode":40029,"errmsg":"invalid code"} 
			$errcode = $r["errcode"];
			$errmsg = $r["errmsg"];
			return self::ret($errcode , $errmsg , $result); 
		}
	}
	
	
	// ============================
	// NOTE:第四步：拉取用户信息(需scope为 snsapi_userinfo)
	function getUserInfo( $openid ){
		$access_token =  $this->accessToken( $openid );
		if(!$access_token ) 
			return self::ret(2,"get access_token local invalid"); 
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=". $access_token
			."&openid=". $openid
			."&lang=zh_CN";
		list($result ,$real_url) = self::send($url);
			 
		//Func::debugWeb([$result, $real_url],false);
		
		$r  = $result ? json_decode($result,true):array();
		
		if(empty($r)){
			return self::ret(10,"get user_info failed ".__line__); 
		}
		if( isset($r['openid']) ){//请求成功
			
			// $this->saveUserinfo($r);
			return self::ret(0,"success LINE".__line__,  $r); 
		}else{// 返回错误 
			// result如：{"errcode":40003,"errmsg":" invalid openid "} 
			$errcode = $r["errcode"];
			$errmsg = $r["errmsg"];
			return self::ret($errcode , $errmsg  .__line__ , $result); 
		}
	}
	
	
	// ============================
	//NOTE: 附：检验授权凭证（access_token）是否有效
	function checkToken( $openid ){
		$access_token =  $this->accessToken( $openid );
		if(!$access_token ) 
			return self::ret(2,"get access_token local invalid");
		$url = "https://api.weixin.qq.com/sns/auth?access_token=". $access_token ."&openid=". $openid;
		list($result ,$real_url) = self::send($url);

		//Func::debugWeb([$result, $real_url],false);
		
		$r  = $result ? json_decode($result,true):array();
		
		$r  = $result ? json_decode($result,true):array();
		
		if(empty($r)){
			return self::ret(1,"get access_token failed LINE".__line__); 
		}
		$errcode = $r["errcode"];
		$errmsg = $r["errmsg"];
		return self::ret($errcode , $errmsg .__line__ , $result); 
	}
	
}