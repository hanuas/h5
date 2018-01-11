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

<link rel="stylesheet" type="text/css" href="/static/js/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="/static/js/multiselect/jqueryUI.css" />
<script src="/static/js/multiselect/jqueryUI.js" type="text/javascript"></script>
<script src="/static/js/multiselect/src/jquery.multiselect.js" type="text/javascript"></script>


<div class="panel panel-default">
        <?php
            if(isset($navs_tpl)){
                $this->display($navs_tpl);
            }
        ?>
		<div class="panel-body">	
			<h1><b class="page-title"><?php echo $title ?></b><b><small class="page-subtitle">&nbsp;&nbsp;&nbsp;<?php echo empty($sub_title)?"":$sub_title;?></small></b></h1>
		</div>
        <br />
        <form role="form" id="linemeta_line" class="form-horizontal text-left" method="post" action="index.php?m=game_manage&c=gameItem&a=index&game_id=<?php echo $_GET['game_id']; ?>" onsubmit="return check_form()">

        <div class="form-group">
		    <label for="sl_must_known" class="col-sm-1 control-label">Package ID <span style="color:red">*</span></label>
		    <div class="col-sm-10">
                <div class="col-sm-9">
                    <input type="text" required="" class="form-control" name="package_id" placeholder=" " value="<?php echo $game_info['package_id']; ?>">
                </div>
            </div>
        </div>
	    <div style="margin-left:0px">
		<div class="panel-body">
            <h3><small style="color:red">注意&nbsp;:&nbsp;添加定价表信息时,每条商品信息必须选中充值渠道下的平台充值! 修改完毕后，请在渠道管理下的修改渠道中提交，才可生效。</small></h3>
			<h3>
                定价表信息&nbsp;&nbsp;<!--<button class="btn btn-success" id="y_create_price" type="button">增加</button>-->
                &nbsp;<!--<a class="btn" href="<?php echo _WEB_FRONT_URL_.'/';?>Resources/third/App_Store_Pricing_Matrix.html" target="_blank">查看Tire定价表</a>-->&nbsp;&nbsp;
                <small><span>自动生成ItemId</span></small>&nbsp;&nbsp;&nbsp;<input type="checkbox" value="1" name="auto_item_id"> 
            </h3>

			
		</div>


        <table class="table pricetable" style="width:100%">
			<tr align="center">
                <td width="5%" align="center">序号</td>
				<td width="23%">RefName</td>
				<td width="10%">Type</td>
				<td width="10%">Price</td>
                <td width="15%">充值渠道</td>
               <!-- <td width="10%">真实货币</td>-->
                <!--<td width="9%">平台币</td>-->
                <!--<td width="9%">列表显示</td>-->
				<td width="10%">游戏货币</td>
				<!--<td width="7%">¥ & $</td>-->
                <td width="22%">ItemId</td>
				<td width="10%"></td>
			</tr>
            <?php 
                if($game_item){
                    foreach($game_item as $key=>$value){
            ?>

			<tr class="y_material_price panel">
                <td rowspan="2" align="center"><h1><?php echo $key+1; ?></h1></td>
				<td>
                    <input type="text" name="reference_name[]" value="<?php echo $value['reference_name']; ?>"  class="form-control" required>
                </td>
				<td>
                <select name="type[]" class="form-control">
                    <option  value="Consumable" <?php if($value['type'] == 'Consumable'){echo 'selected'; } ?> >元宝包</option>
                    <option  value="Non-renewing" <?php if($value['type'] == 'Non-renewing'){echo 'selected'; } ?> >月卡类</option>  
                    <option  value="Non-renewing-w" <?php if($value['type'] == 'Non-renewing-w'){echo 'selected'; } ?> >周卡类</option> 
                </select>
                    <input type="text" name="month[]" value="<?php echo $value['month']; ?>" class="form-control"  <?php if($value['type'] != 'Non-renewing'){echo 'style="display:none"'; } ?>  placeholder="月数">
                    <input type="text" name="week[]" value="<?php echo $value['week']; ?>" class="form-control"  <?php if($value['type'] != 'Non-renewing-w'){echo 'style="display:none"'; } ?>  placeholder="周数">
                </td>
				<td><select class="form-control" name="tire[]">
				<?php for($i=1;$i<88;$i++){ ?>
					<option value="<?php echo $i ?>" <?php if($value['tire'] == $i){echo 'selected';} ?> >Tire<?php echo $i?></option>
				<?php }?>
				</select></td>
                <td align="center">
                <select class="form-control" name="channel_id[]" id="channelList_<?php echo $key; ?>" multiple='multiple'>
                <?php foreach($pay_channels as $channelvalue){ ?>
                		<option value="<?php echo $channelvalue['channel_id'];?>" <?php if(in_array($channelvalue['channel_id'],explode(',',$value['channel_id'])) ){echo 'selected';} ?> ><?php echo $channelvalue['channel_name']; ?></option>
                <?php } ?>
                </select>
                </td>

				<td>
					<input type="text" name="coin[]" value="<?php echo $value['coin']; ?>" class="form-control" placeholder="数量" required  style="display:inline;width:45%;">
					<input type="text" name="coin_unit[]" value="<?php echo $value['coin_unit']; ?>" class="form-control" required placeholder="单位" value="金币" style="display:inline;width:45%;">
				</td>

                <td><input type="text" name="item_id[]" value="<?php echo $value['item_id']; ?>" class="form-control" ></td>
				<td ><button class="btn btn-danger" type="button" onclick="removeSelfPrice(this)">删除此条</button></td>
			</tr>
            <tr class="y_material_price2 panel">
                <td>
                    商品价格(单位:RMB)：
				    <input type="text" name="value[]" value="<?php echo $value['value']; ?>" class="form-control" required  placeholder="" style="display:inline;width:30%;">
                </td>
				<td>¥ & $:
					<input type="text" name="rmbprice[]" value="<?php echo $value['rmbprice']; ?>" class="form-control" placeholder="RMB" style="display:inline;width:35%;" >
					<input type="text" name="dolarprice[]" value="<?php echo $value['dolarprice']; ?>" class="form-control" placeholder="USD" style="display:inline;width:35%;">
				</td>
                <td colspan="2"><input type="text" name="display_name[]" value="<?php echo $value['display_name']; ?>" class="form-control display_name" required value="" placeholder="显示名称"></td>
                <td colspan="3"><input type="text" name="description[]" value="<?php echo $value['description']; ?>" class="form-control description" required value="" placeholder="描述"></td>

            </tr>
            <script>
                    $("#channelList_<?php echo $key; ?>").multiselect({
                        header: false
                    });
            </script>
            <?php   } ?>
            <?php }else{ ?>
			<tr class="y_material_price panel">
                <td rowspan="2" align="center"><h1>1</h1></td>
				<td>
                    <input type="text" name="reference_name[]" class="form-control" required>
                </td>
				<td>
                <select name="type[]" class="form-control">
                    <option value="Consumable">元宝包</option>
                    <option  value="Non-renewing">月卡类</option>
                    <option  value="Non-renewing-w">周卡类</option>
                </select>
                <input type="text" name="month[]" class="form-control"  style="display:none" placeholder="月数">
                <input type="text" name="week[]" class="form-control"  style="display:none" placeholder="周数">
                </td>
				<td><select class="form-control" name="tire[]">
				<?php for($i=1;$i<90;$i++){ ?>
					<option value="<?php echo $i?>">Tire<?php echo $i?></option>
				<?php }?>
				</select></td>
                <td align="center">
                <select class="form-control" name="channel_id[]" id="channelList_0" multiple='multiple'>
                <?php foreach($pay_channels as $value){ ?>
                		<option value="<?php echo $value['channel_id'];?>"><?php echo $value['channel_name']; ?></option>
                <?php } ?>
                </select>
                </td>

				<td>
					<input type="text" name="coin[]" class="form-control" placeholder="数量" required  style="display:inline;width:45%;">
					<input type="text" name="coin_unit[]" class="form-control" required placeholder="单位" value="金币" style="display:inline;width:45%;">
				</td>

                <td><input type="text" name="item_id[]" class="form-control" ></td>
				<td ><button class="btn btn-danger" type="button" onclick="removeSelfPrice(this)">删除此条</button></td>
			</tr>
            <tr class="y_material_price2 panel">
                <td>
                    商品价格(单位:RMB)：
				    <input type="text" name="value[]" class="form-control" required  placeholder="" style="display:inline;width:30%;">
                </td>
				<td>¥ & $:
					<input type="text" name="rmbprice[]" class="form-control" placeholder="RMB" style="display:inline;width:35%;" >
					<input type="text" name="dolarprice[]" class="form-control" placeholder="USD" style="display:inline;width:35%;">
				</td>
                <td colspan="2"><input type="text" name="display_name[]" class="form-control display_name" required value="" placeholder="显示名称"></td>
                <td colspan="3"><input type="text" name="description[]" class="form-control description" required value="" placeholder="描述"></td>

            </tr>
            <?php } ?>
        </table>
        <button class="btn btn-success" id="y_create_price" type="button">增加商品</button>
        <br />
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-4"><button type="submit" class="btn btn-primary">&nbsp;&nbsp;&nbsp;提交&nbsp;&nbsp;&nbsp;</button></div>
        </div>

                
        </form>
       
