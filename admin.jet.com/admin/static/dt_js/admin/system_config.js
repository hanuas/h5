window.onload=function(){


	$("<tr>"
	+"<th width='20%'><span class='LAN_Group'>组</span></th>"
	+"<th width='15%'><span class='LAN_Name'>名称</span></th>"
	+"<th width='50%'><span class='LAN_Value'>值</span></th>"
	+"<th width='15%'><span class='LAN_Type'>类型</span></th>"
	+"</tr>").appendTo('#data_list thead');

	var datalist_ajax_url="index.php?m=admin&c=system&a=index_ajax";
	var data_list_sel='#data_list ';
	
	var editor = new $.fn.dataTable.Editor( {
		"ajaxUrl": datalist_ajax_url,
		"domTable": data_list_sel,
		"fnDrawCallback": function( oSettings ) {  eloadLanguage(); },//重新翻译语言
		"fields": [
			{"label": _lan_trans(".LAN_Group","组"),"name": "segment","sClass1":"center"},
			{"label": _lan_trans(".LAN_Name","名称"),"name": "name"},
			{"label": _lan_trans(".LAN_Value","值"),"name": "value","type":"textarea"},
			{"label": _lan_trans(".LAN_Type","类型") ,"name": "type",
				"type": "radio",
				"ipOpts": [
					{ "label": "JSON", "value": "json" },
					{ "label": _lan_trans(".LAN_Integer","整形"), "value": "int" },
					{ "label": _lan_trans(".LAN_Float","浮点"), "value": "float" },
					{ "label": _lan_trans(".LAN_String","字符串"), "value": "string"} 
					//,{ "label": "对象", "value": "object" }
				]
			}
		],
		"i18n":{"sUrl": "js/DataTablesExt/dataTables.editor.zh_CN.json"}
	} );
	
// 	editor.on( 'preOpenSince', function ( e, json, data ) {reloadLanguage();
// 		alert( 'New row added' );
// 	} );
	branchButtons = getBranchButtons(js_privilege,editor);
	var oTable=$(data_list_sel).dataTable({
		"sDom": "Tfrtip",
		"pageLength":20,
		"sAjaxSource":datalist_ajax_url,
		"aoColumns": [
			{"mData": "segment"}, //"sClass": "center"  clss名为center
			{"mData": "name"},
			{"mData": "value"},
			{"mData": "type"}
		],
		"oTableTools": {
			"sRowSelect": "single",
			"aButtons": branchButtons
		},
		"fnDrawCallback": function( oSettings ) {  reloadLanguage(); },//重新翻译语言
		"oLanguage": {	"sUrl": _getDataTablesLanguageUrl()} //本地化文件位置，函数位于 js/lingua/lan.lingua.js
		
	} );



	//字段搜索
	var allSegments=[],options='';
	for(var i in js_para){
		options+='<option value="'+js_para[i]+'">'+js_para[i]+'</option>';
	};
	$("<tr>"
	+'<th  colspan="1"><select>'
		+'<option value="" class="LAN_All">全部</option>'
		+ options
		+'</select></th>'
	//+'<th width="20px"  colspan="1"><input type="text" name="search_module" placeholder="输入ID或名称" value="" class="search_init"></th>'
	//+'<th width="20px"  colspan="1"><input type="text" name="search_user" placeholder="用户ID或用户名" value="" class="search_init"></th>'
	+'<th colspan="4"></th>'
	+"</tr>").prependTo(data_list_sel+' thead');

	$(data_list_sel+" thead select").change( function () {
        oTable.fnFilter( this.value, 0 );
    } );
	// $(data_list_sel+'thead input[name="search_module"]').keyup( function () {
//         oTable.fnFilter( this.value, 1 );
//     } );
// 	$(data_list_sel+'thead input[name="search_user"]').keyup( function () {
//         oTable.fnFilter( this.value, 2 );
//     } );
//     
	
	
}