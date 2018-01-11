<?php 

/**
  	@自动化测试工具类集
  	
  	@by 乔成磊
  	
 	@Classes: 	
 		Test  		  测试工具类(用户仅使用此类的接口)
 		TestRule	  测试规则
 		TestRulePath  测试规则中的索引路径规则
  	
  	@Test中的testTicketCS函数
  	//TICKETCS 测试函数
	public function testTicketCS($interface,$para,$name="",$rules=false)
		$interface 要测试的服务器的接口
		$para： 向服务器传递的参数
		$name： 本测试的名称
		$rules：本地校验规则，
			1）当值为false时忽略本地校验
			2）当值为非空时，格式为[ index_path=>rule, …… ]
				index_path中
					+表示前面这个索引必须存在，- 表示必须不能有前面的索引，? 表示可有可无
				如index_path为："game+qcl-list+cn?dota-msg-" 假设要校验的数据为 data
					则合法的数据有：
						data[game][list][cn] 
						data[game][list]
					不合法的数据有：
						data[game] //game下没有list的子元素
						data[game][qcl]
						data[game][list][cn][dota]
						data[game][list][cn][msg]
	
				rule可选值为：
				TRule_NoCheck 
				TRule_CheckIndex //该值被定义为1。 其实只要rule非0 则肯定会校验 datapath
				TRule_NoEmpty 
				TRule_MustArray 

				[TRule_Length		, $len]  //数组时是元素的个数，字符串时是串的长度
				[TRule_LengthMin	, $len] 
				[TRule_LengthMax	, $len] 
				[TRule_Equal		, $rule_data] 
				[TRule_Identical	, $rule_data] 
				[TRule_NotEqual		, $rule_data] 
				[TRule_NotIdentical	, $rule_data] 
				[TRule_Greater		, $rule_data]  //要大于rule_data
				[TRule_GreaterEqual	, $rule_data]  
				[TRule_LessThan		, $rule_data] 
				[TRule_LessEqual	, $rule_data]  
 */
 
 
////================================== TestRule Defines ======================================////

//错误码
define("TErr_PathFormat"				,711);
define("TErr_LackPathData"				,712);
define("TErr_ReturnedForbiddenData"		,713);

define("TErr_RuleCheckLackPara"	,700);
define("TErr_CheckIndex"	,710);
define("TErr_NoEmpty"		,720);
define("TErr_MustArray"		,730);
define("TErr_Length"		,740);
define("TErr_Assert"		,750);

//校验规则类型
define("TRule_NoCheck"		,0);
define("TRule_CheckIndex"	,1);
define("TRule_NoEmpty"		,2);
define("TRule_MustArray"	,3);
define("TRule_Length"		,4);
define("TRule_LengthMin"	,5);
define("TRule_LengthMax"	,6);

define("TRule_TimeFormat"	,7);

define("TRule_Equal"		,"==");
define("TRule_Identical"	,"===");
define("TRule_NotEqual"		,"!=");
define("TRule_NotIdentical" ,"!==");

define("TRule_Greater"		,">");
define("TRule_GreaterEqual"	,">=");
define("TRule_LessThan"		,"<");
define("TRule_LessEqual"	,"<=");



class Test_Test extends Test{}

class Test {

	public function __construct(){
		header("Content-type: text/html; charset=utf-8");
		$this->resetIndex();
		$this->resetSubIndex();
		//$this->app_key=APP_KEY;
	}

	//显示模式。以下前四个函数互斥
	public function displaySimple(){$this->displayConf(false,false);}
	public function displayDetail(){$this->displayConf(true,true);}
	public function displayDetailFailed(){$this->displayConf(false,true);}
	public function displayConf($detail=false,$detailFailed=true){$this->outputDetail=$detail; $this->outputDetailFailed=$detailFailed;}
	public function displayDetailOnce(){return $this->outputDetailOnce=true;}
	
