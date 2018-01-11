<?php 


class auto_fetchController extends commonController{
 
	 
	public function __construct(){
		//
		
	}

	
	
	/**
	*	自动拉取订单
	*
	**/
	// 5 */1 * * * /usr/bin/php /data/www/opentool.jupiter.com/console/index.php r=auto_fetch/orders 
	// 每小时05分运行 自动拉取订单
	//本机：~/Work/Baidu/Jupiter/Jupiter_Web/opentool.jupiter.com/console/index.php r=auto_fetch/orders 
	public function ordersAction(){ 
        list( $time_from, $time_to, $t, $h) = $this->parseFromTo(); 
        
		$icenter = _new("Service_ICenter");   
		$page = 0;
		$page_size = 50;
		
		$sql = "delete from  tb_pay_orders where paid_time > '$time_from' and paid_time <= '$time_to' ";
		$data = Doris\DDB::execute($sql );
		$r = false;
		do{
			$r = $icenter->orderGet($time_from, $time_to ,false,false,$page ,$page_size );
			//   [$recvedOK,$recvCode,$recvMsg,$recvData,$raw];
		
			if($r[1] == 0){//成功
			
			
				$fields = 'order_id, user_id, user_name, channel_id, pay_time, paid_time, pay_ip, pay_amount, pay_lcoins, pay_state, transaction_id, card_no, phone_no, risk, pay_platform, is_user, pay_currency, union_id, product_id, server_id';
				$values ='';
				$fields_arr = explode("," , $fields );
				$datas = $r[3]['root']; 
			
				foreach($datas  as $idx => &$data){
					if( $idx > 0 )$values .= "," ;
					$values .= "(" ; 
					array_walk($fields_arr ,function ($v,$k) use(&$values,&$data ) { 
						if($k > 0)  $values .= "," ;
						 $values .= "'".$data[trim($v)] ."'" ;
					});
					$values .= ")" ;
				}
				if(!empty($values)){
					$sql = "INSERT INTO tb_pay_orders ($fields ) values ".$values;
					
					$result = Doris\DDB::execute($sql );
					// Doris\debugWeb( $sql );
					echo $page;
				}
			
			}else{ 
				//出错
			}
			sleep(1); // 休息一下，不要频繁拉大数据
			$page ++;
		}while( !empty($r[3]['root']) && count($r[3]['root']) ==  $page_size );
	}
	
	
	/**
	*	自动拉取用户
	*	
	**/
	// 10 */1 * * * /usr/bin/php /data/www/opentool.jupiter.com/console/index.php r=auto_fetch/users 
	// 每小时10分运行 
	//本机：~/Work/Baidu/Jupiter/Jupiter_Web/opentool.jupiter.com/console/index.php r=auto_fetch/users 
	public function usersAction(){
        list( $time_from, $time_to, $t, $h) = $this->parseFromTo(); 
        
		$icenter = _new("Service_ICenter");   
		$page = 0;
		$page_size = 50;
		
		$sql = "delete from  tb_game_union_user_reg where reg_time > '$time_from' and reg_time <= '$time_to' ";
		$data = Doris\DDB::execute($sql );
		$r = false;
		do{
		 	$r = $icenter->unionUsersGet($time_from,  $time_to,  false, $page ,$page_size ) ;
		
			if($r[1] == 0){//成功
			
			
				$fields = 'game_id, union_id, child_union_id, user_id, user_name, nick_name, expand_user_name, email, mobile, reg_time';
				$values ='';
				$fields_arr = explode("," , $fields );
				$datas = $r[3]['root']; 
			
				foreach($datas  as $idx => &$data){
					if( $idx > 0 )$values .= "," ;
					$values .= "(" ; 
					array_walk($fields_arr ,function ($v,$k) use(&$values,&$data ) { 
						if($k > 0)  $values .= "," ;
						 $values .= "'".$data[trim($v)] ."'" ;
					});
					$values .= ")" ;
				}
				if(!empty($values)){
					$sql = "INSERT INTO tb_game_union_user_reg ($fields ) values ".$values;
					
					$result = Doris\DDB::execute($sql );
					// Doris\debugWeb( $sql );
					echo $page;
				}
			
			}else{ 
				//出错
			}
			sleep(1); // 休息一下，不要频繁拉大数据
			$page ++;
		}while( !empty($r[3]['root']) && count($r[3]['root']) ==  $page_size );
	}
	 
	//  0 */1 * * * /usr/bin/php /data/www/opentool.jupiter.com/console/index.php r=auto_fetch/test 
	//本机：~/Work/Baidu/Jupiter/Jupiter_Web/opentool.jupiter.com/console/index.php r=auto_fetch/test 
	public function testAction(){
		echo 123;
	}
	
	
	
	
 
}

