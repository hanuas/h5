<?php 

include_once _ROOT_DIR_."common/action.php";  

class commonController extends Action{

	public function __construct(){
	}
	
	/**
	 * @Description 确认后台用户是否登录
	 * @param string $back_url 跳转地址
	 * @return boolean false or array
	 */
	public function ensureLogin($back_url=null){
		if( empty($_SESSION['userinfo'])){
			$this->redirect_with_back("/",$back_url);
			return false;
		}else{
			return $_SESSION['userinfo'];
		}
	}
	public function getCurrentUser( ){
		if( empty($_SESSION['userinfo'])){ 
			return false;
		}else{
			return $_SESSION['userinfo'];
		}
	}
	public function getCurrentUserId( ){
	
    	//TODO: ensure id name
		if( empty($_SESSION['userinfo']['id'])){ 
			return false;
		}else{
			return $_SESSION['userinfo']['id'];
		}
	}

		
	/*
    * @Description SESSION存储用户登录信息
    */
    public function sessionUserInfo($userInfo){
        $_SESSION['userinfo'] = $userInfo;
    }

    /*
    * @Description 刷新SESSION存储用户信息
    */
    public function refreshSESSION(){
    	//TODO: read userinfo
        $userinfo = null; //Doris\DApp::newClass("Admin_AdminModel")->readAdminById($_SESSION['userinfo']['id']);  //取用户信息
		$_SESSION['userinfo'] = $this->admin = $userinfo;    
    }

	/**
	 * @Description 获取用户基本信息
	 * @param $userid  int
	 * @return boolean false or array
	 */
	public function getUserInfoById($userid){ 
    	//TODO: read from db
        return null;
	}
	
	/**
	 * @Description 获取用户基本信息
	 * @param $username string
	 * @return boolean false or array
	 */
	public function getUserInfoByName($username){
    	//TODO: read from db
		return null;
	} 
	
    /**
    * @Description 清除登录信息
    *
    */
	public function clear_login(){
		session_destroy(); 
		return true;
	}
	 
    
	static function echoData($status,$message,$data=null){
		echo json_encode([
			"status"  => $status,
			"message" => $message,
			"data"	  =>  $data ,
			"time"	  =>  date("Y-m-d H:i:s") 
		]); 
		exit;
	}

	
}