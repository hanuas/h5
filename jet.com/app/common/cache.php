<?php
/**
 * @name 缓存相关
 * @author qiaochenglei
 *
 */
/*************   缓存相关的接口函数  *****************/
/**
 *当timeout为0时表示永远有效
 * $type 为枚举类型(x,f,m),默认为 x
		f 为文件缓存；
		m 为 Memcache 或 Memcached 缓存；
		r 为 Redis
		x 为混合型先从 m 里读取，没有的话再从 f 读取缓存，然后将从 f 中读取的缓存写入 m 缓存
		
	返回：如果有就返回该值，如果没有返回false
 */
 

class Cache{

	static function getM($name, $module='', $timeout=0) {
		if(!DMemcache::$isUse)return false;
		$key=$module?$module.'__'.$name:$name;
		return DMemcache::get($key);
	}
	
	/**
	 * 
	 * 删除缓存的格式 setM($name,null,……)
	 */
	static function setM($name, $data, $module='', $timeout=0) {
		if(!DMemcache::$isUse)return false;
	
		$key=$module?$module.'__'.$name:$name;
		if($data===null){
			$ret=DMemcache::delete($key);
		}else{
			$ret=DMemcache::set($key,$data,$timeout);
		}
		return $ret;
	}


	static function getF($name, $module='', $timeout=0) {
		$key=$module?$module.'/'.$name:$name;
		$path=_CACHE_DIR_;

		$store=F($key, '', $path);
		if(!$store || !isset($store['expire']))return false;
	
		$expire = $store['expire']*1;
		if($expire>0 && $expire <= time())//过期
		{
			F($key, null, $path);
			return false;
		}
		return $store['data'];
	}
	
	static function setF($name, $data, $module='', $timeout=0) {
		$key=$module?$module.'/'.$name:$name;
		$path=_CACHE_DIR_;
	
		if($data===null)//del
			return F($key, null, $path);
	
		//set
		$expire=($timeout*1==0)  ?  0  :  time()+$timeout*1;
		$store=array('expire'=>$expire,'data'=>$data);
		return F($key, $store, $path);
	}
	
	static function getX($name, $module='', $timeout=0) {
	
		$data=self::getM($name, $module, $timeout) ;
		if($data===false){
			$data=self::getF($name, $module, $timeout);
			if($data!==false){//从文件缓存复制到内存缓存
				self::setM($name, $data, $module, $timeout);
			}
		}
		return $data;
	}

	static function setX($name, $data, $module='', $timeout=0) {
		$ret=self::setF($name, $data, $module,  $timeout);
		$ret=self::setM($name, $data, $module,  $timeout);
		return $ret;
	}
	
	//参考： http://www.cnblogs.com/weafer/archive/2011/09/21/2184059.html	
	static function redis($linkName){
		static $redisObj=[];
		if(!empty($linkName[$redisObj])){
			return $redisObj[$linkName];
		}
		
		$confRedis = Config::get("redis");
		
		$confServers = $confRedis['servers'];
		$redis = new RedisEx();  
		if(!array_key_exists($linkName, $confServers)){
			// DLog::error("redis server $linkName not configed! line:[".__LINE__."] ,file:[".__FILE__."]");
			
            $log_str = date("Y-m-d H:i:s")." redis server $linkName not configed! line:[".__LINE__."] ,file:[".__FILE__."]";
            error_log($log_str, 3, '/data/log/error_redis.log');
			return;
		}
		$conf = explode(":", $confServers[$linkName]);
		$redis->connect($conf[0],$conf[1], $confRedis['time_out'] );  
		if(!empty($conf[3])){//验证密码
			$auth = $redis->auth( $conf[3]  ); //参考 http://www.cnblogs.com/txw1958/p/php-redis-auth.html
		}
		
		if( !empty( $conf[2] ) ){
			
			$redis->select($conf[2]);
		}
		$redisObj[$linkName]=$redis;
		
		return $redisObj[$linkName]; 
	}
}

class RedisEx extends Redis {
	 /**
     * 设置值  构建一个字符串
     * @param string $key KEY名称
     * @param string $value  设置值
     * @param int $timeOut 时间  0表示无过期时间
     */
    public function set($key, $value, $timeOut=0) {
        $retRes = parent::set($key, $value);
        if ($timeOut > 0){
            parent::expire($key, $timeOut);
        }
        return $retRes;
    }
    
