<?php
class loginController extends icommonController{
    
    /**
    *   @Desc 检验token，生成code
    *   模仿:https://api.11h5.com/login?cmd=getCodeByToken&token=6ae018a134282b8e64eb4ea85840ad0d
    */
    public function getCodeByTokenAction($paras){
        if(!@$_GET['token']){//参数缺失
            json_exit(10010,"参数缺失");
        }
        $token = trim($_GET['token']);
        $service = new AuthService();
        $access_token_info = $service->getTokenInfo($token);
        
        if(!$access_token_info){    //token校验失败
            json_exit(402,"token校验失败");
        }

        //检查用户token
        $game_id = @$_GET['game_id']+0;
        $userService = new UserService($game_id);
        $comArgs = $this->getCommonArgs($_GET);
        if(!$userService->checkUserToken($access_token_info['user_id'],$access_token_info['user_token'],$comArgs)){
            json_exit(402,"token校验失败");
        }

        //生成code
        $auth_code = $service->createCode($access_token_info['user_id'],$access_token_info['user_token']);
        if(!$auth_code){
            json_exit(403,"code生成失败");
        }
        $res = array("code"=>$auth_code,"error"=>0);
        echo json_encode($res);exit;
    }

    //检验code,得到token及用户信息
    //模仿 https://api.11h5.com/login?cmd=checkCode&code=c-440d6d5be5b94e455078952252324ad3
    public function checkCodeAction($paras){
        if(!@$_GET['code']){//参数缺失
            json_exit(10010,"参数缺失");
        }
        $code = trim($_GET['code']);
        $service = new AuthService();
        $code_info = $service->getCodeInfo($code);
        if(!$code_info){
            json_exit(403,"code校验失败");
        }
        //删除code
        $service->deleteCode($code);
        //检查用户token
        $game_id = @$_GET['game_id']+0;
        $userService = new UserService($game_id);
        $comArgs = $this->getCommonArgs($_GET);
        if(!$userService->checkUserToken($code_info['user_id'],$code_info['user_token'],$comArgs)){
            json_exit(402,"code校验失败");
        }


        //生成token
        $access_token = $service->createAccessToken($code_info['user_id'],$code_info['user_token']);
        if(!$access_token){
            json_exit(403,"token生成失败");
        }
        $com_args = $this->getCommonArgs($_GET);
        $is_trial = $this->isTrial($game_id,$code_info['user_id'],$code_info['user_token'],$com_args);

        if($is_trial === false){
            json_exit(403,"获取用户信息失败");
        }

        $result = array(
            "uid"=>$code_info['user_id'],
            "token"=>$access_token,
            "focus"=>1,
            "trial"=>$is_trial,
            "error"=>0
        );
        echo json_encode($result);
    }

    /*
     *  判断是否为游客
     *  1:是  0 否
     *  false 失败
     */
    private function isTrial($game_id,$user_id,$user_token,$com_args){
        $userService = new UserService($game_id);
        $res = $userService->getUserInfo($user_id,$user_token,$com_args);
        if($res['state'] != 1){
            return false;
        }
        $userInfo = $res['data'];
        return $userInfo['type'] == 3?1:0;
    }

    //检验token,得到用户信息
    //模仿https://api.11h5.com/login?cmd=checkToken&token=6ae018a134282b8e64eb4ea85840ad0d
    public function checkTokenAction($paras){
        if(!@$_GET['token']){//参数缺失
            echo json_encode(array());exit;
        }
        $token = trim($_GET['token']);
        $com_args = $this->getCommonArgs($_GET);
        $service = new AuthService();
        $access_token_info = $service->getTokenInfo($token);
        
        if(!$access_token_info){    //token校验失败
            json_exit(402,"token校验失败");
        }
        //检查用户token
        $game_id = @$_GET['game_id']+0;
        $userService = new UserService($game_id);
        if(!$userService->checkUserToken($access_token_info['user_id'],$access_token_info['user_token'],$com_args)){
            json_exit(402,"token校验失败");
        }


        //获取用户是否为游客
        $is_trial = $this->isTrial($game_id,$access_token_info['user_id'],$access_token_info['user_token'],$com_args);

        if($is_trial === false){
            json_exit(403,"获取用户信息失败");
        }
        $result = array(
            "uid"=>$access_token_info['user_id'],
            "focus"=>1,
            "trial"=>$is_trial,
            "error"=>0
        );
        echo json_encode($result);

    }

