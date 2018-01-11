<?php 
/**
 * 
 *
 */ 
 
use Doris\DApp,
 Doris\DCache,
 Doris\DLog,
 Doris\DConfig;
 




class Service_Picture{
   
    public function syncPicture(){
        $shell = _APP_DIR_.'sh/syncimg.sh';
        $cmd = "sh {$shell}";
        exec($cmd,$out,$ret);
        if($ret == 0){
            return true;
        }else{
            return false;
        }
    }
   
}


