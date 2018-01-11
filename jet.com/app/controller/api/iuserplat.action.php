<?php  
// Dispatch::load("Service_User");
// Dispatch::loadController("","icommon");

class iuserplatController extends icommonController{
 

    //通过用户名注册
    public function registByUserNameAction($paras){
        if(!$_GET['appid'] || !$_GET['username']|| !$_GET['password']){
            self::echoData(1 ,"参数不全");
        }
        if(!isUserName($_GET['username'])){
            self::echoData(2 ,"用户名格式错误");
        }
        if(!isPassword($_GET['password'])){
            self::echoData(2 ,"密码格式错误");
        }
        $User2Service = new User2Service($_GET['appid']);
        $res = $User2Service->registByUserName($_GET);

        if($res['state'] == 1) {
            self::echoData(0, "success", $res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    //通过手机号注册—发送验证码
    public function sendCodeRegistAction($paras){
        $res = $this->sendCode($_GET,"regphone");
        if($res['state'] == 1) {
            #setcookie("vstr", $res['data']['vstr'], time()+100000);
            self::echoData(0, "success",$res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }   

    //发送验证码
    private function sendCode($paras,$verify_type){
        $User2Service = new User2Service(1011);
        $paras['verifytype'] = $verify_type;
        return $User2Service->sendCode($paras);

    }


    //通过手机号注册
    public function registByMobileAction($paras){
        $User2Service = new User2Service(1011);
        $res = $User2Service->registByMobile($_GET);
        #$_GET['vstr'] = $_COOKIE['vstr']; //vstr暂get传过来
        #print_r($_COOKIE);
        if($res['state'] == 1) {
            self::echoData(0, "success", $res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    //用户登陆
    public function loginAction($paras){
        $User2Service = new User2Service(1011);
        $res = $User2Service->login($_GET);
        if($res['state'] == 1) {
            self::echoData(0, "success", $res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }
    //三方登陆
    public function thirdLoginAction($paras){
        $User2Service = new User2Service(1011);
        $res = $User2Service->thridLogin($_GET);
        if($res['state'] == 1) {
            self::echoData(0, "success", $res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    //游客登陆
    public function fplayAction($paras){
        $User2Service = new User2Service(1011);
        $res = $User2Service->fplay($_GET);
        if($res['state'] == 1) {
            self::echoData(0, "success", $res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    
    //游客绑定手机—发送验证码
    public function sendCodeFplayBindAction($paras){
        $res = $this->sendCode($_GET,"bindphone");
        if($res['state'] == 1) {
            self::echoData(0, "success",$res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }


    //游客绑定手机
    public function fplayBindMobileAction($paras){
        $User2Service = new User2Service(1011);
        $res = $User2Service->fplayBindMobile($_GET);
        if($res['state'] == 1) {
            self::echoData(0, "success");
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    //用户找回密码—发送验证码
    public function sendCodeFindPwdAction($paras){
        $res = $this->sendCode($_GET,"getpwdphone");
        if($res['state'] == 1) {
            self::echoData(0, "success",$res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    //用户找回密码—检测验证码
    public function checkFindPwdCodeAction($paras){
        $User2Service = new User2Service(1011);
        $res = $User2Service->checkFindPwdCode($_GET);
        if($res['state'] == 1) {
            self::echoData(0, "success",$res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    //用户找回密码—找回密码
    public function findPwdAction($paras){
        $User2Service = new User2Service(1011);
        $res = $User2Service->findPwd($_GET);
        if($res['state'] == 1) {
            self::echoData(0, "success");
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    //用户修改密码
    public function updatePwdAction($paras){
        $User2Service = new User2Service(1011);
        $res = $User2Service->updatePwd($_GET);
        if($res['state'] == 1) {
            self::echoData(0, "success");
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    //绑定手机号—发短信
    public function sendCodeBindAction($paras){
        $res = $this->sendCode($_GET,"bindphone");
        if($res['state'] == 1) {
            self::echoData(0, "success",$res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    //绑定手机号—绑定
    public function bindMobileAction($paras){
        $User2Service = new User2Service(1011);
        $res = $User2Service->bindMobile($_GET);
        if($res['state'] == 1) {
            self::echoData(0, "success",$res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    
    //获取用户信息
    public function getUserInfoAction($paras){
        $User2Service = new User2Service(1011);
        $res = $User2Service->getUserInfo($_GET);
        if($res['state'] == 1) {
            self::echoData(0, "success",$res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    //手机短信登陆 — 发送验证码
    public function sendCodeLoginAction($paras){
        $res = $this->sendCode($_GET,"regcode");
        if($res['state'] == 1) {
            self::echoData(0, "success",$res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }

    //手机短信登陆
    public function mobileCodeLoginAction($paras){
        $User2Service = new User2Service(1011);
        $res = $User2Service->mobileCodeLogin($_GET);
        if($res['state'] == 1) {
            self::echoData(0, "success",$res['data']);
        }else{
            self::echoData(1 ,$res['msg']);
        }
    }




    
 	

	
}