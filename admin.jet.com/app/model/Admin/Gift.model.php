<?php 
class Admin_GiftModel{
    public function __construct(){
        
    }
    
    public static function getCardCountByGiftId($gift_id){
        $stmt = Doris\DDB::pdoSlave()->prepare('select count(*) as num from gift_card where gift_id = ?');
        $stmt->bindParam(1,$gift_id);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count['num']?$count['num']:0;
    }

    public static function updateGift($updateArgs,$gift_id){
        $set = 'set ';
        foreach($updateArgs as $k=>$v){
            $set.= "{$k}='{$v}',";
        }
        $set = trim($set,',');
        $sql = "update gift {$set} where gift_id={$gift_id}";
        return Doris\DDB::execute($sql);
    }
  
    
    public static function readGiftByGiftId($gift_id){
        if(!$gift_id){
            return false;
        }
        $stmt = Doris\DDB::pdoSlave()->prepare('select * from gift where gift_id = ?');
        $stmt->bindParam(1,$gift_id);
        $stmt->execute();
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
        return $info;
    }


    public static function delGiftById($id){
        $id = $id+0;
        if(!$id){
            return false;
        }
        return Doris\DDB::pdo()->exec("delete from gift where gift_id =  {$id} ");
    }



}