	//显示序号相关
	public function beginSubTest(){$this->useSubIndex=true; $this->resetSubIndex();}
	public function endSubTest(){$this->useSubIndex=false; $this->resetSubIndex();}
	public function resetIndex(){$this->index=0;}
	public function resetSubIndex(){$this->subindex=0;}
	public function addIndex($step=1){$this->index+=$step;$this->resetSubIndex();return $this->index;}
	public function addSubIndex($step=1){$this->subindex+=$step;return $this->subindex;}
	
	//服务器URL
	public function setHost($url){return $this->serverHost=$url;}
	public function setHostOnce($url){ $this->serverHostOnce=$url; return $this->useServerHostOnce=true;}
	public function getHost(){return $this->serverHost;}
	
	//设设置测试标题
	public function setTitle($title){return $this->title=$title;}
	//服务器返回码的设置
	public function setServerSuccessCode($code){$this->serverSuccessCode=$code;}
	public function setServerSuccessCodeOnce($code){
		$this->serverSuccessCodeOnce=$code; 
		return $this->useServerSuccessCodeOnce=true;
	}
	
	//设置UID,  TicketCS 或 TicketBS 时要用到
	public function setUid($uid){return $this->uid=$uid;}
	public function setUidOnce($uid){$this->uidOnce=$uid; return $this->useUidOnce=true;}
	public function getUid(){return $this->uid;}
	
	//当测试的是 TicketCS 或 TicketBS 时，要传递的加密key
	public function setAppKey($app_key){return $this->app_key=$app_key;}
	public function getAppKey(){return $this->app_key;}
	
	//当测试的是 POST 时,要设置返回码 code、msg、data的名称
	public function setRecvInfoOnREST($codeName,$msgName,$dataName){
		$this->codeNameOnPost=$codeName;
		$this->msgNameOnPost=$msgName;
		$this->dataNameOnPost=$dataName;
	}
	
	//测试入口
	public function testTicketCS($interface,$para,$name="",$rules=false){return $this->runTest($interface,$para,$name,$rules,"TicketCS");}
	public function testTicketBS($interface,$para,$name="",$rules=false){return $this->runTest($interface,$para,$name,$rules,"TicketBS");}
	public function testPOST($interface,$para,$name="",$rules=false){return $this->runTest($interface,$para,$name,$rules,"POST");}

	///////////////
	private $outputDetail=true;
	private $outputDetailFailed=true;//在出错时输出详细信息
	private $outputDetailOnce=false;//当为true时，输出一次详细信息，之后自动置为false
	
	private $serverHost ;
	private $serverHostOnce;
	private $useServerHostOnce=false;
	
	private $uid;
	private $uidOnce;
	private $useUidOnce=false;
	
	private $app_key;
	
	private $codeNameOnPost="code";
	private $msgNameOnPost="message";
	private $dataNameOnPost="data";
	
	private $title;
	
	
	private $justInterface=false;
	//索引与计数
	private $index=0;
	private $subindex=1;
	private $useSubIndex=false;
	private $testedCount=0;
	private $testedSuccessedCount=0;
	private $testedFailedCount=0;
	//表格输出相关的变量
	private $maxCols=9;
		
	//服务器返回码的设置
	private $serverSuccessCode=0;
	private $serverSuccessCodeOnce=0;
	private $useServerSuccessCodeOnce=false;
	
	public function beginTest(){
		$this->testedCount=0;
		$this->testedSuccessedCount=0;
		$this->testedFailedCount=0;
		echo "<html><title>$this->title</title><body>";
		$style=<<<STYLE
		 	<style>
		 	.title{font-size:24pt;line-height:45pt;}
		 	.subtitle{font-size:14pt;line-height:14pt;}
		 	.test_module{
		 		font-size:25 pt;
		 		font-weight:bold;
		 		line-height:50px;
		 		}
		 	.msg{
		 		font-size:20 pt;
		 		font-style:italic;
		 		font-weight:bold;
		 		line-height:40px;
		 		}
		 	pre {
				white-space: pre-wrap; /* css-3 */ 
				white-space: -moz-pre-wrap; /* Mozilla, since 1999 */ 
				white-space: -pre-wrap; /* Opera 4-6 */ 
				white-space: -o-pre-wrap; /* Opera 7 */ 
				word-wrap: break-word; /* Internet Explorer 5.5+ */ 
				overflow:auto;
				} 
		 	.intf{color:blue}
		 	.name{color:gray}
		 	.succ{color:green}
		 	.warning{
		 		color:chocolate
		 		}
		 	.fail{color:red}
			</style>
STYLE;
		echo $style;
		echo "<span class='title'>$this->title</span><br>";
		echo "<table >";
	}
	public function endTest(){
		echo "<span class='subtitle'>测试完成，共进行了 $this->testedCount 次测试。$this->testedSuccessedCount 次成功，$this->testedFailedCount 次失败</span><br>";
	
		echo "</table></body></html>";
		
	}
	
