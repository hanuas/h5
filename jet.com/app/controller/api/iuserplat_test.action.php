<?php 
 
// Dispatch::load("Test");

class iuserplat_testController extends Test {
 	private $KEY;
 	private $SECRET; 
	function beforeTest(){
		$this->KEY = "8U21OQ3C"; 
		$this->SECRET ="JIP7XSRYCTA8V46FSVY0";
		//$user_pdo = DBTool::getPdoByString("user_plat", Config::get("user_plat/db")  );  
		
		
	}
	
	
	function afterTest(){

		
		
		 
		
	}
	public function __construct(){
 
		header("Content-type: text/html; charset=utf-8");
 
        $this->setHost(Config::get("web_address")."/");

        $this->setTitle("iuserplat 测试");
        $this->setRecvInfoOnREST("status","message","data");
        
        $this->afterTest();
        $this->beforeTest();
        
		$this->beginTest();
	}
	
	public function __destruct(){
		 $this->endTest();
		 if( !isset($_GET['k']) )
       	 	$this->afterTest();
         parent :: __destruct();
	}
	
	//MARK:- 一些辅助函数
	private function _getSign( $arrFields, $plat="cps"){
		
		$rawString = "";
		foreach($arrFields as $fName => $fValue)
		{
			$rawString .= "{$fName}={$fValue}";
		} 
		$resign = md5($rawString . "secret={$this->SECRET}");
		 
		
		return $resign;
	}
	private function commonData($exData = []){
		$data = $exData ;
        $commonKey = array("appid"=>"1011","ip"=>"111.204.81.180","os_type"=>"iOS","net_type"=>0,"device"=>"QWERASSDALKJDSKLDJAAQ112","sdk_version"=>"v1.0","adv_channel"=>"baidu","os_version"=>"v3.0","carrier"=>"10000","app_version"=>"v1.0");
        foreach($commonKey as $key=>$val){
            $data[$key] = isset($args[$key])?$args[$key]:$val;
        }

		return $data;
	} 
	
	//MARK:- 开始测试

    //用户名注册
	public function registByUserNameAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/registByUserName" ,
			
			$this->commonData([
                "username"=>"gao9730827",
                "password"=>"a123456"
				
			] ),
				
