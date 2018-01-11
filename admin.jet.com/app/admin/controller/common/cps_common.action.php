<?php 
    	
use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Mjoin,
DataTables\Editor\Validate;



use Doris\DApp;

class cps_commonController extends  commonController{

	//MARK: 构造函数（存储SESSION）==============================
	public function __construct(){
		parent::__construct();
		
		$this->sessionUserData();
		
	}
	
	//
	/*
	*	把全局字典加到SESSION里去
	*	游戏、渠道、用户游戏、用户渠道  
	*/
	public function sessionUserData(  ){
		$user_id = $_SESSION['admin']['id'];
	 	$_SESSION['mygames'] = [];
	 	$_SESSION['myunions'] = [];
	 	$_SESSION['allgames'] = [];
	 	$_SESSION['allunions'] = [];
		if($this->hasPrivilege("cps_agent","ca_unions")){
			$_SESSION['myunions'] = self::getAgentUnions( $user_id ); 
			$_SESSION['mygames']  = self::getUnionGames( $_SESSION['myunions'] );
		}
		
		if($this->hasPrivilege("cps_manage")){
			$_SESSION['allunions'] = Doris\DDB::pdo()->query( "select id as value, name as label from tb_unionlist  " )->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
			self::simplifyColumnGroup($_SESSION['allunions'] ); 
			
			$_SESSION['allgames']  = Doris\DDB::pdo()->query( "select game_id as value,game_name as label from tb_games  " )->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
			self::simplifyColumnGroup($_SESSION['allgames'] );
		}
		
		//Doris\debugWeb($_SESSION);
	}
	
	
	
	//MARK: 工具函数==============================
	/*
	*		获取agent_id对应的渠道
	*		返回：
	*			返回示例：[10080=>"Chanel1", 10081=>"Chanel2"]  
	*			其中key为渠道id, VALUE为渠道名
	*
	*/
	public static function getAgentUnions($agent_id){
		$unions = [];
		$sql = "select id , name  from tb_unionlist where id in (select union_id from tb_sys_user_union where user_id = '$agent_id' )" ;
		$unions = Doris\DDB::pdo()->query( $sql )->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
		self::simplifyColumnGroup($unions );
		return $unions ;
	}
	
	/*
	*		获取unions对应的游戏
	*		参数unions 
	*			为 getAgentUnions 返回的数据类型
	*		返回：
	*			返回示例：[520001=>"游戏1", 520002=>"游戏2"]  
	*			其中key为游戏id, VALUE为游戏名
	*/
	public static function getUnionGames($unions){ 
		$games = [];
		$union_ids = array_keys($unions);
		$union_ids = implode(",", $union_ids );
		if($union_ids){
			$sql = "select game_id as value,game_name as label from tb_games  where game_id in (select product_id from tb_unionlist where id in ($union_ids) )" ; 
			$games = Doris\DDB::pdo()->query( $sql)->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
			self::simplifyColumnGroup( $games  );
		}
		return $games ;
	}
	 
	/*
	*		获取Agent用户信息(即系统用户)
	*/
	public static function getAgentInfo($agent_id){  
		$sql = "select * from tb_sys_user  where id = '$agent_id'" ; 
		 return  Doris\DDB::pdo()->query( $sql)->fetch( ); 
	 
	}
	
	/*
	*	记录操作日志
	*/
	public static function logOperation($title,$camera,$newcamera,$type = "default",$log=[]){
		_load( "Admin_ActionLogModel");
		$detail['camera'] = $camera;
		$detail['newcamera'] = $newcamera;
		$detail['log']=$log;
		Admin_ActionLogModel::logging($title,$detail,$type,$category="cps");
	 
	}
	
