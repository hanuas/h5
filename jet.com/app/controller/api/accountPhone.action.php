<?php
class accountPhoneController extends icommonController{

    public function test(){
        //供测试用例使用漏洞。上线需要删除
        session_destroy();
        session_id("ga5eidutbm50fl17anhv2t6na7");
        session_start();
        $_SESSION['validateCode'] = 1234;
    }


    /**
    *  手机登录注册绑定接口
    *  参考 https://login.11h5.com/account/api.php?c=phone&
    */
    public function indexAction($paras)
    {
        $action_arr = array("getVerifyImg", "verifyImgAndGetSms", "phoneSmsLogin", "chgPasswd", "bindUidForPhone");
        $action = @$_POST['d'];
        if (!in_array($action, $action_arr)) {
            json_exit(10001,"参数错误");
        }

        switch ($action) {
            case "getVerifyImg":
                $this->getVerifyImg();
                break;
            case "verifyImgAndGetSms":
                $this->verifyImgAndGetSms();
                break;
            case "phoneSmsLogin": //短信验证码登陆
                $this->phoneSmsLogin();
                break;
            case "chgPasswd": //找回密码
                $this->chgPasswd();
            break;
            case "bindUidForPhone": //游客绑定手机
                $this->bindUidForPhone();
        }

    }

    /**
     * 游客绑定手机
     */
    private function bindUidForPhone(){
        $phone = trim(@$_POST['phone']);
        $code = trim(@$_POST['smsCode']);
        $passwd = trim(@$_POST['passwd']);
        $access_token = trim(@$_POST['token']);
        $uid = trim(@$_POST['uid']);
        $game_id = trim(@$_POST['game_id'])+0;
        if(!$game_id){$game_id = @$_GET['game_id']+0;}
        $common_args = $this->getCommonArgs($_GET); //获取公共参数
        //检查参数的合法性
        if(!$phone || !$code || !isMobile($phone) || !isPassword($passwd) || !$uid || !$access_token ){
            json_exit(5,"参数错误");
        }

        $authService = new AuthService();
        //得到用户的游戏token
        $tokenInfo = $authService->getTokenInfo($access_token);
        if(@$tokenInfo['user_id'] != $uid){
            //token错误
            json_exit(8,"登陆信息过期");
        }
        $user_token = $tokenInfo['user_token'];
        $service = new UserService($game_id);
        $res = $service->fplayBindMobile($uid,$user_token,$phone,$code,$passwd,$common_args);
        if($res['state'] != 1){
            if($res['state'] == ErrorCodeService::PARAMETER_ERROR){
                json_exit(5,"参数错误");
            }else if($res['state'] == ErrorCodeService::MOBILE_CODE_ERROR){
                json_exit(11,"手机验证码错误");
            }else if($res['state'] == ErrorCodeService::MOBILE_CODE_INVALID){
                json_exit(11,"手机验证码已使用");
            }else if($res['state'] == ErrorCodeService::MOBILE_CODE_TIMEOUT){
                json_exit(12,"手机验证码过期");
            }else if($res['state'] == ErrorCodeService::MOBILE_INUSE){
                json_exit(10,"手机已被绑定或注册");
            }else{
                json_exit(6,"网络错误");
            }
        }
        json_exit(0,"success");

    }

