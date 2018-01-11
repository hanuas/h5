<?php
/**
* BY 乔成磊 
*/
namespace Doris;



/************************************************
**	DDispatch 类
************************************************/

$CUR_MODULE = false;
$CUR_CONTROLLER = false;
$CUR_ACTION = false;

class DDispatch{

	private $router;
	private $plgin;
	function __construct($conf,$plgin=null){
		//var_dump($conf);
		$this->router = new DRouter($conf);
		$this->plgin = $plgin;
		
		//$routeInfo=$router->deal();
		//var_dump($routeInfo);
	}
	//function getDipatchLevel(){return $this->router->dispatchLevel;}
	function dipatch(){
		$requestUri = $_SERVER['REQUEST_URI'];
		if($this->plgin) 
			$requestUri = $this->plgin->routerStartup($requestUri );
		
		$routeInfo = $this->router->deal( $requestUri );
		
		if($this->plgin)  
			$routeInfo = $this->plgin->routerShutdown( $routeInfo );
		
		
		
		$m = $routeInfo[1]; // strtolower( $routeInfo[1])
		$c = $routeInfo[2]; // strtolower( $routeInfo[2])
		$a = $routeInfo[3]; // strtolower( $routeInfo[3]) 
		
		defined("_MODULE_") 	or define("_MODULE_" 		, $m );
		defined("_CONTROLLER_") or define("_CONTROLLER_"	, $c );
		defined("_ACTION_") 	or define("_ACTION_"		, $a );
		
		
		$m = $routeInfo[1];
		$c = $routeInfo[2];
		$a = $routeInfo[3];
		
		
		$className = $c . _CONTROLLER_POSTFIX_ ;
		$actionName = $a . _ACTION_POSTFIX_ ;
		$paras	= &$routeInfo[4];
		
		

		if($this->plgin)  
			$routeInfo = $this->plgin->preDispatch( $objController , $this , $routeInfo);
		
		//开始调用控制器
		$_SESSION['CURRENT_ACTION_PARAS']= $paras ;
		$objController = self::callAction($m , _CONTROLLER_ , _ACTION_ , $paras );
		
		if($this->plgin)  
			$routeInfo = $this->plgin->postDispatch( $objController , $this , $routeInfo);

	}
	


	public static function pageNotFound(){
		echo "DDispatch , not found:<br> ";
		
		$args = func_get_args();
		echo "<pre>";
		if(is_array($args))
			print_r($args);
		else
			var_dump($args);
		echo "</pre>";
		return false;
	} 


	public static function loadClassCommon($classname, $file , $initPara=null,$initialize = 1) {
		static $classes = array();
		$key = md5($file);
		if (isset($classes[$key])) {
			if (!empty($classes[$key])) {
				return $classes[$key];
			} else {
				return true;
			}
		}
		$class_exist=false;
		if (file_exists($file)) {
			 include_once $file;
			$class_exist=true;
			
		}else if(class_exists($classname)){
			if($initialize){//此处标记一下，下面会有具体执行初始化
				$class_exist=true;
			}else{
				$classes[$key] = true;
				return $classes[$key];
			}
		}
		if($class_exist){
			$name = $classname;
			if ($initialize) {
				if($initPara!==null)$classes[$key] = new $name($initPara);
				else				$classes[$key] = new $name;
			} else {
				$classes[$key] = true;
			}
			return $classes[$key];
		} else {
			return false;
		}
	}
	
	public static function _new($className , $initPara=null, $relativePathOfApp ='' )
	{
		return self::loadClassCommon(
				$className	,
				Dispatch::getFilePathByclassName($className , $relativePathOfApp),
				$initPara,
				1
		);
	}

	public static function include_third( $ref_path, $once = true, $require = false){
		if($require){
			if($once)
				require_once _THIRD_DIR_ . $ref_path ;
			else
				require _THIRD_DIR_ . $ref_path ; 
		}else{
			if($once)
				include_once _THIRD_DIR_ . $ref_path ;
			else
				include _THIRD_DIR_ . $ref_path ; 
		}
	}
	 
