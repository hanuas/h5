<?php 
class Admin_GameItemModel{
    public function __construct(){
        
    }
    
    public static function readGameItemByGameId($gameId){
        $sql = "select * from game_item where game_id={$gameId} ";
        $stmt = Doris\DDB::pdoSlave()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }  


    public static function getCamera($gameId){
        $priceItem = self::readGameItemByGameId($gameId);
        $camera = array();
        foreach($priceItem as $key=>$value){
            $info['refName'] = $value['reference_name'];
            $info['充值渠道ID'] = $value['channel_id'];
            $info['itemId'] = $value['item_id'];
            $info['tire价格'] = $value['tire'];
            $info['类型'] = $value['type'];
            $info['游戏货币,单位'] = $value['coin'].','.$value['coin_unit'];
            $info['列表显示'] = $value['reference_name']?'是':'否';
            $info['人民币和美元价格'] = $value['rmbprice'].','.$value['dolarprice'];
            $info['价格'] = $value['value'];
            $camera[] = $info;
        }
        return $camera;
    }

    public static function deleteByGameId($gameId){
        return Doris\DDB::pdo()->exec("delete from game_item where game_id = {$gameId} ");
    }



}