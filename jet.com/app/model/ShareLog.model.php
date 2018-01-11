<?php
class ShareLogModel
{
	const TABLE = "share_log";
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

}
?>