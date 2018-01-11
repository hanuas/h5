<?php   $this->echoBackLink();	?>
<br>

建议信息：
<pre>
<?php   
	if(is_array($advise_info))
		print_r($advise_info);
	else 
		var_dump($advise_info);
		
?>

</pre>

 
<form action="?m=user&c=advise&a=replySms&uid=<?php echo $uid;?>&id=<?php echo $id;?>" method="post" 
target="_blank"
> 
	 
	<textarea rows="5" cols="60" name="content" id="content"></textarea>
	<br />
	<input type="submit" name="submit" value="提交" />
</form>
