
<?php Doris\DApp::loadClass("FormBS");  ?>

<script>
js_para=<?php echo( $this->val('js_para') ? ($js_para) : "false" )?>;
js_para2=<?php echo( $this->val('js_para2') ? ($js_para2) : "false" )?>;
</script>
	
<div class="panel panel-default">
		<div class="panel-body">	
			<?php $this->echoBackLink();?>
			<h1>
                <b class="page-title"><?php echo $title;?> </b>
                <b><small class="page-subtitle"><?php echo empty($sub_title)?"":$sub_title;?></small></b>
            </h1>
			<?php 
				//echo empty($bread_crumb)?"":"当前路径：".$bread_crumb;
				$this->pr('bread_crumb');
				if($this->val("form_action")){
					FormBS::out_form_begin("common_dt_form",$this->val("form_action"));
				}
			?>
			
			<table class="table table-striped table-bordered table-hover datatable" id="data_list">
				
				<thead>
				</thead>
				<tfoot>
				</tfoot>
			</table>
			<?php 
				if($this->val("form_action")){
						FormBS::out_form_val("","","the_sels",array("type"=>"hidden","id"=>"the_sels"));
						FormBS::out_form_btn_begin();
							FormBS::out_form_btn_add("&nbsp;&nbsp;&nbsp;提交&nbsp;&nbsp;&nbsp;","submit","submit_theForm");
						FormBS::out_form_btn_end();
					FormBS::out_form_end();
				};
			?>
		</div>

<script src="<?php echo 'static/dt_js/'.$js; ?>"></script>

</div>

<style>
    input[type='text'],select,input[type="password"]{
    background-color: #FFFFFF;
    border: 1px solid #CCCCCC;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    color: #555555;
    font-size: 14px;
    height: 32px;
    line-height: 1.42857;
    padding: 6px 12px;
    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
    vertical-align: middle;
    width: 100%;
    }
    #data_list_filter input{
        width:170px;
    }
    .td_center{ text-align:center}
    div .DTE_Field_Error{padding-top:3px;font-size:14px}
    input[type='checkbox']{ margin-right:5px}
</style>

