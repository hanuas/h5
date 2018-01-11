window.onload=function(){

	$("<tr>"
	+"<th style='text-align:center' width=10%><span class='LAN_UserName'>用户名</span></th>"
	+"<th style='text-align:center' width=10%><span class='LAN_Nickname'>昵称</span></th>"
	+"<th  style='text-align:center' width=10%>Leader</th>"
	+"<th  style='text-align:center' width=10%><span class='LAN_Mobile'>电话</span></th>"
	+"<th  style='text-align:center' width=10%><span class='LAN_Email'>邮箱</span></th>"
	+"<th  style='text-align:center' width=10%><span class='LAN_Gender'>性别</span></th>"
	+"<th  style='text-align:center' width=20%><span class='LAN_Role'>角色</span></th>"
	+"</tr>").appendTo('#data_list thead');
	
	var datalist_ajax_url="index.php?m=sysuser&c=adminIndex&a=index_ajax";
	var data_list_sel='#data_list ';
	var editor = new $.fn.dataTable.Editor( {
		"ajaxUrl": datalist_ajax_url,
		"domTable": "#data_list",
		"fields": [
			{"label": _lan_trans(".LAN_UserName","用户"),"name": "user_name"},
			{"label": _lan_trans(".LAN_Nickname","昵称"),"name": "nick_name"},
			{"label": _lan_trans(".LAN_Password","密码"),"name": "user_pwd","type":"password"},
			{"label": _lan_trans(".LAN_Mobile","电话号码"),"name": "phone"},
			{"label": _lan_trans(".LAN_Email","邮箱地址"),"name": "email"},
			{"label": _lan_trans(".LAN_User","用户")+_lan_trans(".LAN_Type","类型"),"name": "user_type","default":"admin","type":"hidden"},
			{"label": _lan_trans(".LAN_Gender","性别"),"name": "gender",
				"type": "select",
				"ipOpts": [
					{ "label": _lan_trans(".LAN_Woman","女士"), "value": "female" },
					{ "label": _lan_trans(".LAN_Man","男士"), "value": "male"} 
				]
			},
			{"label": "Leader","name": "leader","type":"select"},				
			{"label": _lan_trans(".LAN_Role","角色"),"name": "sys_role[].id", "type": "checkbox"}

		],
		//"i18n":{"sUrl": "js/DataTablesExt/dataTables.editor.zh_CN.json"}
	} );
	
	branchButtons = getBranchButtons(js_privilege,editor);

 	
    
	var oTable = $(data_list_sel).dataTable({
		"sDom": "Tfrtip",
		"pageLength":20,
		"sAjaxSource":datalist_ajax_url,
		"aoColumns": [
			{"mData": "user_name","sClass":"td_center"}, 
			{"mData": "nick_name","sClass":"td_center"},
			{"mData": "manager.user_name","sClass":"td_center"},
			{"mData": "phone","sClass":"td_center"},
			{"mData": "email","sClass":"td_center"},
			{"mData": "gender","sClass":"td_center"},
			{"mData": "sys_role","mRender": "[, ].role_name","sClass":"td_center"}
			//{"mRender": dtRender_cutStr(5),"aTargets": "tb_sys_privilege"}
			//{"mData": "privilige_name"},
			//{"mData": "type"}
		],
		"oTableTools": {
			"sRowSelect": "single",
			"aButtons":branchButtons
		    //[{"sExtends":"editor_edit"}]
		},
        "fnInitComplete": function( settings, json ) {
            editor.field('sys_role[].id').update( json.sys_role );
			editor.field('leader').update( json.userlist);
        },
		"fnDrawCallback": function( oSettings ) {  reloadLanguage(); },//重新翻译语言
		"oLanguage": {	"sUrl": _getDataTablesLanguageUrl()} //本地化文件位置，函数位于 js/lingua/lan.lingua.js
		
	} );
	//字段搜索
	$("<tr>"
	+'<th  colspan="1"><input type="text" id="searchName" placeholder="" ></th>'
	+'<th  colspan="1"><input type="text" id="searchNickName" placeholder="" ></th>'
	+'<th width="20px"  colspan="1"><input type="text" id="searchLeader" placeholder="" value="" class="search_init"></th>'
	//+'<th width="20px"  colspan="1"><input type="text" name="search_user" placeholder="用户ID或用户名" value="" class="search_init"></th>'
	+'<th colspan="4"></th>'
	+"</tr>").prependTo(data_list_sel+' thead');

	$("#searchName").keyup( function () {
        oTable.fnFilter( this.value, 0 );
    } );
	$("#searchNickName").keyup( function () {
        oTable.fnFilter( this.value, 1 );
    } );
	$("#searchLeader").keyup( function () {
        oTable.fnFilter( this.value, 2 );
    } );
	// $(data_list_sel+'thead input[name="search_module"]').keyup( function () {
//         oTable.fnFilter( this.value, 1 );
//     } );
// 	$(data_list_sel+'thead input[name="search_user"]').keyup( function () {
//         oTable.fnFilter( this.value, 2 );
//     } );
//     

}