    /**
     *  token 得到 token_key
     *  参考:https://api.11h5.com/login?cmd=getTokenKey&token=0f804668620c013e699fec93eba3a616
     */
    public function getTokenKeyAction($paras){
        if(!@$_GET['token']){//参数缺失
            json_exit(402,"参数缺失");
        }
        $token = trim($_GET['token']);
        $service = new AuthService();
        //获取access_token
        $access_token_info = $service->getTokenInfo($token);
        if(!$access_token_info){
            json_exit(402,"token校验失败");
        }
        //检查用户token
        $game_id = @$_GET['game_id']+0;
        $userService = new UserService($game_id);
        $comArgs = $this->getCommonArgs($_GET);
        if(!$userService->checkUserToken($access_token_info['user_id'],$access_token_info['user_token'],$comArgs)){
            json_exit(402,"token校验失败");
        }
        $token_key = $access_token_info['token_key'];
        if(!$token_key){
            json_exit(402,"token校验失败");
        }
        $res = array("tokenkey"=>$token_key,"error"=>0);
        echo json_encode($res);exit;
    }

    /**
     *  token_key 得到 token
     *  参照:https://api.11h5.com/login?cmd=getTokenByTokenKey&tokenkey=k_426529263_8793bff5a89b24ee0100b08a71189693
     */
    public function getTokenByTokenKeyAction($paras){
        $tokenkey = @$_GET['tokenkey'];
        if(!$tokenkey){//参数缺失
            json_exit(10010,"参数缺失");
        }
        $service = new AuthService();
        $tokenkeyInfo = $service->getTokenKeyInfo($tokenkey);
        $token = $tokenkeyInfo['access_token'];
        if(!$token){
            json_exit(1,"token_key校验失败");
        }
        $access_token_info = $service->getTokenInfo($token);
        if(!$access_token_info){json_exit(1,"token_key校验失败");}
        //检查用户token
        $game_id = @$_GET['game_id']+0;
        $userService = new UserService($game_id);
        $comArgs = $this->getCommonArgs($_GET);
        if(!$userService->checkUserToken($access_token_info['user_id'],$access_token_info['user_token'],$comArgs)){
            json_exit(402,"token_key校验失败");
        }

        $res = array("token"=>$token,"error"=>0);
        echo json_encode($res);exit;
    }

    /**
    *   试玩 返回access_token
    *   //http://www.jet.com/api/login/getTrialUid?game_id=1001&ip=111.204.81.180&os_type=iOS&net_type=0&device=QWERASSDALKJDSKLDJAAQ112&sdk_version=v1.0&adv_channel=baidu&os_version=v3.0&carrier=10000&app_version=v1.0
    */
    public function getTrialUidAction(){
        $game_id = @$_GET['game_id']+0;
        if(!@$_GET['device']){  //参数缺失
            json_exit(10010,"参数缺失");
        }
        $userService = new UserService($game_id);
        $common_args = $this->getCommonArgs($_GET);
        $res = $userService->fplay($common_args);
        if($res['state'] != 1){
            json_exit(10020,$res['msg']);
        }
        $userInfo = $res['data'];
        $authService = new AuthService();
        $access_token = $authService->createAccessToken($userInfo['ktuid'],$userInfo['token']);
        if(!$access_token){
            json_exit(10020,"token生成失败");
        }
        $res = array("token"=>$access_token,"error"=>0,"uid"=>$userInfo['ktuid']);
        echo json_encode($res);
    }



    /**
    *   获取游戏sdk的token(开天的token)
    */
    public function getUserTokenAction(){
        if(!@$_GET['token']){  //参数缺失
            json_exit(402,"参数缺失");
        }
        $token = trim($_GET['token']);
        $authService = new AuthService();
        $access_token_info = $authService->getTokenInfo($token);
        if(!$access_token_info){  //token错误
            json_exit(402,"token错误");
        }
        //检查用户token
        $game_id = @$_GET['game_id']+0;
        $userService = new UserService($game_id);
        $comArgs = $this->getCommonArgs($_GET);
        if(!$userService->checkUserToken($access_token_info['user_id'],$access_token_info['user_token'],$comArgs)){
            json_exit(402,"token错误");
        }

        $res = array('userToken'=>$access_token_info['user_token'],"uid"=>$access_token_info['user_id'],"error"=>0);
        echo json_encode($res);
    }

