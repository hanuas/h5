<?php 
class Admin_GameNewsModel{
    public function __construct(){
        
    }
    
  
    
    public static function readGameNewsById($id){
        if(!$id){
            return false;
        }
        $stmt = Doris\DDB::pdoSlave()->prepare('select * from game_news where id = ?');
        $stmt->bindParam(1,$id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    }

    public static function delGameNewsById($id){
        $id = $id+0;
        if(!$id){
            return false;
        }
        return Doris\DDB::pdo()->exec("delete from game_news where id =  {$id} ");
    }

}