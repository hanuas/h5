<?php
class UserService extends commonService{

    const LOGIN_URL = "https://testgsdkapi.ktsdk.com/web_H5/user/login";  //登陆接口地址
    const FPLAY_URL = "https://testgsdkapi.ktsdk.com/web_H5/auto/userreg";   //快速登陆接口

    //"regphone","getpwdphone","bindphone"
    const BINDPHONE_SEND_CODE_URL = "https://testgsdkapi.ktsdk.com/web_H5/sendsms/verifycode";        //绑定手机发送验证码
    const GETPWDPHONE_SEND_CODE_URL = "https://testgsdkapi.ktsdk.com/web_H5/sendsms/verifycode"; //找回密码发送验证码
    const REGPHONE_SEND_CODE_URL = "https://testgsdkapi.ktsdk.com/web_H5/sendsms/verifycode";         //手机注册发送验证码
    const REGCODE_SEND_CODE_URL = "https://testgsdkapi.ktsdk.com/web_H5/sendsms/verifycode";         //手机短信登陆发送验证码


    const THIRD_LOGIN_URL = "https://testgsdkapi.ktsdk.com/web_H5/oauth/login"; //三方登陆接口地址
    const REG_USERNAME_URL = "https://testgsdkapi.ktsdk.com/web_H5/user/namereg"; //用户名注册
    const REG_MOBILE_URL = "https://testgsdkapi.ktsdk.com/web_H5/user/mobilereg"; //手机号注册

    const FIND_PWD_CODE_CHECK_URL = "https://testgsdkapi.ktsdk.com/web_H5/user/chkgetpwdcode"; //找回密码验证码验证
    const FIND_PWD_URL = "https://testgsdkapi.ktsdk.com/web_H5/user/resetpwd"; //找回密码,执行修改操作
    const UPDATE_PWD_URL = "https://testgsdkapi.ktsdk.com/web_H5/usercore/editpwd"; //修改密码

    const FPLAY_BIND_MOBILE_URL = "https://testgsdkapi.ktsdk.com/web_H5/auto/binduser";  //游客绑定手机
        
    const BIND_MOBILE_URL = "https://testgsdkapi.ktsdk.com/web_H5/usercore/authchkcode";    //普通用户绑定手机
    const GET_USER_INFO_URL = "https://testgsdkapi.ktsdk.com/web_H5/usercore/getinfo"; //获取用户信息
    const CHECK_USER_TOKEN_URL = "https://testgsdkapi.ktsdk.com/web_H5/user/checktoken";//检查用户token
    const GET_USER_REALVERIFY_URL = "https://testgsdkapi.ktsdk.com/web_H5/usercore/authname";//获取用户实名认证信息
    const SET_URL_REALVERIFY_URL = "https://testgsdkapi.ktsdk.com/web_H5/usercore/setauthname"; //设置用户实名认证信息

    const MOBILE_CODE_LOGIN_URL = "https://testgsdkapi.ktsdk.com/web_H5/user/regcode"; //手机短信验证码登陆

    const MOBILE_CODE_VSTR_REDIS_KEY = "M_Vstr:"; //例:M_Vstr:13406897455_bindphone 表示的是 13406897455绑定手机的vstr
    const MOBILE_CODE_VSTR_REDIS_KEY_EXPIRE_TIME = 600; //手机验证码vstr的redis过期时间

    const LOGIN_ATTEMPTS_MAX_NUM = 11;  //登陆尝试错误最大次数
    const LOGIN_ATTEMPTS_NOTNEED_VCODE_NUM = 10; //登陆不需要验证码的次数
    const LOGIN_ATTEMPTS_NUM_REDIS_KEY = "Elogin_num:"; //登陆尝试错误次数的redis key 例:"Elogin_num:18678469563"
    const LOGIN_ATTEMPTS_NUM_REDIS_KEY_EXPIRE_TIME = 1800; //登陆尝试错误次数的redis key的过期时间



    public function __construct($app_id = 0){
        if(!$app_id){
            $app_id = self::COMMON_APP_ID;  //如果不传app_id 就用通用的app_id
        }
        parent::__construct($app_id);
    }

    //获取用户登陆尝试次数
    public function getLoginAttemptsNum($username){
        $redis = self::getRedis();
        $redis_key = self::LOGIN_ATTEMPTS_NUM_REDIS_KEY.$username;
        return $redis->get($redis_key);
    }

    //检查用户登陆是否需要图片验证码
    public function checkIsNeedLoginVCode($username){
        $userLoginNum = $this->getLoginAttemptsNum($username);
        if($userLoginNum>=self::LOGIN_ATTEMPTS_NOTNEED_VCODE_NUM){
            return true;
        }else{
            return false;
        }
    }

