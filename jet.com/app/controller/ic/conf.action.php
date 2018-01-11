<?php
class confController extends icommonController{
    
    /**
    *
    */
    public function indexAction(){
        if(!in_array(@$_GET['cmd'],array("isRealVerify","addRealVerify"))){
            json_exit(10002,"参数错误");
        }

        switch($_GET['cmd']){
            case "isRealVerify":
                $this->isRealVerify();
            break;
            case "addRealVerify":
                $this->addRealVerify();
            break;
        }

    }

    /**
     * @desc 实名认证查询接口
     */
    private function isRealVerify(){
        $token = trim(@$_GET['token']);
        if(!$token){
            json_exit(402,"缺少token参数");
        }
        $authService = new AuthService();
        $tokenInfo = $authService->getTokenInfo($token);
        //检查用户token
        $game_id = @$_GET['game_id']+0;
        $userService = new UserService($game_id);
        $comArgs = $this->getCommonArgs($_GET);
        if(!$userService->checkUserToken($tokenInfo['user_id'],$tokenInfo['user_token'],$comArgs)){
            json_exit(402,"token校验失败");
        }
        $res = $userService->getRealVerifyInfo($tokenInfo['user_id'],$tokenInfo['user_token'],$comArgs);
        if($res['state'] != 1 && $res['state'] != ErrorCodeService::USER_NOT_VERIFY_REAL_NAME){
            json_exit($res['state'],$res['msg']);
        }

        $verifyInfo = @$res['data'];
        if(@$verifyInfo['idcard']){
            echo json_encode(array("isVerify"=>1,"realname"=>$verifyInfo['realname'],"idcard"=>$verifyInfo['idcard'],"error"=>0));
        }else{
            echo json_encode(array("isVerify"=>0,"realname"=>'',"idcard"=>'',"error"=>0));
        }

    }

    /**
     * 实名认证
     */
    public function addRealVerify(){
        $token = trim(@$_GET['token']);
        $realname = trim(@$_GET['realname']);
        $idcard = trim(@$_GET['idcard']);
        if(!$token || !$realname || !$idcard){
            json_exit(402,"参数缺失");
        }
        if(!is_idcard($idcard)){
            json_exit(60005,"身份证号码错误");
        }
        if(!isChineseName($realname)){
            json_exit(60005,"身份证号码错误");
        }

        $authService = new AuthService();
        $tokenInfo = $authService->getTokenInfo($token);
        //检查用户token
        $game_id = @$_GET['game_id']+0;
        $userService = new UserService($game_id);
        $comArgs = $this->getCommonArgs($_GET);
        if(!$userService->checkUserToken($tokenInfo['user_id'],$tokenInfo['user_token'],$comArgs)){
            json_exit(402,"token校验失败");
        }

        $res = $userService->setRealVerifyInfo($tokenInfo['user_id'],$realname,$idcard,$comArgs);
        if($res['state'] == 1){
            echo json_encode(array('error'=>0));exit;
        }elseif($res['state'] == ErrorCodeService::USER_ID_CARD_EXIST){
            json_exit(60004,"该身份证已经被实名认证了");
        }else{
            json_exit($res['state'],$res['msg']);
        }




    }

    


    


}

