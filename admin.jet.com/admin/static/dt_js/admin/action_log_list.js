window.onload=function(){
	var type_list = js_para.type_list;
	type_list["_EMPTY_STRING_"]="all";
	table_editor = genEditorTable('#data_list' ,"?m=admin&c=action_log&a=index_ajax", 
		[
		
			{
				"label": "用户名",
				"name": "user_name",
				"width":"15%",
				"search":true
			},
			{
				"label": "分组",
				"name": "category", 
				"width":"10%",
				"search":true
			},
			{
				"label": "类型",
				"name": "type",
				"search": type_list
			},
			{
				"label": "操作概要",
				"name": "title"
			},
			{
				"label": "操作时间",
				"name": "time"
			},
			{
				"label": "查看",
				"name":"id",// "id", 
				"render": function(val,type,row){return '<a href="?m=admin&c=action_log&a=browseLogDetail&id='+val+'"><span class="LAN_ViewDetails">查看详情</span></a>'},
            }
		],
		{
			"privilege":js_privilege,
			"serverSide":true,
			"tableOnly":true,
			"columnDefs":[{orderable:false, targets:[0,1,2,3,4,5]  }],
			"order":[[ 4, 'desc' ]],
			"disable_keyup_search":true,
			"row_select":"none",
			"initComplete":  function( settings, json ) {
				 	$("#data_list_filter").hide(); 
				 
			}
		}
	);//end genEditorTable
	
	oTable = table_editor[0];
	
}
function dt_view(id){window.location.href="?m=action_log&a=browseLogDetail&id="+id;}












