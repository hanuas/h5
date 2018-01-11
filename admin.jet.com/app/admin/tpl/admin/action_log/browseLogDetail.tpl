<?php $this->display('header.tpl'); ?>
<?php
?>
<div class="panel panel-default">
<a href="javascript:jump(-1)">&lt; <span class="LAN_Back">返回</span></a>
		<div class="panel-body">	
	        <h1><b class="page-title"><?php echo $this->val('title'); ?></b><b><small class="page-subtitle"><?php echo empty($sub_title)?"":$sub_title;?></small></b></h1>
		</div>
        <form id="linemeta_line" class="form-horizontal text-left"  action="index.php?m=game&c=accessControl&a=checkJointLogin&id=<?php echo $_GET['id']; ?>" method="post" onsubmit="return check_form()">
            
           <div class="form-group ">
			    <label class="col-sm-1 control-label">日志ID :</label>
				    <div class="col-sm-11">
				        <p class="form-control-static"><?php echo $loginfo['id']; ?></p>
					</div>
			</div>
            
           <div class="form-group ">
			    <label class="col-sm-1 control-label">类型 :</label>
				    <div class="col-sm-11">
				        <p class="form-control-static"><?php echo $loginfo['type']; ?></p>
					</div>
			</div>
            
            <div class="form-group ">
			    <label class="col-sm-1 control-label">Title :</label>
				    <div class="col-sm-11">
				        <p class="form-control-static"><?php echo $loginfo['title']; ?></p>
					</div>
			</div>
            <div class="form-group ">
			    <label class="col-sm-1 control-label">操作人 :</label>
				    <div class="col-sm-11">
				        <p class="form-control-static"><?php echo $loginfo['user_name']; ?></p>
					</div>
			</div>
           <div class="form-group ">
			    <label class="col-sm-1 control-label">时间 :</label>
				    <div class="col-sm-11">
				        <p class="form-control-static"><?php echo $loginfo['time']; ?></p>
					</div>
			</div>
           <div class="form-group ">
			    <label class="col-sm-1 control-label">详情 :</label>
				    <div class="col-sm-11">
				        <p class="form-control-static">
                        <?php
                        if(!is_array($loginfo['action_detail'])){
                            echo $loginfo['action_detail'];
                        }else{
                            foreach($loginfo['action_detail']['log'] as $k=>$v){
                                echo $v.'<br /><br />';
                            }
                        }
                        ?>
                        </p>
					</div>
			</div>
          <?php if(is_array(@$loginfo['action_detail']['camera'])){ ?>
           <div class="form-group ">
			    <label class="col-sm-1 control-label">更改前快照 :</label>
				    <div class="col-sm-11">
				        <p class="form-control-static">
                        <?php
                            echo '<pre>';
                            print_r($loginfo['action_detail']['camera']);
                            echo '</pre>';
                            /*
                            foreach(@$loginfo['action_detail']['camera'] as $key=>$value){
                                foreach($value as $k=>$v){
                                    echo $k.'&nbsp;:&nbsp;'.$v.'&nbsp;&nbsp;<br />';
                                }
                                echo '<hr/>';
                            } 
                            */
                            

                        ?>

                        </p>
					</div>
			</div>
         <?php } ?>
          <?php if(is_array(@$loginfo['action_detail']['newcamera'])){ ?>
           <div class="form-group ">
			    <label class="col-sm-1 control-label">当前状态快照 :</label>
				    <div class="col-sm-11">
				        <p class="form-control-static">
                        <?php
                            echo '<pre>';
                            print_r($loginfo['action_detail']['newcamera']);
                            echo '</pre>';
                            /*
                            foreach(@$loginfo['action_detail']['camera'] as $key=>$value){
                                foreach($value as $k=>$v){
                                    echo $k.'&nbsp;:&nbsp;'.$v.'&nbsp;&nbsp;<br />';
                                }
                                echo '<hr/>';
                            } 
                            */
                            

                        ?>

                        </p>
					</div>
			</div>
         <?php } ?>
        </form>

<style>
.form-control{max-width:600px;margin-left:0px}
#linemeta_line{margin-left:40px}
.y_material_price_lang,.y_material_picture{ background-color:#DCDCDC}
#linemeta_line{min-height:400px}

</style>
<?php $this->display('footer.tpl'); ?>

