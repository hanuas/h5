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
                <label for="sl_must_known" class="col-sm-2 control-label">订单ID:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['order_id']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">第三方订单id:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['thirdOrderID']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">支付渠道:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['gateway']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">渠道标识:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['channel']; ?></p>
                </div>
            </div>


            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">游戏名称:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $game_info['game_name']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">游戏APPID:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['appid']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">大区ID:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['areaID']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">服务器ID</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['serverID']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">服名称:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['serverName']; ?></p>
                </div>
            </div>


            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">开天用户ID:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['ktuid']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">游戏账号ID:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['accountID']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">角色ID:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['roleID']; ?></p>
                </div>
            </div>
            
            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">角色名称:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['roleName']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">角色等级:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['roleLevel']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">产品ID:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['completeTime']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">产品名称:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['completeTime']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">产品描述:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['completeTime']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">货币类型:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['currency']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">金额:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['amount']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">真实金额:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['realamount']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">订单状态:</label>
                <div class="col-sm-10">
                    <p class="form-control-static">
                        <?php 
                            if($order_info['payState'] == "1"){
                                echo "未支付";
                            }else if($order_info['payState'] == "2"){
                                echo "支付成功";
                            }else if($order_info['payState'] == "3"){
                                echo "发货成功";
                            }
                        ?>
                    </p>
                </div>
            </div>


            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">下单时间:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['addtime']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">支付时间:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['payOrderTime']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">完成时间:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['completeTime']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">ip地址:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['userip']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">开天扩展参数:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['completeTime']; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label for="sl_must_known" class="col-sm-2 control-label">扩展参数:</label>
                <div class="col-sm-10">
                    <p class="form-control-static"><?php echo $order_info['completeTime']; ?></p>
                </div>
            </div>
            
         
            <br />
            <br />
                
        </form>
       
</div>

<style>
.panel{min-height:400px}
#linemeta_line{margin-left:50px}
.form-control{max-width:600px}
</style>

<?php $this->display('footer.tpl'); ?>
