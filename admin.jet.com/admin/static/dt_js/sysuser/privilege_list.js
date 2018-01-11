function dtRender_privilegeType() {
	var ret=function ( val, type, row ) {
		switch(val){
		case 'm': 		return'M';//LAN_Pattern
		case 'mc': 		return'MC';
		case 'mca': 	return'MCA';
		case 'mcab': 	return'MCAB';
		case 'url': 	return'URL';
		case 'name': 	return'NAME';
		}
		return val;
	}
	return ret;
};

window.onload=function(){

	var datalist_ajax_url="index.php?m=sysuser&c=privilege&a=index_ajax";
	var data_list_sel='#data_list ';
	
	$("<tr>"
	+"<th style='text-align:center;' width='20%'><span class='LAN_Group'>分组</span></th>"
	+"<th  style='text-align:center'width='30%'><span class='LAN_Privilege'>权限</span><span class='LAN_Name'>名称</span></th>"
	+"<th style='text-align:center;'width='20%'><span class='LAN_Privilege'>权限</span><span class='LAN_Type'>类型</span></th>"
	+"<th style='text-align:center'width='30%'><span class='LAN_Privilege'>权限</span><span class='LAN_Detail'>详情</span></th>"
	+"</tr>").appendTo(data_list_sel+' thead');
	
	var editor = new $.fn.dataTable.Editor( {
		"ajaxUrl": datalist_ajax_url,
		"domTable": "#data_list",
		"fields": [
			{"label":  _lan_trans(".LAN_Group","组名"),"name": "the_group","sClass1":"center"},
			{"label":  _lan_trans(".LAN_Privilege","权限")+ _lan_trans(".LAN_Name","名称"),"name": "privilege_name","sClass1":"center"},
			{"label": _lan_trans(".LAN_Privilege","权限")+ _lan_trans(".LAN_Type","类型"),"name": "authtype",
				"type": "select",
				"ipOpts": [
					{ "label": "MCAB",	"value": "mcab"},
					{ "label": "MCA", 	"value": "mca" },
					{ "label": "MC", 	"value": "mc"} ,
					{ "label": "M", 	"value": "m" },
					{ "label": "URL", 	"value": "url" },
					{ "label": "NAME",	"value": "name" }
				]
			},
			{"label": "Module(M)","name": "m"},
			{"label": "Controller(C)","name": "c"},
			{"label": "Action(A)","name": "a"},
			{"label": "Branch(B)","name": "branch",
				"type":"checkbox",
				"ipOpts":[
				    { "label": "查看", "value": "read" },
					{ "label": "新建", "value": "add" },
					{"label":"删除","value":"del"},
					{ "label": "修改", "value": "update"}       
				 ]
			
			},
			{"label": "URL","name": "url"},
			{"label": "NAME","name": "name"},

			
		],
		//"i18n":{"sUrl": "js/DataTablesExt/dataTables.editor.zh_CN.json"}
	} );
	
	branchButtons = getBranchButtons(js_privilege,editor);
	var oTable=$(data_list_sel).dataTable({
		"sDom": "Tfrtip",
		"pageLength":20,
		"sAjaxSource":datalist_ajax_url,
		"aoColumns": [
			{"mData": "the_group","sClass": "td_center" },
			{"mData": "privilege_name","sClass": "td_center" },
			{"mData": "authtype","sClass": "td_center"  ,'mRender': dtRender_privilegeType()},
			{"mData": "authtype","sClass": "td_center",'mRender':function(val,type,row){
				if(val == 'm'){
					return 'M:'+row.m;
				}else if(val == 'mc'){
					return 'M:'+row.m+' > C:'+row.c;
				}else if(val == 'mca'){
					return 'M:'+row.m+' > C:'+row.c+' > A:'+row.a;
				}else if(val == 'mcab'){
					return 'M:'+row.m+' > C:'+row.c+' > A:'+row.a+' > B:'+row.branch;
				}else if(val == 'name'){
					return 'NAME:'+row.name;
				}else if(val == 'url'){
					return 'URL:'+row.url; 
				}
			
			}}

		],
		"oTableTools": {
			"sRowSelect": "single",
			"aButtons":branchButtons
		},
		"fnDrawCallback": function( oSettings ) {  reloadLanguage(); },//重新翻译语言
		"oLanguage": {	"sUrl": _getDataTablesLanguageUrl()} //本地化文件位置，函数位于 js/lingua/lan.lingua.js
		
	} );

	//字段搜索
	var allSegments=[],options='';
	for(var i in js_para){options+='<option value="'+js_para[i]+'">'+js_para[i]+'</option>';};
	var authTypeSelect =  '<select id="selectAuthType">'
						 +'<option value="" class="LAN_All">全部</option>'
						 +'<option value="M">M</option>'
						 +'<option value="MC">MC</option>'
						 +'<option value="MCA">MCA</option>'
						 +'<option value="MCAB">MCAB</option>'
						 +'<option value="NAME">NAME</option>'
						 +'<option value="URL">URL</option>'
						 +'</select>';
	$("<tr>"
	+'<th  colspan="1"><select id="selectGroup">'
		+'<option value="">全部</option>'
		+ options
		+'</select></th>'
	+'<th><input type="text" id="privilegeName"></th>'
	+'<th>'
	+authTypeSelect
	+'</th>'
	+'<th colspan="3"></th>'
	+"</tr>").prependTo(data_list_sel+' thead');

	$("#selectGroup").change( function () {
        oTable.fnFilter( this.value, 0 );
    } );
	$("#privilegeName").keyup( function () {
        oTable.fnFilter( this.value, 1 );
    } );
	$("#selectAuthType").change( function () {
        oTable.fnFilter( this.value, 2 );
    } );

}