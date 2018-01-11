
<!-- inline scripts related to this page -->
<script type="text/javascript">	

jQuery(function($) {
	 $(document).on('click', '.toolbar a[data-target]', function(e) {
		e.preventDefault();
		var target = $(this).data('target');
		$('.widget-box.visible').removeClass('visible');//hide others--
		$(target).addClass('visible');//show target
	 });
});
	
//以上代码并没有什么卵用（原 切换注册、登录页用的）
	

</script>