	public function addSeparator($newGroupName=""){
		//echo "</table><table ><tr><td colspan=".$this->maxCols.">$newGroupName</td></tr>";
	
		$this->addRow("<span class='test_module'>$newGroupName</span>",2);
	}
	
	public function runTest($interface,$para,$name="",$rules=false,$deliverType="POST"){
		if(!$interface) $interface=$this->justInterface; //如果没传，就用上次的inerface（用于子测试）
		if(!$interface) {echo "请填写interface参数";exit(0);}
		
		$uid=$this->getAutoUid();
		$url=$this->getAutoHost().$interface;
		$index=$this->getAutoIndex();
		
		$this->beginRow($index);
		$recvRaw = null;
		switch($deliverType){
		default:
		case "TicketCS":
			list($recvedOK,$recvCode,$recvMsg,$recvData)
				=$this->runTest_doTicketCS($url,$uid,$para);
			break;
		case "TicketBS":
			list($recvedOK,$recvCode,$recvMsg,$recvData)
				=$this->runTest_doTicketBS($url,$para);
			
			break;
		default :// POST GET PUT DELETE

			list($recvedOK,$recvCode,$recvMsg,$recvData,$recvRaw, $real_url)
				=$this->runTest_do($url,$para,$deliverType);
			break;
		}
		if(!$recvedOK){
			$this->dealResponse_networkFailed();
		}else{
			$this->dealResponse($interface,$name,$para,$recvCode,$recvMsg,$recvData,$rules,$recvRaw, $real_url);
		}
		$this->endRow();
		if($interface)
			$this->justInterface=$interface;
			
		return [$recvedOK,$recvCode,$recvMsg,$recvData];
	}
	
	//不做网络请求，仅显示传进来的数据，及本地校验
	public function justShow($name,$recvCode,$recvMsg,$recvData,$rules,$paras = [],$recvRaw = null){
		$index=$this->getAutoIndex();
		$this->beginRow($index);
		$this->dealResponse("",$name,$paras,$recvCode,$recvMsg,$recvData,$rules,$recvRaw);
		$this->endRow();
	}
	private function runTest_doTicketCS($url,$uid,$para){
		$tk=_class_ex("TicketCS","security");
		list($recvTk,$real_url)  =$tk->send($url,$uid, $this->app_key ,$para);
		$recvedOK	=$recvTk;
		$recvCode	=$recvTk ? $recvTk->getCode()	: -1;
		$recvMsg	=$recvTk ? $recvTk->getMsg()	: "";
		$recvData	=$recvTk ? $recvTk->getData()	: null;
		return [$recvedOK,$recvCode,$recvMsg,$recvData];
	}
	
	private function runTest_doTicketBS($url,$para){
		$tk=_class_ex("TicketBS","security");
		list($recvTk,$real_url) =$tk->send($url,$para);
	//	var_dump($svrSuccCode);
		$recvedOK	=$recvTk;
		$recvCode	=$recvTk ? $recvTk->getCode()	: -1;
		$recvMsg	=$recvTk ? $recvTk->getMessage(): "";
		$recvData	=$recvTk ? $recvTk->getData()	: null;
		
		return [$recvedOK,$recvCode,$recvMsg,$recvData];
	}
	
	

