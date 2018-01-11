<?php
class ProductModel
{
	// public $tableName = 'product';
	const TABLE = "product";
	public $pdo;
	public $mod; 
	
	public function __construct()
	{
		//$this->mod = &ado_link($this->tableName);
		$this->pdo = DBTool::getPdoByString("user", Config::get("user/db")  );
		$this->mod =  new DBTable(self::TABLE, $this->pdo );
	}
	
	/**
	 * @desc 根据产品ID获取产品信息
	 * @param int $productID 产品ID
	 * 
	 * @return array 
	 * ['product_id'] 产品ID
	 * ['product_name'] 产品名称
	 * ['product_desc'] 产品描述
	 * ['product_logo'] 产品logo
	 * ['index_url'] 主页URL
	 * ['help_url'] 帮助系统URL
	 * ['bbs_url'] 论坛URL
	 * */
	public function getProductByID($productID)
	{
		return $this->mod->MyGetRow('*', '`product_id`="'.$productID.'"');
	}
	
	/**
	 * @desc 获取所有的产品
	 * 
	 * @return array
	 * */
	public function getAllProduct($product_domain = "")
	{

		if (in_array($_SESSION['user_name'], $GLOBALS['cout']['test_account']) || $_GET['s'] == 'n') $where = " 1 ";
		else $where = " product_status = 1 ";
		if($product_domain){
			$where .= " and product_domain='".$product_domain."' ";
		}
		$where .= " ORDER BY show_seque desc,product_status desc,product_id";
		//return $this->mod->MyGetAll('product_id,product_name,is_recommend,product_status,product_domain,product_type,product_currency',$where);
		return 	$this->mod->MyGetAll('*',$where);
	}
}
?>