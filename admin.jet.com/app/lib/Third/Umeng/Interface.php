<?php
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidBroadcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidFilecast.php');
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidGroupcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidUnicast.php');
require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidCustomizedcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSBroadcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSFilecast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSGroupcast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSUnicast.php');
require_once(dirname(__FILE__) . '/' . 'notification/ios/IOSCustomizedcast.php');

class UmengInterface {
	protected $appkey           = NULL; 
	protected $appMasterSecret     = NULL;
	protected $timestamp        = NULL;
	protected $validation_token = NULL;

	function __construct($key, $secret) {
		$this->appkey = $key;
		$this->appMasterSecret = $secret;
		$this->timestamp = strval(time());
	}
	function commonSend($sender){
		// ob_start();
// 		$sender->send();	
// 		//$content  = ob_get_contents();
//     	ob_end_clean();


		$content = $sender->send();	
    	$r = json_decode($content, true);
    	$r["status"] = $r["ret"] == "SUCCESS" ? 0 : 1;
    	$r["message"] = $content;
    	return $r;
	}
	function returnException($e){
		$msg = $e->getMessage();
		$data = strchr($msg,"details:");
		$data = json_decode( substr($data,8), true);
    	return [
    		"status" => 2,
    		"data" => $data,
    	 	"message"=>$msg 
    	];
	}
	function sendAndroidBroadcast( $title,$text, $arrCustomizedFields = [],$production_mode = "true") {
		try {
			$brocast = new AndroidBroadcast($title,$text);
			$brocast->setAppMasterSecret($this->appMasterSecret);
			$brocast->setPredefinedKeyValue("appkey",           $this->appkey);
			$brocast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			$brocast->setPredefinedKeyValue("ticker",           "Android broadcast ticker");
			$brocast->setPredefinedKeyValue("title",            $title);
			$brocast->setPredefinedKeyValue("text",             $text);
			$brocast->setPredefinedKeyValue("after_open",       "go_app");
			// Set 'production_mode' to 'false' if it's a test device. 
			// For how to register a test device, please see the developer doc.
			$brocast->setPredefinedKeyValue("production_mode", $production_mode);
			// [optional]Set extra fields
			foreach( $arrCustomizedFields as $k => $v ){ 
				$brocast->setCustomizedField( $k, $v);
			}
			
			$brocast->setExtraField("test", "helloworld");
			return $this->commonSend($brocast); 
		} catch (Exception $e) { 
			return $this->returnException($e);
		}
	}

	function sendAndroidUnicast() {
		try {
			$unicast = new AndroidUnicast();
			$unicast->setAppMasterSecret($this->appMasterSecret);
			$unicast->setPredefinedKeyValue("appkey",           $this->appkey);
			$unicast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// Set your device tokens here
			$unicast->setPredefinedKeyValue("device_tokens",    "xx"); 
			$unicast->setPredefinedKeyValue("ticker",           "Android unicast ticker");
			$unicast->setPredefinedKeyValue("title",            "Android unicast title");
			$unicast->setPredefinedKeyValue("text",             "Android unicast text");
			$unicast->setPredefinedKeyValue("after_open",       "go_app");
			// Set 'production_mode' to 'false' if it's a test device. 
			// For how to register a test device, please see the developer doc.
			$unicast->setPredefinedKeyValue("production_mode", "true");
			// Set extra fields
			$unicast->setExtraField("test", "helloworld");
			return $this->commonSend($unicast); 
		} catch (Exception $e) { 
			return $this->returnException($e);
		}
	}

