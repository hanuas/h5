<?php 
 
// Dispatch::load("Test");

class user_testController extends Test {
 	private $KEY;
 	private $SECRET; 
	function beforeTest(){
		$this->KEY = "8U21OQ3C"; 
		$this->SECRET ="JIP7XSRYCTA8V46FSVY0";
		//$user_pdo = DBTool::getPdoByString("user_plat", Config::get("user_plat/db")  );
        $url = Config::get("web_address")."/api/accountPhone/test";
        file_get_contents($url);
		/*
	    session_destroy();
        session_id("123456");
        session_start();
        $_SESSION['validateCode'] = 1234;
        $userRedis = $this->getUserRedis();
        $userRedis->set("Elogin_num:gao9730827",10);
        */
    }
	
	
	function afterTest(){

		
		
		 	
	}
    //获取用户redis
    private function getUserRedis(){
        return Cache::redis('user');
    }
	public function __construct(){
 
		header("Content-type: text/html; charset=utf-8");
 
        $this->setHost(Config::get("web_address")."/");

        $this->setTitle("用户相关 测试");
        $this->setRecvInfoOnREST("error","message","");
        
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
        $commonKey = array(
            "appid"=>"1011",
            "ip"=>"111.204.81.180",
            "os_type"=>"iOS",
            "net_type"=>0,
            "device"=>"QWERASSDALKJDSKLDJAAQ112",
            "sdk_version"=>"v1.0",
            "adv_channel"=>"baidu",
            "os_version"=>"v3.0",
            "carrier"=>"10000",
            "app_version"=>"v1.0"
        );
        foreach($commonKey as $key=>$val){
            $data[$key] = isset($args[$key])?$args[$key]:$val;
        }

		return $data;
	} 


    //
	
	//MARK:- 开始测试

    //登陆
	public function loginAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/accountLogin/auth" ,
			
			$this->commonData([
                "uid"=>"gao9730827",
                "password"=>"a123456",
				
			] ),
				
