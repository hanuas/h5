<?php
class PayChannelModel
{
	// public $tableName = 'product';
	const TABLE = "pay_channel";
	public $pdo;
	public $mod; 
	
	public function __construct()
	{
		$this->pdo = DBTool::getPdoByString("common", Config::get("common/db")  );
		$this->mod =  new DBTable(self::TABLE, $this->pdo );
	}


	

	/**
	 *  根据 game_id,item_id,channel_platform(平台) 获取支付渠道列表
	 */
	public function getPayChannelsByItem($game_id,$item,$channel_platform)
	{
        $sql = "select c.channel_id,c.channel_name,c.channel_weight,c.channel_platform,value,coin,item_name from game_item as i left join pay_channel as c on i.channel_id = c.channel_id where i.item ='{$item}' and i.game_id={$game_id} and c.is_enable = 1 and c.channel_platform = {$channel_platform} order by channel_weight desc";
	    return DBTool::fetchAll( $sql,[], $this->pdo); 
    }
	
	
}

