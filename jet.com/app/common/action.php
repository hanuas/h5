<?php 
/**
* 	Description:   Action，所有controller的基类，提供Controller的基本功能，及页面渲染
* 	Author:        乔成磊
*	Note: 本文件要在 frame.php 之后加载
*
*/ 

class Action{
	public function __construct() {
        //控制器初始化
        if(method_exists($this,'_initialize'))
            $this->_initialize();
    
	}
	
	public function echoBackLink($prefix=''){
		echo $prefix.'<a href="javascript:jump(-1)">< <span class="LAN_Back"> 返回</span></a>';
	}
    protected function isAjax() {
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
            if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
                return true;
        }
        
        if(!empty($_POST['ajax']) || !empty($_GET['ajax']))
            // 判断Ajax方式提交
            return true;
        return false;
    }
	
	public function redirect_with_back($url,$backUrl, $time=0, $msg='') {
		$url = append_url($url,"jump",($backUrl));
		$this->redirect($url, $time, $msg);
	}
	public  function back_or($or_redirect_url='index.php', $time=0, $msg='') {
		$jump=isset($_GET['jump'])?  urldecode($_GET['jump']) : null;
		if(!$jump)$jump=isset($_POST['jump'])?  urldecode($_POST['jump']) : null;
		
		//echo "<pre>";var_dump($_SESSION);exit();
		
		if($jump)
			$this->redirect($jump);
		else 
			$this->redirect($or_redirect_url);
	}
	public function apend_backurl_after($url) {//前端渲染时做URL转换
		if(isset($_REQUEST['jump']))
			return append_url($url,"jump",$_REQUEST['jump']);
		return $url;
	}
	
	public  function cur_url(){
		return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	
	public  function parent_url(){
		$u= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$u=substr($u, 0,strrpos($u,'.php'));

		$u=substr($u, 0,strrpos($u,'/'));
		return $u;
	}
	
	
	// URL重定向
	public  function redirect($url, $time=0, $msg='') {
		//多行URL地址支持
		$url = str_replace(array("\n", "\r"), '', $url);
		if (empty($msg))
			$msg = "系统将在{$time}秒之后自动跳转到{$url}！";
		if (!headers_sent()) {
			// redirect
			if (0 === $time) {
				header('Location: ' . $url);
			} else {
				header("refresh:{$time};url={$url}");
				echo($msg);
			}
			exit();
		} else {
			$str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
			if ($time != 0)
				$str .= $msg;
			exit($str);
		}
	}
	
	public function dispatchJump($message,$status=1,$jumpUrl='',$ajax=false) {
        // 判断是否为AJAX返回
        if($ajax || $this->isAjax()) $this->ajaxReturn($ajax,$message,$status);
        $jumpUrl=$jumpUrl;
		
		$msgTitle=$status? '成功：' : '失败：';
  
        if($status) {
			$message=$message;
           $waitSecond=1;
           if($jumpUrl===null) $jumpUrl=$_SERVER["HTTP_REFERER"];
           
          	 include 'tpl/dispatch_jump.php';
        }else{
			$error=$message;
             $waitSecond=3;
           if($jumpUrl===null) $jumpUrl=$_SERVER["HTTP_REFERER"];
           
           include 'tpl/dispatch_jump.php';
            exit ;
        }
    }

    public function error($message,$jumpUrl='',$ajax=false) {
    	
        $this->dispatchJump($message,0,$jumpUrl,$ajax);
    }


    public function success($message,$jumpUrl='',$ajax=false) {
        $this->dispatchJump($message,1,$jumpUrl,$ajax);
    }
	/**
     +----------------------------------------------------------
     * Ajax方式返回数据到客户端
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param mixed $data 要返回的数据
     * @param String $info 提示信息
     * @param boolean $status 返回状态
     * @param String $status ajax返回类型 JSON XML
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
	public function ajaxReturn($data,$info='',$status=1,$type='') {
        $result  =  array();
        $result['status']  =  $status;
        $result['info'] =  $info;
        $result['data'] = $data;
        //扩展ajax返回数据, 在Action中定义function ajaxAssign(&$result){} 方法 扩展ajax返回数据。
        if(method_exists($this,'ajaxAssign')) 
            $this->ajaxAssign($result);
        if(empty($type)) $type  =   'JSON';//C('DEFAULT_AJAX_RETURN');
        if(strtoupper($type)=='JSON') {
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:text/html; charset=utf-8');
            exit(json_encode($result));
        }elseif(strtoupper($type)=='XML'){
            // 返回xml格式数据
            header('Content-Type:text/xml; charset=utf-8');
            exit(xml_encode($result));
        }elseif(strtoupper($type)=='EVAL'){
            // 返回可执行的js脚本
            header('Content-Type:text/html; charset=utf-8');
            exit($data);
        }else{
            // TODO 增加其它格式
        }
    }
    
	
	/**
	 * tpl============================================================
	 * 以下为模板相关
	 */
    private $vals=array();

    public function relay_jump(){
    	$jump=isset($_GET['jump'])?  urldecode($_GET['jump']) : null;
    	if(!$jump)$jump=isset($_POST['jump'])?  urldecode($_POST['jump']) : null;
    	//echo $jump;
    	if($jump){
    		//$this->vals['jump']=$jump;
    		echo '<INPUT TYPE="hidden" NAME="jump" VALUE="'.$jump.'">';
    	}

    }

    public function assign_jump(){
    	$jump=isset($_GET['jump'])?  urldecode($_GET['jump']) : null;
    	//echo $jump;
    	if($jump)$this->vals['jump']=$jump;
    }

    public function assign($name,$val){
    	$this->vals[$name]=$val;
    }

    public function clear(){
    	$this->vals=array();
    }


  	private function ensure_path($file,$actionPara=false,$reportError=true){
  		if(empty($file))
  			return false;
  		//如果是绝对路径则直接返回
  		if(strpos( $file, _ROOT_DIR_) === 0) 
  			return $file;
  		
		$module 	= 	Dispatch::curModule($actionPara);
		$controller = 	Dispatch::curController($actionPara);
	
	
  		if( $module)
    		$file_path=(_TEMPLATE_DIR_.$module."/".$controller."/$file");
    	else 
    		$file_path=(_TEMPLATE_DIR_.$controller."/$file");
    		
    	if(file_exists($file_path)){
    		return $file_path; 
    	} 
    	
    	if( strpos($file,"/") === 0 ){ 
    		$file_path=(_TEMPLATE_DIR_."/$file"); 
    	}
    	
    	if(file_exists($file_path)){
    		return $file_path; 
    	} 
    	
    	
    	if($reportError ){ 
			$this->error("模板不存在：$file_path","index.php");
			$file_path=false; 
    	}

    }
    
	/*
	*	直接输出模板
	*	$file 要输出的模板名
	*
	*	$actionPara 可选，传给action的参数可以原样传到这里来。一般保持默认（即false）
	*		当 Action 支持被内部其它Action调用时，该参数必须传。否则程序会找不到正确的模板路径
	*/
    public function display($file,$actionPara=false,$mustInclude=false){
    	$file_path=$this->ensure_path($file,$actionPara,$mustInclude);
    	//echo($file_path ."<br>");
    	if(!$mustInclude && !file_exists($file_path)) 
    		return false;
    	
    	extract($this->vals);
    	
    	include $file_path;
    	return true;
    }


	/*
	*	带有布局功能的渲染   //参考YII的设计 http://www.tuicool.com/articles/BrY7Vf7
	*		以下有三个函数与此有关：
	*			render		在控制器需要渲染时使用
	*			beginContent	小部件布局文件开始处调用，并需要指定父布局文件
	*			endContent		小部件布局文件结束处调用
	*		注：wiget可嵌套
	*
	*	$actionPara 可选，传给action的参数可以原样传到这里来。一般保持默认（即false）
	*		当 Action 支持被内部其它Action调用时，该参数必须传。否则程序会找不到正确的模板路径
	*/
	
	public function render($file,$layout="main.tpl",$actionPara=false){
		//exit($file);
		if(is_array($file)){
			foreach($file as $key => $value){
				ob_start();
					extract($this->vals);
					$this->display($value,$actionPara);
					$$key  = ob_get_contents();
				ob_end_clean();
    			$this->assign($key,$$key);//这个可不用加
			}
		}else  {
		
			ob_start();
				extract($this->vals);
				$this->display($file,$actionPara);
				$dorisContent  = ob_get_contents();
			ob_end_clean();	
			
			$this->assign("dorisContent",$dorisContent);//这个可不用加
		} 
//     	ob_start();
// 			$this->display($file."_js",$actionPara,false);
// 			$dorisPageJsContent  = ob_get_contents();
// 		ob_end_clean();
// 		//当render的对象是wiget类型时，这句的含义是把变量传到父布局中去。不加会在父布局里找不到变量
// 		$this->assign("dorisPageJsContent",$dorisPageJsContent);
		
    	include _LAYOUT_DIR_.$layout;
    	
    }
   
    private $parentLayout=null;
	public function beginContent($parentLayout="main"){//开始小部件
		$this->parentLayout = $parentLayout;		
		ob_start();
	}
	
	public function endContent(){//结束小部件
    	extract($this->vals);
    		$dorisContent  = ob_get_contents();
    	ob_end_clean();
    	
    	include _LAYOUT_DIR_.$this->parentLayout;
	}

	
	
    public function clear_inc($file){
    	$key=md5(_TEMPLATE_DIR_.$file);
    	setcache($key,null,'cached_pages');
    	session ($key,null);

    }

    public function inc_c(){
    	$files = func_get_args();
    	$files_str	= implode('-', $files);
    	$key=md5(_TEMPLATE_DIR_.$files_str);
    	//echo 'haha';return ;
    	$content=Cache::getF($key , 'cached_pages' , 3600 );
    	if(!$content){
    		ob_start();
    		foreach($files as $i => $file){
    			$this->display($file);
    		}
    		$content  = ob_get_contents();
    		ob_end_clean();
    		//echo $content;
    		Cache::setF($key , $content , 'cached_pages' , 3600 );
    	}
    	echo $content;

    }

    public function inc_s($file){
    	$key=md5(_TEMPLATE_DIR_.$file);
    	$content=session($key);

    	if(!$content){
    		ob_start();
    		$this->display($file);
    		$content = ob_get_contents();
    		ob_end_clean();
    		session($key,$content);
    	}
    	echo $content;
    }

    /**
     * 先读缓存，缓存中没有就先读文件，再将其缓存
     *
     * 缓存分为m,x,f,s四种方式
     *	 	m为内存方式
     *	 	f为文件方式
     *		x为综合方式，m有则读m,m中没有则读f中的，然后把f中的内存读到m中。
     *		s为SESSION方式
     *	参数示例： "type1:file1|file2|file3","type2:filex|filey" 
     */

    public function inc(){
    	$args = func_get_args();
    	foreach($args as $i => $k_files_str){
    		$kf=explode(':', $k_files_str);
    		$type=$kf[0];
    		$files_str=$kf[1];
    		$key=md5(_TEMPLATE_DIR_.$files_str);
    		$files=explode('|', $files_str);
    		switch ($type) {
    			case 's':$content=session($key);break;
    			case 'm':$content=Cache::getM($key , 'cached_pages' , 3600 );break;
    			case 'x':$content=Cache::getX($key , 'cached_pages' , 3600 );break;
    			case 'f':case 'c':
    			default: $content=Cache::getF($key , 'cached_pages' , 3600 );break;
    		}
    		if(!$content){
    			ob_start();
    			foreach($files as $i => $file){
    				$this->display($file);
    			}

    			$content  = ob_get_contents();
    			ob_end_clean();
    			//echo $content;
    			switch ($type) {
    				case 's':session($key,$content); break;
    				case 'm':Cache::setM($key , $content , 'cached_pages' , 3600 );	break;
    				case 'x':Cache::setX($key , $content , 'cached_pages' , 3600 );	break;
    				case 'f':case 'c':
    				default:Cache::setF($key , $content , 'cached_pages' , 3600 );	break;
    			}
    			Cache::setF($key , $content , 'cached_pages' , 3600 );
    		}
    		echo $content;
    	}

    }
	//变量输出相关
	public function pr($arrName,$name=''){
		//var_dump($this->vals);
		if(!isset($this->vals[$arrName]))return;
		$arr=$this->vals[$arrName];
		if(is_array($arr)){
			if($arr && isset($arr[$name]))echo $arr[$name];
		}else if($arr)echo $arr;
	}
	
	public function arrval($arrName,$name=''){
		//var_dump($this->vals);exit;

		if(!isset($this->vals[$arrName]))return;
		$arr=$this->vals[$arrName];
		if(is_array($arr)){

			if($arr && isset($arr[$name]))return $arr[$name];

		}else if($arr)return $arr;

	}
	
	public function has_val($name){
		if(!isset($this->vals[$name]))return false;
		return true;
	}
	
	public function val($name){
		if(isset($this->vals[$name]))return $this->vals[$name];
		return false;
	}
	
	
	public function isPost(){
		return $_SERVER['REQUEST_METHOD'] == "POST";
	}
	public function isGet(){
		return $_SERVER['REQUEST_METHOD'] == "GET";
	}
	public function isPut(){
		return $_SERVER['REQUEST_METHOD'] == "PUT";
	}
	public function isDelete(){
		return $_SERVER['REQUEST_METHOD'] == "DELETE";
	}
	public function getRequestMethod(){
		return $_SERVER['REQUEST_METHOD'] ;
	}
	
	public function requestParas(){
		parse_str(file_get_contents('php://input'), $data);
		$data = array_merge($_GET, $_POST, $data);
		return $data;
	}
}


