<?php
class GameModel
{
	const TABLE = "game";
	public $pdo;
	public $mod; 
	
	public function __construct()
	{
		//$this->mod = &ado_link($this->tableName);
		$this->pdo = DBTool::getPdoByString("common", Config::get("common/db")  );
		$this->mod =  new DBTable(self::TABLE, $this->pdo );
	}
    
    //get 
    public function getGameInfoByAppId($appid){
        return $this->mod->MyGetRow('*',"appid = {$appid}");
    }

    public function getGameList($where){
        return $this->mod->MyGetAll('*', $where);
    }
    
  
}
?>