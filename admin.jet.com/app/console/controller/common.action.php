<?php 

use Doris\DApp;

class commonController extends Doris\DAction{
	

	static function echoData($status,$message,$data=null){
		echo json_encode([
			"status"  => $status,
			"message" => $message,
			"data"	  =>  $data ,
			"run_time"	  =>  date("Y-m-d H:i:s") 
		]);
		echo "	   \n";
		exit;
	}
	static public function parseFromTo(){
		$t = empty($_GET['t']) ? 0 :  (int)$_GET['t']; 
		$h = empty($_GET['h']) ? 0 :  (int)$_GET['h']; 
        $time_to = self::getTimeAdjustToHour(time(), $t,$h )  ; 
        $time_from = $time_to - 3600;
        return [ $time_from, $time_to, $t, $h];
	}
	//返回离现在最近的整点时间
	static public function getTimeAdjustToHour($timeStamp , $daysAgo = 0 ,$hoursAgo = 0 ){
		$newTime = strtotime( date("Y-m-d H:0:0", $timeStamp ) );
		$newTime = $newTime - 86400 * $daysAgo - 3600*$hoursAgo;
		return $newTime;
	}
	
}