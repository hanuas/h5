<?php
class PayOrdersModel
{
	// public $tableName = 'product';
	const TABLE = "pay_orders";
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

    public function getInfoByOrderId($fields,$order_id){
        return  $this->mod->MyGetRow($fields, "order_id = '{$order_id}'");

    }

    public function getInfo($fields,$where){
        return  $this->mod->MyGetRow($fields, $where);
    }
    
    //添加
    public function add($addData){
        return $this->mod->MyInsert($addData);
    }
	
    //修改
	public function update($updateArgs,$order_id){
        return $this->mod->MyUpdate($updateArgs,"order_id = '{$order_id}'");
    }

    //获取充值总额
    public function getPayAmonut($where){
        $res = $this->mod->MyGetRow('sum(amount) as pay_amount', $where);
        if($res === false){
            return false;
        }
        return $res['pay_amount']?$res['pay_amount']:0;
    }
}
?>