<?php $this->display($menu);  ?>
<script>
var js_para=<?php echo( $this->val('js_para') ? json_encode($js_para ): "[]" )?>;
session_id=js_para["session_id"];
</script>
<script src="/static/js/jquery/jquery-1.9.1.min.js"></script>
<script src="/static/js/md5.js"></script>

<link href="/static/js/uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<script src="/static/js/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="/static/js/Datetimepicker/bootstrap-datetimepicker.min.css" />
<script src="/static/js/Datetimepicker/bootstrap-datetimepicker.min.js"></script>

<script src="/static/page_js/uploadify_pic.js"></script>
<div class="panel panel-default">
        <?php
            if(isset($navs_tpl)){
                $this->display($navs_tpl);
            }
        ?>
		<div class="panel-body">	
			<h1><b class="page-title"><?php echo $title ?></b><b><small class="page-subtitle"><?php echo empty($sub_title)?"":$sub_title;?></small></b></h1>
		</div>
        <br />
        <form role="form" id="linemeta_line" class="form-horizontal text-left" method="post" action="/index.php?m=game_manage&c=gift&a=updateGift&gift_id=<?php echo $_GET['gift_id']; ?>" enctype="multipart/form-data">

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包名称<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="gift_title"  class="form-control"  required value="<?php echo $gift_info['gift_title']; ?>" ></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包领取方式<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                    <select readonly disabled style="width:30%" id="get_type">
                        <option value="normal" <?php if($gift_info['get_type'] == 'normal'){echo 'selected';} ?> >礼包码</option>
                        <option value="union_code" <?php if($gift_info['get_type'] == 'union_code'){echo 'selected';} ?> >统一礼包码</option>
                        <option value="qq_group_num" <?php if($gift_info['get_type'] == 'qq_group_num'){echo 'selected';} ?> >QQ群</option>
                    </select>
                    &nbsp;&nbsp;&nbsp;<font color="red">不可更改</font>
                    </p>
                </div>
            </div>
            <?php if($gift_info['get_type'] == 'union_code'){ ?>
            <div class="form-group union_code">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包统一码<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="union_code" required  class="form-control union_code" value="<?php echo $gift_info['union_code']; ?>" ></p>
                </div>
            </div>
            <?php } ?>

            <?php if($gift_info['get_type'] == 'qq_group_num'){ ?>
            <div class="form-group qq_group_num" >
                <label for="sl_must_known" class="col-sm-2 control-label">QQ群号<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="qq_group_num" required  class="form-control qq_group_num"  value="<?php echo $gift_info['qq_group_num']; ?>" ></p>
                </div>
            </div>
            <div class="form-group qq_group_num">
                <label for="sl_must_known" class="col-sm-2 control-label">QQ群链接<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="qq_group_link" required  class="form-control qq_group_num"  value="<?php echo $gift_info['qq_group_link']; ?>" ></p>
                </div>
            </div>
            <?php } ?>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包简介<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><textarea name="brief_intro"  class="form-control"  required><?php echo $gift_info['brief_intro']; ?></textarea></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包权重<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="gift_weight" style="width:30%"  required value="<?php echo $gift_info['gift_weight']; ?>" >&nbsp;&nbsp;&nbsp;礼包权重，大的靠前</p>
                </div>
            </div>
            <?php if($gift_info['get_type'] == 'normal'){ ?>
            <div class="form-group normal">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包码文件:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="file" name="card_file" id="file_upload_1" class="normal" > <font color="red" >请上传TXT格式文件，每一行为一条礼包码</font></p>
                </div>
            </div>
            <?php } ?>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">开始时间<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="start_time"  class="form-control"  required value="<?php echo $gift_info['start_time']; ?>" id="start_time"></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">结束时间<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="end_time"  class="form-control" id="end_time" value="<?php echo $gift_info['end_time']; ?>"  required value="" ></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包状态<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        启用&nbsp;:&nbsp;<input type="radio" name="gift_status" value="1" <?php if($gift_info['gift_status'] == 1){echo 'checked';} ?> >&nbsp;&nbsp;
                        禁用&nbsp;:&nbsp;<input type="radio" name="gift_status" value="0" <?php if($gift_info['gift_status'] == 0){echo 'checked';} ?>>
                    </p>
                </div>
            </div>
            
            
            <div class="form-group ">
			    <label class="col-sm-3 control-label"></label>
						<div class="col-sm-9">
						 <p class="form-control-static">
                            <button class="btn btn-primary">提交</button>
 						</p>
						</div>
			</div>

                
        </form>
       
</div>
<script>
   //FileUploadify("file_upload_1","file_upload_queue_1","上传图片",session_id,"index.php?m=cps_manage&c=cm_attachment&a=upload_ajax");
$("#start_time").datetimepicker(); 
$("#end_time").datetimepicker();  

$(function(){
    $("select[name='get_type']").bind("change",function(){
        var type = $(this).val();
        if(type == 'normal'){
            $('.normal').show();
            $('.union_code').hide();
            $('.qq_group_num').hide();
            $('.normal').find("input").attr("required",true);
            $('.union_code').find("input").removeAttr("required");
            $('.qq_group_num').find("input").removeAttr("required");
        }else if(type == 'union_code'){
            $('.union_code').show();
            $('.normal').hide();
            $('.qq_group_num').hide();

            $('.union_code').find("input").attr("required",true);
            $('.normal').find("input").removeAttr("required");
            $('.qq_group_num').find("input").removeAttr("required");
        }else{
            $('.qq_group_num').show();
            $('.normal').hide();
            $('.union_code').hide();

            $('.qq_group_num').find("input").attr("required",true);
            $('.normal').find("input").removeAttr("required");
            $('.union_code').find("input").removeAttr("required");
        }
    })
})
</script>
<style>
.panel{min-height:400px}
#linemeta_line{margin-left:50px}
.form-control{max-width:600px}
</style>

<?php $this->display('footer.tpl'); ?>
