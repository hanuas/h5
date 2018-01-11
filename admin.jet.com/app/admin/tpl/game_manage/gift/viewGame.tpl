<?php $this->display($menu);  ?>
<script>
var js_para=<?php echo( $this->val('js_para') ? json_encode($js_para ): "[]" )?>;
session_id=js_para["session_id"];
</script>
<script src="/static/js/jquery/jquery-1.9.1.min.js"></script>
<script src="/static/js/md5.js"></script>

<link href="/static/js/uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<script src="/static/js/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
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
        <form role="form" id="linemeta_line" class="form-horizontal text-left" method="post" action="index.php?m=game_manage&c=game&a=updateGame&id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data">

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">游戏名称:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $game_info['game_name']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">APPID:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $game_info['appid']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">url:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $game_info['url']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">type:</label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        <select name="type" class="form-control" disabled>
                            <option value="1" <?php if($game_info['type'] == '1'){echo "selected"; } ?>>1</option>
                            <option value="2" <?php if($game_info['type'] == '2'){echo "selected"; } ?>>2</option>
                            <option value="3" <?php if($game_info['type'] == '3'){echo "selected"; } ?>>3</option>
                        </select></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">dc_appid:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $game_info['dc_appid']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">td_appid:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $game_info['td_appid']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">进入游戏URL:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $game_info['entry_url']; ?></p>
                </div>
            </div>
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">游戏内嵌页地址:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $game_info['content_url']; ?></p>
                </div>
            </div>
            
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">Token传递类型:</label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        <select name="token_type" class="form-control" disabled>
                            <option value="1" <?php if($game_info['token_type'] == '1'){echo "selected"; } ?> >UserToken</option>
                            <option value="0" <?php if($game_info['token_type'] == '0'){echo "selected"; } ?> >Token</option>
                        </select>
                    </p>
                </div>
            </div>
            
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">是否为横屏游戏:</label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        否&nbsp;&nbsp;<input type="radio" disabled name="orientation" value="0" <?php if($game_info['orientation'] == '0'){echo "checked"; } ?> >
                        是&nbsp;&nbsp;<input type="radio" disabled name="orientation" value="1" <?php if($game_info['orientation'] == '1'){echo "checked"; } ?>>
                    </p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">是否支持微信:</label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        否&nbsp;&nbsp;<input type="radio" disabled name="wx_option" value="0" <?php if($game_info['wx_option'] == '0'){echo "checked"; } ?> >
                        是&nbsp;&nbsp;<input type="radio" disabled name="wx_option" value="1" <?php if($game_info['wx_option'] == '1'){echo "checked"; } ?> >
                    </p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">是否可使用优惠券:</label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        否&nbsp;&nbsp;<input type="radio" disabled name="use_vuconpon" value="0" <?php if($game_info['use_vuconpon'] == '0'){echo "checked"; } ?> >
                        是&nbsp;&nbsp;<input type="radio" disabled name="use_vuconpon" value="1" <?php if($game_info['use_vuconpon'] == '1'){echo "checked"; } ?>>
                    </p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">桌面icon:</label>
                <div class="col-sm-10">
                    <input type="hidden" name="file_path" class="file_upload_1">
                    <img class="file_upload_1" style="max-width:200px;max-height:200px;" src="<?php echo $game_info['desktop_icon']; ?>" >
                </div>
            </div>
            <br />
            <br />
                
        </form>
       
</div>
<script>
   //FileUploadify("file_upload_1","file_upload_queue_1","上传图片",session_id,"index.php?m=cps_manage&c=cm_attachment&a=upload_ajax");
   applyImgUploadify("file_upload_1","file_upload_queue_1","上传图片",session_id,"index.php?m=game_manage&c=game&a=upload_ajax")
  
</script>
<style>
.panel{min-height:400px}
#linemeta_line{margin-left:50px}
.form-control{max-width:600px}
</style>

<?php $this->display('footer.tpl'); ?>
