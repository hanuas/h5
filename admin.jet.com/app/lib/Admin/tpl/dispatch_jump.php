<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php if(!$status){ $message=$error;} ?>

<?php if(is_int($jumpUrl) && $jumpUrl<0){ ?>
<script language="JavaScript">
function myrefresh()
{
   history.go(<?php echo $jumpUrl ?> )
}
setTimeout('myrefresh()',<?php echo $waitSecond ?> * 1000); //指定1秒刷新一次
</script>
<?php }else{ ?> 
<meta http-equiv='Refresh' content='1<?php echo $waitSecond; ?>;URL=<?php echo $jumpUrl; ?> '>
<?php } ?> 

<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>提示信息</title>

<style type="text/css">
*{ padding:0; margin:0; font-size:12px}
.showMsg .guery {white-space: pre-wrap; /* css-3 */white-space: -moz-pre-wrap; /* Mozilla, since 1999 */white-space: -pre-wrap; /* Opera 4-6 */white-space: -o-pre-wrap; /* Opera 7 */	word-wrap: break-word; /* Internet Explorer 5.5+ */}
a:link,a:visited{text-decoration:none;color:#0068a6}
a:hover,a:active{color:#ff6600;text-decoration: underline}
.showMsg{border: 1px solid #1e64c8; zoom:1; width:450px; height:174px;position:absolute;top:50%;left:50%;margin:-87px 0 0 -225px}
.showMsg h5{background-image: url(./images/msg.png);background-repeat: no-repeat; color:#fff; padding-left:35px; height:25px; line-height:26px;*line-height:28px; overflow:hidden; font-size:14px; text-align:left}
.showMsg .content{ padding:46px 12px 10px 45px; font-size:14px; height:66px;}
.showMsg .bottom{ background:#e4ecf7; margin: 0 1px 1px 1px;line-height:26px; *line-height:30px; height:26px; text-align:center}
.showMsg .ok,.showMsg .guery{background: url(./images/msg_bg.png) no-repeat 0px -560px;}
<?php if($status){ ?>
.showMsg .guery{background-position: left -560px;}
<?php }else{ ?>
.showMsg .guery{background-position: left -460px;}
<?php } ?>
</style>
</head>
<body>
<div class="showMsg" style="text-align:center">
	<h5>提示信息</h5>
    <?php if($status){ ?>
    <div class="content guery" style="display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline; max-width:280px"><?php echo $message; ?></div>
    <?php }else{ ?>
    <div class="content guery" style="display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline; max-width:280px"><?php echo $error; ?></div>
    <?php } ?> 
    <div class="bottom">
    <?php if(isset($closeWin)){ ?>
	页面将在 <span class="wait"><?php echo $waitSecond; ?> </span> 秒后自动关闭，<a href="javascript:void(0)" onclick="pageJump('<?php echo $jumpUrl; ?>')">如果您的浏览器没有自动关闭，请点击这里</a>
    <?php }else{ ?>
    页面将在 <span class="wait"><?php echo $waitSecond; ?> </span> 秒后自动跳转，<a href="javascript:void(0)" onclick="pageJump('<?php echo $jumpUrl; ?>')">如果您的浏览器没有自动跳转，请点击这里</a>
    <?php } ?>
	
        </div>
</div>
<script style="text/javascript">

function pageJump(url){
	 	if( url*1 <0) {location.href = 'javascript:history.go('+url+')';return;}
		location.href = url;
}
function close_dialog() {
	
}

</script>
</body>
</html>
<!--
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
<title>页面提示</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv='Refresh' content='1111111<?php echo $waitSecond; ?>;URL=<?php echo $jumpUrl; ?> '>

<style>
html, body{margin:0; padding:0; border:0 none;font:14px Tahoma,Verdana;line-height:150%;background:white}
a{text-decoration:none; color:#174B73; border-bottom:1px dashed gray}
a:hover{color:#F60; border-bottom:1px dashed gray}
div.message{margin:10% auto 0px auto;clear:both;padding:5px;border:1px solid silver; text-align:center; width:45%}
span.wait{color:blue;font-weight:bold}
span.error{color:red;font-weight:bold}
span.success{color:blue;font-weight:bold}
div.msg{margin:20px 0px}
</style>

</head>
<body>
<div class="message">
	<div class="msg">
	<?php if($status){ ?>
	<span class="success"><?php echo $msgTitle; ?> <br><?php echo $message; ?> </span>
	<?php }else{ ?>
	<span class="error"><?php echo $msgTitle; ?><br><?php echo $error; ?> </span>
	<?php } ?> 
	</div>
	<div class="tip">
	<?php if(isset($closeWin)){ ?>
		页面将在 <span class="wait"><?php echo $waitSecond; ?> </span> 秒后自动关闭，如果不想等待请点击 <a href="<?php echo $jumpUrl; ?> ">这里</a> 关闭
	<?php }else{ ?>
		页面将在 <span class="wait"><?php echo $waitSecond; ?> </span> 秒后自动跳转，如果不想等待请点击 <a href="<?php echo $jumpUrl; ?> ">这里</a> 跳转
	<?php } ?> 
	</div>
</div>
</body>
</html>
-->