	public static function load($className , $relativePathOfApp ='' )
	{
		return self::loadClassCommon(	
			$className	,
			self::getFilePathByclassName($className , $relativePathOfApp),
			null,
			0 	
		);
	}
	/*
	*	如果是二级路由，则把 $m 设置为null即可 （此时设置了也不起作用）
	*/
	public static function callAction($m , $c  , $a, $paras) {
		
		$file = _CONTROLLER_DIR_ . ( !empty($m) ? $m. "/" : "").$c.".action.php";
		$objController = self::loadClassCommon($c._CONTROLLER_POSTFIX_, $file , null);
		
		if( method_exists( $objController , $a._ACTION_POSTFIX_)  ){
			//$paras=$_GET;
// 			$paras["_innerCallModule_"]		=$m;
// 			$paras["_innerCallController_"]	=$c;
// 			$paras["_innerCallAction_"]		=$a;
			
			$GLOBALS['CUR_MODULE'] = $m;
			$GLOBALS['CUR_CONTROLLER'] = $c;
			$GLOBALS['CUR_ACTION'] = $a;
			
			call_user_func_array(array($objController,$a._ACTION_POSTFIX_), array($paras));
			
		}else 
			return self::pageNotFound("callAction:",["file"=>$file ,"controller"=>$c, "action"=>$a]);
		
		return $objController ;
	}
	
	/*
	*	加载控制器，
	*		如之前创建过则这个控制器则返回控制器对象，此时必定加载成功
	*		如果之前没有创建过这个控制器 bool 类型 以示加载成功或失败
	*/
	public static function loadController($m , $c  , $dispatchLevel =3 ) {
		$supportModule = $dispatchLevel > 2 && !empty( $m );
		$file = _CONTROLLER_DIR_ .($supportModule ? $m. "/" : "").$c.".action.php";
		$theController = self::loadClassCommon($c._CONTROLLER_POSTFIX_, $file , null , 0);
		return $theController;
	}
	static function str_end_with($str,$subStr){
		return substr($str, -(strlen($subStr)))==$subStr;
	}
	public static function getFilePathByclassName($className , $relativePathOfApp ){
		if(empty($relativePathOfApp)){
			
			if(self::str_end_with($className,"Model")){
				$file = _MODEL_DIR_.str_replace("_","/", substr($className , 0 , strlen($className)-5) ).".model.php";
				//exit($file);
			}else if(self::str_end_with($className,"Service")){
				$file = _LIB_DIR_."Service/".str_replace("_","/", substr($className , 0 , strlen($className)-7) ).".service.php";
			}else{
				$file = _LIB_DIR_.str_replace("_","/", $className ).".class.php";
			}
		}else{
			$file = _APP_DIR_ . $relativePathOfApp;
		}
		return $file;
	}
	
	public static function curModule( ){
		return isset($GLOBALS['CUR_MODULE']) ? $GLOBALS['CUR_MODULE'] : _MODULE_;
	}
	public static function curController( ){
		return isset($GLOBALS['CUR_CONTROLLER']) ? $GLOBALS['CUR_CONTROLLER'] : _CONTROLLER_;
	}
	public static function curAction(){
		return isset($GLOBALS['CUR_ACTION']) ? $GLOBALS['CUR_ACTION'] : _ACTION_; 
	}
	
}//end class DDispatch












/**
* DRouter 类 
* 	deal开头的函数，返回值格式一致:
*		level为2时：[$isMache,null, $c , $a ,$paras]
*		level为3时：[$isMache, $m , $c , $a ,$paras]
*
*	$conf 路由配置, 默认是 static
*	 配置示例：

"router"=>[


	"dispatch_level"=>2,
	
	"route_list"=>[
	
	
		"static"	=>	[ "type"=>"static" ],
		
		"simple"	=>	[ "type"=>"simple"	,	"schema"=>["m"=>"m","c"=>"c","a"=>"a"] ],
	
		"rewrite"	=>	[ 
			"type"=>"rewrite"	,	
			"schema"=>"article/:ident", 
			"route"=>["c"=>"products","a"=>"view"]
		],
	
		"supervar"	=>	[ 
			"type"=>"supervar",	
			"schema"=>"r"
		],
		
		"regex"		=>	[ 
			"type"=>"regex"	,	
			"schema"=>"product/([a-zA-Z]+)([0-9]+)", 
			"route"=>["c"=>"products","a"=>"view"], 
			"map"=>[1=>"ident"]//从0开始起
		],
	]
],


*	END $conf示例
*/

class DRouter {

	public  $dispatchLevel;
	private $routeList;
	private $baseUri;
	
