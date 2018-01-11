<!-- 先引用 main 布局文件， -->
<?php $this->beginContent('test/wiget.tpl');?>
	<div style="color:chocolate">
		<span >wiget_level2 BEGIN</span>
	(<?php echo $test;?>)
		<span> <?php echo($dorisContent); ?></span>
		<span >wiget_level2 END</span>
	</div>
<?php $this->endContent();?>