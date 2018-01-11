<?php

 use Doris\DApp,
 Doris\DCache,
 Doris\DDB,
 Doris\DLog,
 Doris\DConfig;
 
define('PHONE_BIND'   ,1<<1);
define('EMAIL_BIND'   ,1<<2);
define('QQ_BIND'      ,1<<3);
define('WEIBO_BIND'   ,1<<4);
define('WEIXIN_BIND'  ,1<<5);
define('QQWEIBO_BIND' ,1<<6);
class Admin_UserModel{
    //绑定标志
    private $bindCode = array(
        'phone'   => PHONE_BIND,
        'email'   => EMAIL_BIND,
        'qq'      => QQ_BIND,
        'weibo'   => WEIBO_BIND,
        'weixin'  => WEIXIN_BIND,
        'qq_weibo'=> QQWEIBO_BIND,
        
    );
    
    private $bindLabel = array(
        PHONE_BIND   => 'phone',
        EMAIL_BIND   => 'email',
        QQ_BIND      => 'qq',
        WEIBO_BIND   => 'weibo',
        WEIXIN_BIND  => 'weixin',
        QQWEIBO_BIND=> 'qq_weibo',
        
    );
    
    private $fields = array(
    	'id',
    	'user_name',
    	'nick_name',
    	'expand_user_name',
    	'gender',
    	'user_pwd',
    	'phone',
    	'qq',
    	'email',
    	'weibo',
    	'weixin',
    	'qqweibo',
    	'bindinfo',
    	'score',
 //    	'token',
//     	'create_token_time',
    	'signature',
    	'tags',
    	'career',
    	'career_cat',
    	'portrait',
    	'create_time',
    	'birthday',
    	'last_use_date',
    	'last_sync_time',
    	'last_install_time',
    	'train_begin_date',
    	'synchornize_status',
    	'target_virtue_count',
    	'moreinfo',
    	'user_status'
    );
     private $fieldsLabel = array(
    	'id'=>"用户ID",
    	'user_name'=>"用户名",
    	'nick_name'=>"昵称",
    	'expand_user_name'=>"扩展用户名",
    	'gender'=>"性别",
    	'user_pwd'=>"密码",
    	'phone'=>"电话",
    	'qq'=>"QQ",
    	'email'=>"Email",
    	'weibo'=>"微博",
    	'weixin'=>"微信",
    	'qqweibo'=>"QQ微博",
    	'bindinfo'=>"绑定信息",
    	'score'=>"积分",
//     	'token'=>"令牌",
//     	'create_token_time'=>"令牌产生时间",
    	'signature'=>"用户签名",
    	'tags'=>"标签",
    	'career'=>"职业",
    	'career_cat'=>"职业类别",
    	'portrait'=>"头像",
    	'create_time'=>"注册时间",
    	'birthday'=>"生日",
    	'last_use_date'=>"最后打开客户端时间",
    	'last_sync_time'=>"最后同步时间",
    	'last_install_time'=>"最后一次安装时间",
    	'train_begin_date'=>"开始训练时间",
    	'synchornize_status'=>"同步状态",
    	'target_virtue_count'=>"目标数量",
    	'moreinfo' => "更多信息",
    	'user_status'=>'用户状态'
    );
    
    
    private $careerList = array('计算机/互联网/通信','生产/工艺/制造','商业/服务业/个体经营','金融/银行/投资/保险','文化/广告/传媒','娱乐/艺术/表演','医疗/护理/制药','律师/法务','教育/培训','公务员/事业单位','学生','其他');

    /**
    *@Description 通过ID读取用户信息
    *@param int $user_id  用户ID
    *@return array $userInfo 用户信息
    */
    public function readUserById($user_id){
    
        return Doris\DDB::fetch("select * from user where id = '{$user_id}'");
    }
  