	public function __construct($conf){
		
		if(! empty( $conf["dispatch_level"] ) )
			$this->dispatchLevel = $conf["dispatch_level"];
		else
			$this->dispatchLevel = 3;
		
		if(! empty( $conf["route_list"] ) ){
			$this->routeList = $conf["route_list"];
		}else{
			$this->routeList=[];
		}
		//末尾添加一条默认路由
		$this->routeList ["static"] = [ "type"=>"static" ];
			
		if(! empty( $conf["base_uri"] ) )
			$this->baseUri = $conf["base_uri"];
		else
			$this->baseUri = false;
			
	}
	
	function deal($requestUri){
		//echo "<pre>";		print_r($this->routeList);exit;
		$this->requestUri = $requestUri;
		foreach($this->routeList as $name => &$route){
			switch($route['type']){
			
			case "static":
				$ret = $this->dealStatic();
				if($ret[0])	{
					$ret[0] = "static";
					return $ret;
				}
				break;
				
			case "simple":
				$ret = $this->dealSimple( $route['schema'] );
				//echo "<pre>";print_r($ret);exit;
				if($ret[0])	{
					$ret[0] = "simple";
					return $ret;
				}
				break;
				
			case "rewrite":
				$ret = $this->dealRewrite( $route['schema'],$route['route'] );
				//print_r($ret);exit;
				if($ret[0]){
					$ret[0] = "rewrite";
					return $ret;
				}
				break;
			
				
			case "regex":
				$ret = $this->dealRegex( $route['schema'], $route['route'] ,@$route['map'] );
				if($ret[0])	{
					$ret[0] = "regex";
					return $ret;
				}
				break;
			
				
			case "supervar":
				$ret = $this->dealSupervar( $route['schema'] );
				if($ret[0])	{
					$ret[0] = "supervar";
					return $ret;
				}
				break;
			}
			
		}
		
		return [ false , null , null , null ];
	}
	
	/*
	*	Simple类型
	*/
	function dealSimple($schema){
		// $m = null, $c = null, $a = null, 
		// $isMatch=false;
		
		if(!$schema) 
			$schema = ["m","c","a"] ;
			
		$m = null ;
		
		if($this->dispatchLevel ==3 ){
			$m = @$_GET[$schema[ 'm']];
			if( empty($m) ) 
				$m =  "index";
			else
				$m = ( $m );
		}
		
		$c = @$_GET[$schema[ 'c' ]];
		if( empty($c) ) 
			$c =  "index";
		else
			$c = ( $c );
		
		$a = @$_GET[$schema[ 'a' ]];
		if( empty($a) ) 
			$a =  "index";
		else
			$a = ( $a );
		
		return [ true , $m , $c , $a , $_GET];
	}
	
	
	/*
	*	Static 类型，默认
	*/
	//TODO: 修改规则以支持带冒号前缀的变量方式（RESTFUL 的接口要用到）
	function dealStatic(){
	
		$arrReqUri =[];
		$this->getReqUri($arrReqUri);
		
		$cnt=count($arrReqUri);
		while( $cnt < $this->dispatchLevel ){
			array_push($arrReqUri, "index");
			$cnt++;
		}
		
		if( $this->dispatchLevel==3 ){
			$m = array_shift($arrReqUri);
			$c = array_shift($arrReqUri);
			$a = array_shift($arrReqUri);
		}else{
			$m = null;
			$c = array_shift($arrReqUri);
			$a = array_shift($arrReqUri);
		}
		
		$cnt=count($arrReqUri);
		//var_dump($arrReqUri);exit;
		$paras = [];
		for($i=0 ; $i < $cnt ; $i+=2){
			$paras[ $arrReqUri[ $i ] ] =  @$arrReqUri[ $i + 1 ] ;
			 
		}
		return [ true , $m , $c , $a ,$paras];
	}
	
