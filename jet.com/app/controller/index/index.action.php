<?php 
// 这个类没用，加上是为和符合框架的加载规则
class indexController {
	function indexAction(){ 
        header("location:/game/?gameid=321365");
        exit;
		header("Content-type: text/html; charset=utf-8");
 	}
}
?>
<!--

<html>
<head>
<title>ICENTER 所有测试（目录）</title>
</head>
<body>
<h1>ICENTER 所有测试（目录）</h1>
<table >
	<tr><td>用户测试(平台)&nbsp;&nbsp; </td><td><a href="/api/iuserplat_test?d" target=_blank>/api/iuserplat_test?d</a></td></tr>  
	<tr><td>支付测试(平台)&nbsp;&nbsp; </td><td><a href="/api/pay_test?d" target=_blank>/api/pay_test?d</a></td></tr>  
</table>
</body>
</html>
-->