			"用户名注册",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
	}

    //发送手机号注册验证码
    public function sendCodeRegist(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/sendCodeRegist" ,
			
			$this->commonData([
                "mobile"=>"18911555199",
				
			] ),
				
			"发送手机注册验证码",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
    }

    //手机短信登陆—发送验证码
    public function sendCodeLoginAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/sendCodeLogin" ,
			
			$this->commonData([
                "mobile"=>"18911555199",
				
			] ),
				
			"手机短信登陆—发送验证码",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
    }

    //手机短信登陆—登陆
    public function mobileCodeLoginAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/mobileCodeLogin" ,
			
			$this->commonData([
                "mobile"=>"18911555199",
                "actcode"=>"526884",
                "vstr"=>"eyJhY3RpZCI6NTksIm1vYmlsZSI6IjE4OTExNTU1MTk5IiwiYWN0Y29kZSI6IjUyNjg4NCJ9",
				
			] ),
				
			"手机短信登陆—登陆",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
    }

    //手机号注册
	public function registByMobileAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/registByMobile" ,
			
			$this->commonData([
                "mobile"=>"18911555199",
                "password"=>"123456",
                "actcode"=>"203923",
                "vstr"=>"eyJhY3RpZCI6NDEsIm1vYmlsZSI6IjE4OTExNTU1MTk5IiwiYWN0Y29kZSI6IjIwMzkyMyJ9",
				
			] ),
				
			"手机号注册",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
	}


    //登陆
	public function loginAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/login" ,
			
			$this->commonData([
                "username"=>"gao6819866",
                "password"=>"123456",
				
			] ),
				
			"用户登陆",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
	}

    //游客登陆
	public function fplayAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/fplay" ,
			
			$this->commonData([
                #"device"=>"18911555199",
				
			] ),
				
			"游客登陆",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
	}


    //游客绑定手机号—发送验证码
    public function sendCodeFplayBindAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/sendCodeFplayBind" ,
			
			$this->commonData([
                "mobile"=>"18911555199",
				
			] ),
				
			"游客绑定手机号—发送验证码",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
    }

    //游客绑定手机号
    public function fplayBindMobileAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/fplayBindMobile" ,
			
			$this->commonData([
                "mobile"=>"18911555199",
                "password"=>"1234567",
                "actcode"=>"788995",
                "ktuid"=>"6121312031",
                "vstr"=>"eyJhY3RpZCI6NDQsIm1vYmlsZSI6IjE4OTExNTU1MTk5IiwiYWN0Y29kZSI6Ijc4ODk5NSJ9",
                "token"=>"97A5843663207A1C1C5198505D6A711F",
				
			] ),
				
			"游客绑定手机号",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
    }


    //用户找回密码—发送验证码
    public function sendCodeFindPwdAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/sendCodeFindPwd" ,
			
			$this->commonData([
                "mobile"=>"18911555199",
				
			] ),
				
			"用户找回密码—发送验证码",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
    }

    //用户找回密码—检测验证码
    public function checkFindPwdCodeAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/checkFindPwdCode" ,
			
			$this->commonData([
                "mobile"=>"18911555199",
                "vstr"=>"eyJhY3RpZCI6NDMsIm1vYmlsZSI6IjE4OTExNTU1MTk5IiwiYWN0Y29kZSI6IjgxOTA3OCIsImFwcGlkIjoiMTAxMSIsImt0dWlkIjoiNjEyMTMxMjAzMCJ9",
                "actcode"=>"819078",
				
			] ),
				
			"用户找回密码—检测验证码",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
    }

    //用户找回密码—找回密码
    public function findPwdAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/findPwd" ,
			
			$this->commonData([
                "password"=>"123456789",
                "vstr"=>"eyJhY3RpZCI6NDMsIm1vYmlsZSI6IjE4OTExNTU1MTk5IiwiYWN0Y29kZSI6IjgxOTA3OCIsImFwcGlkIjoiMTAxMSIsImt0dWlkIjoiNjEyMTMxMjAzMCJ9",
				
			] ),
				
			"用户找回密码—找回密码",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
    }

    //三方登陆
    public function thirdLoginAtion(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/thirdLogin" ,
			
			$this->commonData([
                "oauth_uid"=>"sina123456789",
                "oauth_type"=>"sina",
				
			] ),
				
			"三方登陆",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
    }

    //修改密码
    public function updatePwdAtion(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/updatePwd" ,
			
			$this->commonData([
                "ktuid"=>"6121312031",
                "token"=>"2D12A5F6897C7ADE1A82DB1A21FB4C81",
                "oldpasswd"=>"1234567",
                "newpasswd"=>"123456",
				
			] ),
				
			"修改密码",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
    }


    //绑定手机号(普通用户)—发短信
    public function sendCodeBindAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/sendCodeBind" ,
			
			$this->commonData([
                "mobile"=>"18911555199",
				
			] ),
				
			"绑定手机号(普通用户)—发短信",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
    }

    //绑定手机号(普通用户)—绑定手机
    public function bindMobileAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/bindMobile" ,
			
			$this->commonData([
                "mobile"=>"18911555199",
                "actcode"=>"298092",
                "ktuid"=>"6121312032",
                "token"=>"DCA76E7682BEBE26DA762459CD9FEE49",
                "vstr"=>"eyJhY3RpZCI6NDUsIm1vYmlsZSI6IjE4OTExNTU1MTk5IiwiYWN0Y29kZSI6IjI5ODA5MiJ9",
				
			] ),
				
			"绑定手机号(普通用户)—绑定手机",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
    }

    //获取用户信息
    public function getUserInfoAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/iuserplat/getUserInfo" ,
			
			$this->commonData([
                "ktuid"=>"6121312032",
                "token"=>"DCA76E7682BEBE26DA762459CD9FEE49",
				
			] ),
				
			"获取用户信息",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
    }



    
	

	
	//===============================================
	
	//index
	public function test(){
		
        $this->displayDetailFailed();
        if( isset($_GET['d']) )$this->displayDetail();
        if( isset($_GET['s']) )$this->displaySimple();
        
        $this->resetIndex();
        
        //开始测试
		
		
		$this->addSeparator("用户相关测试");//输出换行分隔符
		$this->registByUserNameAction();
		#$this->sendCodeRegist();
        #$this->registByMobileAction();
        #$this->loginAction();
        #$this->fplayAction();
        //$this->sendCodeFplayBindAction();
        #$this->fplayBindMobileAction();
        #$this->sendCodeFindPwdAction();
        #$this->checkFindPwdCodeAction();
        #$this->findPwdAction();
        //$this->thirdLoginAtion();
        #$this->updatePwdAtion();
        #$this->sendCodeBindAction();
        #$this->bindMobileAction();
        #$this->getUserInfoAction();
        #$this->sendCodeLoginAction();
        #$this->mobileCodeLoginAction();
	}
	
	/*
	*	http://icenter.netkingol.com/iuserplat_test?d&k
	*/
	public function indexAction(){ 
		$this->test(); 	
	}
	
}