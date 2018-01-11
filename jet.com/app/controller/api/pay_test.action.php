<?php 
 
// Dispatch::load("Test");

class pay_testController extends Test {
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

        $this->setTitle("支付 测试");
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
        $commonKey = array("ip"=>"111.204.81.180","os_type"=>"iOS","net_type"=>0,"device"=>"QWERASSDALKJDSKLDJAAQ112","sdk_version"=>"v1.0","adv_channel"=>"baidu","os_version"=>"v3.0","carrier"=>"10000","app_version"=>"v1.0","appid"=>"1011");
        foreach($commonKey as $key=>$val){
            $data[$key] = isset($args[$key])?$args[$key]:$val;
        }

		return $data;
	} 
	
	//MARK:- 开始测试

    //创建订单
	public function createOrderAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/pay/createOrder" ,
			
			$this->commonData([
                'areaID'=>'901',    //大区id
                'serverID'=>'9011',     //服务器id
                'serverName'=>'开天9区',   //服务器名
                'roleID'=>'100',       //角色id
                'roleName'=>'屠龙宝刀',     //角色名
                'roleLevel'=>'10',    //角色等级
                'productID'=>'10111',    //商品id
                'productName'=>'倚天剑',  //商品名称
                'accountID'=>'10',    //游戏账号id
                'extendbox'=>'123456',    //扩展参数，用于支付通知发货
                'gateway'=>'wxpay',      //支付类型
                'ktuid'=>'6121312032',        //用户唯一标识
                'token'=>'DCA76E7682BEBE26DA762459CD9FEE49',        //token
                'kt_payext'=>'',    //第三方支付类型，默认为空(用于苹果支付)
				
			] ),
				
			"创建订单",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
	}


    //订单列表
	public function orderListAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/pay/getOrderList" ,
			
			$this->commonData([
                'ktuid'=>'6121312032',    //开天用户id
                'token'=>'DCA76E7682BEBE26DA762459CD9FEE49',     //token
                'page'=>'1',     //第几页
                'pagesize'=>'10', //每页显示数量
                'paystate'=>0,//0全部，1,未支付，2支付成功，等待加元宝，3加元宝成功
			] ),
				
			"订单列表",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
	}

    //订单详情
	public function orderInfoAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/pay/getOrderInfo" ,
			
			$this->commonData([
                'ktuid'=>'6121312032',    //开天用户id
                #'token'=>'DCA76E7682BEBE26DA762459CD9FEE49',     //token
                'order_id'=>'851121884546799986',     //订单id
			] ),
				
			"订单详情",
			[
			],
			"GET"
		); 
		
		$this->endSubTest();
	}


    //订单详情
	public function orderNotifyAction(){
		$this->beginSubTest();
		
		list ($recvedOK,$recvCode,$recvMsg,$recvData)=
			$this->runTest( "api/pay/orderNotify" ,
			
			$this->commonData([
                'uid'=>'6121312032',    //开天用户id
                'orderID'=>'851125289381878458',     //订单id
                'thirdOrderID'=>time(),     //订单id
                'payTime'=>time(),     //订单id
                'money'=>'10',     //订单id
			] ),
				
			"订单详情",
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
		
		
		$this->addSeparator("支付相关测试");//输出换行分隔符
		$this->createOrderAction();
        $this->orderListAction();
        $this->orderInfoAction();
        $this->orderNotifyAction();
		
	}
	
	/*
	*	http://icenter.netkingol.com/iuserplat_test?d&k
	*/
	public function indexAction(){ 
		$this->test(); 	
	}
	
}