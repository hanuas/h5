<?php 
class Admin_SysconfigModel{
    public $configInfo;
	
    public function getConfig($segMent,$name){
        $this->configInfo = Doris\DDB::pdo()->query("select id,segment,name,type,value from sys_config where segment='{$segMent}' and name='{$name}'")->fetch(PDO::FETCH_ASSOC);
        return $this->configInfo;
    }

    public function readConfigByName($name){
        $configInfo =  Doris\DDB::pdo()->query("select * from sys_config where name='{$name}'")->fetch(PDO::FETCH_ASSOC);
        if(json_decode(@$configInfo['value'])){
            $configInfo['value'] = json_decode($configInfo['value'],true);
        }
        return $configInfo;
    }
}