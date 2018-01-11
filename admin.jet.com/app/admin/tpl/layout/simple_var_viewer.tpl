
<?php $this->beginContent('main.tpl');?>	
	
<pre>

	<?php  
		$this->echoBackLink();
		if(is_array($var))
			print_r($var);
		else 
			var_dump($var);
	?>

</pre>



<?php $this->endContent();?>