    /**
     * 找回密码
     */
    private function chgPasswd(){
        $phone = trim($_POST['phone']);
        $code = trim($_POST['smsCode']);
        $passwd = trim($_POST['passwd']);
        $game_id = trim(@$_POST['game_id'])+0;
        if(!$game_id){$game_id = @$_GET['game_id']+0;}
        $common_args = $this->getCommonArgs($_GET); //获取公共参数
        //检查参数的合法性
        if(!$phone || !$code || !isMobile($phone) || !isPassword($passwd)  ){
            json_exit(5,"参数错误");
        }
        $service = new UserService($game_id);
        //1.检查验证码正确性
        $checkCodeRes = $service->checkFindPwdCode($phone,$code,$common_args);
        if($checkCodeRes['state'] != 1){
            if($checkCodeRes['state'] == ErrorCodeService::PARAMETER_ERROR){
                json_exit(5,"参数错误");
            }else if($checkCodeRes['state'] == ErrorCodeService::MOBILE_CODE_ERROR){
                json_exit(11,"手机验证码错误");
            }else if($checkCodeRes['state'] == ErrorCodeService::MOBILE_CODE_INVALID){
                json_exit(11,"手机验证码已使用");
            }else if($checkCodeRes['state'] == ErrorCodeService::MOBILE_CODE_TIMEOUT){
                json_exit(12,"手机验证码过期");
            }else{
                json_exit(6,"网络错误A");
            }
        }
        $vstr = $checkCodeRes['data']['vstr'];
        //2.重置密码
        $updateRes = $service->findPwd($passwd,$vstr,$common_args);
        if($updateRes['state'] != 1){
            if($updateRes['state'] == ErrorCodeService::PARAMETER_ERROR){
                json_exit(5,"参数错误");
            }else if($updateRes['state'] == ErrorCodeService::MOBILE_CODE_ERROR){
                json_exit(11,"手机验证码错误");
            }else if($updateRes['state'] == ErrorCodeService::MOBILE_CODE_INVALID){
                json_exit(11,"手机验证码已使用");
            }else if($updateRes['state'] == ErrorCodeService::MOBILE_CODE_TIMEOUT){
                json_exit(12,"手机验证码过期");
            }else if($updateRes['state'] == ErrorCodeService::RESET_PASSWORD_FAILED){
                json_exit(1,"重置密码错误");
            }else if($updateRes['state'] == ErrorCodeService::USER_NOT_EXIST){
                json_exit(13,"用户不存在");
            }else{
                json_exit(6,"网络错误B");
            }
        }
        json_exit(0,"success");
    }


    /**
     * 短信验证码登陆
     * 
     */
    private function phoneSmsLogin(){
        $phone = trim($_POST['phone']);
        $code = trim($_POST['smsCode']);
        $game_id = trim(@$_POST['game_id'])+0;
        if(!$game_id){$game_id = @$_GET['game_id']+0;}
        $common_args = $this->getCommonArgs($_GET); //获取公共参数
        //检查参数的合法性
        if(!$phone || !$code || !isMobile($phone)  ){
            json_exit(5,"参数错误");
        }
        $service = new UserService($game_id);
        $res = $service->mobileCodeLogin($phone,$code,$common_args);
        if($res['state'] != 1){
            if($res['state'] == ErrorCodeService::PARAMETER_ERROR){
                json_exit(5,"参数错误");
            }else if($res['state'] == ErrorCodeService::MOBILE_CODE_ERROR){
                json_exit(11,"手机验证码错误");
            }else if($res['state'] == ErrorCodeService::MOBILE_CODE_INVALID){
                json_exit(11,"手机验证码已使用");
            }else if($res['state'] == ErrorCodeService::MOBILE_CODE_TIMEOUT){
                json_exit(12,"手机验证码过期");
            }else{
                json_exit(6,"网络错误");
            }
        }

        //登陆成功
        $userInfo = $res['data'];
        $authService = new AuthService();
        $access_token = $authService->createAccessToken($userInfo['ktuid'],$userInfo['token']);
        if(!$access_token){
            json_exit(6,"创建token失败");
        }
        $res = array("token"=>$access_token,"error"=>0);
        echo json_encode($res);

    }

    //获取图片验证码
    private function getVerifyImg(){
        $validateCode = new ValidateCode();
        $session_id = session_id();
        $base64 = $validateCode->getBase64();
        $code = $validateCode->getCode();
        $_SESSION['validateCode'] = $code;
        $res = array('verify_session'=>$session_id,'image'=>$base64,'error'=>0);
        echo json_encode($res);exit;
    }