</div>
<script>
	$("#channelList_0").multiselect({
		header: false
	});
    function createChannelIds(){
        $("input[name='channel_ids[]']").remove();
        $("select[name='channel_id[]']").each(function(i){
            $("#linemeta_line").append("<input type='hidden' name='channel_ids[]' value='"+$(this).val()+"'>"); 


        })
    }

    $('.ui-multiselect').removeAttr('style');
    $('.ui-multiselect-menu').css('width',300);
    $('btn').addClass('btn');

    function createMultiselect(selectId){
        $("#"+selectId).multiselect({
            header: false
        }); 
        $('.ui-multiselect ').removeAttr('style');
        $('btn').addClass('btn');
    }
  
</script>
<script>
	var tirelist = '<?php  for($i=1;$i<100;$i++){ ?><option value="<?php echo $i; ?>"><?php echo 'Tire'.$i; ?></option> <?php } ?>';
	var channellist = '<?php  foreach($pay_channels as $value){ ?><option value="<?php echo $value["channel_id"]; ?>"><?php echo $value["channel_name"]; ?></option> <?php } ?>';
	var currencylist = '';
    var deviceContent = '';
	$("#y_create_price").bind("click",function(){
        var id = new Date().getTime();
        var index = $('.y_material_price').length-1;
        var parentChannelId = $('select[name="channel_id[]"]').eq(index).val();
        var parentUnit = $('input[name="coin_unit[]"]').eq(index).val();
		$(".pricetable").append(returnPriceContent(id));
        $('select[name="channel_id[]"]').eq(index+1).val(parentChannelId);
        $('input[name="coin_unit[]"]').eq(index+1).val(parentUnit);
        bindItemChange();
        changeSelfItemId(index+1);
        createMultiselect(id);
        changeStyle();
	});

     function returnPriceContent(id){
        var $length = $('.y_material_price').length+1;
        return '<tr class="y_material_price panel">'
                +'<td rowspan="2" align="center">'
                +'<h1>'+$length+'</h1>'
                +'</td>'
			    +'<td>'
                +'  <input type="text" name="reference_name[]" class="form-control" required>'
                +'</td>'
				+'<td>'
                +'<select name="type[]" class="form-control"><option   value="Consumable">元宝包</option><option  value="Non-renewing">月卡类</option><option  value="Non-renewing-w">周卡类</option></select>'
                +'<input type="text" name="month[]" class="form-control"  style="display:none" placeholder="月数">'
                +'<input type="text" name="week[]" class="form-control"  style="display:none" placeholder="周数">'
                +'</td>'
				+'<td><select class="form-control" name="tire[]">'
                +tirelist
				+'</select></td>'
                +'<td align="center">'
                +'<select class="form-control" name="channel_id[]" id="'+id+'" multiple="multiple" >'
                +channellist
                +'</select>'
                +'</td>'
				+'<td>'
				+'	<input type="text" name="coin[]" class="form-control" placeholder="数量" required  style="display:inline;width:45%;">'
				+'	<input type="text" name="coin_unit[]" class="form-control" required placeholder="单位" style="display:inline;width:45%;">'
				+'</td>'
                +'<td><input type="text" name="item_id[]" class="form-control" ></td>'
				+'<td><button class="btn btn-danger" type="button" onclick="removeSelfPrice(this)">删除此条</button></td>'
			    +'</tr>'
                +'<tr class="y_material_price2  panel">'
				+'<td >商品价格(单位:RMB)：'
                +' <input type="text" name="value[]" class="form-control" required  placeholder="" style="display:inline;width:30%;"></td>'
				+'<td>¥ & $:'
				+'<input type="text" name="rmbprice[]" class="form-control" placeholder="RMB" style="display:inline;width:35%;" >'
				+'<input type="text" name="dolarprice[]" class="form-control" placeholder="USD" style="display:inline;width:35%;">'
				+'</td>'
                +'<td colspan="2"><input type="text" name="display_name[]" class="form-control display_name" required value="" placeholder="显示名称"></td>'
                +'<td colspan="3"><input type="text" name="description[]" class="form-control description" required value="" placeholder="描述"></td>'
                +'</tr>';
  	 }
	

	function removeSelfPrice(obj){
		if($(".y_material_price").length >1){
            $(obj).parent("td").parent("tr").nextUntil('.y_material_price').remove();
		    $(obj).parent("td").parent("tr").remove();
		}else{
			alert("至少添加一条定价");
		}
	}



	function check_form(){
        createChannelIds();
        var item_id = [];
        var isSelectChannel = true;
		var isItemPass =true;
        $("input[name='channel_ids[]']").each(function(){
			if($(this).val() == 'null'){
				if(!isSelectChannel){return;}
				alert('漏选充值渠道');
				isSelectChannel = false;
			}
		})
        $("input[name='item_id[]']").each(function(){
        	if(!isItemPass){return;}
            if(in_array($(this).val(),item_id)){
                isItemPass = false;
                alert("item_id不能重复");
            }
            item_id.push($(this).val());
            
        })
		if(isSelectChannel && isItemPass ){
			return true;
		}else{
			return false;
		}
	}

    
    function bindItemChange(){
        $("input[name='package_id']").keyup(function(){
            changeItemId();
        })
        $("input[name='package_id']").change(function(){
            changeItemId();
        })
        $("select[name='tire[]']").change(function(){
            var curIndex = $(this).index("select[name='tire[]']");
            changeSelfItemId(curIndex);
        })
        $("select[name='type[]']").bind('change',function(){
            var curIndex = $(this).index("select[name='type[]']");
            changeSelfItemId(curIndex);
            if($(this).val() == 'Non-renewing'){
                $(this).next("input[name='month[]']").show();
                $(this).next().next("input[name='week[]']").hide();
            }else if($(this).val() == 'Non-renewing-w'){
                $(this).next().next("input[name='week[]']").show();
                $(this).next("input[name='month[]']").hide();
            }else{
                $(this).next().next("input[name='week[]']").hide();
                $(this).next("input[name='month[]']").hide();
            }
        })
        $("input[name='coin[]']").keyup(function(){
            var curIndex = $(this).index("input[name='coin[]']");
            changeSelfItemId(curIndex);
        })
        $("input[name='coin[]']").change(function(){
            var curIndex = $(this).index("input[name='coin[]']");
            changeSelfItemId(curIndex);
        }) 
        $("input[name='month[]']").keyup(function(){
            var curIndex = $(this).index("input[name='month[]']");
            changeSelfItemId(curIndex);
        })
        $("input[name='month[]']").change(function(){
            var curIndex = $(this).index("input[name='month[]']");
            changeSelfItemId(curIndex);
        }) 
        $("input[name='week[]']").keyup(function(){
            var curIndex = $(this).index("input[name='week[]']");
            changeSelfItemId(curIndex);
        })
        $("input[name='week[]']").change(function(){
            var curIndex = $(this).index("input[name='week[]']");
            changeSelfItemId(curIndex);
        })
    }

    function changeItemId(){
        if($("input[name='auto_item_id']:checked").val()!='1'){
            return;
        }
        $("input[name='item_id[]']").each(function(i){
            var package_id = $("input[name='package_id']").val();
            var tire = $("select[name='tire[]']").eq(i).val();
            var gold = $("input[name='coin[]']").eq(i).val();
            var month = $("input[name='month[]']").eq(i).val();
            var week = $("input[name='week[]']").eq(i).val();
            if($("select[name='type[]']").eq(i).val() == 'Consumable'){
                var item_id = package_id+'.t'+tire+'g'+gold;
            }else if(($("select[name='type[]']").eq(i).val() == 'Non-renewing')){
                var item_id = package_id+'.m'+month+'t'+tire+'g'+gold;
            }else{
                var item_id = package_id+'.w'+week+'t'+tire+'g'+gold;
            }
            $(this).val(item_id);
        })
    }
    function changeSelfItemId(index){
        if($("input[name='auto_item_id']:checked").val()!='1'){
            return;
        }
        var package_id = $("input[name='package_id']").val();
        var tire = $("select[name='tire[]']").eq(index).val();
        var gold = $("input[name='coin[]']").eq(index).val();
        var month = $("input[name='month[]']").eq(index).val();
        var week = $("input[name='week[]']").eq(index).val();
        if($("select[name='type[]']").eq(index).val() == 'Consumable'){
            var item_id = package_id+'.t'+tire+'g'+gold;
        }else if($("select[name='type[]']").eq(index).val() == 'Non-renewing'){
            var item_id = package_id+'.m'+month+'t'+tire+'g'+gold;
        }else{
            var item_id = package_id+'.w'+week+'t'+tire+'g'+gold;
        }
        $("input[name='item_id[]']").eq(index).val(item_id);
    }
    bindItemChange();
    function changeStyle(){
        $('input[name="reference_name[]"]').css("border","1px solid #FF9912");
        $('select[name="tire[]"]').css("border","1px solid #FF9912");
        $('input[name="coin[]"]').css("border","1px solid #FF9912");
        $('input[name="value[]"]').css("border","1px solid #FF9912");
        $('select[name="currency[]"]').css("border","1px solid #FF9912");
        $('input[name="lcoin[]"]').css("border","1px solid #FF9912");
        $('.description').css("border","1px solid #FF9912");
        $('.display_name').css("border","1px solid #FF9912");
        $('.y_material_price,.y_material_price2').css("border","2px solid #EB8E55");
        //$('.y_material_price').css("border-bottom","0px solid #EB8E55");
        $('.y_material_price2').css("border-top","3px solid #d5d5d5");
        $('.y_material_price2 ').css("border-bottom","2px solid #EB8E55");
     
        
    }
    changeStyle();
  
</script>





<style>
.panel{min-height:400px}
#linemeta_line{margin-left:50px}
.form-control{max-width:600px}

input.form-control{padding-left:2px;padding-right:2px}
select.form-control{padding-left:4px;padding-right:4px}
#linemeta_line{margin-left:40px}
.y_material_price_lang,.y_material_picture{ background-color:#DCDCDC}
.uploadify-queue-item{height:38px;margin-bottom:0px;line-height:20px}
.uploadify-progress{margin-top:4px}
.table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {line-height:30px;padding-left:6px }
.uploadify{float:right}
#linemeta_line{margin-left:10px}
/*以下为多选插件css*/
.ui-widget-content{background: none;z-index:999;background:white;}
.ui-widget-content{border:2px solid #CCCCCC}
.ui-multiselect{line-height:26px}
.ui-icon{background-position:-128px -13px}
.ui-state-default{color: #555555;font-weight:lighter}

</style>

<?php $this->display('footer.tpl'); ?>
