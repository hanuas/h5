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
 
class PushModel {

	public function getTimetagConfig($plat, $date, $timeTag ){
		$sql = "select*from tb_push_timetag where `timetag`= '{$timeTag}' and `plat`= '{$plat}' ";
		$row = DDB::fetch($sql);
		// if(!$row ){ 
// 			$autoFromDate = DConfig::get("push/{$plat}/autoConfTimetag_FromDate");
// 			
// 			if( !empty($autoFromDate ) ){
// 				$sqlFrom = "select*from tb_push_timetag where `senddate`= '{$autoFromDate}' and `timetag`= '{$timeTag}' and `plat`= '{$plat}' ";
// 				$row = DDB::fetch($sqlFrom);
// 				 
// 				if($row){
// 					unset($row["id"]);
// 					$row["senddate"] =  $date; 
// 					$row = 	DDB::db()->tb_push_timetag()->insert( $row ); 
// 				}
// 			}
// 		}
		if($row){
			$row["senddate"] =  $date; 
		}
		return $row;
	}

	public function getTimetagHistory($plat, $date, $timeTag ){
		$row = DDB::fetch("select*from tb_push_timetag_history where `senddate`= '{$date}' and `timetag`= '{$timeTag}' and `plat`= '{$plat}' ");
		 
		return $row;
	}

	public function getTimetagHistoryByTaskId($taskId ){
		$row = DDB::fetch("select*from tb_push_timetag_history where `task_id`= '{$taskId}'  "); 
		return $row;
	}
	
	public function getTimetagHistoryId($id ){
		$row = DDB::fetch("select*from tb_push_timetag_history where `id`= '{$id}'  "); 
		return $row;
	}
	
	public function addTimetagHistory($row ){
		DDB::db()->push_timetag_history()->insert( $row );
		return $row;
	}
	public function updateTimetagHistory($id,$arrUpdate ){
		$row = DDB::db()->push_timetag_history[$id];
		return $row->update( $arrUpdate ); 
	}
}