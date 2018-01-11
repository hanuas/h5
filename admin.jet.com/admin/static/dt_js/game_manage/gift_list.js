datalist_ajax_url="index.php?m=game_manage&c=gift&a=index_ajax&game_id="+js_para.game_id;
data_list_sel='#data_list ';

window.onload=function(){
	gift_type = js_para.gift_type;
	gift_status = js_para.gift_status;
	table_editors = genEditorTable(data_list_sel ,datalist_ajax_url, 
		[
			{
				"width":"8%",
				"label": "礼包ID",
				"name": "gift.gift_id",
				"search": true
			},
			{
				"width":"10%",
				"label": "礼包Title",
				"name": "gift.gift_title",
				"search": true
			},

			{
				"width":"10%",
				"label": "礼包类型",
				"name": "gift.get_type",
				"render":function ( val, type, row ) {
					 if(val == "normal"){
						return "普通礼包";
					 }else if(val == "qq_group_num"){
						return "QQ群礼包";
					 }else if(val == "point"){
						return "积分礼包";
					 }else if(val == "vip"){
						return "VIP礼包";
					 }else if(val == "union_code"){
						return "唯一码礼包";
					 }
					 	
				},
				"search":gift_type
			},
			{
				"width":"5%",
				"label": "权重",
				"name": "gift.gift_weight",
				"search": false
			},
			{
				"width":"8%",
				"label": "礼包启用",
				"name": "gift.gift_status",
				"render":function ( val, type, row ) {
					 if(val == "1"){
						return "<font color='green'>Y</font>";
					 }else{
						return "<font color='red'>N</font>";
					 }
					 	
				},
				"search": gift_status
			},
			{
				"width":"10%",
				"label": "开始时间",
				"name": "gift.start_time",
				"search": false
			},
			{
				"width":"10%",
				"label": "结束时间",
				"name": "gift.end_time",
				"search": false
			},
			{
				"width":"15%",
				"label": "礼包简介",
				"name": "gift.brief_intro",
				"search": false
			},
			{
				"width":"15%",
				"label": "操作",
				"name": "operation",
				"search": false
			} 
		],
		{
			"privilege":js_privilege,
			//"allow_print":true,
			"disable_keyup_search":false,
			"serverSide":true,
			"tableOnly":true,
			"row_select":"none",
			"initComplete":  function( settings, json ) {
				 	$("#data_list_filter").remove();
					
				 
			}
		}
	);
	oTable = table_editors[0];
}



// 重新生成 URL
function getNewListAjaxUrl(){
	var tmp_para="";
	if(!!cur_game_id  ){
		tmp_para="&game_id="+cur_game_id ;
	} 
	var newUrl=datalist_ajax_url+tmp_para;
	//alert(newUrl);
	return newUrl;
}