	//MARK: 共用的一些Editor请求==============================
	/*
	*	订单的Ajax请求
	*	入参：
	*		union_pairs 为 getAgentUnions 返回的数据类型
	*		agent_view 代理商视角，还是管理员视角
	*/
	public function ordersEditorData( $union_pairs ,$agent_view = true){ 
		$db = Doris\DApp::loadDT();
 		$editor = Editor::inst( $db, 'tb_pay_orders', 'id' )
			->fields( 
				Field::inst( 'id' ),
				Field::inst( 'order_id' ),
				//Field::inst( 'user_id' ),
				Field::inst( 'user_name' ),
				Field::inst( 'channel_id' ),
				Field::inst( 'pay_time' )
						->setFormatter(function ($val, $data, $field) { 
							return strtotime($val);
						})
						->getFormatter(function ($val, $data, $field) {
							return date("Y-m-d H:i:s",$val);
						}),
				Field::inst( 'paid_time' )
						->setFormatter(function ($val, $data, $field) { 
							return strtotime($val);
						})
						->getFormatter(function ($val, $data, $field) {
							return date("Y-m-d H:i:s",$val);
						}),
				Field::inst( 'pay_ip' ),
				Field::inst( 'pay_amount' ),
				//Field::inst( 'pay_lcoins' ),
				//Field::inst( 'pay_state' ),
				Field::inst( 'transaction_id' ),
				//Field::inst( 'card_no' ),
				Field::inst( 'phone_no' ), 
				//Field::inst( 'risk' ),
				Field::inst( 'pay_platform' ),
				//Field::inst( 'is_user' ),
				Field::inst( 'pay_currency' ),
				Field::inst( 'union_id' ),
				Field::inst( 'ot_status' ),
 				Field::inst( 'product_id' )
// 				Field::inst( 'server_id' ) 
			);
		$editor	->where("pay_state",2); 
		$editor	->where("pay_amount",0,">");
		
		$editor->where(function($q) use ($union_pairs){
		 	$union_ids = array_keys($union_pairs);
		 	if($union_ids){
				$union_ids = implode(",", $union_ids );
				$q->where('union_id', "( $union_ids) ", 'IN', false); 
			}else{
				$q->where('union_id', "-1" )->and_where('union_id', "-2"); 
			}
		 });
		 
		if(!empty($_GET["union_id"])){
		 	$union_id = $_GET["union_id"];
		 	
			$editor	->where("union_id", $union_id);
		}
		 
		$ot_status = @$_GET["ot_status"];
		if(!empty( $ot_status ) ){ 
			$editor	->where("ot_status", $ot_status);
		}
		
		if( $agent_view ){
			$editor	->where("ot_status", 10,"<"); 
		} 
		if(!empty($_GET['time_from'] ) && @strtotime($_GET['time_from'] ) ){
		 
			$editor	->where("paid_time", strtotime($_GET['time_from'] ) ,">=");
		}
		
		if(!empty($_GET['time_to'] ) && @strtotime($_GET['time_to'] ) ){
			$time_to = $_GET['time_to'];
		 	if(strlen($time_to) < 12){ //转成长时间格式
		 		$time_to .= " 23:59:59";
		 	}
			$editor	->where("paid_time", strtotime($time_to) ,"<=");
		}
		$editor	->process( $_POST )->json();
	}
	
