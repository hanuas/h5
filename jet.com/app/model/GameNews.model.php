<?php
class GameNewsModel
{
	const TABLE = "game_news";
	public $pdo;
	public $mod; 
	
	public function __construct()
	{
		//$this->mod = &ado_link($this->tableName);
		$this->pdo = DBTool::getPdoByString("common", Config::get("common/db")  );
		$this->mod =  new DBTable(self::TABLE, $this->pdo );
	}

    
    public function getNewsList($where,$fields = "*"){
        return $this->mod->MyGetAll($fields, $where);
    }

    public function getNewsInfoById($id){
        return $this->mod->MyGetRow('*',"id = {$id} and status=1");
    }

    public function getCount($where){
        $res = $this->mod->MyGetRow('count(*) as num',$where);
        return @$res['num']?$res['num']:0;
    }
    

}