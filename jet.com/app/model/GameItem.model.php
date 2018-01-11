<?php
class GameItemModel
{
	const TABLE = "game_item";
	public $pdo;
	public $mod; 
	
	public function __construct()
	{
		//$this->mod = &ado_link($this->tableName);
		$this->pdo = DBTool::getPdoByString("common", Config::get("common/db")  );
		$this->mod =  new DBTable(self::TABLE, $this->pdo );
	}
    
    //get 
    public function getItemInfoById($id){
        return $this->mod->MyGetRow('*',"id = {$id}");
    }

    public function getItemInfoItemId($item_id,$game_id){
        return $this->mod->MyGetRow('*',"game_id = {$game_id} and item_id='{$item_id}'");
    }
}
?>