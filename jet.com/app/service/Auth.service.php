<?php
class AuthService{
    const ACCESS_TOKEN_EXPIRE_TIME = 1000000;   //access_token 过期时间
    const AUTHORIZATION_CODE_EXPIRE_TIME = 600; //authorization_code 过期时间
    const useRedis = true;

    private static function getRedis(){
        return cache::redis("auth");
    }

    //废弃
    public function getTokenInfo_delete($token){

        $model = new OauthAccessTokensModel();
        $token_info = $model->getAccessToken($token);
        return $token_info;
    }

    //根据token获取token信息
    public function getTokenInfo($token){
        $redis = self::getRedis();
        $redis_key = "access_token:".$token;
        $token_info = $redis->get($redis_key);
        if($token_info){
            return json_decode($token_info,true);
        }else{
            return false;
        }
    }

    //create code  得到code
    public function createCode_delete($uid){
        $code = uniqid();
        $expire_time = date("Y-m-d H:i:s",time()+self::AUTHORIZATION_CODE_EXPIRE_TIME);

        $add_args = array(
            "authorization_code"=>$code,
            "user_id"=>$uid,
            "expires"=>$expire_time
        );
        //oauth_authorization_codes
        $model = new OauthAuthorizationCodesModel();
        if($model->add($add_args) === false){
            return false;
        }else{
            return $code;
        }
    }

    //创建code并存redis
    public function createCode($user_id,$user_token){
        $code = $this->buildAuthorizationCode($user_id);
        $redis_key = "code:".$code;
        $code_info = array(
            "authorization_code"=>$code,
            "user_id"=>$user_id,
            "user_token"=>$user_token,
        );
        $redis = self::getRedis();
        $res = $redis->setex($redis_key, self::AUTHORIZATION_CODE_EXPIRE_TIME, json_encode($code_info));
        if(!$res){return false;}
        return $code;
    }

    //删除code
    public function deleteCode_delete($code){
        $model = new OauthAuthorizationCodesModel();
        return $model->deleteCode($code);
    }

    //删除code
    public function deleteCode($code){
        $redis = self::getRedis();
        $redis_key = "code:".$code;
        return $redis->del($redis_key);
    }

    //create token  得到access_token
    public function createAccessToken_delete($uid){
        $access_token = uniqid(); //test
        $expire_time = date("Y-m-d H:i:s",time()+self::ACCESS_TOKEN_EXPIRE_TIME);

        $add_args = array(
            "access_token"=>$access_token,
            "user_id"=>$uid,
            "expires"=>$expire_time
        );
        $model = new OauthAccessTokensModel();
        if($model->add($add_args) === false){
            return false;
        }else{
            return $access_token;
        }
    }

    /**
     * @param $user_id
     * @param $user_token
     * @return bool|string
     * @desc 生成access_token
     * 每次生成access_token 都会生成一个对应的token_key
     * token和token_key互相对应
     */
    public function createAccessToken($user_id,$user_token){
        $access_token = $this->buildAccessToken($user_id);

        //得到token_key
        $token_key = $this->createTokenKey($user_id,$access_token);
        if(!$token_key){
            return false;
        }
        $redis_key = "access_token:".$access_token;
        $access_token_info = array(
            "access_token"=>$access_token,
            "user_id"=>$user_id,
            "user_token"=>$user_token,
            "token_key"=>$token_key
        );
        $redis = self::getRedis();
        $res = $redis->setex($redis_key, self::ACCESS_TOKEN_EXPIRE_TIME, json_encode($access_token_info));
        if(!$res){
            return false;
        }
        return $access_token;
    }

    //创建并存储token_key    token_key===》access_token
    private function createTokenKey($user_id,$access_token){
        $token_key = $this->buildTokenKey($user_id);
        $redis_key = "token_key:".$token_key;
        $token_key_info = array(
            "access_token"=>$access_token
        );
        $redis = self::getRedis();
        $res = $redis->setex($redis_key, self::ACCESS_TOKEN_EXPIRE_TIME, json_encode($token_key_info));
        if(!$res){
            return false;
        }
        return $token_key;
    }

    /**
     * @param $token
     * @return $token_key
     * @desc 通过token获得token_key
     */
    public function getTokenKeyByToken($token){
        $token_info = $this->getTokenInfo($token);
        if(!$token_info){return false;}
        $token_key = $token_info['token_key'];
        return $token_key;
    }

    /**
     * @param $token_key
     * @return tokenKeyInfo
     */
    public function getTokenKeyInfo($token_key){
        $redis_key = "token_key:".$token_key;
        $redis = self::getRedis();
        $tokenKeyInfo = $redis->get($redis_key);
        if(!$tokenKeyInfo){return false;}
        return json_decode($tokenKeyInfo,true);
    }



    //创建唯一的access_token
    public function buildAccessToken($user_id){
        return md5("access_token:".$user_id.getMillisecond().mt_rand(100000,999999));
    }

    //创建唯一的authorization_code
    public function buildAuthorizationCode($user_id){
        return md5("authorization_code:".$user_id.getMillisecond().mt_rand(100000,999999));
    }
    //创建唯一的token_key
    public function buildTokenKey($user_id){
        return md5("token_key:".$user_id.getMillisecond().mt_rand(100000,999999));
    }


    //
    public function getCodeInfo_delete($code){
        $model = new OauthAuthorizationCodesModel();
        $code_info = $model->getCodeInfo($code);
        return $code_info;
    }

    //根据code获取code信息
    public function getCodeInfo($code){
        $redis = self::getRedis();
        $redis_key = "code:".$code;
        $code_info = $redis->get($redis_key);
        if($code_info){
            return json_decode($code_info,true);
        }else{
            return false;
        }
    }

}