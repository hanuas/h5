<?php
class ProductItemModel
{
	//public $tableName = 'product_item';
	const TABLE = "product_item";
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
	public function getItemBypid($productID)
	{
        $where ='`product_id`='.$productID.' and can_list=1 and channel_id =0 order by coin';
		return $this->mod->MyGetAll('*', $where);
	}

    /*
     * 根据渠道和item来获取该条信息
     * @param int $cid 渠道id
     * @param int $item 商品名称
     * @return 返回查询的信息
     */

    public function getItemNameByCidItem($cid, $item) {
        $where = "`item`='" . $item . "' and channel_id =" . $cid . " limit 1 ";
        return $this->mod->MyGetAll('*', $where);
    }

	public function getAllProductItem($productID){
		$where = ' 1 ';
		if ($productID !== '') $where .= ' and product_id = '.$productID;
		$where .= ' order by value ASC';
		return $this->mod->MyGetAll('*',$where);
	}
}
?>