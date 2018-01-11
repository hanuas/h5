
<?php Doris\DApp::loadClass("FormBS");  ?>

<div class="panel panel-default" >
	<div class="panel-heading"><a href="javascript:jump(-1)">< <span class="LAN_Back">返回</span></a> <span class="LAN_ModifyUserinfo">修改管理员个人信息</span></div>
<div class="panel-body" >
	<p >
		<form  role="form "class="form-horizontal text-center"  action="?m=index&c=home&a=modify" method ="post">
			<div class="text-left" style="padding-top:50px">
				
				<div class="form-group ">
					<label class="col-sm-2  control-label LAN_UserName">用户名</label>
					<div class="col-sm-4">
					  <p class="form-control-static"><?php echo $_SESSION['admin']['user_name'] ?></p>
					</div>
				</div>
				<div class="form-group">
						<label for="email" class="col-sm-2 control-label LAN_Email">邮箱</label>
						<div class="col-sm-4">
						  <input type="email" class="form-control" required  id="email" name="info[email]" placeholder="Email"  value='<?php echo $_SESSION['admin']['email'] ?>'>
						</div>
				</div>
				<div class="form-group">
						<label for="tel" class="col-sm-2 control-label LAN_Mobile">手机号</label>
						<div class="col-sm-4">
						  <input type="tel" class="form-control" id="tel"  pattern="^0{0,1}(13[0-9]|145|15[0-9]|153|156|152|18[0-9])[0-9]{8}"  required name="info[phone]" placeholder="Mobile" value='<?php echo $_SESSION['admin']['phone'] ?>'>
						</div>
				</div>
				
				<div class="form-group ">
						<label for="tel" class="col-sm-2 control-label LAN_Gender">性别</label>
						<div class="col-sm-4">
							<div class="btn-group" data-toggle="buttons">
							  <label class="btn btn-warning  y_sex <?php echo ( ($_SESSION['admin']['gender'] == 'male')? "active":""); ?>" >
								<input type="radio" name="info[gender]"   <?php echo ( ($_SESSION['admin']['gender'] == 'male')? "checked":""); ?>  id="gender_male" value='male' > <span class="LAN_Man">男</span>
							  </label>
							  <label class="btn btn-warning y_sex  <?php echo ( ($_SESSION['admin']['gender'] == 'female')? "active":""); ?> " >
								<input type="radio" name="info[gender]" <?php echo ( ($_SESSION['admin']['gender'] == 'female')? "checked":""); ?> id="gender_female" value='female'> <span class="LAN_Woman">女</span>
							  </label>
							</div> 
						</div>
				</div>
				
				<div class="form-group">
						<label for="tel" class="col-sm-2 control-label LAN_Extend">扩展</label>
						<div class="col-sm-8">
							<textarea rows="8" cols="80" name="info[extend]"><?php echo $_SESSION['admin']['extend'] ?></textarea>
						  </div>
				</div>
				
				<div class="form-group">
						<label for="tel" class="col-sm-2 control-label LAN_Extend">扩展示例</label>
						<div class="col-sm-8">
以下扩展定义了快接菜单<b>必须是JSON格式</b>，<a target="_blank" href="http://wiki.qiaochenglei.cn/resources/ace1.3.3/html/buttons.html">更多图标</a> , <a target="_blank" href="http://fontawesome.dashgame.com/">更更多</a>
 <pre>
{
  "shortcuts":[
   { "url":"?m=index&c=home&a=modify", "icon":"glyphicon glyphicon-plus","btnClass":"btn-success"},
   { "url":"?m=virtue&c=index",  "icon":"fa fa-pencil", "btnClass":"btn-info"},
   { "url":"?m=user&c=index",  "icon":"fa fa-users", "btnClass":"btn-warning"}
  ]
}
 </pre>
						</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-4 col-sm-offset-4" >
						<button type="submit"  style="margin:0 auto" class="btn btn-primary ">&nbsp;&nbsp;&nbsp;<span class="LAN_OK">确定</span>&nbsp;&nbsp;&nbsp;</button>
					</div>
				</div>
				
			</div>
        </form>
	</p>
</div>

<script type="text/javascript">

				 $(".y_sex").bind("click",function(){
						$(".y_sex").removeClass("active");
						$(this).addClass("active");
						$("input[name='info[gender]']").removeAttr("checked");
						$(this).children("input").attr("checked","checked");

				})

</script>