	private function runTest_do($url,$para,$deliverType){
		list($raw,$real_url)=self::send($url,$para,  $deliverType   );
		
		$r=$raw ? json_decode($raw,true):array();
		$recvedOK=$raw;
		$recvCode=$raw? (isset($r[$this->codeNameOnPost])?$r[$this->codeNameOnPost]:TErr_RuleCheckLackPara):-1;
		$recvMsg= $raw? (isset($r[$this->msgNameOnPost])?$r[$this->msgNameOnPost]:"未发现 $this->msgNameOnPost 字段"):"网络不可达";
		$recvData=$raw?( isset($r[$this->dataNameOnPost])?$r[$this->dataNameOnPost]:null):null;
        if($recvData === null && $raw && !$this->dataNameOnPost){
            $recvData = $r;
        }
		#print_r([$recvedOK,$recvCode,$recvMsg,$recvData,$raw,$real_url]);exit;
        
		return [$recvedOK,$recvCode,$recvMsg,$recvData,$raw,$real_url];
	}
	
	
	public static function send($url, $data,$method='POST') {
		$postdata = http_build_query($data);
		
		if(strtoupper($method) =="GET"){
			$jointer = "";
			if( substr($url,-1) != '?' ){//如果末尾字符不是 '?' 则继续判断
				//如果有 '?' 则添加 '&' 否则添加 '?'
				$jointer = ((strpos($url, '?') !== false) ? '&' : '?');
			}
			$url .= $jointer.$postdata;
			$postdata = "";
			
		}
		
		$options = array(
				'http' => array(
						'method' => $method,
						'header' => 'Content-type:application/x-www-form-urlencoded',
						'content' => $postdata,
						'timeout' => 15 * 60 // 超时时间（单位:s）
				)
		);
		$context = stream_context_create($options);
		$result = @file_get_contents($url, false, $context);
		echo " $method";
		return [$result ,$url];
	}
	
	private function dealResponse_networkFailed(){
			$this->testedFailedCount++;
			$this->testedCount++;
			$this->addCell(  "<span class='fail'>Failed</span>");
			$this->addRow("");
			$this->addCell("<pre>网络不可达</pre>",-1);
			
	}
	private function dealResponse($interface,$name,$para,$code,$msg, $data, $rules, $recvRaw = null, $real_url=null){
		$svrSuccCode=$this->getAutoServerSuccessCode();
		
		
		if($code==$svrSuccCode){
			$local_checked=false;
			if($rules){//使用用户定义的规则 本地验证
				 $local_checked=true;
				 list($local_checked_ok,$checkInfo)=TestRule::checkByRules($data,$rules);
				 if(!$local_checked_ok){
				 	$this->testedFailedCount++;
				 	
					$this->addCell("<span class='fail'>$interface</span> <span class='name'>$name</span> <hr>");
					$this->addCell( "<span class='fail'>RuleCheck Failed</span>",-2);
					$this->printDataBlock("<span class='fail'>RuleCheckInfo</span>",$checkInfo);
					$this->test_printDetail($para,$data,$recvRaw,$real_url);
				 }//END ruleCode !=0
			}
			
			if( $local_checked && $local_checked_ok  || !$local_checked){//服务器返回成功，及本地检查也成功
				$this->testedSuccessedCount++;
				$this->addCell("<span class='intf'>$interface</span> <span class='name'>$name</span> <hr>");
				$this->addCell( "<span class='succ'>".($local_checked?"RuleCheck OK":"OK")."</span>",-2);
				//var_dump($this->outputDetail );
				if($this->outputDetail || $this->outputDetailOnce){
					$this->test_printDetail($para,$data,$recvRaw,$real_url);
				}
			}
		}else{//服务器返回失败码
			$this->testedFailedCount++;
			$this->addCell("<span class='fail'>$interface</span> <span class='name'>$name</span> <hr>");
			$this->addCell(  "<span class='fail'>Failed</span>",-2);
			$this->addRow("");
			$this->addCell("<pre>code=".$code. " &nbsp; &nbsp; msg=".$msg."</pre>",-1);
			if($this->outputDetail || $this->outputDetailOnce||$this->outputDetailFailed)
			{
				$this->test_printDetail($para,$data,$recvRaw,$real_url);
			}
		}
		$this->testedCount++;
	}

