<?php
 
class UtilModel{

	static  function getPdo(){ 
        $user_pdo = DBTool::getPdoByString("user", Config::get("user/db")  );
        return $user_pdo;
	}
	
    static function getCountryByIP($ip)
	{	
		$sql = "select*from ipdata $ip between startIP and endIP ";
        return DBTool::fetch( $sql,[], self::getPdo() ); 
        
		//if ($card_type) $where .= " and card_type='{$card_type}'";
		//return $this->mod->MyGetRow('*',$where);
	}

}
