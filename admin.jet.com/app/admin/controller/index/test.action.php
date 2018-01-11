<?php 
use Doris\DDispatch;

class testController {
	
	
	
	public function indexAction($para){
		echo "current MCA: <pre>";
		var_dump(
			[	DDispatch::curModule($para),
				DDispatch::curController($para),
				DDispatch::curAction($para)
			]
		);
	}
	
	public function callActionAction(){
		_app()->callAction("index","test","wiget_level2");
	}
	
	public function dbAction($para){
		Doris\utf8_header();
		$data=Doris\DDB::fetchAll("select*from sys_role");
		Doris\dump($data);
		
	}
	
	public function renderAction($para){
		Doris\utf8_header();
		//echo 1342;exit;
		_app()->action()->assign("test","test varable");
		//_app()->action()->display("../../layout/test/main");exit;
		_app()->action()->render("index.tpl","test/main.tpl");
	}
	
	public function wigetAction($para){
		Doris\utf8_header();
		_app()->action()->assign("test","test varable");
		_app()->action()->render("index.tpl","test/wiget.tpl");
	}
	
	public function wiget_level2Action($para){
		Doris\utf8_header();
		//echo 1342;exit;
		_app()->action()->assign("test","test varable");
		
		
		//exit($innerCallModule);
		_app()->action()->render("index.tpl","test/wiget_level2.tpl",$para);
	}
	
	
	public function logAction($para){
		Doris\utf8_header();
		Doris\DLog::log("hello");
		
	}
	
	
	public function confAction($para){
		$conf =  	Doris\DConfig::get();
		echo "<pre>";
		print_r($conf["push"]);
		print_r($conf["dispatch"]);
	}
}