	private function test_printDetail($requestData,$responseData,$recvRaw = null,$real_url=null){
		$this->printDataBlock("Request Data",["full_url"=>$real_url,"paras"=>$requestData]);
		$this->printDataBlock("Response Data",$responseData);
		if($recvRaw!==null){
			$this->printDataBlock("Raw Response Data",$recvRaw);
			
		}
		$this->outputDetailOnce=false;
	}
	
	private function printDataBlock($name,$data){
		$this->addRow("<hr >");
		$this->addCell("<span  style='max-width:50%;' class='msg'>$name</span>",-1);
		
		$this->addRow("");
		if(is_array($data))
			$print_data=print_r($data, true); 
		else
			$print_data=var_export($data, true);
		$this->addCell('<pre style="max-width:50%;">'.$print_data."</pre>",-1);
		
		//$this->addCell('<div style="max-width:50%;">'.$print_data."</div>",-1);
	}
	
	private function getAutoIndex(){//不可重复调用
		if($this->useSubIndex){	
			if($this->subindex==0)
				$this->addIndex();//第一次子测试的同时，也要累加主测试序号
			$this->addSubIndex();
		}
		else 	
			$this->addIndex();
		$index=$this->index;
		if($this->useSubIndex) $index=$this->index ."<span class='warning'>.".$this->subindex."</span>";
		return $index;
	}
	private function getAutoServerSuccessCode(){//不可重复调用
		if( ! $this->useServerSuccessCodeOnce) return $this->serverSuccessCode;
		$this->useServerSuccessCodeOnce=false;
		return $this->serverSuccessCodeOnce;
	}
	
	private function getAutoHost(){//不可重复调用
		if( ! $this->useServerHostOnce) return $this->serverHost;
		$this->useServerHostOnce=false;
		return $this->serverHostOnce;
	}
	private function getAutoUid(){//不可重复调用
		if( ! $this->useUidOnce) return $this->uid;
		$this->useUidOnce=false;
		return $this->uidOnce;
	}
	private function beginRow($firstTD,$cols=1){
		$cols=$cols>0? $cols: $this->maxCols+$cols;
		echo "<tr><td colspan='$cols'>$firstTD";
	}
	private function addRow($firstTD,$cols=1){
		$cols=$cols>0? $cols: $this->maxCols+$cols;
		echo "</td></tr><tr><td colspan='$cols'>$firstTD";
	}
	private function endRow(){echo "</td></tr>";}
	private function addCell($content,$cols=1){
		$cols=$cols>0? $cols: $this->maxCols+$cols;
		echo "</td><td colspan='$cols' >$content";
	}
	
	public function __destruct()
	{
	}
}


////================================== TestRulePath ======================================////
class TestRulePath{
	private $elements;
	private $idxs;
	private $chks;
	private $curPath="";
	private $code=0;
	private $msg="";
	
	public function __construct($str_path){
		$this->decode($str_path);
	}
	public function decode($str_path){
		$str_path=$this->formatPathString($str_path);
		//	echo ($this->formatPathString($str_path)."<hr>");
		$indexes=array();
		if(preg_match_all('/[^\+\-\?]+/',$str_path , $indexes)){
			$this->idxs=$indexes[0];
		}else{
			return $this->setErr(TErr_PathFormat,"PathFormatError");
		}
		
		$checks=array();
		if(preg_match_all('/[\+\-\?]/',$str_path , $checks)){
			$this->chks=$checks[0];
		}else{
			return $this->setErr(TErr_PathFormat,"PathFormatError");
		}
		
		$cnt=count($this->idxs);
		if($cnt != count($this->chks) ||  $cnt==0 ){
			return $this->setErr(TErr_PathFormat,"PathFormatError");
		}
		$this->elements=array();
		for($i=0;$i<$cnt;$i++){
			$this->elements[$this->idxs[$i].""]  =  $this->chks[$i];
		}
		
		return true;
	}
	
