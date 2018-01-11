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
        <form role="form" id="linemeta_line" class="form-horizontal text-left" method="post" action="/index.php?m=game_manage&c=gift&a=addGift&type=normal&game_id=<?php echo $_GET['game_id']; ?>" enctype="multipart/form-data">

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包名称<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="gift_title"  class="form-control"  required value="" ></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包领取方式<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                    <select name="get_type" style="width:30%" id="get_type">
                        <option value="normal">礼包码</option>
                        <option value="union_code">统一礼包码</option>
                        <option value="qq_group_num">QQ群</option>
                    </select>
                    &nbsp;&nbsp;&nbsp;<font color="red">注意:选择后不可更改</font>
                    </p>
                </div>
            </div>
            <div class="form-group union_code" style="display:none">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包统一码<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="union_code"  class="form-control union_code" value="" ></p>
                </div>
            </div>
            <div class="form-group qq_group_num" style="display:none">
                <label for="sl_must_known" class="col-sm-2 control-label">QQ群号<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="qq_group_num"  class="form-control qq_group_num"  value="" ></p>
                </div>
            </div>
            <div class="form-group qq_group_num" style="display:none">
                <label for="sl_must_known" class="col-sm-2 control-label">QQ群链接<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="qq_group_link"  class="form-control qq_group_num"  value="" ></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包简介<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><textarea name="brief_intro"  class="form-control"  required value="" ></textarea></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包权重<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="gift_weight" style="width:30%"  required value="" >&nbsp;&nbsp;&nbsp;礼包权重，大的靠前</p>
                </div>
            </div>

            <div class="form-group normal">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包码文件:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="file" name="card_file" id="file_upload_1" class="normal" required > <font color="red" >请上传TXT格式文件，每一行为一条礼包码</font></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">开始时间<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="start_time"  class="form-control"  required value="<?php echo date('Y-m-d H:i',time()+3600) ;?>" id="start_time"></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">结束时间<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="end_time"  class="form-control" id="end_time" value="<?php echo date('Y-m-d H:i',time()+3600) ;?>"  required value="" ></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包状态<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        启用&nbsp;:&nbsp;<input type="radio" name="gift_status" value="1">&nbsp;&nbsp;
                        禁用&nbsp;:&nbsp;<input type="radio" name="gift_status" value="0" checked>
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
