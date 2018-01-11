<?php 

	/**
	 *  @Description 后台用户model类
	 *  
	 * 
	 */

class Admin_AdminModel{
    public $allAdmin;
    private $adminInfo;

    public function __construct(){
    //     if(isset($_SESSION['admin'])){
//             $this->adminInfo = $_SESSION['admin'];
//         }    
    }

    public function checkUserNamePwd($username,$password){
        $handle = Doris\DDB::pdoSlave()->prepare('select * from sys_user where user_name=:username and user_pwd=:password');
		$handle->execute(array(
		    'username'=>$username,
			'password'=>md5($password)
		));    
        $userInfo = $handle->fetch(pdo::FETCH_ASSOC);
        return $userInfo;
    }


    /* 
    *@Description 修改密码
    */
    public function updatePwd($password,$userid){
		$stmt = Doris\DDB::pdo()->prepare('update sys_user set user_pwd = ? where id = ?');
	    $stmt->bindParam(1,$password);
		$stmt->bindParam(2,$userid);
		$updateSucc = $stmt->execute();   
        return $updateSucc;
    }
	
    public function getAllAdmin(){
        $allAdmin = Doris\DDB::pdoSlave()->query("select id,username,password,email,phone,gender,leader from sys_user")
                   ->fetchAll(PDO::FETCH_ASSOC);
        foreach($allAdmin as $key=>$value){
            $adminList[$value['id']] = $value; 
        }
        $this->allAdmin = $adminList;
        return $adminList;
    }

	/**
	 * @Description 获取用户基本信息
	 * @param $userid  int
	 * @return boolean false or array
	 */
	public function readAdminById($userid){
		if(!$userid){
			return false;
		}
		$stmt = Doris\DDB::pdoSlave()->prepare('select * from sys_user where id = ?');
		$stmt->bindParam(1,$userid);
        $stmt->execute();
        $userinfo = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userinfo;
	}
	
	/**
	 * @Description 获取用户基本信息
	 * @param $username string
	 * @return boolean false or array
	 */
	public function readAdminByName($username){
		$stmt = Doris\DDB::pdoSlave()->prepare('select * from sys_user where user_name = ?');
		$stmt->bindParam(1,$username);
		$stmt->execute();
		$userinfo = $stmt->fetch(PDO::FETCH_ASSOC);
		return $userinfo;
	}



    /**
    *@Description 根据区域权限和角色获取后台用户
    *@param int $areaId 区域id
    *@param string $roleName 角色名
    */
    public function getAdminByAreaAndRole($areaId,$roleName){
        $sql = "select u.id as id,username,password,email,gender,phone,leader,area_privilege from sys_user as u left join sys_user_role as ur on ur.user_id = u.id left join sys_role as r on r.id=ur.role_id where area_privilege like '%{$areaId}%' and r.role_name='{$roleName}'";
        $user = Doris\DDB::pdoSlave()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $user;
    
    }
	
    
}
