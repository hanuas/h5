<!-- 先引用 main 布局文件， -->
<?php $this->beginContent('test/main.tpl');?>
	<div style="">
		<span >wiget BEGIN</span>
	 (<?php echo $test;?>)
		<span> <?php echo($dorisContent); ?></span>
		<span >wiget END</span>
	</div>
<?php $this->endContent();?>