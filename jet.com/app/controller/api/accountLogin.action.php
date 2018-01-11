<?php  
// Dispatch::load("Service_User");
// Dispatch::loadController("","icommon");

class accountLoginController extends icommonController{
     
     //登陆
    public function authAction(){

        $username = trim($_POST['uid']);
        $password = trim($_POST['password']);
        $verify_code = trim(@$_POST['verify_code']);
        $verify_session = trim(@$_POST['verify_session']);
        $common_args = $this->getCommonArgs($_GET); //获取公共参数

        if(!$username || !$password){
            json_exit(101,"参数错误");
        }

        $game_id = @$_POST['game_id']+0;
        if(!$game_id){$game_id = @$_GET['game_id']+0;}
        $userService = new UserService($game_id);

        $needVCode = $userService->checkIsNeedLoginVCode($username);    //是否需要验证图片验证码
        if($needVCode){
            if(!$verify_code || !$verify_session){
                json_flag_exit(103,5,"请输入验证码");
            }
            $session = getSessionBySessionId($verify_session);
            if(@strtolower($session['validateCode']) != strtolower($verify_code)){
                json_flag_exit(103,5,"图片验证码错误");
            }
        }

        //检查登陆是否尝试次数过多
        $is_limit = $userService->checkIsLoginAttemptsLimit($username);
        if($is_limit){
            json_flag_exit(103,1,"尝试次数过多！");
        }

        //Array ( [state] => 1 [data] => Array ( [ktuid] => 115980832073 [token] => 79A34BF7E870DA32FE786A47C48B238E [uemail] => [uphone] => [is_anti] => 1 ) )
        $res = $userService->login($username,$password,$common_args);
        if($res['state'] != 1){
            if($res['state'] == ErrorCodeService::USER_NOT_EXIST){
                json_exit(201,"用户名不存在");
            }else if($res['state'] == ErrorCodeService::USER_PASSWORD_ERROR){
                //增加错误登陆次数
                $userService->userLoginAttemptsNumIncr($username);
                if($needVCode){
                    json_flag_exit(103,1,"尝试次数过多！");
                }else{
                    json_exit(205,"用户名或密码错误");
                }
            }else{
                json_exit(102,"系统错误");
            }
        }
        //登录成功
        $userInfo = $res['data'];
        $authService = new AuthService();
        $access_token = $authService->createAccessToken($userInfo['ktuid'],$userInfo['token']);
        if(!$access_token){
            json_exit(102,"创建token失败");
        }
        $res = array("token"=>$access_token,"error"=>0);
        echo json_encode($res);


    }
}