    //获取短信验证码
    //type: 1 找回密码 2 手机短信验证码登陆  3 绑定
    private function verifyImgAndGetSms(){

        $phone = trim($_POST['phone']);
        $type = trim($_POST['type'])+0;
        $game_id = trim(@$_POST['game_id'])+0;
        if(!$game_id){$game_id = @$_GET['game_id']+0;}
        $verify_code = trim($_POST['verify_code']);
        $verify_session = trim($_POST['verify_session']);
        $common_args = $this->getCommonArgs($_GET); //获取公共参数


        //检查参数的合法性, type:1:找回密码,2:手机短信验证码登陆 3.游客绑定
        if(!$phone || !$type || !$verify_code || !$verify_session || !isMobile($phone) || !in_array($type,array(1,2,3)) ){
            json_exit(5,"参数错误");
        }
        $session = getSessionBySessionId($verify_session);
        if(@strtolower($session['validateCode']) != strtolower($verify_code)){
            json_exit(3,"图片验证码错误");
        }

        switch($type){
            case 1:      //找回密码code
                $this->getFindPwdMobileCode($phone,$game_id,$common_args);
            break;

            case 2:     //手机验证码登陆
                $this->getMobileLoginCode($phone,$game_id,$common_args);
            break;

            case 3:     //游客绑定
                $this->getFplayBindCode($phone,$game_id,$common_args);
            break;
        }



    }

    //获取找回密码的短信验证码
    private function getFindPwdMobileCode($mobile,$game_id,$common_args){
        $service = new UserService($game_id);
        $res = $service->sendCode($mobile,"getpwdphone",$common_args);
        if($res['state'] != 1){
            if($res['state'] == ErrorCodeService::MOBILE_UNBIND){
                json_exit(13,"手机号未绑定");
            }else if($res['state'] == ErrorCodeService::MOBILE_CODE_OVER_LIMIT){
                json_exit(1013,"发送验证码超过次数限制");
            }else{
                json_exit(6,"网络错误");

            }
        }

        $res  = $res['data'];
        json_exit(0,"success");

    }

    //获取手机短信登陆的验证码
    private function getMobileLoginCode($mobile,$game_id,$common_args){
        $service = new UserService($game_id);
        $res = $service->sendCode($mobile,"regcode",$common_args);
        if($res['state'] != 1){
            if($res['state'] == ErrorCodeService::MOBILE_CODE_OVER_LIMIT){
                json_exit(1013,"发送验证码超过次数限制");
            }else{
                json_exit(6,"网络错误");
            }
        }
        $res  = $res['data'];
        json_exit(0,"success");

    }

    //获取手机短信登陆的验证码
    private function getFplayBindCode($mobile,$game_id,$common_args){
        $service = new UserService($game_id);
        $res = $service->sendCode($mobile,"bindphone",$common_args);
        if($res['state'] != 1){

            if($res['state'] == ErrorCodeService::MOBILE_INUSE){
                json_exit(10,"手机号已被使用");
            }else if($res['state'] == ErrorCodeService::MOBILE_CODE_OVER_LIMIT){
                json_exit(1013,"发送验证码超过次数限制");
            }else{
                json_exit(6,"网络错误");
            }
        }

        $res  = $res['data'];
        json_exit(0,"success");
    }
    

    public function baseAction(){
        $url = "http://www.jetapi.com/api/accountPhone";
        echo base64_encode(file_get_contents($url));
    }


    //记录用户最后一次玩的游戏
    public function userPlay(){
        $game_id = @$_GET['gameid']+0;
        $token = trim(@$_GET['token']);
        $cps_id = trim(@$_GET['chid']);
        $sub_cps_id = trim(@$_GET['subchid']);
        $from = trim(@$_GET['from']);
        
        if(!$game_id && !$token ){  //参数缺失
            $arr = array("error"=>10001);   
            echo json_encode($arr);exit;
        }

        $service = new AuthService();
        $access_token_info = $service->getTokenInfo($token);
        if(!$access_token_info){    //token校验失败
            json_exit(10001,"token校验失败");
        }        

        //TODO: 入库

        echo json_encode(array());


    }

    


}

