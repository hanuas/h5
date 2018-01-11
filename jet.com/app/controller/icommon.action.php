<?php 
 


class icommonController {

	protected $_method;
    
    //公共参数集合
    private static $COMMON_ARGS = array("ip","os_type","net_type","device","sdk_version","carrier","os_version","app_version","adv_channel","cps_channel");

	public function __construct(){ 
	}
	
	static function echoData($status,$message,$data=null){
		echo json_encode([
			"status"  => $status,
			"message" => $message,
			"data"	  => ["root" => $data ]
		]);
		exit;
	}

    //获取公共参数
    public function getCommonArgs($args){
        if(!@$args['ip']){$args['ip'] = getClientIP();}
        if(!@$args['os_type']){$args['os_type'] = 'h5';}
        if(!@$args['net_type']){$args['net_type'] = 0;}
        if(!@$args['carrier']){$args['carrier'] = 0;}

        if(!@$args['adv_channel'] && @$args['chid']){
            @$args['adv_channel'] = @$args['chid'];   //兼容渠道参数
        }
        if(!@$args['cps_channel'] && @$args['subchid']){
            @$args['cps_channel'] = @$args['subchid'];   //子兼容渠道参数
        }

        $commonKey = self::$COMMON_ARGS;
        $commonArgs = array();

        foreach($commonKey as $key){
            $commonArgs[$key] = (isset($args[$key]) && $args[$key])?$args[$key]:"";
        }


        return $commonArgs;
    }
	
	
	
	
 
	function parsePage( $maxPageSizeLimition = 100  ){
		$page = @$_GET['page'] ;
		if(empty($page)) $page = 0;
		$page_size = @$_GET['page_size'];
		if( empty($page_size) || $page_size > $maxPageSizeLimition ) $page_size = $maxPageSizeLimition;
		
		$sqlLimit = " limit ".($page*$page_size).",$page_size ";
		return [$page,$page_size,$sqlLimit];
	}

	function getHash(&$key, $n = 64) {
		$hash = crc32($key) >> 16 & 0xffff;
		return sprintf("%02s", $hash % $n);
	}

	function getAgeByBirthday($birthday ){
		//$birthday = '1985-02-01';  
		$age = date('Y', time()) - date('Y', strtotime($birthday)) - 1;  
		if (date('m', time()) == date('m', strtotime($birthday))){  
  
			if (date('d', time()) > date('d', strtotime($birthday))){  
			$age++;  
			}  
		}elseif (date('m', time()) > date('m', strtotime($birthday))){  
			$age++;  
		}  
		return  $age;  
	}
	
	function underscopeName($name){
		return strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $name));
	}	
	
	function camelName( $str , $ucfirst = true)
	{
		$str = ucwords(str_replace('_', ' ', $str));
		$str = str_replace(' ','',lcfirst($str));
		return $ucfirst ? ucfirst($str) : $str;
	}
	
	function repleaseFieldNameInRow( &$rowData , $oldFieldName, $newFieldName){
		if( isset($rowData[ $oldFieldName ]) ){
			$rowData[ $newFieldName ] = $rowData[ $oldFieldName ];
			unset( $rowData[ $oldFieldName ] );
		}
	}


}