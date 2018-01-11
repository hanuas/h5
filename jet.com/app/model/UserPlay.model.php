<?php
class UserPlayModel
{
	const TABLE = "user_play";
	public $pdo;
	public $mod; 
	
	public function __construct()
	{
		//$this->mod = &ado_link($this->tableName);
		$this->pdo = DBTool::getPdoByString("common", Config::get("common/db")  );
		$this->mod =  new DBTable(self::TABLE, $this->pdo );
	}

    
    //添加
    public function add($addData){
        return $this->mod->MyInsert($addData);
    }

    //修改
    public function updateById($updateArgs,$user_id){
        return $this->mod->MyUpdate($updateArgs,"user_id = '{$user_id}'");
    }

    //修改
    public function update($sql){
        return DBTool::execute( $sql , $this->pdo );
    }
}
?>