    /**
     *  根据token获取用户信息
     */
    public function getUserByTokenAction(){
        $token = @trim($_GET['token']);
        if(!$token){json_exit(402,"参数缺失");}
        $authService = new AuthService();
        $access_token_info = $authService->getTokenInfo($token);
        if(!$access_token_info){  //token错误
            json_exit(402,"token错误");
        }
        //检查用户token
        $game_id = @$_GET['game_id']+0;
        $userService = new UserService($game_id);
        $comArgs = $this->getCommonArgs($_GET);
        if(!$userService->checkUserToken($access_token_info['user_id'],$access_token_info['user_token'],$comArgs)){
            json_exit(402,"token错误");
        }
        $res = $userService->getUserInfo($access_token_info['user_id'],$access_token_info['user_token'],$comArgs);
        if($res['state'] !=  1){
            json_exit(402,"token错误");
        }

        $result = array(
            "nickname"=>$res['data']['username'],
            "headimgurl"=>"",
            "uid"=>$res['data']['ktuid'],
            "error"=>0
        );
        echo json_encode($result);

    }


    //参考https://login.11h5.com/account/api.php?c=new_login&d=redirectAuth&pf=sina&gameid=123&back_url=xxx
    public function redirectAuthAction(){
        $pf = trim(@$_GET['pf']); //平台
        $pf_dict = array("sina","qq","wxqrcode","wx","mqq");
        //检查参数的合法性
        if(!$pf || !in_array($pf,$pf_dict)){
            json_exit(5,"参数错误");
        }


        switch($pf){
            case 'sina':
                $this->redirectSinaLogin();
            break;
            case 'wxqrcode':
                $this->redirectWXqrcodeLogin();
            break;
            case 'qq':
            case 'mqq':
                $this->redirectQQLogin();
            break;
            case 'wx':
                $this->redirectWXLogin();
        }


    }

    //跳转到新浪的三方登陆
    private function redirectSinaLogin(){
        $service = new SinaLoginService();
        $authUrl = $service->getAuthUrl();
        header("location:".$authUrl);
    }

    //跳转到微信二维码的三方登陆
    private function redirectWXqrcodeLogin(){
        $service = new WXqrcodeLoginService();
        $authUrl = $service->getAuthUrl();
        header("location:".$authUrl);
    }

    //跳转到QQ的三方登陆
    private function redirectQQLogin(){
        $service = new QQLoginService();
        $authUrl = $service->getAuthUrl();
        header("location:".$authUrl);
    }

    //跳转到微信公众号登陆
    private function redirectWXLogin(){
        $service = new WXLoginService();
        $authUrl = $service->getAuthUrl();
        #echo $authUrl;exit;
        header("location:".$authUrl);
    }

    //QQ三方登陆回调
    //http://graph.qq.com/demo/index.jsp?code=9A5F************************06AF&state=test
    public function qqAuthAction(){
        #var_dump($_SESSION);exit;
        if(@$_GET['error']){
            echo $_GET['error_description'];exit;
        }
        $code = trim(@$_REQUEST['code']);
        if(!$code){
            echo "参数错误";exit;
        }
        $service = new QQLoginService();
        if(!$service->checkState(@$_GET['state'])){
            echo "参数违法";exit;
        }
        $access_token = $service->getAccessToken($code);
        if(!$access_token){
            echo "get accesstoken error";exit;
        }
        $third_uid = $service->getOpenId($access_token);
        if(!$third_uid){
            echo "get open id";exit;
        }
        $userInfo = $service->getUserInfo($access_token,$third_uid);
        $face_img = @$userInfo['figureurl_qq_2']?$userInfo['figureurl_qq_2']:'';
        $extParams = array();
        if(@$_GET['state']){
            parse_str($_SESSION['qq_query_string'],$extParams);
        }
        //登陆并返回游戏
        $this->thirdLoginPlayGame($third_uid,'qq',$extParams,$face_img);
    }


    //新浪三方登陆回调
    public function sinaAuthAction(){
       # print_r($_SESSION);exit;
        $service = new SinaLoginService();
        $code = trim(@$_REQUEST['code']);
        if(!$code){
            header("location:http://jet.netkingol.com/game/?".$_SESSION['sina_query_string']);exit;
            echo "参数错误";exit;
        }

        if(!$service->checkState($_GET['state'])){
            echo "参数违法";exit;
        }
        $access_token = $service->getAccessToken($code);
        if(!$access_token){
            echo "get accesstoken error";exit;
        }
        $info = $service->getUserInfo($access_token['access_token'],$access_token['uid']);
        // { ["access_token"]=> string(32) "2.00nc_NnCeQsv6C8d20ff338dk1ESFC" ["remind_in"]=> string(9) "157679999" ["expires_in"]=> int(157679999) ["uid"]=> string(10) "2559479833" ["isRealName"]=> string(4) "true" }
        $third_uid = $access_token['uid'];
        $faceImg = @$info['avatar_hd']?$info['avatar_hd']:'';
        $extParams = array();
        if(@$_GET['state']){
           parse_str($_SESSION['sina_query_string'],$extParams);
        }
        $this->thirdLoginPlayGame($third_uid,'sina',$extParams,$faceImg);
    }