    /**
    *@Description 获取绑定标志信息
    */
    public function getBindCode(){
        return $this->bindCode;
    }
    /**
    *@Description 
    */
    public function getBindLabel(){
        return $this->bindLabel;
    }
    /**
    *@Description 
    */
    public function getFieldsLabel(){
        return $this->fieldsLabel;
    }
    /**
    *@Description 通过关键字读取用户信息
    *@param String $keyword  搜索关键字
    *@param int    $currentPage 当前页码
    *@param int    $pageSize    偏移量
    */
    public function readByKeyWords($keyword,$currentPage=1,$pageSize=20){
        $start = ($currentPage-1)*$pageSize;
        return Doris\DDB::pdoSlave()->query("select * from user where name like '%{$keyword}%' limit {$start},{$pageSize}")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readCountByKeywords($keyword){
        $count = Doris\DDB::pdoSlave()->query("select count(*) as num from user where name like '%{$keyword}%'")->fetch(PDO::FETCH_ASSOC);
        return $count['num'];
    }
    
    /**
    *@Description 绑定标识码转换为数组
    *
    */
    public function bindCodeToArr($code){
        $codeList = $this->bindCode;
        $arr = array();
        foreach($codeList as $v){
            if(($v & $code) == $v){
                $arr[] = $v;
            }
        }

        return $arr;
    }
    /**
    *@Description 通过email读取用户信息
    *@param int $email  用户email
    *@return array $userinfo 用户信息
    */
    public function readUserByEmail($email){
        $user_id = $this->readUserIdByEmail($email);
        if($user_id){
            return $this->readUserById($user_id);
        }else{
            return false;
        }
    }

    /**
    *@Description 通过手机号读取用户信息
    *@param int $phone  用户手机号
    *@return array $userinfo 用户信息
    */
    public function readUserByPhone($phone){
        $user_id = $this->readUserIdByPhone($phone);
        if($user_id){
            return $this->readUserById($user_id);
        }else{
            return false;
        }
    }
    /**
    *@Description 检查邮箱唯一性
    *@param string $email 邮箱地址
    *@param int $besides_uid 用户id
    */
    public function checkEmailUnique($email,$besides_uid = ''){
        $user_id = $this->readUserIdByEmail($email);
        if(!$user_id){
            return true;
        }elseif($user_id == $besides_uid){
            return true;
        }else{
            return false;
        }
    }
    public function readUserIdByPhone($phone){
        $user_id = Doris\DDB::pdoSlave()->query("select uid from useridx_phone where phone = '{$phone}'")->fetch(PDO::FETCH_ASSOC);
        $user_id = $user_id?$user_id['uid']:false;
        return $user_id;
    }
    public function readUserIdByEmail($email){
        $user_id = Doris\DDB::pdoSlave()->query("select uid from useridx_email where email = '{$email}'")->fetch(PDO::FETCH_ASSOC);
        $user_id = $user_id?$user_id['uid']:false;
        return $user_id;
    }

    /**
    *@Description 检查电话号唯一性
    *@param string $phone 电话号
    *@param int $besides_uid 用户id
    */
    public function checkPhoneUnique($phone,$besides_uid = ''){
        $user_id = $this->readUserIdByPhone($phone);
        if(!$user_id){
            return true;
        }elseif($user_id == $besides_uid){
            return true;
        }else{
            return false;
        }
    }

    public function addEmail_User($cur_email,$uid,$old_email=''){
        if($old_email){
            $res = $this->delEmail_user($old_email);
        }else{
            $res = true;
        }
        if($res){
            if($cur_email){
                return Doris\DDB::db()->useridx_email()->insert(array('email'=>$cur_email,'uid'=>$uid));
            }else{
                return true;
            }
        }
    }
    public function addPhone_User($cur_phone,$uid,$old_phone=''){ 
        if($old_phone){
            $res = $this->delPhone_user($old_phone);
        }else{
            $res = true;
        }
        if($res){
            if($cur_phone){
                return Doris\DDB::db()->useridx_phone()->insert(array('phone'=>$cur_phone,'uid'=>$uid));
            }else{
                return true;
            }
        }
    }
    public function del_user($uid){
        $userInfo = $this->readUserById($uid);
        if(@$userInfo['email']){
            $this->delEmail_user($userInfo['email']);
        }
        if(@$userInfo['phone']){
            $this->delPhone_user($userInfo['phone']);
        }
        return Doris\DDB::pdo()->query("delete from user where id = '{$uid}'");
    }
    public function delPhone_user($phone){
        return Doris\DDB::pdo()->query("delete from useridx_phone where phone = '{$phone}'");
    }
    public function delEmail_user($email){
        return Doris\DDB::pdo()->query("delete from useridx_email where email = '{$email}'");
    }
    
    /**
    *@Description 插入用户数据
    */
    public function insertUser($data){
        $data = $this->filterFields($data);
//         $data['create_token_time'] = date('Y-m-d H:i:s');
//         $data['create_time'] = date('Y-m-d H:i:s');
        return Doris\DDB::db()->user()->insert($data);
    }
    
    public function updateUser($data,$id){
    	$data = $this->filterFields($data);
    	$user_row = Doris\DDB::db()->user[$id];
    	foreach($data as $k=>$v){
    		$user_row[$k] = $v;
    	} 
    	return $user_row->update();   	
    }
    /**
    * @Description 过滤数据库中不存在的字段
    * @param array $fields 待过滤字段
    * @return array $fields 过滤后字段
    */
    private function filterFields($fields){
        $table_fields = $this->fields;
        foreach($fields as $k=>$v){
            if(!in_array($k,$table_fields)){
                unset($fields[$k]);
            }
        }
        return $fields;
    }
    /**
    *@Description 获取用户标签
    */
    public function getTags($tag_ids){
        $tag_ids = $tag_ids?$tag_ids:0;
        return Doris\DDB::pdoSlave()->query("select * from tag where id in ({$tag_ids})")->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
    *@description 获取职业列表
    */
    public function getCareerList(){
        return $this->careerList;
    }

    public function handelUser($userList,$params,$addElement = ''){
        $admin_url = Doris\DApp::newClass('Sysconfig')->readConfigByName('admin_host');
        $new_list = array();
        foreach($userList as $key=>$value){
            $userInfo = array();
            foreach($value as $k=>$v){
                if(in_array($k,$params)){
                    if($k=='face' && $v){
                        $userInfo[$k] = $admin_url['value'].'/'.$v;
                    }elseif($k=='tags'){
                        $userInfo[$k] = $this->getTags($v);
                    }else{
                        $userInfo[$k] = $v;
                    }
                    if(is_null($v)){
                        $userInfo[$k] = '';
                    }
                }
                if($addElement&&$userInfo){
                    foreach($addElement as $ke=>$va){
                        $userInfo[$ke] = $va;
                    }
                }
            }
            $new_list[] = $userInfo;
        }
        return $new_list;
    }
 
    
}
