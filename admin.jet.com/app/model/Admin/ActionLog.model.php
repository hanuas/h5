<?php
/**
*	操作日志Model类
*/
//camera
class Admin_ActionLogModel{
	
    /**
    *@Description 记录操作日志
    *@param string$type 操作类型
    *@param string $title 操作主题
    *@param mixed $detail 操作详情
    */
    public static function logging($title,$detail,$type='default',$category="default"){
        date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai' 设置默认时区
        $data['user_id'] = $_SESSION['admin']['id'];
        $data['user_name'] = $_SESSION['admin']['user_name'];
        $data['title'] = $title;
        $data['category'] = $category;
        $data['type'] = $type;
        $data['action_detail'] = is_array($detail)?json_encode($detail):$detail;
        $data['time'] = date('Y-m-d H:i:s');
        Doris\DDB::db()->sys_action_log()->insert($data);

    }

	public static function log($oper_id,$oper_name,$title,$camera,$newcamera,$log=[], $type = "default",$category="default"){
		date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai' 设置默认时区
		$detail=[];
		$detail['camera'] = $camera;
		$detail['newcamera'] = $newcamera;
		$detail['log']=$log;
		 
        $data['user_id'] = $oper_id;
        $data['user_name'] = $oper_name ;
        $data['title'] = $title;
        $data['category'] = $category;
        $data['type'] = $type;
        $data['action_detail'] = is_array($detail)?json_encode($detail):$detail;
        $data['time'] = date('Y-m-d H:i:s');
        Doris\DDB::db()->sys_action_log()->insert($data);

	 
	}
    public  function readActionLogById($id){
        $logInfo = Doris\DDB::pdoSlave()->query("select * from sys_action_log where id={$id}")->fetch(PDO::FETCH_ASSOC);
        if($this->isJson($logInfo['action_detail'])){
            $logInfo['action_detail'] = json_decode($logInfo['action_detail'],true);
            foreach($logInfo['action_detail'] as $k=>$v){
                if(is_numeric($k)){
                    unset($logInfo['action_detail'][$k]);
                    $logInfo['action_detail']['log'][$k] = $v; 
                }else if ($k=='camera'){
                    unset($logInfo['action_detail'][$k]);
                    $logInfo['action_detail']['camera'] = $v; 
                }else if($k=='newcamera'){
                    unset($logInfo['action_detail'][$k]);
                    $logInfo['action_detail']['newcamera'] = $v;                 
                }
            }
        }
        return $logInfo;
    }

    public  function isJson($jsonData){
        $json_arr = json_decode($jsonData,true);
        if(is_array($json_arr)){
            return true;
        }else{
            return false;
        }
    }

    public function delLog($id){
        $res = Doris\DDB::pdo()->query("delete from sys_action_log where id={$id}");
        return $res;
    }

    

}