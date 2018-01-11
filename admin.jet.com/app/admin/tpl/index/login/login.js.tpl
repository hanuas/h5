
<script src="static/js/md5.js"></script>		  
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
	
//以上代码并没有什么卵用（功能：切换注册、登录页用的）
	
function check_login() {
	var password = $("input[name='secret']").val();
	if(password.length != 32){
		var md5pass=hex_md5(password);
	}else{
		var md5pass = password;
	}
	$("input[name='secret']").val(md5pass);
	return true;
}

	
</script>