    //微信二维码三方登陆回调
    public function wxqrcodeAuthAction(){
        $service = new WXqrcodeLoginService();
        $code = trim($_REQUEST['code']);
        if(!$code){
            echo "参数错误";exit;
        }
        if(!$service->checkState($_GET['state'])){
            echo "参数违法";exit;
        }
        $access_token = $service->getAccessToken($code);
        if(!$access_token || !$access_token['openid']){
            echo "get accesstoken error";exit;
        }
       //$third_uid = $access_token['openid'];
        $third_uid = $service->getUnionID($access_token['openid'],$access_token['access_token']);
        if(!$third_uid){
            echo 'get unionid error'; exit;
        }

        $extParams = array();
        if(@$_GET['state']){
            parse_str($_SESSION['wxqrcode_query_string'],$extParams);
        }
        $this->thirdLoginPlayGame($third_uid,'wx',$extParams);
    }

    //微信三方登陆回调
    public function wxAuthAction(){
        $service = new WXLoginService();
        $code = trim($_REQUEST['code']);
        if(!$code){
            echo "参数错误";exit;
        }
        if(!$service->checkState($_GET['state'])){
            echo "参数违法";exit;
        }
        $access_token = $service->getUserAuthAccessToken($code);
        if(!$access_token || !@$access_token['openid']){
            echo "get accesstoken error";exit;
        }
        $third_uid = $service->getUnionID($access_token['openid'],$access_token['access_token']);
        if(!$third_uid){
            echo 'get unionid error';exit;
        }
        $extParams = array();
        if(@$_GET['state']){
            parse_str($_SESSION['wx_query_string'],$extParams);
        }
        $this->thirdLoginPlayGame($third_uid,'wx',$extParams,'',$access_token['openid']);
    }

    //登陆并返回游戏
    private function thirdLoginPlayGame($third_uid,$plat,$extParams,$faceImg = '',$open_id = ''){
        $userService = new UserService(@$extParams['game_id']);
        $comArgs = $this->getCommonArgs($extParams);
        $res = $userService->thridLogin($third_uid,$plat,$comArgs,$faceImg,$open_id);
        if($res['state'] != 1){
            echo "服务器错误";exit;
        }
        $userInfo = $res['data'];
        //创建code
        $authService = new AuthService();
        $code = $authService->createCode($userInfo['ktuid'],$userInfo['token']);
        //http://play.11h5.com/game/?gameid=123&code=c-0eec5a5425bf8c15a8e6948a9a6cd5d5
        if(!$code){
            echo "服务器错误";exit;
        }
        if(@$extParams['back_url']){
            $back_url = $extParams['back_url'];
        }else{
            $back_url = "http://jet.netkingol.com/game";
        }
        if(strpos('?',$back_url) === false){
            $back_url.='?code='.$code;
        }else{
            $back_url.='&code='.$code;
        }
        if(@$extParams['pf']){$back_url.="&pf=".$extParams['pf'];}
        if(@$extParams['chid']){$back_url.="&chid=".$extParams['chid'];}
        if(@$extParams['subchid']){$back_url.="&subchid=".$extParams['subchid'];}
        if(@$extParams['device']){$back_url.="&device=".$extParams['device'];}
        if(@$extParams['gameid']){$back_url.="&gameid=".$extParams['gameid'];}
        header("location:{$back_url}");

    }

    //三方登陆
    public function thirdLoginAction(){
        $oauth_uid = trim($_GET['oauth_uid']);
        $oauth_type = trim($_GET['oauth_type']);
        if(!$oauth_uid || !$oauth_type){
            json_exit(10010,"参数缺失");
        }
        $comArgs = $this->getCommonArgs($_GET);
        $gameid = @$_GET['game_id']+0;
        $UserService = new UserService($gameid);
        $res = $UserService->thridLogin($oauth_uid,$oauth_type,$comArgs);
        if($res['state'] != 1) {
            json_exit($res['state'],$res['msg']);
        }
        $userInfo = $res['data'];
        $authService = new AuthService();
        $access_token = $authService->createAccessToken($userInfo['ktuid'],$userInfo['token']);

        $return_data = array(
            "uid"=>$userInfo['ktuid'],
            "token"=>$access_token,
            "error"=>0
        );
        echo json_encode($return_data);
    }

    public function testAction(){
        $service = new AuthService();
        $res = $service->createAccessToken(110,"112");
        var_dump($res);
    }




}