    //检查用户是否尝试次数过多
    public function checkIsLoginAttemptsLimit($username){
        $userLoginNum = $this->getLoginAttemptsNum($username);
        if($userLoginNum>=self::LOGIN_ATTEMPTS_MAX_NUM){
            return true;
        }else{
            return false;
        }
    }

    //用户登陆尝试错误次数加1
    public function userLoginAttemptsNumIncr($username){
        $redis = self::getRedis();
        $redis_key = self::LOGIN_ATTEMPTS_NUM_REDIS_KEY.$username;
        $num = $redis->get($redis_key);
        if(!$num){$num = 1;}else{$num++;}
        $redis->setex($redis_key,$num,self::LOGIN_ATTEMPTS_NUM_REDIS_KEY_EXPIRE_TIME);
    }



    public function test(){
        echo "test.".$this->APP_ID;
    }

    private static function getRedis(){
        return cache::redis("user");
    }


    /**
    *   登陆接口
    */
    public function login($username,$password,$commonArgs){

        //获取公共参数
        $sendArgs = $commonArgs;
        
        //组合为要发送的参数
        $sendArgs['username'] = $username;
        $sendArgs['password'] = $password;
        
        $result = $this->send($sendArgs,[],self::LOGIN_URL);
        if($result === false){
            return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"通信失败");
        }
        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>$result['msg_code'],"msg"=>$result['msg_content']);
        }

        $data = json_decode($result['data'],true);
        $localUserService = new LocalUserService();
        $localUserService->registLocalUser($data['ktuid'],$data['username'],$password,$data['uphone']);
        return array("state"=>1,"data"=>$data);

    }

    
    //手机验证码登陆
    public function mobileCodeLogin($mobile,$code,$comArgs){

        $redis = self::getRedis();
        $redis_key = self::MOBILE_CODE_VSTR_REDIS_KEY."{$mobile}_regcode";
        $vstr = $redis->get($redis_key);

        if(!$vstr){
            return array("state"=>ErrorCodeService::MOBILE_CODE_TIMEOUT,"msg"=>"手机验证码过期");
        }
        $sendArgs = $comArgs;
        
        //组合为要发送的参数
        $sendArgs['mobile'] = $mobile;
        $sendArgs['actcode'] = $code;
        $sendArgs['vstr'] = $vstr;
        $result = $this->send($sendArgs,[],self::MOBILE_CODE_LOGIN_URL);
        #var_dump($result);
        if($result === false){
            return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"通信失败");
        }
        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>$result['msg_code'],"msg"=>$result['msg_content']);
        }

        $data = json_decode($result['data'],true);
        $localUserService = new LocalUserService();
        $localUserService->registLocalUser($data['ktuid'],'','',$data['uphone']);
        return array("state"=>1,"data"=>$data);
    }


    /**
    *   游客登陆
    *   
    */

    public function fplay($comArgs){
        $result = $this->send($comArgs,[],self::FPLAY_URL);
        if($result === false){
            return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"通信失败");
        }
        $result = json_decode($result,true);
        if($result['msg_code'] !== "20000"){
            return array("state"=>$result['msg_code'],"msg"=>$result['msg_content']);
        }
        $data = json_decode($result['data'],true);
        return array("state"=>1,"data"=>$data);
    }



    /**
    *   
    *   发送验证码
    *
    */
    public function sendCode($mobile,$verifytype,$comArgs){
        $verify_types = array("bindphone","regphone","getpwdphone","regcode");

        //组合为要发送的参数
        $sendArgs = $comArgs;
        $sendArgs['mobile'] = $mobile;
        $sendArgs['verifytype'] = $verifytype;
        
        $api_url = constant('self::' . strtoupper($sendArgs['verifytype'])."_SEND_CODE_URL");
        $result = $this->send($sendArgs,[],$api_url);
        #var_dump($result);exit;
        if($result === false){
             return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"通信失败");
        }
        $result = json_decode($result,true);
        if($result['msg_code'] !== "20000"){
            return array("state"=>$result['msg_code'],"msg"=>$result["msg_content"]);
        }
        $data = json_decode($result['data'],true);
        
        $vstr = $data['vstr'];
        //存redis
        $redis = self::getRedis();
        $redis_key = self::MOBILE_CODE_VSTR_REDIS_KEY."{$mobile}_{$verifytype}";
        $res = $redis->setex($redis_key, self::MOBILE_CODE_VSTR_REDIS_KEY_EXPIRE_TIME, $vstr);
        if(!$res){
            return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"redis存储失败");
        }
        return array("state"=>1,"data"=>$data);

    }

    /**
    *   三方登陆
    */

    public function thridLogin($oauth_uid,$oauth_type,$com_args,$face_img = '',$open_id = ''){
        //获取公共参数
        $sendArgs = $com_args;
        //组合为要发送的参数
        $sendArgs['oauth_uid'] = $oauth_uid;
        $sendArgs['oauth_type'] = $oauth_type;
        $result = $this->send($sendArgs,[],self::THIRD_LOGIN_URL);
        if($result === false){
            return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"通信失败");
        }
        #var_dump($result);exit;
        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>$result['msg_code'],"msg"=>$result["msg_content"]);
        }


        $data = json_decode($result['data'],true);
        $ktuid = $data['ktuid'];
        $token = $data['token'];
        $uphone = $data['uphone'];
        $localUserService = new LocalUserService();
        $localUserService->registLocalUser($data['ktuid'],$data['username'],'',$data['uphone'],'',$face_img,$open_id);
        return array("state"=>1,"data"=>$data);
    }


    /**
    *   用户名注册
    *
    */
    public function registByUserName($args){
        if(!@$args['username'] || !@$args['password']){
            return array("state"=>2,"msg"=>"用户名密码为空");
        }

        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合为要发送的参数
        $sendArgs['username'] = $args['username'];
        $sendArgs['password'] = $args['password'];

        $result = $this->send($sendArgs,[],self::REG_USERNAME_URL);
        if($result === false){
            return array("state"=>3,"msg"=>"通信失败");
        }
        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>2,"msg"=>$result['msg_content']);
        }

        $data = json_decode($result['data'],true);

        $ktuid = $data['ktuid'];
        $token = $data['token'];
        $uphone = $data['uphone'];
        $userInfo = array(
            'ktuid'=>$ktuid,
            'token'=>$token,
            'uphone'=>$uphone
        );
        return array("state"=>1,"data"=>$userInfo);
    }

    /**
    *
    *   手机号注册
    */
    public function registByMobile($args){
        if(!@$args['mobile'] || !@$args['password'] || !@$args['actcode'] || !@$args['vstr']){
           return array("state"=>2,"msg"=>"参数缺少");
        }

        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合为要发送的参数
        $sendArgs['mobile'] = $args['mobile'];
        $sendArgs['password'] = $args['password'];
        $sendArgs['actcode'] = $args['actcode'];
        $sendArgs['vstr'] = $args['vstr'];

        $result = $this->send($sendArgs,[],self::REG_MOBILE_URL);
        if($result === false){
           return array("state"=>3,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>4,"msg"=>$result['msg_content']);
        }

        $data = json_decode($result['data'],true);

        $ktuid = $data['ktuid'];
        $token = $data['token'];
        $uphone = $data['uphone'];
        return array("state"=>1,"data"=>$data);
    }


    /**
    *   用户找回密码-检测验证码
    */
    public function checkFindPwdCode($mobile,$actCode,$comArgs){
        $redis = self::getRedis();
        $redis_key = self::MOBILE_CODE_VSTR_REDIS_KEY."{$mobile}_getpwdphone";
        $vstr = $redis->get($redis_key);
        if(!$vstr){
            return array("state"=>ErrorCodeService::MOBILE_CODE_TIMEOUT,"msg"=>"手机验证码过期");
        }

        $sendArgs = $comArgs;
        
        //组合为要发送的参数
        $sendArgs['mobile'] = $mobile;
        $sendArgs['vstr'] = $vstr;
        $sendArgs['actcode'] = $actCode;

        $result = $this->send($sendArgs,[],self::FIND_PWD_CODE_CHECK_URL);
        #print_r($result);exit;
        if($result === false){
            return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>$result['msg_code'],"msg"=>$result['msg_content']);
        }

        $data = json_decode($result['data'],true);
        return array("state"=>1,"data"=>$data);
    }

    /**
    *   用户找回密码-设置新密码
    */
    public function findPwd($password,$vstr,$comArgs){
        $sendArgs = $comArgs;
        //组合为要发送的参数
        $sendArgs['vstr'] = $vstr;
        $sendArgs['password'] = $password;

        $result = $this->send($sendArgs,[],self::FIND_PWD_URL);
        if($result === false){
             return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);
        if($result['msg_code'] !== "20000"){
             return array("state"=>$result['msg_code'],"msg"=>$result['msg_content']);
        }

        return array("state"=>1);


    }

    /**
    *   修改密码
    */
    public function updatePwd($args){
        if(!@$args['ktuid'] || !@$args['token'] || !@$args['oldpasswd'] || !@$args['newpasswd']){
            return array("state"=>2,"msg"=>"参数缺少");
        }

        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合为要发送的参数
        $sendArgs['ktuid'] = $args['ktuid'];
        $sendArgs['token'] = $args['token'];
        $sendArgs['oldpasswd'] = $args['oldpasswd'];
        $sendArgs['newpasswd'] = $args['newpasswd'];

        $result = $this->send($sendArgs,[],self::UPDATE_PWD_URL);
        if($result === false){
            return array("state"=>3,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
             return array("state"=>4,"msg"=>$result['msg_content']);
        }

        return array("state"=>1);

    }

    /**
    *   游客登陆-绑定手机号
    */
    public function fplayBindMobile($uid,$userToken,$mobile,$actCode,$passwd,$comArgs){
        $redis = self::getRedis();
        $redis_key = self::MOBILE_CODE_VSTR_REDIS_KEY."{$mobile}_bindphone";
        $vstr = $redis->get($redis_key);
        if(!$vstr){
            return array("state"=>ErrorCodeService::MOBILE_CODE_TIMEOUT,"msg"=>"手机验证码过期");
        }

        $sendArgs = $comArgs;
        //组合为要发送的参数
        $sendArgs['mobile'] = $mobile;
        $sendArgs['actcode'] = $actCode;
        $sendArgs['ktuid'] = $uid;
        $sendArgs['token'] = $userToken;
        $sendArgs['vstr'] = $vstr;
        $sendArgs['password'] = $passwd;

        $result = $this->send($sendArgs,[],self::FPLAY_BIND_MOBILE_URL);
        if($result === false){
            return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>$result['msg_code'],"msg"=>$result['msg_content']);
        }

        return array("state"=>1);

    }

    /**
    *   普通用户绑定手机号(非游客)
    *
    */
    public function bindMobile($args){
        if(!@$args['mobile'] || !@$args['actcode'] || !@$args['ktuid'] || !@$args['token'] || !@$args['vstr']){
            return array("state"=>2,"msg"=>"参数缺少");
        }

        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合为要发送的参数
        $sendArgs['mobile'] = $args['mobile'];
        $sendArgs['actcode'] = $args['actcode'];
        $sendArgs['ktuid'] = $args['ktuid'];
        $sendArgs['token'] = $args['token'];
        $sendArgs['vstr'] = $args['vstr'];

        $result = $this->send($sendArgs,[],self::BIND_MOBILE_URL);
        if($result === false){
            return array("state"=>3,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>4,"msg"=>$result['msg_content']);
        }

        $data = json_decode($result['data'],true);

        $username = $data['username'];
        #$uphone = $data['uphone'];
        return array("state"=>1,"data"=>$data);
    }


    /**
    *   获取用户信息
    */
    public function getUserInfo($user_id,$user_token,$common_args){
        $sendArgs = $common_args;
        
        //组合为要发送的参数
        $sendArgs['ktuid'] = $user_id;
        $sendArgs['token'] = $user_token;

        $result = $this->send($sendArgs,[],self::GET_USER_INFO_URL);
        if($result === false){
             return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
             return array("state"=>$result['msg_code'],"msg"=>$result['msg_content']);
        }

        $data = json_decode($result['data'],true);
        return array("state"=>1,"data"=>$data);
    }

    /**
     * 检查用户token
     * 暂用用户信息的接口
     */
    public function checkUserToken($user_id,$user_token,$common_args){
        $sendArgs = $common_args;

        //组合为要发送的参数
        $sendArgs['ktuid'] = $user_id;
        $sendArgs['token'] = $user_token;

        $result = $this->send($sendArgs,[],self::CHECK_USER_TOKEN_URL);
        if($result === false){
            return false;
        }

        $result = json_decode($result,true);
        if($result['msg_code'] !== "20000"){
            return false;
        }

        return true;
    }


    /**
     *   获取实名认证信息
     */
    public function getRealVerifyInfo($user_id,$user_token,$common_args){
        $sendArgs = $common_args;

        //组合为要发送的参数
        $sendArgs['ktuid'] = $user_id;
        $sendArgs['token'] = $user_token;

        $result = $this->send($sendArgs,[],self::GET_USER_REALVERIFY_URL);
        if($result === false){
            return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>$result['msg_code'],"msg"=>$result['msg_content']);
        }

        $data = json_decode($result['data'],true);
        return array("state"=>1,"data"=>$data);
    }

    /**
     * 设置实名认证信息
     */
    public function setRealVerifyInfo($user_id,$realname,$idcard,$common_args){
        $sendArgs = $common_args;

        //组合为要发送的参数
        $sendArgs['ktuid'] = $user_id;
        $sendArgs['realname'] = $realname;
        $sendArgs['idcard'] = $idcard;


        $result = $this->send($sendArgs,[],self::SET_URL_REALVERIFY_URL);
        if($result === false){
            return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"通信失败");
        }
        $result = json_decode($result,true);
        if(!$result){
            return array("state"=>ErrorCodeService::NET_ERROR,"msg"=>"通信失败");
        }
        if($result['msg_code'] !== "20000"){
            return array("state"=>$result['msg_code'],"msg"=>$result['msg_content']);
        }
        $data = json_decode(@$result['data'],true);
        return array("state"=>1,"data"=>$data);
    }








 
}