<?php
class OauthAuthorizationCodesModel
{
	const TABLE = "oauth_authorization_codes";
	public $pdo;
	public $mod; 
	
	public function __construct()
	{
		//$this->mod = &ado_link($this->tableName);
		$this->pdo = DBTool::getPdoByString("common", Config::get("common/db")  );
		$this->mod =  new DBTable(self::TABLE, $this->pdo );
	}
    
    public  function getAll(){
        return 	$this->mod->MyGetAll('*',"1");
    }

    //get 
    public function getCodeInfo($code){
        $time = date("Y-m-d H:i:s");
        return $this->mod->MyGetRow('*',"authorization_code = :authorization_code and expires>'{$time}'",array(":authorization_code"=>$code));
    }
    
    //添加
    public function add($addData){
        return $this->mod->MyInsert($addData);
    }

    public function deleteCode($code){
        return $this->mod->MyDelete("authorization_code = '{$code}'");
    }
	
    //修改
	public function update($updateArgs,$order_id){
        return $this->mod->MyUpdate($updateArgs,"order_id = '{$order_id}'");
    }
}
?>