datalist_ajax_url="index.php?m=game_manage&c=gameNews&a=index_ajax&game_id="+js_para.game_id;
data_list_sel='#data_list ';

window.onload=function(){
	//gift_type = js_para.gift_type;
	n_status = js_para.n_status;
	//console.log(n_status);
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
				"label": "标题",
				"name": "title",
				"search": true
			},
			{
				"width":"10%",
				"label": "权重",
				"name": "weight",
				"search": false
			},
			{
				"width":"10%",
				"label": "添加时间",
				"name": "add_time",
				"search": false
			},
			{
				"width":"10%",
				"label": "状态",
				"name": "status",
				"render":function ( val, type, row ) {
					 if(val == 1){
						return "显示";
					 }else if(val == 0){
						return "隐藏";
					 }
					 	
				},
				"search": n_status
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







