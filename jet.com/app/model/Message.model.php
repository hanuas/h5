<?php
class MessageModel
{ 
	const TABLE = "message";
	public $pdo;
	public $mod;
	
	public function __construct()
	{
		//@$this->pdo = &ado_link($this->tableName);
		$this->pdo = DBTool::getPdoByString("user", Config::get("user/db")  );
		$this->mod =  new DBTable(self::TABLE, $this->pdo );
	}
	
	/**
	 * @desc 添加待发送邮件
	 * @param string $to 发送对象
	 * @param string $subject 发送主题
	 * @param $body 发送主体
	 * @param string sendTime 添加时间(可选)
	 * 
	 * @return int mail_id 
	 * */
	public function addMessage($phone,$body,$module='',$product_id='',$server_id='',$card_type='',$sendTime='')
	{
		$addData = array(
			'phone' => $phone,
			'message_body' => $body,
			'module' => $module,
			'product_id' => $product_id,
			'server_id' => $server_id,
			'card_type' => $card_type,
			'send_time' => ($sendTime)?$sendTime:time()
		);
		return $this->mod->MyInsert($addData);
	}
	
	/**
	 * @desc 根据ID获取待发送邮件
	 * @param int $mailID
	 * 
	 * @return array
	 * ['mail_id'] 邮件ID
	 * ['mail_to'] 发送对象
	 * ['mail_subject'] 发送主题
	 * ['mail_body'] 发送主体
	 * ['send_time'] 添加时间
	 * */
	public function getMessageByID($messageId)
	{
		return $this->mod->MyGetRow('*', '`id`="'.$messageId.'"');
	}

	public function getMessageByPhone($phone,$module='',$product_id='',$server_id='',$card_type='')
	{
        $where = "phone={$phone}";
        if($module){
            $where.=" and module='{$module}'";
        }
        if($product_id){
            $where.=" and product_id={$product_id}";
        }
        if($server_id){
            $where.=" and server_id={$server_id}";
        }
        if($card_type){
            $where.=" and card_type={$card_type}";
        }
		return $this->mod->MyGetRow('*',$where);
	}

    public function getCount($module='',$product_id='',$server_id='',$card_type=''){
        $where="phone>=0";
        if($module){
            $where.=" and module='{$module}'";
        }
        if($product_id){
            $where.=" and product_id={$product_id}";
        }
        if($server_id){
            $where.=" and server_id={$server_id}";
        }
        if($card_type){
            $where.=" and card_type={$card_type}";
        }
        $r = $this->mod->MyGetRow('count(*) as cou', $where);
        return $r['cou'];
    }

    public function updateMessageByID($arrUpData,$messageId){
        $r = $this->mod->MyUpdate($arrUpData, "id={$messageId}");
		return $r;
    }
	
	/**
	 * @desc 删除邮件
	 * @param string $where
	 * */
	public function delMessage($where)
	{
		$this->mod->MyDelete($where);
	}
	
	public function getAll($where)
	{
		return $this->mod->MyGetAll('*', $where);
	}
}
?>