
<!--
*	主模板文件  
*	@ 乔成磊 20150827
-->			

<?php $this->beginContent('main.basic.tpl');?>	
	<body class="no-skin">

		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<!-- #section:basics/sidebar.mobile.toggle -->
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<!-- /section:basics/sidebar.mobile.toggle -->
				<div class="navbar-header pull-left">
					<!-- #section:basics/navbar.layout.brand -->
					<a href="?" class="navbar-brand">
						<i class="ace-icon fa fa-fighter-jet fa-lg "></i>
						<small>
						
							<!-- <img class="nav-user-photo" src="static/img/logo.png" alt="多纷互动" height="25px"> -->
							<!-- <i class="fa fa-cloud"></i> -->
							<small > <?php echo site_title(); ?></small>
						</small>
					</a>

					<!-- /section:basics/navbar.layout.brand -->

					<!-- #section:basics/navbar.toggle -->

					<!-- /section:basics/navbar.toggle -->
				</div>

				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">


						<!-- #section:basics/navbar.user_menu -->
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<?php if( isset($_SESSION['admin']['gender']) && $_SESSION['admin']['gender'] == 'male' ) {?>
									<img class="nav-user-photo" src="static/assets/avatars/user.jpg" alt="User's Photo" />
								<?php }else{ ?>
									<img class="nav-user-photo" src="static/assets/avatars/avatar3.png" alt="User's Photo" />
								<?php }?>
								<span class="user-info">
									<small>欢迎,</small>
									<?php echo $_SESSION['admin']['user_name'] ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="?m=index&c=home&a=modify">
										<i class="ace-icon fa fa-user"></i>
										个人信息
									</a>
								</li>

								<li>
									<a href="?m=index&c=login&a=change_pass">
										<i class="ace-icon fa fa-key"></i>
										修改密码
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="?m=index&c=login&a=logout">
										<i class="ace-icon fa fa-power-off"></i>
										退出
									</a>
								</li>
							</ul>
						</li>

						<!-- /section:basics/navbar.user_menu -->
					</ul>
				</div>

				<!-- /section:basics/navbar.dropdown -->
			</div><!-- /.navbar-container -->
		</div>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar                  responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts" >
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						    <!-- #section:basics/sidebar.layout.shortcuts -->
						    
						    <?php 
						  		$ext = @$_SESSION['admin']['extend'];
								$json=json_decode($ext);
								//var_dump($json);exit;
								if(!empty($json) && !empty($json->shortcuts) ){
									foreach($json->shortcuts as $shotcut){//开始逐项输出菜单
										$out = "<button class=\"btn {$shotcut->btnClass}\" onclick=\"jump('{$shotcut->url}')\">";
										$out .= " <i class=\"ace-icon {$shotcut->icon}\"></i> ";
										$out .= "</button> ";
										echo $out;
									}
									
								}
						    
						    ?>
						    <!-- 

							<button class="btn btn-success" onclick="jump('?m=virtue&c=dashboard')">
								<i class="ace-icon fa fa-signal"></i>
							</button>
						
							<button class="btn btn-info" onclick="jump('?m=virtue&c=index')">
									<i class="ace-icon fa fa-pencil"></i>
							</button>
						
							<button class="btn btn-warning" onclick="jump('?m=user&c=index')">
										<i class="ace-icon fa fa-users"></i>
							</button>
 -->


					     	<!-- /section:basics/sidebar.layout.shortcuts -->
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->
				
				<?php echo $mainLeftMenu;?>
				
				<?php //include "test/demo_left_menu_tree.tpl";?>

				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>

			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb" id="breadcrumbs_nav">
							
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
							<!--
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="搜索 ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
							-->
							
						</div><!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					
					<div class="page-content">
						
						<?php echo $dorisContent;?>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<?php if(isset($mainFooter))echo $mainFooter;?>
					<!-- /section:basics/footer -->
				</div>
			</div>

			
		</div><!-- /.main-container -->
		

	</body>		
	<?php
		ob_start();//因为这里的main.js.tpl需要在其它JS加载之后才加载，所以这里以变量的形式传递
			//extract($this->vals);
			//include "main.js.tpl";
			$mainPageJsContent  = ob_get_contents();
		ob_end_clean();		
		$this->assign("mainPageJsContent",$mainPageJsContent);//这个可不用加
	?>
<?php $this->endContent();?>