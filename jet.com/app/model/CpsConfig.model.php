<?php
class CpsConfigModel
{
	const TABLE = "cps_config";
	public $pdo;
	public $mod; 
	
	public function __construct()
	{
		//$this->mod = &ado_link($this->tableName);
		$this->pdo = DBTool::getPdoByString("common", Config::get("common/db")  );
		$this->mod =  new DBTable(self::TABLE, $this->pdo );
	}
    
    //get 
    public function getCpsInfo($cps_id){
        $time = date("Y-m-d H:i:s");
        return $this->mod->MyGetRow('*',"cps_id = {$cps_id}");
    }
    
  
}
?>