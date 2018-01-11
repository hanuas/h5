<?php
class PayService extends commonService{

    const CREATE_ORDER_URL = "https://testgsdkapi.ktsdk.com/web_H5/pay/getorder";  //下单接口地址
    const ORDER_LIST_URL = "https://testgsdkapi.ktsdk.com/web_H5/ordercore/getorderlist"; //获取订单列表url
    const ORDER_INFO = "https://testgsdkapi.ktsdk.com/web_H5/ordercore/getorder"; //获取订单详情
    const ORDER_NOTIFY = "https://testgsdkapi.ktsdk.com/web_H5/pay/callback";   //支付回调
   

    public function __construct($app_id = 0){
        if(!$app_id){
            $app_id = self::COMMON_APP_ID;  //如果不传app_id 就用通用的app_id
        }
        parent::__construct($app_id);
    }

    public function setAppId($app_id){
        parent::__construct($app_id);
    }


    public function test(){
        echo "test.".$this->APP_ID;
    }

    public function getLocalOrderInfoByOrderId($order_id){
        $payOrdersModel = new PayOrdersModel();
        return $payOrdersModel->getInfoByOrderId('*',$order_id);
    }

    //检查订单是否支付完成
    public function checkOrderIsPaied($order_id){
        $payOrdersModel = new PayOrdersModel();
        return $payOrdersModel->getInfo('order_id',"order_id='{$order_id}' and payState>1");
    }

    /**
     * 下单接口
     */
    public function createOrder($ktuid,$token,$gateway,$productID,$productName,$amount,$areaID,$serverID,$serverName,$roleID,$roleName,$roleLevel,$accountID,$extendbox,$kt_payext,$com_args){
        //公共参数
        $sendArgs = $com_args;
        //组合为要发送的参数
        $sendArgs['areaID'] = $areaID;
        $sendArgs['serverID'] = $serverID;
        $sendArgs['serverName'] = $serverName;
        $sendArgs['roleID'] = $roleID;
        $sendArgs['roleName'] = $roleName;
        $sendArgs['roleLevel'] = $roleLevel;
        $sendArgs['productID'] = $productID;
        $sendArgs['productName'] = $productName;
        $sendArgs['accountID'] = $accountID;
        $sendArgs['extendbox'] = $extendbox;
        $sendArgs['gateway'] = $gateway;
        $sendArgs['ktuid'] = $ktuid;
        $sendArgs['token'] = $token;
        $sendArgs['kt_payext'] = $kt_payext;
        #print_r($sendArgs);exit;
        $result = $this->send($sendArgs,[],self::CREATE_ORDER_URL);
        if($result === false){
            return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"通信失败");
        }
        $result = json_decode($result,true);
        if($result['msg_code'] !== "20000"){
            return array("state"=>$result['msg_code'],"msg"=>$result['msg_content']);
        }

        $data = json_decode($result['data'],true);

        $order_id = $data['orderid'];
        $model = new PayOrdersModel();

        //添加到数据库

        $addArgs = array();
        $addArgs['order_id'] = $order_id;
        $addArgs['ktuid'] = $ktuid;
        $addArgs['appid'] = $this->APP_ID;
        $addArgs['channel'] = $com_args['adv_channel'];
        $addArgs['currency'] = 'RMB';
        $addArgs['amount'] = $amount;
        $addArgs['realamount'] = $amount;

        $addArgs['roleID'] = $roleID;
        $addArgs['roleName'] = $roleName;
        $addArgs['roleLevel'] = $roleLevel;
        $addArgs['areaID'] = $areaID;
        $addArgs['accountID'] = $accountID;
        $addArgs['serverID'] = $serverID;
        $addArgs['serverName'] = $serverName;
        $addArgs['gateway'] = $gateway;
        //$addArgs['payOrderTime'] = "";
        $addArgs['productID'] = $productID;
        $addArgs['productName'] = $productName;
        $addArgs['productDesc'] = $productName;
        $addArgs['kt_ext'] = $kt_payext;
        $addArgs['extendbox'] = $extendbox;
        $addArgs['userip'] = getClientIP();
        $addArgs['addtime'] = date("Y-m-d H:i:s");


        $add_res = $model->add($addArgs);
        if($add_res === false){
            return array("state"=>ErrorCodeService::INSERT_DB_ERROR,"msg"=>"添加数据错误");
        }