	// http://opentool.netkingol.com/index.php?m=cps_manage&c=cm_agent&a=orders_calc&time_from=2017-01-04&time_to=2017-05-02&agent_id=5&union_id=10080&op_status=1
	// 如果 agent_view=true 则只显示 ot_status 小于10的状态
	public function orders_calc( $union_pairs ,$agent_view = true){
		$where = "";
		$union_ids = array_keys($union_pairs);
		if($union_ids){
			$union_ids = implode(",", $union_ids ); 
			$where = " union_id in ($union_ids)";
		}else{
			$where = " 1=-1 "; //表明用户没有渠道
		}
		
		$union_id = @$_GET["union_id"];
		if(!empty( $union_id ) ){
		 	$where .= " and union_id = '$union_id' "; 
		}
		 
		$ot_status = @$_GET["ot_status"];
		if(!empty( $ot_status ) ){
		 	$where .= " and ot_status = '$ot_status' ";
		}
		if( $agent_view ){
		 	$where .= " and ot_status < '10' ";
		} 
		if(!empty($_GET['time_from'] ) && @strtotime($_GET['time_from'] ) ){
		 	$where .= " and paid_time >= '".strtotime($_GET['time_from'] )."' ";
		}
		
		if(!empty($_GET['time_to'] ) && @strtotime($_GET['time_to'] ) ){
			$time_to = $_GET['time_to'];
		 	if(strlen($time_to) < 12){ //转成长时间格式
		 		$time_to .= " 23:59:59";
		 	} 
			
		 	$where .= " and paid_time <= '". strtotime( $time_to ) ."' ";
		}
		$where .= " and pay_state = '2' and pay_amount > 0 ";
		
		$sql = "select sum(pay_amount) amount,count(*)  count , GROUP_CONCAT(DISTINCT(order_id)) oids from tb_pay_orders where $where ";
		// 订单统计信息
		$data = Doris\DDB::fetch($sql );
		if(!$data['amount']){
			$data['amount'] = 0;
		}
		
		// 计算返利信息 agent_ratio  	ratio	deduct 
		if(!empty( $union_id ) ){
			$sql = "select game_id , union_id , ratio , agent_ratio from tb_pay_rebate_ratio where union_id = '$union_id' ";
			$rebate = Doris\DDB::fetch($sql );
			$data['rebate'] = $rebate;
			$data['deduct'] = $data['amount'] *  $rebate['agent_ratio'] / 100 ;
		}
		
		//读取用户剩余平台币：
		$agent_id = @(int)$_GET['agent_id'];
    	if( empty($agent_id )  ){
			echo json_encode(["code"=>6, "msg"=>"代理商信息有误"]);
			exit;
		}
		
		$sql = "select user_coins from tb_sys_user where id = '$agent_id' ";
		$udata = Doris\DDB::fetch($sql );
		$data['user_coins']=empty($udata['user_coins'])? 0: $udata['user_coins'];
		return ["code"=>0, "msg"=>"", "data"=>$data] ;
	}
	 
	/*
	* 单条计算
	*/
	public function one_order_calc( $id, $agent_id, $union_id  ,$agent_view = true){
		$sql = "select sum(pay_amount) amount,count(*)  count , GROUP_CONCAT(DISTINCT(order_id)) oids from tb_pay_orders where id = '$id' ";
		// 订单统计信息
		$data = Doris\DDB::fetch($sql );
		if(!$data['amount']){
			$data['amount'] = 0;
		}
		
		// 计算返利信息 agent_ratio  	ratio	deduct 
		if(!empty( $union_id ) ){
			$sql = "select game_id , union_id , ratio , agent_ratio from tb_pay_rebate_ratio where union_id = '$union_id' ";
			$rebate = Doris\DDB::fetch($sql );
			$data['rebate'] = $rebate;
			$data['deduct'] = $data['amount'] *  $rebate['agent_ratio'] / 100 ;
		}
		
		//读取用户剩余平台币：
		
		$sql = "select user_coins from tb_sys_user where id = '$agent_id' ";
		$udata = Doris\DDB::fetch($sql );
		$data['user_coins']=empty($udata['user_coins'])? 0: $udata['user_coins'];
		return ["code"=>0, "msg"=>"", "data"=>$data] ;
	}
	
	
	/*
	* 执行返利
	*/
	public function do_deduct(   $agent_id, $stat_data  ,$logtype , $logs = [] ){ 
		
		$rebate = $stat_data['rebate'];
		
		//执行返提成、更新所有涉及的订单
		$agent_ratio = $rebate['agent_ratio'];
		$deduct = $stat_data['deduct'];
		// 加平台币
		$sql = "update tb_sys_user set `user_coins` = `user_coins`+'$deduct' where id = '$agent_id' ";
		$data = Doris\DDB::execute($sql );
		// 更新订单
		$oids = $stat_data['oids'];
		$oids = "'".str_replace(",","','",$oids)."'";
		$sql = "update tb_pay_orders set `ot_status` = 2 where order_id in ($oids)";
		$data = Doris\DDB::execute($sql );
		$sql = "select user_coins from tb_sys_user where id = '$agent_id' ";
		$udata = Doris\DDB::fetch($sql );
		
		// 记LOG
		$log_title = "给代理商：$agent_id 添加平台币：{$stat_data['deduct']}，订单总额：{$stat_data['amount']}，更新后平台币：{$udata['user_coins']}";
		self::logOperation($log_title, $stat_data, $udata, $logtype ,$logs);
		//Doris\debugWeb([ $log_title , $stat_data,$udata]);
		
		return ["code"=>0, "msg"=>"成功", "data"=> $udata ];
	}
}