			"用户登陆（不需要验证码）",
			[
                "token+"=>1
			],
			"POST"
		);
        $info = json_decode($recvedOK,true);
        $_SESSION['token'] = @$info['token'];
		$this->endSubTest();
	}

    //获取图片验证码
    public function getImgVCodeAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/accountPhone" ,
			[
                "d"=>"getVerifyImg",				
			],
				
			"获取图片验证码",
			[
                "verify_session+"=>1,
                "image+"=>1
			],
			"POST"
		);
        $info = json_decode($recvedOK,true);
        $image = $info['image'];		
		$this->endSubTest();
        #echo '<div style="clear:both"></div><img style="clear:both;display:block"  src="data:image/jpg;base64,'.$image.'" />';
        
    }

    //登陆需要图片验证码
    public function loginWithVCodeAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/accountLogin/auth" ,

                $this->commonData([
                    "uid"=>"gao9730827",
                    "password"=>"123456",
                    "verify_session"=>"1234561",
                    "verify_code"=>"1234"
                ] ),

                "用户登陆（登陆需要图片验证码）",
                [
                    "token+"=>1
                ],
                "POST"
            );
            $info = json_decode($recvedOK,true);
            $_SESSION['token'] = @$info['token'];

        $this->endSubTest();
    }

    //游客登录
    public function getTrialUidAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/login/getTrialUid" ,

                $this->commonData([
                    "device"=>"ADSDJSLKDJLKSAJDQ",
                ] ),

                "游客登陆",
                [
                    "token+"=>1
                ],
                "GET"
            );
            #$info = json_decode($recvedOK,true);
            #$_SESSION['token'] = $info['token'];

        $this->endSubTest();
    }



    //checkToken
	public function checkTokenAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/login/checkToken" ,
			[
                "token"=>$_SESSION['token'],
				
			],
			"checkToken",
			[
                #"uid"=>"115980832073",
                "uid+"=>["==", "115980832073"],

			],
			"GET"
		); 
		
		$this->endSubTest();
	}

    //createCode
    public function createCodeAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/login/getCodeByToken" ,
                [
                    "token"=>@$_SESSION['token'],

                ],
                "createCode",
                [
                    "code+"=>1,
                ],
                "GET"
            );
        $info = json_decode($recvedOK,true);
        $_SESSION['code'] = $info['code'];
        $this->endSubTest();
    }

    //checkCode
    public function checkCodeAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/login/checkCode" ,
                [
                    "code"=>$_SESSION['code'],
                ],

                "checkCode",
                [
                    "uid+"=>["==", "115980832073"],
                ],
                "GET"
            );

        $this->endSubTest();
    }

    //getTokenKey
    public function getTokenKeyAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/login/getTokenKey" ,
                [
                    "token"=>$_SESSION['token'],
                ],

                "getTokenKey",
                [
                    "tokenkey+"=>1,
                ],
                "GET"
            );
        $info = json_decode($recvedOK,true);
        $_SESSION['tokenkey'] = @$info['tokenkey'];
        $this->endSubTest();
    }

    //checkTokenKey
    public function checkTokenKeyAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/login/getTokenByTokenKey" ,
                [
                    "tokenkey"=>$_SESSION['tokenkey'],
                ],

                "checkTokenKey",
                [
                    "token+"=>1,
                ],
                "GET"
            );
        $this->endSubTest();
    }

    //getUserToken
    public function getUserTokenAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/login/getUserToken" ,
                [
                    "token"=>$_SESSION['token'],
                ],

                "getUserToken",
                [
                    "userToken+"=>1,
                    "uid+"=>["==", "115980832073"],
                ],
                "GET"
            );
        $this->endSubTest();
    }

    //getUserByToken
    public function getUserByTokenAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/login/getUserByToken" ,
                [
                    "token"=>$_SESSION['token'],
                ],

                "getUserByToken",
                [
                    "uid+"=>["==", "115980832073"],
                ],
                "GET"
            );
        $this->endSubTest();
    }

    //checkNotify
    public function checkNotifyAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/busi/getNotice" ,
                [
                    "gameid"=>123,
                ],

                "checkNotify",
                [
                    #W"userToken+"=>1,
                    #"uid+"=>["==", "115980832073"],
                ],
                "GET"
            );
        $this->endSubTest();
    }  
    
    //userPlayAction
    public function userPlayAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/busi/userPlay" ,
                [
                    "gameid"=>123,
                    "token"=>$_SESSION['token'],
                    "chid"=>"baidu",
                    "subchid"=>2,

                ],

                "记录用户玩过的游戏",
                [
                    #W"userToken+"=>1,
                    #"uid+"=>["==", "115980832073"],
                ],
                "GET"
            );
        $this->endSubTest();
    }  
    //getChannelInfo
    public function getChannelInfoAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/conf/getCpsConfig" ,
                [
                    "chid"=>1,

                ],
                "获取渠道配置信息",
                [
                    #"userToken+"=>1,
                    "config+loginHideOption+"=>["==", "0,0,0,0"],
                    "config+wxh5pay+"=>["==", "0"],
                    "config+wxPay+"=>["==", "0"],
                    "config+alih5pay+"=>["==", "0"],
                ],
                "GET"
            );
        $this->endSubTest();
    }

    //applyFriend
    public function applyFriendAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/user/wechat" ,
                [
                    "cmd"=>"applyFriend",
                    "token"=>$_SESSION['token'],
                    "fuid"=>12

                ],
                "建立好友申请",
                [
                ],
                "GET"
            );
        $this->endSubTest();    
    }

    //获取短信验证码(找回密码)
    public function verifyImgAndGetSmsFindPwdAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/accountPhone" ,

                $this->commonData([
                    "d"=>"verifyImgAndGetSms",
                    "phone"=>"18911555199",
                    "verify_code"=>"1234",
                    "type"=>1,
                    "verify_session"=>"ga5eidutbm50fl17anhv2t6na7",
                ] ),

                "获取短信验证码(找回密码)",
                [
                    
                ],
                "POST"
            );
            #$info = json_decode($recvedOK,true);
            #$_SESSION['token'] = $info['token'];

        $this->endSubTest();
    }

    //找回密码
    public function chgPasswdAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/accountPhone" ,

                $this->commonData([
                    "d"=>"chgPasswd",
                    "phone"=>"18911555199",
                    "passwd"=>"12345678",
                    "smsCode"=>"549390",
                ] ),

                "找回密码",
                [
                    
                ],
                "POST"
            );
            #$info = json_decode($recvedOK,true);
            #$_SESSION['token'] = $info['token'];

        $this->endSubTest();
    }


    //获取短信验证码(手机验证码登陆)
    public function verifyImgAndGetSmsLoginAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/accountPhone" ,

                $this->commonData([
                    "d"=>"verifyImgAndGetSms",
                    "phone"=>"18911555199",
                    "verify_code"=>"1234",
                    "type"=>2,
                    "verify_session"=>"ga5eidutbm50fl17anhv2t6na7",
                ] ),

                "获取短信验证码(手机验证码登陆)",
                [
                    
                ],
                "POST"
            );
            #$info = json_decode($recvedOK,true);
            #$_SESSION['token'] = $info['token'];

        $this->endSubTest();
    }

    //手机验证码登陆
    public function phoneSmsLoginAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/accountPhone" ,

                $this->commonData([
                    "d"=>"phoneSmsLogin",
                    "phone"=>"18911555199",
                    "smsCode"=>"787025",
                ] ),

                "手机验证码登陆",
                [
                    "token+"=>1
                ],
                "POST"
            );
            #$info = json_decode($recvedOK,true);
            #$_SESSION['token'] = $info['token'];

        $this->endSubTest();
    }

    //获取短信验证码(试玩用户绑定)
    public function verifyImgAndGetSmsFplayBindAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/accountPhone" ,

                $this->commonData([
                    "d"=>"verifyImgAndGetSms",
                    "phone"=>"18911555199",
                    "verify_code"=>"1234",
                    "type"=>3,
                    "verify_session"=>"ga5eidutbm50fl17anhv2t6na7",
                ] ),

                "获取短信验证码(试玩用户绑定)",
                [
                    
                ],
                "POST"
            );
            #$info = json_decode($recvedOK,true);
            #$_SESSION['token'] = $info['token'];

        $this->endSubTest();
    }

    //三方登陆，提供给微端
    public function thirdLoginAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/login/thirdLogin" ,

                $this->commonData([
                    "oauth_uid"=>"asdasdasd",
                    "oauth_type"=>"sina",
                ] ),

                "三方登陆",
                [
                    
                ],
                "GET"
            );
            #$info = json_decode($recvedOK,true);
            #$_SESSION['token'] = $info['token'];

        $this->endSubTest();
    }

    //试玩用户绑定
    public function bindUidForPhoneAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "api/accountPhone" ,

                $this->commonData([
                    "d"=>"bindUidForPhone",
                    "phone"=>"18911555199",
                    "smsCode"=>"646901",
                    "passwd"=>"123456",
                    "token"=>"406bbed9a06e7cfce89099192f96db7b",
                    "uid"=>"115980832081",
                ] ),

                "获取短信验证码(试玩用户绑定)",
                [

                ],
                "POST"
            );
        #$info = json_decode($recvedOK,true);
        #$_SESSION['token'] = $info['token'];

        $this->endSubTest();
    }

    //获取用户实名认证信息
    public function isRealVerifyAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "ic/conf" ,

                $this->commonData([
                    "cmd"=>"isRealVerify",
                    "token"=>$_SESSION['token'],
                ] ),

                "获取用户实名认证信息",
                [
                    "realname+"=>1,
                    "idcard+"=>1,
                ],
                "GET"
            );

        $this->endSubTest();
    }

    //设置实名认证信息
    public function addRealVerifyAction(){
        $this->beginSubTest();

        list ($recvedOK,$recvCode,$recvMsg,$recvData)=
            $this->runTest( "ic/conf" ,

                $this->commonData([
                    "cmd"=>"addRealVerify",
                    "token"=>$_SESSION['token'],
                    "realname"=>"张三",
                    "idcard"=>"370211199006250516"
                ] ),

                "设置实名认证信息",
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
        
        $this->loginAction();

        #$this->getImgVCodeAction();
        #$this->loginWithVCodeAction();
        #$this->getUserByTokenAction();
        /*
        //$this->getTrialUidAction();
        $this->checkTokenAction();

        $this->createCodeAction();
        $this->checkCodeAction();
        $this->getTokenKeyAction();
        $this->checkTokenKeyAction();
        $this->getUserTokenAction();
        $this->checkNotifyAction();
        $this->userPlayAction();
        $this->getChannelInfoAction();
        $this->applyFriendAction();
       
        #$this->verifyImgAndGetSmsFindPwdAction();
        $this->chgPasswdAction();

        $this->verifyImgAndGetSmsLoginAction();
        $this->phoneSmsLoginAction();

        #$this->verifyImgAndGetSmsFplayBindAction();
        $this->bindUidForPhoneAction();

        $this->thirdLoginAction();
        */

        #$this->isRealVerifyAction();
        #$this->addRealVerifyAction();

	}
	
	/*
	*	http://icenter.netkingol.com/iuserplat_test?d&k
	*/
	public function indexAction(){ 
		$this->test(); 	
	}
	
}