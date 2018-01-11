<?php
/**
 * AdviseModel
 * @author qiaochenglei
 * 2015-12-02
 *
 */
 _load("Base_UserDataCommonModel");
 
class AdviseModel extends Base_UserDataCommonModel { 
	function addUserAdvise($uid,$clientPlat,$version,$title, $content, $time,$category){
		$paras = [
			'user_id' => $uid ,
    		'title' => $title ,
    		'content' => $content ,
    		'category' => $category ,
    		'plat' => $clientPlat ,
    		'time' => $time ,
    		'version' => $version ,
		];
		$result = $this->db()->advise->insert( $paras );
		return $result ;
	}
	
	function getUserAdviseById($id){ 
        return Doris\DDB::fetch("select * from tb_advise where id = '{$id}'");
	}
}