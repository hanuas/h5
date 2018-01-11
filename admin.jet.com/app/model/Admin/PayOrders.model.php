<?php 
class Admin_PayOrdersModel{
    public function __construct(){
        
    }
    
  
    
    public static function readOrderById($id){
        if(!$id){
            return false;
        }
        $stmt = Doris\DDB::pdoSlave()->prepare('select * from pay_orders where id = ?');
        $stmt->bindParam(1,$id);
        $stmt->execute();
        $order_info = $stmt->fetch(PDO::FETCH_ASSOC);
        return $order_info;
    }


}