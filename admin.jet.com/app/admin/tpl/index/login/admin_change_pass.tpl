
<?php Doris\DApp::loadClass("FormBS");  ?>

<div class="panel panel-default" >
	<div class="panel-heading" id=''><a href="javascript:jump(-1)">< <span class="LAN_Back">返回</span></a> <span class="LAN_ModifyPassword">修改密码</span></div>
<div class="panel-body"   style="height:450px">
	<p >
		<form  role="form "class="form-horizontal text-center"  action="?m=index&c=login&a=change_pass" method ="post" onsubmit="return check_form()">
			<div class="text-left" style="padding-top:50px">
				<div class="form-group">
						<label for="oldpassword" class="col-sm-4 control-label"><span class="LAN_Old">原</span> <span class="LAN_Password">密码</span></label>
						<div class="col-sm-4">
						  <input type="password" class="form-control" id="oldpassword"  required name="info[oldpassword]" placeholder="Old Password" >
						</div>
				</div>
				<div class="form-group">
						<label for="password" class="col-sm-4 control-label"><span class="LAN_New">新</span> <span class="LAN_Password">密码</span></label>
						<div class="col-sm-4">
						  <input type="password" class="form-control" id="password" required name="info[password]" placeholder="New Password"  >
						</div>
				</div>
				<div class="form-group">
						<label for="repass" class="col-sm-4 control-label"><span class="LAN_Confirm">确认</span> <span class="LAN_Password">密码</span></label>
						<div class="col-sm-4">
						  <input type="password" class="form-control" id="repass" name="info[repass]" placeholder="Re Confirm"  onChange="checkPasswords()">
						</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4 col-sm-offset-4">
						<button type="submit" class="btn btn-primary "  >&nbsp;&nbsp;&nbsp;<span class="LAN_OK">确定</span>&nbsp;&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;
						<button class="btn btn-warning " onclick="location='./index.php'">&nbsp;&nbsp;&nbsp;<span class="LAN_Cancel">取消</span>&nbsp;&nbsp;&nbsp;</button>
					</div>
				</div>
				
			</div>
        </form>
	</p>
</div>

<script type="text/javascript">
				function checkPasswords() {
			        var password = document.getElementById("password");
			        var repassword = document.getElementById("repass");
			 //        	alert(password.value);
// 			        	alert(repassword.value);
			        if (password.value != repassword.value){
						
			        	repassword.setCustomValidity("两次输入的密码不一致");
			        }else{
                        var oldmd5pass=hex_md5($("#oldpassword").val());
                        $("#oldpassword").val(oldmd5pass);
                        var md5pass = hex_md5($("#password").val());
                        $("#password").val(md5pass);
                        var md5repass =  hex_md5($("#repass").val());
                       
                        $("#repass").val(md5repass);
			        	repassword.setCustomValidity("");
			        }

			    }


</script>
