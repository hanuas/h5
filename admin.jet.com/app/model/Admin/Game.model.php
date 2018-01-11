<?php 
class Admin_GameModel{
    public function __construct(){
        
    }
    
  
    
    public static function readGameById($id){
        if(!$id){
            return false;
        }
        $stmt = Doris\DDB::pdoSlave()->prepare('select * from game where id = ?');
        $stmt->bindParam(1,$id);
        $stmt->execute();
        $game_info = $stmt->fetch(PDO::FETCH_ASSOC);
        return $game_info;
    }

    public static function readGameByAppId($appid){
        if(!$appid){
            return false;
        }
        $stmt = Doris\DDB::pdoSlave()->prepare('select * from game where appid = ?');
        $stmt->bindParam(1,$appid);
        $stmt->execute();
        $game_info = $stmt->fetch(PDO::FETCH_ASSOC);
        return $game_info;
    }

    


    public static function delGameById($id){
        $id = $id+0;
        if(!$id){
            return false;
        }
        return Doris\DDB::pdo()->exec("delete from game where id =  {$id} ");
    }


    public static function readAllGameList($filed_arr){
        $field_str = implode(",",$filed_arr);
        $stmt = Doris\DDB::pdoSlave()->prepare("select {$field_str} from game");
        $stmt->execute();
        $game_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $game_list;
    }

}