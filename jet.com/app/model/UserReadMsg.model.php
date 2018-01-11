<?php
class UserReadMsgModel
{
	const TABLE = "user_read_msg";
	public $pdo;
	public $mod; 
	
	public function __construct()
	{
		//$this->mod = &ado_link($this->tableName);
		$this->pdo = DBTool::getPdoByString("common", Config::get("common/db")  );
		$this->mod =  new DBTable(self::TABLE, $this->pdo );
	}

    public function readInfo($fields,$where){
        return $this->mod->MyGetRow($fields,$where);
    }

    //修改
    public function updateById($updateArgs,$id){
        return $this->mod->MyUpdate($updateArgs,"id = '{$id}'");
    }

    //添加
    public function add($addData){
        return $this->mod->MyInsert($addData);
    }
    

}