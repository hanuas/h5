<?php
class Admin_RoleModel{
    
    public function readRoleById($role_id){
        return Doris\DDB::pdoSlave()->query("select * from sys_role where id = {$role_id}")->fetch(PDO::FETCH_ASSOC);
    }

    public function delRoleById($role_id){
        return Doris\DDB::pdo()->query("delete from sys_role_privilege where role_id={$role_id}");
    }
}