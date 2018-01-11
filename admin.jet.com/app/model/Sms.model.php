<?php
/**
 * PushModel
 * @author qiaochenglei
 * 2015-12-02
 *
 */
 
  
 use Doris\DApp,
 Doris\DDB,
 Doris\DConfig;
 
class SmsModel {

	public function generateRRID( $partner_plat = "md"){
		return $partner_plat."_".time();
	}
	public function createTempTask($rrid,$phone, $content ,$stime,$partner_plat = "md"){
		$nowString = date("Y-m-d H:i:s");
	 	$row = [
	 		"rrid" => $rrid,
	 		"phone" => $phone,
	 		"content" => $content,
	 		"create_time" => $nowString ,
	 		"update_time" => $nowString ,
	 		"stime" => $stime,
	 		"partner_plat" => $partner_plat,
	 		"return_status" => -1,
	 		"return_message" => "",
	 	];
		if($row){ 
			$result = 	DDB::db()->sms()->insert( $row );
		}
		return $result ;
	}

	public function updateTask($rrid,$return_status, $return_message, $partner_plat = "md"){
	
		$arrUpdate = [ 
	 		"update_time" => date("Y-m-d H:i:s"),
	 		"return_status" => $return_status,
	 		"return_message" => $return_message,
	 	];
		$row = DDB::db()->sms->where("partner_plat = ? and rrid = ?", $partner_plat, $rrid )->fetch();
		
		//$db->application("title = ?", "Adminer")->fetch();
		return $row->update( $arrUpdate ); 
	}
 
}