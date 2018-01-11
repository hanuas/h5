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
<script src="/static/js/ckeditor/ckeditor.js"></script>
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
        <form role="form" id="linemeta_line" class="form-horizontal text-left" method="post" action="index.php?m=game_manage&c=gameNews&a=updateGameNews&id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data">

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">资讯标题<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="title"  class="form-control"  required value="<?php echo $news_info['title']; ?>" ></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">资讯状态<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        显示&nbsp;&nbsp;<input type="radio" name="status" value="1" <?php if($news_info['status'] == 1){echo 'checked'; }  ?> >
                        隐藏&nbsp;&nbsp;<input type="radio" name="status" value="0" <?php if($news_info['status'] == 0){echo 'checked'; }  ?> >
                    </p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">权重<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static"><input type="text" name="weight"  class="form-control"  required value="<?php echo $news_info['weight']; ?>" ></p>
                </div>
            </div>


            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">资讯内容<span style="color:red">*</span></label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                    <textarea type="text" name="content"  class="form-control"  required  id="editor"  cols="20" rows="2"><?php echo $news_info['content']; ?></textarea>
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
    CKEDITOR.replace( 'editor',{customConfig : "singConfig.js"});
</script>
<style>
.panel{min-height:400px}
#linemeta_line{margin-left:50px}
.form-control{max-width:600px}
</style>

<?php $this->display('footer.tpl'); ?>