        return array("state"=>1,"data"=>array('order_id'=>$order_id));

    }

    /**
    *   下单接口
    */
    public function createOrder_del($args){
        if(!@$args['areaID'] || !@$args['serverID'] || !@$args['serverName'] || !@$args['roleID'] || !@$args['roleName'] || !@$args['roleLevel'] || !@$args['productID'] || !@$args['productName'] || !@$args['accountID'] || !@$args['extendbox']|| !@$args['gateway']|| !@$args['ktuid']|| !@$args['token']){
            return array("state"=>2,"msg"=>"缺少参数");
        }
        
        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合为要发送的参数
        $sendArgs['areaID'] = $args['areaID'];
        $sendArgs['serverID'] = $args['serverID'];
        $sendArgs['serverName'] = $args['serverName'];
        $sendArgs['roleID'] = $args['roleID'];
        $sendArgs['roleName'] = $args['roleName'];
        $sendArgs['roleLevel'] = $args['roleLevel'];
        $sendArgs['productID'] = $args['productID'];
        $sendArgs['productName'] = $args['productName'];
        $sendArgs['accountID'] = $args['accountID'];
        $sendArgs['extendbox'] = $args['extendbox'];
        $sendArgs['gateway'] = $args['gateway'];
        $sendArgs['ktuid'] = $args['ktuid'];
        $sendArgs['token'] = $args['token'];
        $sendArgs['kt_payext'] = isset($args['kt_payext'])?$args['kt_payext']:"";
        
        $result = $this->send($sendArgs,[],self::CREATE_ORDER_URL);
        
        if($result === false){
            return array("state"=>3,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>4,"msg"=>$result['msg_content']);
        }
        $data = json_decode($result['data'],true);
        
        $order_id = $data['orderid']; 
        $model = new PayOrdersModel();
        
        //添加到数据库

        $addArgs = array();
        $addArgs['order_id'] = $order_id;
        $addArgs['ktuid'] = $args['ktuid'];
        $addArgs['appid'] = $this->APP_ID;
        $addArgs['channel'] = $args['adv_channel'];
        $addArgs['currency'] = 'RMB';
        $addArgs['amount'] = 11; //从自己库里取
        $addArgs['realamount'] = 11;

        $addArgs['roleID'] = $args['roleID'];
        $addArgs['roleName'] = $args['roleName'];
        $addArgs['roleLevel'] = $args['roleLevel'];
        $addArgs['areaID'] = $args['areaID'];
        $addArgs['accountID'] = $args['accountID'];
        $addArgs['serverID'] = $args['serverID'];
        $addArgs['serverName'] = $args['serverName'];
        $addArgs['gateway'] = $args['gateway'];
        //$addArgs['payOrderTime'] = "";
        $addArgs['productID'] = $args['productID'];
        $addArgs['productName'] = $args['productName'];
        $addArgs['productDesc'] = $args['productName'];
        $addArgs['kt_ext'] = $sendArgs['kt_payext'];
        $addArgs['extendbox'] = $args['extendbox'];
        $addArgs['userip'] = getClientIP();
        $addArgs['addtime'] = date("Y-m-d H:i:s");

        
        $add_res = $model->add($addArgs);
        if($add_res === false){
            return array("state"=>5,"msg"=>"添加数据错误");
        }
        
        return array("state"=>1,"data"=>$data);


    }

    /**
    *   获取订单列表
    */

    public function getOrderList($args){
        if(!@$args['ktuid'] || !@$args['token'] ){
            return array("state"=>2,"msg"=>"缺少参数");
        }

        $args['page'] = intval($args['page'])>0?intval($args['page']):1;
        $args['pagesize'] = intval($args['pagesize'])>0?intval($args['pagesize']):10;
        
        //订单状态，0全部，1,未支付，2支付成功，等待加元宝，3成功
        if($args['paystate'] != 0 && $args['paystate'] != 2 && $args['paystate'] != 3){
            $args['paystate'] = 0;
        }


        
        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合为要发送的参数
        $sendArgs['ktuid'] = $args['ktuid'];
        $sendArgs['token'] = $args['token'];
        $sendArgs['page'] = $args['page'] = 10;
        $sendArgs['pagesize'] = $args['pagesize'];
       
        
        $result = $this->send($sendArgs,[],self::ORDER_LIST_URL);
        if($result === false){
            return array("state"=>3,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>4,"msg"=>$result['msg_content']);
        }
        if(@!$result['data']){
            //没有订单
            $result['data'] = array();
        }
        
        $data = json_decode($result['data'],true);
       
        
        return array("state"=>1,"data"=>$data);


    }
    
    //获取订单详情
    public function getOrderInfo($args){
        if(!@$args['ktuid'] || !@$args['order_id'] ){
            return array("state"=>2,"msg"=>"缺少参数");
        }
        
        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合为要发送的参数
        $sendArgs['ktuid'] = $args['ktuid'];
        $sendArgs['order_id'] = $args['order_id'];   
        
        $result = $this->send($sendArgs,[],self::ORDER_INFO);
        
        if($result === false){
            return array("state"=>3,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>4,"msg"=>$result['msg_content']);
        }
        $data = json_decode($result['data'],true);
       
        
        return array("state"=>1,"data"=>$data);
    }

    public function orderNotify($uid,$orderID,$thirdOrderID,$payTime,$money,$common_args){

        //公共参数
        $sendArgs = $common_args;

        //组合要发送的参数
        $sendArgs['uid'] = $uid;
        $sendArgs['orderID'] = $orderID;
        $sendArgs['thirdOrderID'] = $thirdOrderID;
        $sendArgs['payTime'] = $payTime;
        $sendArgs['money'] = $money;

        $paySign = md5($uid.$orderID.$thirdOrderID.$payTime.$money.$this->APP_SECRET);
        $sendArgs['paySign'] = $paySign;

        $result = $this->send($sendArgs,[],self::ORDER_NOTIFY);
        if($result === false){
            return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"通信失败");
        }
        $result = json_decode($result,true);
        if($result['msg_code'] !== "20000"){
            $updateArgs = array();
            $updateArgs['payState'] = 2;
            $updateArgs['payOrderTime'] = $payTime;
            $updateArgs['thirdOrderID'] = $thirdOrderID;
            $model = new PayOrdersModel();
            $model->update($updateArgs,$orderID);
            return array("state"=>$result['msg_code'],"msg"=>$result['msg_content']);
        }


        //改本地数据库订单状态
        $updateArgs = array();
        $updateArgs['payOrderTime'] = $payTime;
        $updateArgs['completeTime'] = date("Y-m-d H:i:s");
        $updateArgs['payState'] = 3;
        $updateArgs['thirdOrderID'] = $thirdOrderID;
        $model = new PayOrdersModel();
        $model->update($updateArgs,$orderID);

        return array("state"=>1);

    }

    public function orderNotify_del($args){
        if(!@$args['uid'] || !@$args['orderID'] || !@$args['thirdOrderID'] || !@$args['payTime'] || !@$args['money'] ){
            return array("state"=>2,"msg"=>"缺少参数");
        }
        
        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合要发送的参数
        $sendArgs['uid'] = $args['uid'];
        $sendArgs['orderID'] = $args['orderID'];
        $sendArgs['thirdOrderID'] = $args['thirdOrderID'];       
        $sendArgs['payTime'] = $args['payTime'];       
        $sendArgs['money'] = $args['money'];  
        
        $paySign = md5($sendArgs['uid'].$sendArgs['orderID'].$sendArgs['thirdOrderID'].$sendArgs['payTime'].$sendArgs['money'].$this->APP_SECRET);
        $sendArgs['paySign'] = $paySign;
        
        $result = $this->send($sendArgs,[],self::ORDER_NOTIFY);
        if($result === false){
            return array("state"=>3,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>4,"msg"=>$result['msg_content']);
        }
        
        
        //改本地数据库订单状态
        
        $updateArgs = array();
        $updateArgs['completeTime'] = date("Y-m-d H:i:s"); 
        $updateArgs['payState'] = 3; 
        $model = new PayOrdersModel();
        $model->update($updateArgs,$args['orderID']);

        return array("state"=>1);

    }


    //获取用户充值总额
    public function getPayAmount($user_id,$app_id,$pay_start_time = "",$pay_end_time = ""){

        $model = new PayOrdersModel();

        $where = "ktuid = {$user_id} and appid = {$app_id}";
        if($pay_start_time){
            $where.=" and payOrderTime >='{$pay_start_time}'";
        }
        if($pay_end_time){
            $where.=" and payOrderTime <='{$pay_end_time}'";
        }
        $where.=" and (payState = 2 or payState =3)";
        return $model->getPayAmonut($where);
    }

    //检查下订单参数
    public function checkPlaceOrderParam($game_id,$uid,$token,$product_id,$areaID,$serverID,$serverName,$roleID,$roleName,$accountID,$com_args){
        //检查参数是否完整
        if(!$game_id || !$uid || !$product_id || !$areaID || !$serverID || !$serverName || !$roleID || !$roleName || !$accountID || !$token){
            return array('state'=>ErrorCodeService::PARAMETER_ERROR,'msg'=>'参数缺失');
        }
        //检查token参数
        $checkTokenRes = $this->checkTokenParam($uid,$token,$game_id,$com_args);
        if(!$checkTokenRes){
            return array('state'=>ErrorCodeService::USER_TOKEN_ERROR,'msg'=>'无效的token信息');
        }
        /*
        //检查游戏参数
        $gameInfo = $this->checkGameAppidParam($game_id);
        if(!$gameInfo){
            return array('state'=>ErrorCodeService::GAME_NOT_EXIST,'msg'=>'游戏不存在');
        }

        //检查游戏item参数
        $checkItemRes = $this->checkGameItem($product_id,$gameInfo['id']);
        if(!$checkItemRes){
            return array('state'=>ErrorCodeService::GAME_ITEM_NOT_EXIST,'msg'=>'游戏商品不存在');
        }
        */

        return array('state'=>1);
    }

    //检查token参数
    private function checkTokenParam($uid,$token,$game_id,$com_args){
        $authService = new AuthService();
        $access_token_info = $authService->getTokenInfo($token);

        if(!$access_token_info){    //token校验失败
            return false;
        }
        //检查用户token
        $userService = new UserService($game_id);
        if(!$userService->checkUserToken($access_token_info['user_id'],$access_token_info['user_token'],$com_args)){
            return false;
        }
        if($access_token_info['user_id'] != $uid){
            return false;
        }
        return true;

    }
    //检查游戏appid
    private function checkGameAppidParam($appid){
        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($appid);
        return $gameInfo?$gameInfo:false;
    }

    //检查商品item参数
    private function checkGameItem($item_id,$game_id){
        $gameItemService = new GameItemService();
        $res = $gameItemService->getItemInfoByItemId($item_id,$game_id);
        if($res){
            return true;
        }else{
            return false;
        }
    }


 
}