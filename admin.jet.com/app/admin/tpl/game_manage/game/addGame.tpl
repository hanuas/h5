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
<link rel="stylesheet" href="/static/assets/css/font-awesome.css">
<link href="/static/js/dropzone/dropzone.css" rel="stylesheet" type="text/css" />
<script src="/static/page_js/uploadify_pic.js"></script>
<script src="/static/js/dropzone/dropzone.js"></script>
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
        <form role="form" id="linemeta_line" class="form-horizontal text-left" method="post" action="index.php?m=game_manage&c=game&a=addGame" enctype="multipart/form-data">

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">游戏名称<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="game_name"  class="form-control"  required value="" ></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">APPID<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="appid"  class="form-control"  required value="" ></p>
                </div>
            </div>
            <!--
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">url<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="url"  class="form-control"  required value="" ></p>
                </div>
            </div>
            
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">type<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        <select name="type" class="form-control">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select></p>
                </div>
            </div>
            
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">dc_appid<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="dc_appid"  class="form-control"  required value="" ></p>
                </div>
            </div>
            -->
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">td_appid<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="td_appid"  class="form-control"  required value="" ></p>
                </div>
            </div>
            <!--
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">进入游戏URL<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="entry_url"  class="form-control"  required value="" ></p>
                </div>
            </div>
            -->
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">游戏内嵌页地址<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="content_url"  class="form-control"  required value="" ></p>
                </div>
            </div>
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">平台游戏地址<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="game_url"  class="form-control"  required value="" ></p>
                </div>
            </div>
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">权重<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="weight"  class="form-control"  required value="0" ></p>
                </div>
            </div>
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">游戏简介<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        <textarea type="text" name="brief_intro"  class="form-control"  required >
                        </textarea>
                    </p>
                </div>
            </div>            
            <!--
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">Token传递类型<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        <select name="token_type" class="form-control">
                            <option value="1">UserToken</option>
                            <option value="0">Token</option>
                        </select>
                    </p>
                </div>
            </div>
            -->
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">是否为横屏游戏<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        否&nbsp;&nbsp;<input type="radio" name="orientation" value="0" checked >
                        是&nbsp;&nbsp;<input type="radio" name="orientation" value="1">
                    </p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">是否显示微信菜单<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        否&nbsp;&nbsp;<input type="radio" name="wx_option" value="0" checked >
                        是&nbsp;&nbsp;<input type="radio" name="wx_option" value="1">
                    </p>
                </div>
            </div>
            <!--
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">是否可使用优惠券<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        否&nbsp;&nbsp;<input type="radio" name="use_vuconpon" value="0" checked >
                        是&nbsp;&nbsp;<input type="radio" name="use_vuconpon" value="1">
                    </p>
                </div>
            </div>
            -->
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">是否独家<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        否&nbsp;&nbsp;<input type="radio" name="is_exclusive" value="0" checked >
                        是&nbsp;&nbsp;<input type="radio" name="is_exclusive" value="1">
                    </p>
                </div>
            </div>
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">是否有礼包<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        否&nbsp;&nbsp;<input type="radio" name="is_gift" value="0" checked >
                        是&nbsp;&nbsp;<input type="radio" name="is_gift" value="1">
                    </p>
                </div>
            </div>
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">是否新游尝鲜<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        否&nbsp;&nbsp;<input type="radio" name="is_new" value="0" checked >
                        是&nbsp;&nbsp;<input type="radio" name="is_new" value="1">
                    </p>
                </div>
            </div>
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">是否必玩爆款<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        否&nbsp;&nbsp;<input type="radio" name="is_hot" value="0" checked >
                        是&nbsp;&nbsp;<input type="radio" name="is_hot" value="1">
                    </p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">桌面icon<span style="color:red">*</span></label>
                <div class="col-sm-10 dropzone dz-clickable" id="file_upload_1" style="width:200px;height:200px;border:1px solid gray">
                    <input type="hidden" name="file_path" class="file_upload_1">
                    <!--
                    <img class="file_upload_1" style="max-width:200px;max-height:200px;" src="<?php echo $game_info['desktop_icon']; ?>" />
                    -->
                </div>
            </div>

            
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">游戏icon<span style="color:red">*</span></label>
                <div class="col-sm-10 dropzone dz-clickable" id="file_upload_2" style="width:200px;height:200px;border:1px solid gray">
                    <input type="hidden" name="icon" class="file_upload_2">
                    <!--<img class="file_upload_2" style="max-width:200px;max-height:200px;" src="<?php echo $game_info['icon']; ?>">-->
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
   //applyImgUploadify("file_upload_1","file_upload_queue_1","上传图片",session_id,"index.php?m=game_manage&c=game&a=upload_ajax")
   //applyImgUploadify("file_upload_2","file_upload_queue_2","上传图片",session_id,"index.php?m=game_manage&c=game&a=upload_ajax")
  
    createDropzone("file_upload_1","index.php?m=game_manage&c=game&a=upload_ajax_dropzon","Filedata",'');
    createDropzone("file_upload_2","index.php?m=game_manage&c=game&a=upload_ajax_dropzon","Filedata",'');
</script>
<style>
.panel{min-height:400px}
#linemeta_line{margin-left:50px}
.form-control{max-width:600px}
</style>

<?php $this->display('footer.tpl'); ?>