	public function formatPathString($str_path){
		while(strpos($str_path,"++"))	$str_path=str_replace("++","+",$str_path);
		while(strpos($str_path,"--"))	$str_path=str_replace("--","-",$str_path);
		while(strpos($str_path,"++"))	$str_path=str_replace("??","?",$str_path);
		$chLast= substr($str_path, -1);
		if($chLast!='+'&& $chLast!='-'&&$chLast!='?')	$str_path.="+";
		return $str_path;
	}
	
	public function encode(){
		$str_path='';
		foreach($this->elements as $k=>$v)
			$str_path.=$k.$v;
		return $str_path;
	}
	
	public function doCheck($data){
		$curNode=$data;
		$this->curPath="";
		foreach($this->elements as $idx=>$chk){
			#echo " $idx=>$chk, ";

			switch($chk){
			case "+"://required
				//var_dump($curNode);
				if(!isset($curNode[ $idx ]) )
					return $this->setErr(TErr_LackPathData,"数据路径 $this->curPath 中缺少索引: $idx");
					
				$curNode=$curNode[ $idx ];
				$this->curPath.=$idx."/";
				break;
			case "-"://must not
				if(isset($curNode[ $idx ]))
					return $this->setErr(TErr_ReturnedForbiddenData,"数据路径 $this->curPath 中禁止返回索引：$idx");
				break;
			case "?"://option
				if(is_array($curNode) && isset($curNode[ $idx ])){
					$curNode=$curNode[ $idx ];
					$this->curPath.=$idx."/";
				}else{
					return true;
				}
				break;
			}
		}//end for
		return true;
	}
	
	public function getPathData($rootData){
		$curNode=$rootData;
		foreach($this->elements as $idx=>$chk){
			//echo " $idx=>$chk, ";
			//var_dump($curNode);
			switch($chk){
			case "+"://required
			case "?"://读取数据时不再是可选项
				$curNode=$curNode[ $idx ];
				//echo "<hr>";	var_dump($curNode);
				break;
			}
		}//end for
		return $curNode;
	}
	
	public function getCurPath(){return  $this->curPath;}
	public function noError(){return  $this->code==0;}
	public function code(){return $this->code;}
	public function msg(){return $this->msg;}
	private function setErr($code,$msg){
		$this->code=$code;  	$this->msg=$msg;
		return $this->code==0;
	}
	//////////////////////////////////test
	public function dump(){
		$data=var_export($this->elements, true);
		echo "eles====><pre>$data</pre>";
		$data=var_export($this->idxs, true);
		echo "idxs====><pre>$data</pre>";
		$data=var_export($this->chks, true);
		echo "chks====><pre>$data</pre><hr>";
		
	}
	public static function test(){
		
		$data=array("game"=>array("list"=>array("01"=>array("ass"=>1))));
		header("Content-type: text/html; charset=utf-8");

		$p=new TestRulePath("game+qcl-list+01?as-da-");
		//$p->decode("game?list+01?as-da-");
		//$p->dump();
		echo $p->encode()."<br>";

		if($p->doCheck($data)){
			echo "测试成功<br>";
			$subData=$p->getPathData($data);
			var_dump($subData);
		}else{
			echo "测试失败<br>code=$p->code msg=$p->msg";
		}
		echo "<hr>";
	}//end test
}
		
