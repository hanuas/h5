<?php
class User2Service extends commonService{

    const LOGIN_URL = "http://test.gsdkapi.ktsdk.com/web_H5/user/login";  //登陆接口地址
    const FPLAY_URL = "http://test.gsdkapi.ktsdk.com/web_H5/auto/userreg";   //快速登陆接口

    //"regphone","getpwdphone","bindphone"
    const BINDPHONE_SEND_CODE_URL = "http://test.gsdkapi.ktsdk.com/web_H5/sendsms/verifycodee";        //绑定手机发送验证码
    const GETPWDPHONE_SEND_CODE_URL = "http://test.gsdkapi.ktsdk.com/web_H5/sendsms/verifycode"; //找回密码发送验证码
    const REGPHONE_SEND_CODE_URL = "http://test.gsdkapi.ktsdk.com/web_H5/sendsms/verifycode";         //手机注册发送验证码
    const REGCODE_SEND_CODE_URL = "http://test.gsdkapi.ktsdk.com/web_H5/sendsms/verifycode";         //手机短信登陆发送验证码


    const THIRD_LOGIN_URL = "http://test.gsdkapi.ktsdk.com/web_H5/oauth/login"; //三方登陆接口地址
    const REG_USERNAME_URL = "http://test.gsdkapi.ktsdk.com/web_H5/user/namereg"; //用户名注册
    const REG_MOBILE_URL = "http://test.gsdkapi.ktsdk.com/web_H5/user/mobilereg"; //手机号注册

    const FIND_PWD_CODE_CHECK_URL = "http://test.gsdkapi.ktsdk.com/web_H5/user/chkgetpwdcode"; //找回密码验证码验证
    const FIND_PWD_URL = "http://test.gsdkapi.ktsdk.com/web_H5/user/resetpwd"; //找回密码,执行修改操作
    const UPDATE_PWD_URL = "http://test.gsdkapi.ktsdk.com/web_H5/usercore/editpwd"; //修改密码

    const FPLAY_BIND_MOBILE_URL = "http://test.gsdkapi.ktsdk.com/web_H5/auto/binduser";  //游客绑定手机
        
    const BIND_MOBILE_URL = "http://test.gsdkapi.ktsdk.com/web_H5/usercore/authchkcode";    //普通用户绑定手机
    const GET_USER_INFO_URL = "http://test.gsdkapi.ktsdk.com/web_H5/usercore/getinfo"; //获取用户信息

    const MOBILE_CODE_LOGIN_URL = "http://test.gsdkapi.ktsdk.com/web_H5/user/regcode"; //手机短信验证码登陆

    public function __construct($app_id){
        parent::__construct($app_id);
    }


    public function test(){
        echo "test.".$this->APP_ID;
    }


    /**
    *   登陆接口
    */
    public function login($args){
        if(!@$args['username'] || !@$args['password']){
            return array("state"=>2,"msg"=>"用户名密码为空");
        }
        
        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合为要发送的参数
        $sendArgs['username'] = $args['username'];
        $sendArgs['password'] = $args['password'];
        
        $result = $this->send($sendArgs,[],self::LOGIN_URL);
        
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

        $userInfo = array(
            "ktuid"=>$ktuid,
            "token"=>$token,
            "uphone"=>$uphone
        );
        return array("state"=>1,"data"=>$userInfo);


    }
    
