<?php 
/**
*	BY 乔成磊 
*	Doris 框架
*/
namespace Doris;

require_once "DCore/DFunction.php";
require_once "DCore/DDispatch.php";
require_once "DCore/DConfig.php";
require_once "DCore/DLog.php";
require_once "DCore/DAction.php";

//以下两者可能在某些情况下用不到，可以考虑优化
require_once "DCore/DDB.php"; 
require_once "DCore/DCache.php"; 


DConfig::config();



class DApp{
	private $dispatch=null;
	private $action=null;
	private $plugin=null;
	private $conf=null;
	
	
	public static function getInstance( $configFile = null ){
		
		static $apps=[];
		if( !$configFile ){
			return @$apps[0];
		}
		
		$app=null;
		$appKey=md5($configFile);
		if(!array_key_exists($appKey, $apps)){
			$app=new self($configFile);
			$apps[$appKey] = $app;
		}else{
			$app = $app[$appKey];
		}
		return $app;
	}
	
	private function __construct($config){
		//productConfig 表示线上配置环境
		
		DConfig::register($config);
		$this->conf = DConfig::get();

		if( !empty($this->conf['plugin']) ){
			require _APP_DIR_."plugin/".$this->conf['plugin'].".php";
			$this->plugin = new $this->conf['plugin'];
		}else{
			$this->plugin = null ;
		}
		//var_dump($this->plugin);exit;
		$this->dispatch = new DDispatch($this->conf["dispatch"],$this->plugin );
		$this->action = new DAction();
		DConfig::configExt($this);
		
		return $this;
		
	}
	
	
	/*
	*	是否支持模块，即是否是三级Controller，否则为二级
	*/
	public function supportModule(){
		return  DConfig::get("dispatch/dispatch_level")==3;//!empty(_MODULE_);
	}
	
	/*
	*	读取配置
	*	filter为过滤器，如 db/main 表示读取主DB的配置，db/main/username 表示读取主DB的用户名
	*	如果filter 非空，且对应的键不存在则返回null
	*	
	*/
	public static function conf($filter=null){
		
		return DConfig::get($filter);
	}
	
	public function action(){
		return $this->action;
	}
	
	public static function redis($linkName){
		return DCache::redis($linkName);
	}
	
	/*
	*	$relativePathFile 相对于 Lib 的路径
	*/
	public static function loadLib($relativePathFile) {//加载
		require_once _LIB_DIR_.$relativePathFile;
		
	}
	/*
	*	$relativePathFile 相对于 DLib 的路径
	*/
	public static function loadSysLib($relativePathFile) {//加载系统中的文件
		require_once _DLIB_DIR_.$relativePathFile;
		
	}

	/*
	*	加载 DataTables
	*/
	public static function loadDT($dbgroup = "main", $index=-1, &$realIndex=-1) {
	
		$sql_details = DDB::getDatatablesConfig($dbgroup ,$index, $realIndex);

		require_once _DLIB_DIR_."DataTables-1.5.3/DataTables.php";
		return  $dataTablesDb;
		
	}

	
	
	public static function callAction($m , $c  , $a) {
		return DDispatch::callAction( $m,$c,$a, DConfig::get("dispatch"));
	}
	
	public  static function loadController($m , $c ) {
		return DDispatch::loadController( $m,$c, DConfig::get("dispatch"));
	}
		
	
	/*
		*	$relativePathOfApp 为【空】时：按类的命名规则自动找路径，规则示例如下（其中 “_”会被拆成 “/” ）：
		*		当类名以 Model 结尾时，如：Hello_WordModel，则文件路径为 _APP_DIR_/model/Hello/Word.model.php
		*		当类名以 Service 结尾时，如：Hello_WordService，则文件路径为 _APP_DIR_/lib/Hello/Word.service.php
		*		当类名不以以上列举的字串结尾时，如 Hello_Word， 则文件路径为 _APP_DIR_/lib/Hello/Word.class.php
		*		
		*	$relativePathOfApp 【不为空】时 ：
		*		$relativePathOfApp 相对于 _APP_DIR_ 的文件路径 
		*		$className 为类名，类名与路径没有关系
				如 self::newCLass("Tesst",null,"lib/common.auto.php"); 会加载文件 _APP_DIR_/lib/common.auto.php
		*		
	*/
	static function newCLass($className , $initPara=null, $relativePathOfApp ='' ) {//创建类
		return DDispatch::loadClassCommon(
				$className	,
				DDispatch::getFilePathByclassName($className , $relativePathOfApp),
				$initPara,
				1
			);
	}

	static function loadClass($className , $relativePathOfApp ='' ) {//加载 创建类
		return DDispatch::loadClassCommon(	
				$className	,
				DDispatch::getFilePathByclassName($className , $relativePathOfApp),
				null,
				0 	
			);
	}
	//把命令行参数转换成键值对参数，并返回
	static function getParasByConsoleArgs(  $argv ) {
		if ( empty(  $argv ) ) return $_GET;
		$paras = [];
		$paras["self_script"] = $argv[ 0 ];
		$count = count($argv);
		for ($i = 1; $i< $count; $i++ ) {
			$arr = explode("=", $argv[$i] );
			if( count($arr ) > 1){
				$paras[ trim($arr [0]) ] = trim($arr [1]) ;
			}else{
				$paras[ $argv[$i] ] = "";
			}
		}
		return $paras;
	}
	/*
	*	开始分发请求
	*/
	public function run(){
		$dispatchType=$this->conf('dispatch');
		if(!$dispatchType)DLog::error("未配置变量：dispatch");
		//TODO: 放开它
		//$this->dispatcher->dispatch($dispatchType);
		$this->dispatch->dipatch();
		return $this;
	}
}



