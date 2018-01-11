<?php
class LocalUserService{

    //注册本地用户
    public function registLocalUser($user_id,$user_name = '',$password = '',$mobile = '',$device = '',$face_img = '',$open_id = ''){
        if(!$user_id){return false;}
        $userModel = new UserModel();
        if($userModel->getUserByUserId($user_id)){
            $updateArr = array();
            if($user_name){$updateArr['username'] = $user_name;}
            if($password){$updateArr['password'] = md5($password);}
            if($mobile){$updateArr['mobile'] = $mobile;}
            if($device){$updateArr['device'] = $device;}
            if($face_img){$updateArr['headimgurl'] = $face_img;}
            if($open_id){$updateArr['open_id'] = $open_id;}
            if($updateArr){
                $userModel->updateById($updateArr,$user_id);
            }
        }else{
            $addArgs = array(
                'user_id'=>$user_id,
                'username'=>$user_name?$user_name:'',
                'password'=>$password?md5($password):'',
                'mobile'=>$mobile?$mobile:'',
                'device'=>$device?$device:'',
                'headimgurl'=>$face_img?$face_img:'',
                'open_id'=>$open_id?$open_id:''
            );
            $userModel->add($addArgs);
        }
    }

    //获取用户信息
    public function getUserInfoByUserId($user_id){
        $userModel = new UserModel();
        return $userModel->getUserByUserId($user_id);
    }

    //增加用户积分
    public function incrUserPoint($user_id,$point){
        $userModel = new UserModel();
        $sql = "update user set `lv` = `lv`+{$point} where user_id={$user_id}";
        return $userModel->update($sql);
    }

    //减积分
    public function reduceUserPoint($user_id,$point){
        $userModel = new UserModel();
        $sql = "update user set `lv` = `lv`-{$point} where user_id={$user_id} and `lv`>={$point}";
        return $userModel->update($sql);
    }

    public function updateUser($updateArr ,$user_id){
        $userModel = new UserModel();
        return $userModel->updateById($updateArr,$user_id);
    }
}