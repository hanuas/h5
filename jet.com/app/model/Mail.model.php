<?php
class MailModel
{ 
	static  function getPdo(){ 
        $user_pdo = DBTool::getPdoByString("user", Config::get("user/db")  );
        return $user_pdo;
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
	public function addMail($to,$subject,$body,$sendTime='')
	{
		$addData = array(
			'mail_to' => $to,
			'mail_subject' => $subject,
			'mail_body' => $body,
			'send_time' => ($sendTime)?$sendTime:time()
		);
		
		return DBTool::insertByArray( 'mail' ,$addData , self::getPdo()); 
 
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
	public function getMailByID($mailID)
	{
		 
		$sql = "select * from  `mail`  where mail_id = '$mailID' ";
		return DBTool::fetch( $sql,[], self::getPdo());  
	}
	
	/**
	 * @desc 删除邮件
	 * @param string $where
	 * */
	public function delMail($where)
	{
		
		return DBTool::execute(  "delete from `mail` where $where", self::getPdo());  
		//$this->mod->MyDelete($where);
	}
	
	public function getAll($where)
	{
				 
		$sql = "select * from  mail  where $where ";
		return DBTool::fetchAll( $sql,[], self::getPdo());
	}
}
?>