    //手机验证码登陆
    public function mobileCodeLogin($args){
        if(!@$args['mobile'] || !@$args['actcode'] || !@$args['vstr']){
            return array("state"=>2,"msg"=>"缺少参数");
        }
        
        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合为要发送的参数
        $sendArgs['mobile'] = $args['mobile'];
        $sendArgs['actcode'] = $args['actcode'];
        $sendArgs['vstr'] = $args['vstr'];
        
        $result = $this->send($sendArgs,[],self::MOBILE_CODE_LOGIN_URL);
        
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


    /**
    *   游客登陆
    *   device 不能为空    
    */

    public function fplay($args){
        if(!@$args['device']){
            return array("state"=>2,"msg"=>"缺少设备参数");
        }
        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        $result = $this->send($sendArgs,[],self::FPLAY_URL);
        if($result === false){
            return array("state"=>3,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>4,"msg"=>$result['msg_content']);
        }

        $data = json_decode($result['data'],true);

        $username = $data['username'];
        $ktuid = $data['ktuid'];
        $token = $data['token'];
        $uphone = $data['uphone'];

        return array("state"=>1,"data"=>$data);
    }



    /**
    *   
    *   发送验证码
    *
    */
    public function sendCode($args){
        if(!$args['mobile'] || !$args['verifytype']){
             return array("state"=>2,"msg"=>"缺少参数"); 
        }
        $verify_types = array("bindphone","regphone","getpwdphone","regcode");

        if(!in_array($args['verifytype'],$verify_types)){
             return array("state"=>3,"msg"=>"验证码类型错误");
        }

        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合为要发送的参数
        $sendArgs['mobile'] = $args['mobile'];
        $sendArgs['verifytype'] = $args['verifytype'];
        
        $api_url = constant('self::' . strtoupper($args['verifytype'])."_SEND_CODE_URL");
        $result = $this->send($sendArgs,[],$api_url);
        
        if($result === false){
             return array("state"=>4,"msg"=>"通信失败");
        }
        $result = json_decode($result,true);
        if($result['msg_code'] !== "20000"){
            return array("state"=>5,"msg"=>$result["msg_content"]);
        }

        $data = json_decode($result['data'],true);
        
        $vstr = $data['vstr'];
        return array("state"=>1,"data"=>$data);

    }

    /**
    *   三方登陆
    */

    public function thridLogin($args){
        if(!@$args['oauth_uid'] || !@$args['oauth_type']){
            return array("state"=>2,"msg"=>"参数缺少");
        }

        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合为要发送的参数
        $sendArgs['oauth_uid'] = $args['oauth_uid'];
        $sendArgs['oauth_type'] = $args['oauth_type'];

        $result = $this->send($sendArgs,[],self::THIRD_LOGIN_URL);
        if($result === false){
            return array("state"=>3,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>4,"msg"=>$result["msg_content"]);
        }


        $data = json_decode($result['data'],true);

        $ktuid = $data['ktuid'];
        $token = $data['token'];
        $uphone = $data['uphone'];
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
    public function checkFindPwdCode($args){
        if(!@$args['vstr'] || !@$args['actcode'] || !@$args['mobile'] ){
            return array("state"=>2,"msg"=>"缺少参数");
        }

        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合为要发送的参数
        $sendArgs['mobile'] = $args['mobile'];
        $sendArgs['vstr'] = $args['vstr'];
        $sendArgs['actcode'] = $args['actcode'];

        $result = $this->send($sendArgs,[],self::FIND_PWD_CODE_CHECK_URL);
        if($result === false){
            return array("state"=>3,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
            return array("state"=>4,"msg"=>$result['msg_content']);
        }

        $data = json_decode($result['data'],true);

        $vstr = $data['vstr'];
        return array("state"=>1,"data"=>$data);
    }

    /**
    *   用户找回密码-设置新密码
    */
    public function findPwd($args){
        if(!@$args['vstr'] || !@$args['password']){
             return array("state"=>2,"msg"=>"缺少参数");
        }

        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合为要发送的参数
        $sendArgs['vstr'] = $args['vstr'];
        $sendArgs['password'] = $args['password'];

        $result = $this->send($sendArgs,[],self::FIND_PWD_URL);
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
    public function fplayBindMobile($args){
        if(!@$args['mobile'] || !@$args['actcode'] || !@$args['ktuid'] || !@$args['token'] || !@$args['vstr'] || !@$args['password']){
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
        $sendArgs['password'] = $args['password'];

        $result = $this->send($sendArgs,[],self::FPLAY_BIND_MOBILE_URL);
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
    public function getUserInfo($args){
        if(!@$args['ktuid'] || !@$args['token']){
            return array("state"=>2,"msg"=>"参数缺少");
        }

        //获取公共参数
        $sendArgs = $this->getCommonArgs($args);
        
        //组合为要发送的参数
        $sendArgs['ktuid'] = $args['ktuid'];
        $sendArgs['token'] = $args['token'];

        $result = $this->send($sendArgs,[],self::GET_USER_INFO_URL);
        if($result === false){
             return array("state"=>3,"msg"=>"通信失败");
        }

        $result = json_decode($result,true);

        if($result['msg_code'] !== "20000"){
             return array("state"=>4,"msg"=>$result['msg_content']);
        }

        $data = json_decode($result['data'],true);

        $username = $data['username'];
        $ktuid = $data['ktuid'];
        $uphone = $data['uphone'];
        return array("state"=>1,"data"=>$data);
    }







 
}