<?php
class CpsConfigService{
    
    
    //获取cps信息
    public function getCpsInfo($cps_id){
        $model = new CpsConfigModel();
        $cps_info = $model->getCpsInfo($cps_id);
        return $cps_info;
    }

   

}