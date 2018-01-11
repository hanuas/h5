<?php
class UserLoginLogModel
{

	const TABLE = "user_login_log"; 
	const TABLE_FIRST = "user_first_login"; 
	static  function getPdo(){ 
        $user_pdo = DBTool::getPdoByString("user", Config::get("user/db")  );
        return $user_pdo;
	}
	
	
	/**
     * @desc 添加用户(user_first_login表)
     * @param array $arrFields
     * ['login_time'] 时间
     * ['product_id'] 游戏id
     * ['server_id'] 分服id
     * ['user_id'] 用户id
     * ['login_ip'] 注册ip
     * ['union_id'] 媒体（联盟）id
     * ['union_id'] 广告id
     * @return int 新添加的用户ID
     * */
    public function addUserFirstLogin($arrFields) {
        $usersMod = new DBTable(self::TABLE_FIRST, self::getPdo() );
        return $usersMod->MyInsert($arrFields);
    }

    public function getFirstLogin($user_id, $product_id, $server_id) {
        $usersMod = new DBTable(self::TABLE_FIRST, self::getPdo() );
        return $usersMod->MyGetRow('*', 'user_id = '.$user_id.' and product_id = '.$product_id.' and server_id = '.$server_id);
    }
        
	//查询用户非限量激活期间是否登录过
	public function getUserActivated($userid,$product_id,$platform=0){
		$usersMod = new DBTable(self::TABLE_FIRST, self::getPdo() );
        return $usersMod->MyGetRow('*', '`user_id`="'.$userid.'" and `product_id`="'.$product_id.'" and `platform`="'.$platform.'" limit 1');
		  
	}




    /**
     * @desc 添加用户(user_login_log表)
     * @param array $arrFields
     * ['login_time'] 时间
     * ['product_id'] 游戏id
     * ['server_id'] 分服id
     * ['user_id'] 用户id
     * ['login_ip'] 注册ip
     * ['union_id'] 媒体（联盟）id
     * ['union_id'] 广告id
     * @return int 新添加的用户ID
     * */
    public function addUserLoginLog($arrFields)
    {
        $usersMod = new DBTable(self::TABLE, self::getPdo() );
        return $usersMod->MyInsert($arrFields);
    }
    
    public function getUserProductByUserID($userid){
        $usersMod = new DBTable(self::TABLE, self::getPdo() );
        return $usersMod->MyGetAll(' distinct product_id ', ' `user_id`='.$userid);
    }
        
	//查询用户非限量激活期间是否登录过
	public function getUserActivated($userid,$product_id,$platform=0){
		$usersMod = new DBTable(self::TABLE, self::getPdo() );
        return $usersMod->MyGetRow('*', '`user_id`="'.$userid.'" and `product_id`="'.$product_id.'" and `platform`="'.$platform.'" limit 1');
		  
	}
}
?>