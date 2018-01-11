cur_game_id=false; 
datalist_ajax_url="index.php?m=pay_manage&c=payChannels&a=index_ajax";
data_list_sel='#data_list ';

window.onload=function(){
	
	table_editors = genEditorTable(data_list_sel ,datalist_ajax_url, 
		[
			{
				"width":"10%",
				"label": "渠道ID",
				"name": "channel_id",
                "editable": false,
				"in_editor": false,
                "search":true
			},
			{
				"width":"10%",
				"label": "渠道名称",
				"name": "channel_name",
				"search":true
			},
			{
				"width":"10%",
				"label": "渠道排序(倒序)",
				"name": "channel_seque",
				"search":false
			},
			{
				"width":"10%",
				"label": "是否启用",
				"name": "is_enable",
 				"render":function ( val, type, row ) { 
 					 if(val == 1){
						return "<font color='green'>Y</font>";
					 }else{
						return "<font color='red'>N</font>";
					 }
 				},
				"type": "select",
				"options": [
					{ "label": "Y", "value": "1" },
					{ "label":"N", "value": "0"} 
				],
			},
			{
				"width":"10%",
				"label": "渠道介绍",
				"name": "channel_intro"
			},
			{
				"width":"10%",
				"label": "渠道说明",
				"name": "channel_tips"
			},
/*					
			{
				"width":"10%",
				"label": "渠道名",
				"name": "name",
				"search": myunions,
// 				"render":function ( val, type, row ) { 
// 					 return val +' / ' + row.id;
// 				}
			}, 
*/
		
	
 
		],
		{
			"privilege":js_privilege,
			//"allow_print":true,
			//"disable_keyup_search":true,
			"serverSide":true,
			"tableOnly":false,
			//"row_select":"none",
			"initComplete":  function( settings, json ) {
				 	$("#data_list_filter").remove();
					
				 
			}
		}
	);
	
	oTable = table_editors[0];
	// 按游戏过虑================
}


function applyCiteCasMenu(selctorStr,source,defaultId){
	catalog_filter=$(selctorStr).cas_menu({ 
		source			:	source,  
		rootId			:   0,//
		defaultId		:  defaultId,//
		inputCtlName	:	'cite_ids[]',
		selectAllOption	:	{label: _lan_trans(".LAN_PleaseSelect","请选择"),value:0},
		fnUserOnchange:function(curSel){
			if(!curSel)	curSel=0;
			//更新引用
			cur_game_id=curSel;
			//alert(cur_game_id);
			//TODO: 更新TABLE
			var settings=oTable.fnSettings();
			oTable.DataTable().ajax.url( getNewListAjaxUrl() ).load();
		},
	});
}
// 重新拉取渠道================
function fetchUnionlist( ){
	if(!cur_game_id){
		alert("请选择一个游戏"); 
		return;
	}
	if( $("#fetch-unionlist-btn i").hasClass("fa-spin") ){
		alert("已在加载中，请勿重复点击");
		return;
	}
	
	$("#fetch-unionlist-btn i").addClass("fa-spinner fa-spin red");
	$("#fetch-unionlist-btn span").text("加载中……"); 
	//刷新选中游戏下的渠道列表 
	$.ajax({async: true, type : "get",url:"?m=cps_manage&c=cm_unionlist&a=fetchUnionlist&game_id="+cur_game_id, data:{}, 
		dataType : 'json',  
		success : function(result) {//$("#footer").html(data);
			$("#fetch-unionlist-btn i").removeClass("fa-spinner fa-spin red");
			$("#fetch-unionlist-btn span").text("重新拉取渠道列表");  
			
			if(result.code==0){
				//alert(result.data.game_id);
				alert("数据拉取完成");
				oTable.DataTable().ajax.url( getNewListAjaxUrl() ).load();
			}else{
				alert("错误："+result.msg);
			}
			
			return;
		} 
	});//END ajax
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









