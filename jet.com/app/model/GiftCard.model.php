<?php
class GiftCardModel
{
	const TABLE = "gift_card";
	public $pdo;
	public $mod; 
	
	public function __construct()
	{
		//$this->mod = &ado_link($this->tableName);
		$this->pdo = DBTool::getPdoByString("common", Config::get("common/db")  );
		$this->mod =  new DBTable(self::TABLE, $this->pdo );
	}
    
    //获取已领取礼包码数量
    public function getGiftCardUsedCount($gift_id){
        $res = $this->mod->MyGetRow('count(*) as num', '`gift_id`="'.$gift_id.'" and is_used = 1');
        if($res === false){
            return false;
        }
        return $res['num'];
    }

    //获取未领取礼包码数量
    public function getGiftCardLeftCount($gift_id){
        $res = $this->mod->MyGetRow('count(*) as num', '`gift_id`="'.$gift_id.'" and is_used = 0');
        if($res === false){
            return false;
        }
        return $res['num'];
    }

    //获取用户领取过的礼包码
    public function getUserUsedCard($user_id,$gift_id){
        $res = $this->mod->MyGetRow('card_no', "gift_id = {$gift_id} and is_used=1 and `user_id`={$user_id} ");
        if($res === false){
            return false;
        }
        return $res['card_no'];
    }

    //添加
    public function add($addData){
        return $this->mod->MyInsert($addData);
    }

    //获取没有使用的礼包码
    public function getUnUsedCard($gift_id,$left_num){
        //查询添加随机数
        $lmt = " limit ".mt_rand(0,$left_num-1).",1";
        $r = $this->mod->MyGetRow('*', "gift_id = {$gift_id} and is_used=0 and user_id=0 and card_status=1".$lmt);
        return $r;

    }

    //删除没有使用的礼包码
    public function delUnUsedCardById($id){
        $where =  "id={$id} and is_used=0 and user_id=0 limit 1";
        $res = $this->mod->MyDelete($where);
        return $res;
    }

    //get list
    public function getCardsList($where){
        return $this->mod->MyGetAll('*', $where);
    }

    
  
}
?>