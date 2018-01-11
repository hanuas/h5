<?php
require_once(dirname(__FILE__) . '/' . 'Umeng/Interface.php');
defined("UmengKey") 	or define("UmengKey", 		"123");
defined("UmengSecret") 	or define("UmengSecret", 	"123"); 


defined("UmengKeyAndroid") 		or define("UmengKeyAndroid", 		"123");
defined("UmengSecretAndroid") 	or define("UmengSecretAndroid", 	"123"); 

class Third_UmengInterface {
	private $intf;
	private $intfAndroid;
	private $production_mode;
	
	function __construct( $is_production_mode = true ) {
		
		$this->production_mode = $is_production_mode ? "true" : "false";
		$this->intf = new UmengInterface(UmengKey, UmengSecret);
		$this->intfAndroid = new UmengInterface(UmengKeyAndroid, UmengSecretAndroid);
	}
	 
	
	/*
		Android 的几个接口
	*/
	
	function sendAndroidGroupcast($tag,$text, $title= "提醒" ) {
		return $this->intfAndroid->sendAndroidGroupcast($tag,$title,$text, $this->production_mode);
	}
	function sendAndroidBroadcast($text, $title= "提醒", $arrCustomizedFields = [] ) { 
		return $this->intfAndroid->sendAndroidBroadcast($title,$text, $arrCustomizedFields, $this->production_mode);
	}

	function sendAndroidCustomizedcast($alias,$alias_type,$text, $title = "提醒" ) { 
		return $this->intfAndroid->sendAndroidCustomizedcast($alias,$alias_type,$title,$text , $this->production_mode);
	}
	
	
	/*
		iOS 的几个接口
	*/
	
	function sendIOSGroupcast($tag, $alert  ) {
		return $this->intf->sendIOSGroupcast($tag, $alert, $this->production_mode);
	}
	function sendIOSBroadcast( $alert, $arrCustomizedFields = [] ) { 
		return $this->intf->sendIOSBroadcast( $alert, $arrCustomizedFields, $this->production_mode);
	}

	function sendIOSCustomizedcast($alias,$alias_type,$alert  ) { 
		return $this->intf->sendIOSCustomizedcast($alias,$alias_type,$alert, $this->production_mode);
	}
	
	function test( ) { 
		include_once "Umeng/Demo.php";
	}
}