    /*
    *	读取、设置、删除 Redis字符串
		@value 
			= false 表示取
			= null 表示删除
			= 其它 表设置
	*/
	private function accessString( $key, $value = false ,$expires_in = 0){
		$redis = $this; 
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
}

/*************   DMemcache 类 (根据配置，兼容Memcache及Memcached)  *****************/
	/*附：
	防火墙是简单有效的方式，如果却是两台服务器都是挂在网的，并且需要通过外网IP来访问Memcache的话，那么可以考虑使用防火墙或者代理程序来过滤非法访问。
	一般我们在Linux下可以使用iptables或者FreeBSD下的ipfw来指定一些规则防止一些非法的访问，比如我们可以设置只允许我们的Web服务器来访问我们Memcache服务器，同时阻止其他的访问。
	# iptables -F
	# iptables -P INPUT DROP
	# iptables -A INPUT -p tcp -s 192.168.0.2 --dport 11211 -j ACCEPT
	# iptables -A INPUT -p udp -s 192.168.0.2 --dport 11211 -j ACCEPT
	* @author qiaochenglei
	*/

class DMemcache{
	private static $_instance;
	private $mem = null;
	private $memd = null;
	private $useLocalClass = false;
	private static $conf = null;
	public static $isUse = null;
	private function __construct(){//单例模式，private标记的构造方法
	
		self::$conf= Config::get("memcache");
		self::$isUse=self::$conf["is_use"];
		if(!self::$isUse){
			return;
		}
		
		//没有安装MEMCACHE扩展的情况
		if(@!class_exists('Memcache')){	
			if(file_exists($memcacheFile = _THIRD_DIR_.'Memcache/memcached.php')) {
				echo '[No Memcache dynamic extention mode!]';
				$this->useLocalClass = true; 
				require_once $memcacheFile;
				$this->mem=new Memcache;
				foreach (self::$conf["servers"] as $server) {
					list($ip, $port, $weight) = explode(':', $server);
					$this->mem->addServer( 
							$ip
							,$port
							,$weight
							);
			
				}       
			}
		}else if(self::$conf["use_memcached"] && @class_exists('Memcached')){
			$this->create_memd();
		}else{
			$this->create_mem();
		}
		if(self::$conf["session"]["on"]){
			ini_set("session.save_handler", "memcache"); 
			ini_set("session.save_path", self::$conf["session"]["link"]); 
			// tcp://127.0.0.1:12301?persistent=1&weight=1&timeout=1&retry_interval=15,tcp://127.0.0.1:12302?persistent=1&weight=1&timeout=1&retry_interval=15
		}
	}
	private function create_mem() {
		$this->mem=new Memcache;
		foreach (self::$conf["servers"] as $server) {
			list($ip, $port, $weight) = explode(':', $server);
			//var_dump($server);
            $this->mem->addServer( $ip
					,$port
					,true
					,$weight
					,1
				 	,15
 					,true
// 					,array('Cache','FailureCallback')
					);
			//$this->mem->addServer($conf['IP'], $conf['PORT']);
			
		}       
    }
	private function create_memd(){
		 $svs=[];	$i=0;
		 foreach (self::$conf["servers"] as $server){
		 	list($ip, $port, $weight) = explode(':', $server);
		 	$svs[$i][0]=$ip;
		 	$svs[$i][1]=$port;
		 	$svs[$i][2]=$weight;
		 	$i++;
		 }
		// var_dump($svs);
		 if(!$this->memd) {
            $this->memd = new MemCached;
            $this->memd->setOption(Memcached::OPT_HASH, Memcached::DISTRIBUTION_CONSISTENT);
            $this->memd->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
            $this->memd->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
            $this->memd->addServers($svs);
            if(!empty(self::$conf['user'])) {
                $this->memd->setSaslAuthData(self::$conf['user'], self::$conf['pass']);
            }
        }
	}
	public function __clone(){trigger_error('Clone is not allow!',E_USER_ERROR);}
	public static function getInstance(){
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self;
		}
		return self::$_instance;
	}
    //封装接口
	//public static function dumpExtendedStats() {print_r(self::getInstance()->mem->getExtendedStats()); }
	//public static function dumpCache($slab_id,$limit_num) {print_r(self::getInstance()->mem->getStats("cachedump",$slab_id,$limit_num)); }
	
	public static function set($key,$val,$timeOut=0) {
		if(self::getInstance()->memd) return self::getInstance()->memd->set($key, $val,$timeOut); 
		return self::getInstance()->mem->set($key, $val, 0, $timeOut);
	}
	public static function get($key) {return @self::getInstance()->mem()->get($key); }
	public static function replace($key,$val,$timeOut) {
		if(self::getInstance()->memd)return self::getInstance()->memd->replace($key, $val, $timeOut); 
		return self::getInstance()->mem->replace($key, $val, 0, $timeOut); 
	}
	public static function delete($key) {return @self::getInstance()->mem()->delete($key); }
	public static function flush() {return self::getInstance()->mem()->flush(); }
	public static function close() {
		if($this->mem )self::getInstance()->mem->close();
		if($this->memd )self::getInstance()->memd->quit();
	}
	private function mem() {return $this->memd ? $this->memd : $this->mem;}
	public function getMemcache() {return  $this->mem;}
	public function getMemcached() {return $this->memd;}
	public function isMemcached() {return  (self::$conf["use_memcached"] && !empty($this->memd));}
}

/********************************     file     ********************************/
// 快速文件数据读取和保存 针对简单类型数据 字符串、数组 (参考自THINKPHP)
function F($name, $value='', $path) {
    static $_cache = array();
    $filename = "$path/$name.php";
    if ('' !== $value) {
        if (is_null($value)) {
            // 删除缓存
			if(file_exists($filename)) return unlink($filename);
			return true;
        } else {
            // 缓存数据
            $dir = dirname($filename);
            // 目录不存在则创建
            if (!is_dir($dir))
                mkdir($dir);
            $_cache[$name] =   $value;
            return file_put_contents($filename, strip_whitespace("<?php\nreturn " . var_export($value, true) . ";\n?>"));
        }
    }
    if (isset($_cache[$name]))
        return $_cache[$name];
    // 获取缓存数据
    if (is_file($filename)) {
        $value = include $filename;
        $_cache[$name] = $value;
    } else {
        $value = false;
    }
    return $value;
}


