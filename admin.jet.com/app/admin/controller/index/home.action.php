<?php 
class homeController extends commonController{
	

	public function indexAction(){
		return $this->modifyAction();
	}
	
	public function modifyAction(){
		if(!empty($_POST)){
			 $this->update_userAction();
		}else{
			$this->render('admin_userinfo.tpl');
		}
	}
	/**
	 * @Description 修改用户资料
	 */
	public function update_userAction(){
		$info=$_POST['info'];
		//验证
		if(empty($info['phone']))$this->error('手机不能为空！',-1);
		if(empty($info['email']))$this->error('邮箱地址不能为空！',-1);
		
		if(!empty($info['extend'])){
			$json=json_decode($info['extend']);
			if( empty($json) )
				$this->error('扩展地址字段必须是JSON格式',-1);
		}
		//检索数据库并更新
        $sys_user = Doris\DDB::db()->sys_user[$_SESSION['admin']['id']];
        $sys_user['phone'] = $info['phone'];
        $sys_user['email'] = $info['email'];
        $sys_user['gender'] = $info['gender'];
        $sys_user['extend'] = $info['extend'];
	    $updateSuccess= $sys_user->update();
		if($updateSuccess){
			$this->refreshSESSION();
			$this->success('修改成功！',-1);
			
		}else{
			$this->error('未修改成功！',-1);
			return;
		}
	
	}//END update_user
	
}