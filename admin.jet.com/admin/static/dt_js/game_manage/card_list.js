datalist_ajax_url="index.php?m=game_manage&c=card&a=index_ajax&gift_id="+js_para.gift_id;
data_list_sel='#data_list ';

window.onload=function(){
	card_used = js_para.card_used;
	table_editors = genEditorTable(data_list_sel ,datalist_ajax_url, 
		[
			{
				"width":"8%",
				"label": "ID",
				"name": "id",
				"search": true
			},
			{
				"width":"10%",
				"label": "礼包码",
				"name": "card_no",
				"search": true
			},
			{
				"width":"10%",
				"label": "创建时间",
				"name": "create_time",
				"search": false
			},
			{
				"width":"10%",
				"label": "是否领取",
				"name": "is_used",
				"search": card_used
			},
			{
				"width":"10%",
				"label": "领取时间",
				"name": "used_time",
				"search": false
			},

			{
				"width":"10%",
				"label": "领取UID",
				"name": "user_id",
				"search": true
			},
			{
				"width":"10%",
				"label": "服务器ID",
				"name": "server_id",
				"search": true,
				"render":function ( val, type, row ) {
					 if(row.server_name){
						return "服务器ID&nbsp;:&nbsp;"+val+"<br/>服务器名&nbsp;:&nbsp;"+row.server_name;
					 }else{
						return val;
					 }
					 	
				}
			},
			{
				"width":"10%",
				"label": "角色ID",
				"name": "server_id",
				"search": true,
				"render":function ( val, type, row ) {
					 if(row.role_name){
						return "角色ID&nbsp;:&nbsp;"+val+"<br/>角色名&nbsp;:&nbsp;"+row.role_name;
					 }else{
						return val;
					 }
					 	
				},
			},
			{
				"width":"10%",
				"label": "备注",
				"name": "card_remark",
				"search": false
			},
	
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







