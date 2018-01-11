<?php
    	
use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Mjoin,
DataTables\Editor\Validate;



/**
 * @Description 订单管理
 * 
 */


class ordersController extends commonController{
	public function indexAction(){
		$this->assign("js", "pay_manage/order_list.js");	
        _load( "Admin_GameModel");
        $game_list = Doris\DDB::pdo()->query( "select appid as value, game_name as label from game  " )->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);

        $order_state = array(
            "1"=>array("未支付"),
            "2"=>array("支付成功"),
            "3"=>array("发货成功"),
        );
        self::simplifyColumnGroup($order_state);

        $this->assign("js_para", json_encode([
			"games"=> $game_list ,
            "order_state"=>$order_state,
		]));
		$this->assign("js_privilege", json_encode(array("privilege_code"=> 0 )));
		$this->assign("title", _lan('BackgroundUserManagement','订单管理'));
		$this->render(false,"common_list.tpl");
	}
	
	public function index_ajaxAction(){
		$db = Doris\DApp::loadDT();
 		$out = Editor::inst( $db, 'pay_orders' ,"id" )
		->fields(
			Field::inst( 'pay_orders.id' ),
			Field::inst( 'pay_orders.order_id' ),
			Field::inst( 'pay_orders.ktuid' ),
			Field::inst( 'pay_orders.amount' ),
			Field::inst( 'pay_orders.gateway' ),
			Field::inst( 'pay_orders.addtime' ),
			Field::inst( 'pay_orders.serverID' ),
			Field::inst( 'pay_orders.serverName' ),
			Field::inst( 'pay_orders.payState' ),
			Field::inst( 'pay_orders.productName' ),
			Field::inst( 'pay_orders.productID' ),
			Field::inst( 'game.game_name' ),
			Field::inst( 'game.appid' )
		)->leftJoin( 'game', 'pay_orders.appid', '=', 'game.appid' )
        ->process($_POST)
        ->data(); 
        _load( "Admin_PrivilegeModel");
        $privilegeModel = new Admin_PrivilegeModel();
        foreach($out['data'] as $k=>$v){
            $out['data'][$k]['operation'] = '';
            if($privilegeModel->checkAuth('pay_manage','orders','viewOrder')){
                #print_r($v);exit;
                $out['data'][$k]['operation'] .= '<a href="?m=pay_manage&c=orders&a=viewOrder&id='.$v['pay_orders']['id'].'">查看订单信息</a>&nbsp;&nbsp;';
            }
          
        }
	    echo json_encode($out);  
	}

    
    //查看订单信息
    public function viewOrderAction(){
        _load( "Admin_PayOrdersModel");
        $order_info = Admin_PayOrdersModel::readOrderById($_GET['id']);
        if(!$order_info){$this->error('页面未找到','/index.php?m=pay_manage&c=orders');}
        _load( "Admin_GameModel");
        $game_info = Admin_GameModel::readGameByAppId($order_info['appid']);
        $this->assign("menu", "game/menu.tpl");
        $this->assign("title", '查看订单信息');
        $this->assign('order_info',$order_info);
        $this->assign('game_info',$game_info);
        $this->render("/pay_manage/orders/viewOrder.tpl","main.tpl");
    }
    
 
}