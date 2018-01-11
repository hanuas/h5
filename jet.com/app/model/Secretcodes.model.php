<?php
class SecretcodesModel
{
    
	const TABLE = "secret_codes";
	public $pdo;
	public $mod;
    
    public function __construct()
    {
        //$this->mod = &ado_link($this->tableName);
        
		$this->pdo = DBTool::getPdoByString("user", Config::get("user/db")  );
		$this->mod =  new DBTable(self::TABLE, $this->pdo );
    }
    
    /**
     * @desc 添加数据
     * @param array $arrAddData 一维数组(键名为表字段名)
     * 
     * @return int 新添加的ID
     * */
    public function addSecretcodes($arrAddData)
    {
        return $this->mod->MyInsert($arrAddData);
    }
    
    /**
     * 根据条件获取全部信息
     * @param string $field 返回字段
     * @param string $where 查询条件
     * 
     * */
    public function getAll($field, $where)
    {
        return $this->mod->MyGetAll($field, $where);
    }
    
    /**
     * 根据条件获取一条记录
     * @param string $field 返回字段
     * @param string $where 查询条件
     * 
     * @return array
     * */
    public function getRow($field, $where)
    {
        return $this->mod->MyGetRow($field, $where);
    }
    
    /**
     * @desc 根据ID获取数据
     * @param int $codeID
     * 
     * @return array
     * */
    public function getCodeByID($codeID,$codeType)
    {
        return $this->mod->MyGetRow('*', '`code_id`="'.$codeID.'",`code_type`="'.$codeType.'"');
    }
    
    /**
     * @desc 根据code_uid,code_type获24小时内的最新数据
     * 
     * @return array
     * */
    public function getCodeByUserID($user_ID,$codeType)
    {
        return $this->mod->MyGetRow('*', '`code_uid`="'.$user_ID.'" and `code_type`="'.$codeType.'" and `code_time`>="' . ($_SERVER['REQUEST_TIME'] - 86400) . '" order by `code_time` desc limit 1');
    }
    
    
    public function getCountByUserID($user_ID,$codeType)
    {
        $timestamp_limit = (isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time()) - 86400;
        return intval($this->mod->MyGetOne('count(*)', "`code_uid`='$user_ID' and `code_type`='$codeType' and `code_time`>='{$timestamp_limit}'"));
    }
    
    /**
     * @desc 修改数据
     * @param array $arrUpData 一维数组(键名为表字段名)
     * @param string $where
     * */
    public function upSecretcodes($arrUpData,$where)
    {
        $this->mod->MyUpdate($arrUpData,$where);
    }
    
    /**
     * @desc 删除数据
     * @param string $where
     * */
    public function delSecretcodes($where)
    {
        $this->mod->MyDelete($where);
    }
}
?>