	function sendAndroidFilecast() {
		try {
			$filecast = new AndroidFilecast();
			$filecast->setAppMasterSecret($this->appMasterSecret);
			$filecast->setPredefinedKeyValue("appkey",           $this->appkey);
			$filecast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			$filecast->setPredefinedKeyValue("ticker",           "Android filecast ticker");
			$filecast->setPredefinedKeyValue("title",            "Android filecast title");
			$filecast->setPredefinedKeyValue("text",             "Android filecast text");
			$filecast->setPredefinedKeyValue("after_open",       "go_app");  //go to app
			print("Uploading file contents, please wait...\r\n");
			// Upload your device tokens, and use '\n' to split them if there are multiple tokens
			$filecast->uploadContents("aa"."\n"."bb");
			return $this->commonSend($filecast); 
		} catch (Exception $e) { 
			return $this->returnException($e);
		}
	}

	function sendAndroidGroupcast($tag, $title, $text, $production_mode = "true") {
		try {
			/* 
		 	 *  Construct the filter condition:
		 	 *  "where": 
		 	 *	{
    	 	 *		"and": 
    	 	 *		[
      	 	 *			{"tag":"test"},
      	 	 *			{"tag":"Test"}
    	 	 *		]
		 	 *	}
		 	 */
			$filter = 	array(
							"where" => 	array(
								    		"and" 	=>  array(
								    						array(
							     								"tag" => $tag
															) 
								     		 			)
								   		)
					  	);
					  
			$groupcast = new AndroidGroupcast();
			$groupcast->setAppMasterSecret($this->appMasterSecret);
			$groupcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$groupcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// Set the filter condition
			$groupcast->setPredefinedKeyValue("filter",           $filter); 
			$groupcast->setPredefinedKeyValue("ticker",           "Android groupcast ticker");
			$groupcast->setPredefinedKeyValue("title",            $title);
			$groupcast->setPredefinedKeyValue("text",             $text);
			$groupcast->setPredefinedKeyValue("after_open",       "go_app");
			// Set 'production_mode' to 'false' if it's a test device. 
			// For how to register a test device, please see the developer doc.
			$groupcast->setPredefinedKeyValue("production_mode",$production_mode );
			return $this->commonSend($groupcast); 
		} catch (Exception $e) { 
			return $this->returnException($e);
		}
	}

	function sendAndroidCustomizedcast($alias,$alias_type,$title,$text, $production_mode = "true") {
		try {
			$customizedcast = new AndroidCustomizedcast();
			$customizedcast->setAppMasterSecret($this->appMasterSecret);
			$customizedcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$customizedcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// Set your alias here, and use comma to split them if there are multiple alias.
			// And if you have many alias, you can also upload a file containing these alias, then 
			// use file_id to send customized notification.
			$customizedcast->setPredefinedKeyValue("alias",            $alias);
			// Set your alias_type here
			$customizedcast->setPredefinedKeyValue("alias_type",       $alias_type);
			$customizedcast->setPredefinedKeyValue("ticker",           "Android customizedcast ticker");
			$customizedcast->setPredefinedKeyValue("title",            $title);
			$customizedcast->setPredefinedKeyValue("text",             $text);
			$customizedcast->setPredefinedKeyValue("after_open",       "go_app");
			return $this->commonSend($customizedcast); 
		} catch (Exception $e) { 
			return $this->returnException($e);
		}
	}

	function sendIOSBroadcast( $alert, $arrCustomizedFields = [],$production_mode = "true") {
		try {
			$brocast = new IOSBroadcast();
			$brocast->setAppMasterSecret($this->appMasterSecret);
			$brocast->setPredefinedKeyValue("appkey",           $this->appkey);
			$brocast->setPredefinedKeyValue("timestamp",        $this->timestamp);

			$brocast->setPredefinedKeyValue("alert", $alert);
			$brocast->setPredefinedKeyValue("badge", 0);
			$brocast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$brocast->setPredefinedKeyValue("production_mode", $production_mode);
			// Set customized fields
			foreach( $arrCustomizedFields as $k => $v ){ 
				$brocast->setCustomizedField( $k, $v);
			}
			//print("Sending broadcast notification, please wait...\r\n");
			//$brocast->send();
			
			return $this->commonSend($brocast);
			//print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			//print("Caught exception: " . $e->getMessage());
			return $this->returnException($e);
		}
	}

