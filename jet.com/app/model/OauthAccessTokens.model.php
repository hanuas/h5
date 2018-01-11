<?php
class OauthAccessTokensModel
{
	const TABLE = "oauth_access_tokens";
	public $pdo;
	public $mod; 
	
	public function __construct()
	{
		//$this->mod = &ado_link($this->tableName);
		$this->pdo = DBTool::getPdoByString("common", Config::get("common/db")  );
		$this->mod =  new DBTable(self::TABLE, $this->pdo );
	}
    
    //get 
    public function getAccessToken($access_token){
        $time = date("Y-m-d H:i:s");
        return $this->mod->MyGetRow('*',"access_token = '{$access_token}' and expires>'{$time}'");
    }
    
    //添加
    public function add($addData){
        return $this->mod->MyInsert($addData);
    }
	
    //修改
	public function update($updateArgs,$order_id){
        return $this->mod->MyUpdate($updateArgs,"order_id = '{$order_id}'");
    }
}
?>