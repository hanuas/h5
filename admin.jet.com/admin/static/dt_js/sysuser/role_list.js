window.onload=function(){


	$("<tr>"
	+"<th style='text-align:center' width=15%><span class='LAN_Role'>角色</span> ID</th>"
	+"<th style='text-align:center' width=15%><span class='LAN_Role'>角色</span><span class='LAN_Name'>名</span></th>"
	+"<th  style='text-align:center' width=60%><span class='LAN_Privilege'>权限</span></th>"
	+"<th  style='text-align:center' width=10%><span class='LAN_Operation'>操作</span></th>"
	+"</tr>").appendTo('#data_list thead');
	var datalist_ajax_url="index.php?m=sysuser&c=role&a=index_ajax";
	var editor = new $.fn.dataTable.Editor( {
		"ajaxUrl": datalist_ajax_url,
		"domTable": "#data_list",
		"fields": [
			{"label": _lan_trans(".LAN_Role","角色")+_lan_trans(".LAN_Name","名"),"name": "role_name"},
			{"label": "权限列表:",
			"name": "sys_privilege[].id",
            "type": "hidden"}
		],
		//"i18n":{"sUrl": "js/DataTablesExt/dataTables.editor.zh_CN.json"}
	} );
	
	branchButtons = getBranchButtons(js_privilege,editor);
	$('#data_list').dataTable({
		"sDom": "Tfrtip",
		"pageLength":20,
		"sAjaxSource":datalist_ajax_url,
		"aoColumns": [
			{"mData": "id","sClass":"td_center"}, 
			{"mData": "role_name","sClass":"td_center"},
			{"mData": "sys_privilege","mRender": "[, ].privilege_name"},
			{"mData": "id","sClass":"td_center","mRender": function(val,type,row){
				return "<a href='?m=sysuser&c=role&a=relatedPrivilege&id="+val+"'><span class='LAN_Update'>修改</span><span class='LAN_Privilege'>权限</span></a>";
			}}
			//{"mRender": dtRender_cutStr(5),"aTargets": "tb_sys_privilege"}
			//{"mData": "privilige_name"},
			//{"mData": "type"}
		],
		"oTableTools": {
			"sRowSelect": "single",
			"aButtons": branchButtons
		},
        "fnInitComplete": function( settings, json ) {
          //editor.field('tb_sys_privilege[].id').update( json.tb_sys_privilege );
        },
		"fnDrawCallback": function( oSettings ) {  reloadLanguage(); },//重新翻译语言
		"oLanguage": {	"sUrl": _getDataTablesLanguageUrl()} //本地化文件位置，函数位于 js/lingua/lan.lingua.js
		
	} );

}