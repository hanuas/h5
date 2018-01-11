<?php
class GiftModel
{
	const TABLE = "gift";
	public $pdo;
	public $mod; 
	
	public function __construct()
	{
		//$this->mod = &ado_link($this->tableName);
		$this->pdo = DBTool::getPdoByString("common", Config::get("common/db")  );
		$this->mod =  new DBTable(self::TABLE, $this->pdo );
	}
    
    //get list
    public function getGiftList($where){
        return $this->mod->MyGetAll('*', $where);
    }

    //获取礼包数量
    public function getGiftCount($where){
        $res = $this->mod->MyGetRow('count(*) as num', $where);
        if($res === false){
            return false;
        }
        return $res['num'];
    }

    public function getGiftInfoById($gift_id){
        $time = date("Y-m-d H:i:s");
        return $this->mod->MyGetRow('*', "gift_id={$gift_id} and start_time <'{$time}' and end_time > '{$time}' and gift_status = 1");
    }
    
  
}
?>