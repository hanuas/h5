<?php   $this->echoBackLink();	?>
<br>
<pre>
<?php   
	if(is_array($var))
		print_r($var);
	else 
		var_dump($var);
		
?> 
</pre>
头像：<img src="http://imback.net/rsc/avatar/<?php echo $var["用户ID"]  ?>.jpg"></img>


