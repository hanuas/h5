<?php 
class indexController extends commonController{
	
	public function indexAction(){
		//	$this->redirect("?c=home"); 
		
		//跳转到第一个用户有权限的页面
		if(isset($_SESSION['admin']['firstPrivilegedMenu']['conf'])){
			$conf = $_SESSION['admin']['firstPrivilegedMenu']['conf'];
			
			$url = "/index.php?m={$conf['auth']['m']}";
			switch($conf['auth']['type']){
			case "mc":	$url .= "&c=".$conf['auth']['c']; break;
			case "mca":	$url .= "&c=".$conf['auth']['c']."&a=".$conf['auth']['a']; break;
			}
			$this->back_or($url);
			
		}
		//这个执行不到
		$this->render("index.tpl","main.tpl");
		
	}
	
	
	public function loginAction(){
	
		$this->render(
			[	
				"dorisContent"=>"login.tpl",
				"mainPageJsContent"=>"login.js.tpl"
			],
			"main.basic.tpl");
	}
	
	
}