	function sendIOSUnicast() {
		try {
			$unicast = new IOSUnicast();
			$unicast->setAppMasterSecret($this->appMasterSecret);
			$unicast->setPredefinedKeyValue("appkey",           $this->appkey);
			$unicast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// Set your device tokens here
			$unicast->setPredefinedKeyValue("device_tokens",    "xx"); 
			$unicast->setPredefinedKeyValue("alert", "IOS 单播测试");
			$unicast->setPredefinedKeyValue("badge", 0);
			$unicast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$unicast->setPredefinedKeyValue("production_mode", "false");
			// Set customized fields
			$unicast->setCustomizedField("test", "helloworld");
			return $this->commonSend($unicast); 
		} catch (Exception $e) { 
			return $this->returnException($e);
		}
	}

	function sendIOSFilecast() {
		try {
			$filecast = new IOSFilecast();
			$filecast->setAppMasterSecret($this->appMasterSecret);
			$filecast->setPredefinedKeyValue("appkey",           $this->appkey);
			$filecast->setPredefinedKeyValue("timestamp",        $this->timestamp);

			$filecast->setPredefinedKeyValue("alert", "IOS 文件播测试");
			$filecast->setPredefinedKeyValue("badge", 0);
			$filecast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$filecast->setPredefinedKeyValue("production_mode", "false");
			print("Uploading file contents, please wait...\r\n");
			// Upload your device tokens, and use '\n' to split them if there are multiple tokens
			$filecast->uploadContents("aa"."\n"."bb");
			return $this->commonSend($filecast); 
		} catch (Exception $e) { 
			return $this->returnException($e);
		}
	}

	function sendIOSGroupcast($tag, $alert, $production_mode = "true") {
		try {
			/* 
		 	 *  Construct the filter condition:
		 	 *  "where": 
		 	 *	{
    	 	 *		"and": 
    	 	 *		[
      	 	 *			{"tag":"iostest"}
    	 	 *		]
		 	 *	}
		 	 */
			$filter = 	array(
							"where" => 	array(
								    		"and" 	=>  array(
								    						array(
							     								"tag" => $tag
															)
								     		 			)
								   		)
					  	);
					  
			$groupcast = new IOSGroupcast();
			$groupcast->setAppMasterSecret($this->appMasterSecret);
			$groupcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$groupcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// Set the filter condition
			$groupcast->setPredefinedKeyValue("filter",           $filter);
			$groupcast->setPredefinedKeyValue("alert",  $alert);
			$groupcast->setPredefinedKeyValue("badge", 0);
			$groupcast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$groupcast->setPredefinedKeyValue("production_mode",  $production_mode );
			return $this->commonSend($groupcast); 
		} catch (Exception $e) { 
			return $this->returnException($e);
		}
	}

	function sendIOSCustomizedcast($alias,$alias_type,$alert, $production_mode = "true") {
		try {
			$customizedcast = new IOSCustomizedcast();
			$customizedcast->setAppMasterSecret($this->appMasterSecret);
			$customizedcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$customizedcast->setPredefinedKeyValue("timestamp",        $this->timestamp);

			// Set your alias here, and use comma to split them if there are multiple alias.
			// And if you have many alias, you can also upload a file containing these alias, then 
			// use file_id to send customized notification.
			$customizedcast->setPredefinedKeyValue("alias", $alias);
			// Set your alias_type here
			$customizedcast->setPredefinedKeyValue("alias_type", $alias_type);
			$customizedcast->setPredefinedKeyValue("alert", $alert);
			$customizedcast->setPredefinedKeyValue("badge", 0);
			$customizedcast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$customizedcast->setPredefinedKeyValue("production_mode", $production_mode );
			return $this->commonSend($customizedcast); 
		} catch (Exception $e) { 
			return $this->returnException($e);
		}
	}
}