	/*
	*	Rewrite 类型
	*/
	//	示例 （其中 ident为变量名）
	// 		"rewrite"	=>	[ 
	// 			"type"=>"rewrite"	,	
	// 			"schema"=>"article/:ident/*", 
	// 			"route"=>["c"=>"products","a"=>"view"]
	// 		],
	function dealRewrite($schema,$route){


		$arrReqUri =[];
		$this->getReqUri($arrReqUri);
		
		$isMatch = true;
		$paras = [];
		
		$arrSchema = explode("/", $schema);
		$arrSchema = array_values(array_diff($arrSchema, array("")));
		
	
		$iReq = 0;
		$cntReq =  count( $arrReqUri );
		foreach($arrSchema as $schemaEle){
		
			$firstLetter = chr( ord($schemaEle) );
			
			if( $firstLetter == ":" ){ //段为变量
			
				$eleName = substr($schemaEle,1);
				  
				if(!empty($arrReqUri[ $iReq ]) ){
					$eleVal = $arrReqUri[ $iReq ];
					$paras[ $eleName ] = $eleVal;
				}
				$iReq ++ ;
				
			}else if( $firstLetter == "*" ){ //通配符，通配符一般在最后 
				 
				for( ; $iReq < $cntReq ;$iReq +=2 ){
					$paras[ $arrReqUri[ $iReq ] ] =  @$arrReqUri[ $iReq + 1 ] ;
				}
				$isMatch = true;
				break;
				
			}else if( $schemaEle == @$arrReqUri[$iReq] ){ //段为变通字符串
				$iReq++;
			
			}else{//匹配失败，跳出循环
				$isMatch = false;
				break;
			}
		
		}
		
		//确定 m c a
		if( $this->dispatchLevel==3 ){
			$m = empty($route['m']) ? "index":$route['m'];;
		}else{
			$m = null;
		}
		
		$c = empty($route['c']) ? "index":$route['c'];
		$a = empty($route['a']) ? "index":$route['a'];
		
		//echo "<pre>";print_r($paras);
		//exit();
		
		return [ $isMatch , $m , $c , $a ,$paras];
		
	}// end function dealRewrite

	/*
	*	Supervar 类型
	*/
	function dealSupervar($schema){
		$r = @$_GET[$schema];
		if( empty($r) ){
			$r = "index/index/index";
		}
		$arrReqUri = explode("/",$r);
		$arrReqUri = array_values(array_diff($arrReqUri, array("")));
		
		$cnt=count($arrReqUri);
		while( $cnt < $this->dispatchLevel ){
			array_push($arrReqUri, "index");
			$cnt++;
		}
		
		if( $this->dispatchLevel==3 ){
			$m = array_shift($arrReqUri);
			$c = array_shift($arrReqUri);
			$a = array_shift($arrReqUri);
		}else{
			$m = null;
			$c = array_shift($arrReqUri);
			$a = array_shift($arrReqUri);
		}
		
		return [ true , $m , $c , $a ,$_GET];
	}
	
	
	/*
	*	Regex 类型
	*/
	
	function dealRegex($schema,$route,$map){
		$arrReqUri = [];
		$uri = $this->getReqUri($arrReqUri);
		
		
		
		$arrSchema = explode("/", $schema);
		$arrSchema = array_values(array_diff($arrSchema, array("")));
		
		$paras = [];
		
		$iReq = 0;
		$cntReq =  count( $arrReqUri );
		$isMatch = true;
		
		foreach($arrSchema as $schemaEle){
			
			$matches = [];
			$iMatchRet = preg_match( "/^$schemaEle$/" , $arrReqUri[ $iReq++ ] ,$matches  );
			
			if( $iMatchRet == 0 ){ 
			
				$isMatch = false;
				break;
				
			}else { 
				$cntMatch = count($matches);
				for( $i=1 ; $i < $cntMatch ; $i++ ){
					$paras[] = $matches[$i];
				}
			}
		
		}
		foreach($map as $k=>$kName){
			if(array_key_exists($k, $paras)){
				$paras[$kName] = $paras[$k];
				unset($paras[$k]);
			}
		}
		//确定 m c a
		if( $this->dispatchLevel==3 ){
			$m = empty($route['m']) ? "index":$route['m'];;
		}else{
			$m = null;
		}
		
		$c = empty($route['c']) ? "index":$route['c'];
		$a = empty($route['a']) ? "index":$route['a'];
		
		return [ $isMatch , $m , $c , $a ,$paras];
	}
	
	/*
	*	获取请求Uri, host之后，？之前的部分。以引用参数返回，数组类型
	*/
	private function getReqUri(&$arrReqUri){
	//	var_dump($this->requestUri );exit;
		$arrUri = explode("?",$this->requestUri);
		//$arrUri=["/a1/as2/as3/as4"];
		if($this->baseUri)
			$uri = substr($arrUri[0],strlen($this->baseUri));
		else
			$uri = $arrUri[0];
		$arrReqUri = explode("/",$uri);
		$arrReqUri = array_values(array_diff($arrReqUri, array("")));
		return $uri;
	}
	
	
}