////================================== TestRule ======================================////
class TestRule{
	public static function checkByRules($data,$rules){
		$succ=true;
		$checkInfo=array();
		foreach($rules as $path=>$rule){
			list($code,$msg)=self::checkRule($path,$rule,$data);
			//return array($code,$msg);
			if($code!=0){
				$succ=false;
				$checkInfo[]=array("code"=>$code,"msg"=>$msg);
			}
		}
		return [$succ,$checkInfo];
	}
	public static  function checkRule($str_path,$rule,$rootData){//return array(1,"has");
		$path=new TestRulePath($str_path);
		if(!$path->noError())return array($path->code(),$path->msg());//有可能path语法不对
		
		if($rule){// >=TRule_CheckIndex
			if( ! $path->doCheck($rootData))
				return array($path->code(),$path->msg());
			//var_dump($rule);echo "<hr>";
		}else{//	TRule_NoCheck
			return array(0,"");
		}
		
		//var_dump($rootData);
		$data=$path->getPathData($rootData);

		//var_dump($data);
		$curPath=$path->getCurPath();	
		switch($rule){
		case TRule_NoEmpty:
			if(empty($data))return array(TErr_NoEmpty,"IndexPath[$curPath]: 数据不能为空");
			break;
		case TRule_MustArray:
			if(!is_array($data))return array(TErr_MustArray,"IndexPath[$curPath]: 数据只能为数组");
			break;
		}
		
		if(is_array($rule)){
			list($ruleCode,$ruleData)=$rule;
			$len=null;
			if($ruleCode==TRule_Length || $ruleCode==TRule_LengthMax || $ruleCode==TRule_LengthMin ){
				if(is_array($data))$len=count($data);
				else if(is_string($data))$len=strlen($data);
				else 
					return array(TErr_MustArray,"IndexPath[$curPath]: 数据只能为数组或字符串");
				
			}
			
			


			switch($ruleCode){
			case TRule_Length:
				//var_dump(TErr_MustArray);
				if($len!=$ruleData)return array(TErr_Length,"IndexPath[$curPath]: 长度只能为：$ruleData");
				break;
			case TRule_LengthMax:
				if($len>$ruleData)return array(TErr_Length,"IndexPath[$curPath]: 长度($len)超出了 $ruleData");
				break;
			case TRule_LengthMin:
				if($len<$ruleData)return array(TErr_Length,"IndexPath[$curPath]: 长度($len)至少应为 $ruleData");
				break;
			case TRule_Equal:
				if($data!=$ruleData)return array(TErr_Assert,"IndexPath[$curPath]: Assert($data==$ruleData) Failed!");
				break;
			case TRule_Identical:
				if($data!==$ruleData)return array(TErr_Assert,"IndexPath[$curPath]: Assert($data===$ruleData) Failed!");
				break;
			case TRule_NotEqual:
				if($data==$ruleData)return array(TErr_Assert,"IndexPath[$curPath]: Assert($data!=$ruleData) Failed!");
				break;
			case TRule_NotIdentical:
				if($data===$ruleData)return array(TErr_Assert,"IndexPath[$curPath]: Assert($data!==$ruleData) Failed!");
				break;
			case TRule_Greater:
				if($data <= $ruleData)return array(TErr_Assert,"IndexPath[$curPath]: Assert($data > $ruleData) Failed!");
				break;
			case TRule_GreaterEqual:
				if($data < $ruleData)return array(TErr_Assert,"IndexPath[$curPath]: Assert($data >= $ruleData) Failed!");
				break;
			case TRule_LessThan:
				if($data >= $ruleData)return array(TErr_Assert,"IndexPath[$curPath]: Assert($data < $ruleData) Failed!");
				break;
			case TRule_LessEqual:
				if($data > $ruleData)return array(TErr_Assert,"IndexPath[$curPath]: Assert($data <= $ruleData) Failed!");
				break;
			case TRule_TimeFormat:
				$tmpTime = @strtotime( $data );
				$theRightTimeStr = @date( $ruleData ,$tmpTime );
				if($theRightTimeStr != $data )
					return array(TErr_Assert,"IndexPath[$curPath]: Time format error( $data is of the format: $ruleData)!");
				break;
			}
		
		}
	}//end checkRule
	
	public static function test(){
		header("Content-type: text/html; charset=utf-8");
	
		$data=array("game"=>array("list"=>array("cn"=>3)));
		list($succ,$info)=self::checkByRules($data,array(
			"game+qcl-listssss+cn?as-da-"=>1,
			"game+qcl-list+cn?as-da-"=>array(TRule_NotEqual,3)
		));
		$data=var_export($info,true);
		
		echo "<br>succ=$succ <br>CheckErrorInfo:<pre>$data</pre>";
	}//end test
}

//TestRulePath::test();
//TestRule::test();

