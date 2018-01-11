<?php $this->display($menu);  ?>
<script>
var js_para=<?php echo( $this->val('js_para') ? json_encode($js_para ): "[]" )?>;
session_id=js_para["session_id"];
</script>
<script src="/static/js/jquery/jquery-1.9.1.min.js"></script>
<script src="/static/js/md5.js"></script>
<!--
<link href="/static/js/uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<script src="/static/js/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
-->
<link rel="stylesheet" type="text/css" href="/static/js/Datetimepicker/bootstrap-datetimepicker.min.css" />
<script src="/static/js/Datetimepicker/bootstrap-datetimepicker.min.js"></script>

<link rel="stylesheet" href="/static/assets/css/font-awesome.css">
<link href="/static/js/dropzone/dropzone.css" rel="stylesheet" type="text/css" />
<script src="/static/page_js/uploadify_pic.js"></script>
<script src="/static/js/dropzone/dropzone.js"></script><div class="panel panel-default">

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
        <form role="form" id="linemeta_line" class="form-horizontal text-left" method="post" action="/index.php?m=game_manage&c=gift&a=addGift&type=vip&game_id=<?php echo $_GET['game_id']; ?>" enctype="multipart/form-data">

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包名称<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="gift_title"  class="form-control"  required value="" ></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包获取条件<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                    <select name="vip_get_condition" class="form-control"  id="get_type">
                        <option value="today_recharge">今日充值</option>
                        <option value="vip_level">VIP等级</option>
                    </select>
                    </p>
                </div>
            </div>

            <div class="form-group union_code" >
                <label for="sl_must_known" class="col-sm-2 control-label">条件值<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="vip_get_condition_val" required  class="form-control union_code" value="" ></p>
                </div>
            </div>   
            
            <div class="form-group union_code" >
                <label for="sl_must_known" class="col-sm-2 control-label">礼包获取条件描述<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="vip_get_condition_desc" required   value="" style="width:30%">&nbsp;如:VIP等级达到10级</p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">是否自动领取<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        是&nbsp;:&nbsp;<input type="radio" name="vip_is_get_code" value="1" checked>&nbsp;&nbsp;
                        否&nbsp;:&nbsp;<input type="radio" name="vip_is_get_code" value="0">
                    </p>
                    <font color="red" >注意：同一游戏的VIP礼包是否自动领取必须一致</font>
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


            <div class="form-group normal no_auto_send">
                <label for="sl_must_known" class="col-sm-2 control-label">礼包内容:</label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                    <table class="table itemtable" style="width:75%">
                        <tr align="center">
                            <td>道具名称</td>
                            <td width="15%">数量</td>
                            <td>图片上传</td>
                            <td>操作</td>
                        </tr>
                        <tr class="y_item panel">
                            <td>
                                <input type="text" name="item_title[]" value=""  class="form-control" required>
                            </td>
                            <td>
                                <input type="text" name="item_num[]" value=""  class="form-control" required>
                            </td>

                            <td align="center">
                                <div class="dropzone dz-clickable" id="file_upload_1" style="width:150px;height:150px;padding: 0px 0px; ">
                                <input type="hidden" name="item_icon[]" value=""   class="file_upload_1" >

                            </td>
                            <td align="center"><button class="btn btn-danger" type="button" onclick="removeSelfItem(this)">删除此条</button></td>
                        </tr>
                    </table>
                    <button class="btn btn-success" id="y_create_item" type="button">增加道具</button>

                    </p>
                </div>
            </div>   
            
            <br />
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
   //applyImgUploadify("file_upload_1","file_upload_queue_1","上传图片",session_id,"index.php?m=game_manage&c=game&a=upload_ajax",".png")
    createDropzone("file_upload_1","index.php?m=game_manage&c=game&a=upload_ajax_dropzon","Filedata",'');


    $("#start_time").datetimepicker(); 
    $("#end_time").datetimepicker();  

    function removeSelfItem(obj){
        if($(".y_item").length >1){
            $(obj).parent("td").parent("tr").nextUntil('.y_item').remove();
            $(obj).parent("td").parent("tr").remove();
        }else{
            alert("至少添加一条道具");
        }
    }

	$("#y_create_item").bind("click",function(){
        var id = new Date().getTime();
		$(".itemtable").append(returnItemContent(id));
        fileData = '';
        createDropzone("file_upload_"+id,"index.php?m=game_manage&c=game&a=upload_ajax_dropzon","Filedata",fileData);
	});

     function returnItemContent(id){
        var $length = $('.y_material_price').length+1;
        return '<tr class="y_item panel">'
               +'<td>'
               +'<input type="text" name="item_title[]" value=""  class="form-control" required>'
               +'</td>'
               +'<td>'
               +'<input type="text" name="item_num[]" value=""  class="form-control" required>'
               +'</td>'
               +'<td align="center">'
               +'<div class="dropzone dz-clickable" id="file_upload_'+id+'" style="width:150px;height:150px;padding: 0px 0px; ">'
               +'<input type="hidden" name="item_icon[]" value=""   class="file_upload_'+id+'" >'
               +'</td>'
               +'<td align="center"><button class="btn btn-danger" type="button" onclick="removeSelfItem(this)">删除此条</button></td>'
               +'</tr>';
  	 }
</script>
<style>
.panel{min-height:400px}
#linemeta_line{margin-left:50px}
.form-control{max-width:600px}
</style>

<?php $this->display('footer.tpl'); ?>
