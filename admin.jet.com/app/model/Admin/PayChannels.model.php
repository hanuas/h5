<?php 
class Admin_PayChannelsModel{
    public function __construct(){
        
    }
    
  


    public static function readList($is_enable = 1){
        $stmt = Doris\DDB::pdoSlave()->prepare("select * from pay_channel where is_enable={$is_enable}");
        $stmt->execute();
        $game_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $game_list;
    }

}