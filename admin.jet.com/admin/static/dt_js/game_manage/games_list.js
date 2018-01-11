window.onload=function(){


	genEditorTable('#data_list' ,"index.php?m=game_manage&c=game&a=index_ajax", 
		[
			{
				"label": "游戏ID",
				"name": "id",
				"search": true
			},
			{
				"label": "APPID",
				"name": "appid",
				"editable": false,
				"search": true
			},
			{
				"label": "游戏名称",
				"name": "game_name",
				"search": true
			},
			{
				"label": "操作",
				"name": "operation",
				"search": false
			} 
		],
		{
			"privilege":js_privilege,
			//"allow_print":true,
			//"disable_keyup_search":true,
			"initComplete":  function( settings, json ) {
				 $("#data_list_filter").remove(); 
			}
		}
	);
	
}









