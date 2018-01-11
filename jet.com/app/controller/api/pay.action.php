<?php  
// Dispatch::load("Service_User");
// Dispatch::loadController("","icommon");

class payController extends icommonController{
    
    //创建订单接口
    public function createOrderAction($params){
        $payService = new PayService(1011);
        $res = $payService->createOrder($_GET);
        if($res['state'] == 1) {
            self::echoData(0, "success",$res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    
    //根据 game_id,item_id,channel_platform(平台) 获取支付渠道列表
    public function getPayChannelsAction($params){
        $model = new PayChannelModel();
        $res = $model->getPayChannelsByItem(1,10,2);
        var_dump($res);exit;
    }


    



    //订单列表接口
    public function getOrderListAction($params){
        $payService = new PayService(1011);
        $res = $payService->getOrderList($_GET);
        if($res['state'] == 1) {
            self::echoData(0, "success",$res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    //订单详细信息接口
    public function getOrderInfoAction($params){
        $payService = new PayService(1011);
        $res = $payService->getOrderInfo($_GET);
        if($res['state'] == 1) {
            self::echoData(0, "success",$res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
        
    }

    //订单通知接口
    public function orderNotifyAction($params){
        $payService = new PayService(1011);
        $res = $payService->OrderNotify($_GET);
        if($res['state'] == 1) {
            self::echoData(0, "success");
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    
	
}