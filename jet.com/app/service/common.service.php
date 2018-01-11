<?php
class commonService{
    protected $APP_ID;
    protected $APP_KEY;
    protected $APP_SECRET;

    const COMMON_APP_ID = 321365;  //通用的app id
    
    
    //key集合
    private static $KEY_ARR = array(
        "1011"=>array(
            "APP_KEY"=>"FD8EEC2EF6C1C7129960AFAAEC39639F",
            "APP_SECRET"=>"2bc496363660db1cfe339b233cda73fb",
        ),
        "321365"=>array(
            "APP_KEY"=>"a74ac74fe48b18daf8b8d9ed27a0ce09",
            "APP_SECRET"=>"8c834a8a00390c891a67d0bb9fde1427",
        )        
    );
    
    //公共参数集合
    private static $COMMON_ARGS = array("ip","os_type","net_type","device","sdk_version","carrier","os_version","app_version","adv_channel");

    
    public function __construct($app_id){
        $this->APP_ID = $app_id;
        $this->APP_KEY = @self::$KEY_ARR[$app_id]['APP_KEY'];
        $this->APP_SECRET = @self::$KEY_ARR[$app_id]['APP_SECRET'];
    }

    //发送请求
    public function send($args,$notSignArgs,$url,$method="POST"){
        $args['timestamp'] = time();
        $sign = $this->createSign($args); 
        //$this->Request();
        
        $send_args = array();
        $send_args['appid'] = $this->APP_ID;
        $send_args['params'] = json_encode(array_merge($args,$notSignArgs));
        $send_args['sign'] = $sign;
        #print_r($send_args);
        #echo '<hr />';
        #echo $url;
        #exit;
        $res = Request::send($url, $send_args,$method);
        #print_r($res);exit;
        if($res[0] === NULL){
            return false;
        }

        
        return $res[0];
    }

 
    
    
    //获取签名
    public function createSign($args){
        ksort($args);
        $metaSign = "";
        foreach($args as $k=>$v){
            if($v !== "" && $v !== null && $v !== false ){
                $metaSign = $metaSign.$k."=".$v."#";
            }
        }
        
        $metaSign = rtrim($metaSign,"#");
        $metaSign = $metaSign.$this->APP_KEY;
        return strtolower(md5($metaSign));

    }
    
    //获取公共参数
    public function getCommonArgs($args){
        $commonKey = self::$COMMON_ARGS;
        $commonArgs = array();

        foreach($commonKey as $key){
            $commonArgs[$key] = isset($args[$key])?$args[$key]:"";
        }

        return $commonArgs;
    }

   
}