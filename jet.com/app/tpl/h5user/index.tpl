<div class="row">
		<div class="col-md-6 col-md-offset-3">
			
			
			<form role="form "class="form-horizontal"  action="/h5user/login" method ="post" onsubmit="return check_login()">
				<div class="panel panel-default well span5 center login-box ">
					<div class="alert alert-warning">
						<center>
							<div class="page-header">
							  <img src="/img/logo.png" alt="..." style="width:80%;">
							  <h1 ></h1>
							</div>
							
						</center>
						
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="mobile">手机号码</label>
						<div class="col-sm-8">
						  <input type="text" required placeholder="MOBILE" name="mobile" id="mobile" class="form-control" required>
						</div>
						
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="password">密码</label>
						<div class="col-sm-8">
						  <input type="password" placeholder="PASSWORD" name="password" id="password" class="form-control" required>
						</div>
					
					</div>
					
					<?php if( !empty($_SESSION['use_vcode']) ){?>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="password">验证码</label>
						<div class="col-sm-8">
						  <input type="text" placeholder="verification code" name="vcode" id="vcode" class="form-control" required style="width:50%;display:inline">
                          <img src="/h5user/createcode" onclick="javascript:this.src='/h5user/createcode?tm='+Math.random();" style="height:35px">
						</div>
					
					</div>
                    <br />
					<?php }?>
                    
					  
					<div class="form-group">
						<div class="col-sm-3  col-sm-offset-3">
							<button class="btn btn-primary " type="submit" name="submit" value="1">&nbsp;&nbsp;&nbsp;登陆&nbsp;&nbsp;&nbsp;</button>&nbsp;
						</div>
						
						<div class="col-sm-3  col-sm-offset-1">
							
							<button class="btn " type="reset">&nbsp;&nbsp;&nbsp;注册&nbsp;&nbsp;&nbsp;</button>
						</div>
					</div>
					
				</div>
			</form>
			
			
		</div>
	</div>
<script type="text/javascript">
	
// 	function check_login() {
//         var password = $("input[name='secret']").val();
//         if(password.length != 32){
// 		    var md5pass=hex_md5(password);
//         }else{
//             var md5pass = password;
//         }
// 		$("input[name='secret']").val(md5pass);
// 		return true;
// 	}

</script>