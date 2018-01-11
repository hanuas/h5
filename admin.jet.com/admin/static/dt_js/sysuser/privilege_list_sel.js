function dtRender_privilegeType() {
	var ret=function ( val, type, row ) {
		switch(val){
		case 'm': 		return'M模式';
		case 'mc': 		return'MC模式';
		case 'mca': 	return'MCA模式';
		case 'mcab': 	return'MCAB模式';
		case 'url': 	return'URL模式';
		case 'name': 	return'NAME模式';
		}
		return val;
	}
	return ret;
};

window.onload=function(){

	var datalist_ajax_url="index.php?m=sysuser&c=role&a=privilege_list_ajax";
	var data_list_sel='#data_list ';
	var defSel	=	js_para2['default'];
		//alert(defSel);
	$("#the_sels").val(defSel);
	var arrDef=explode(",",defSel);
	
	$("<tr>"
	+"<th style='text-align:center;' width='20%'>分组</th>"
	+"<th style='text-align:center;'width='20%'>权限类型</th>"
	+"<th style='text-align:center'width='20%'>权限ID</th>"
	+"<th  style='text-align:center'width='40%'>权限名</th>"
	+"</tr>").appendTo(data_list_sel+' thead');
	
	var editor = new $.fn.dataTable.Editor( {
		"ajaxUrl": datalist_ajax_url,
		"domTable": "#data_list",
		"fields": [		],
		//"i18n":{"sUrl": "js/dataTables.editor.zh_CN.json"}
	} );
	
	//branchButtons = getBranchButtons(js_privilege,editor);
	 oTable=$(data_list_sel).dataTable({
		"sDom": "Tfrtip",
		"sAjaxSource":datalist_ajax_url,
		"pageLength":20,
		"aoColumns": [
			{"mData": "the_group","sClass": "td_center" },
			{"mData": "authtype","sClass": "td_center"  ,'mRender': dtRender_privilegeType()},
			{"mData": "id","sClass": "td_center" }, //"sClass": "center"  clss名为center
			{"mData": "privilege_name","sClass": "td_center" }
		],
		"oTableTools": {
			"sRowSelect": "multi",
			"aButtons":{}
		},
		"fnDrawCallback": function( oSettings ) {  reloadLanguage(); },//重新翻译语言
		"oLanguage": {	"sUrl": _getDataTablesLanguageUrl()} ,//本地化文件位置，函数位于 js/lingua/lan.lingua.js
	
		"fnInitComplete": function ( settings, json ) {
			var nodes=oTable.fnGetNodes();
			for(var i=0,len=nodes.length;i<len;i++){
				var data = oTable.fnGetData( nodes[i] );
				if(in_array(data.id,arrDef)){
					 $(nodes[i]).addClass("active");
				}
			}
		} 
		
	} );

	//字段搜索
	var allSegments=[],options='';
	for(var i in js_para){options+='<option value="'+js_para[i]+'">'+js_para[i]+'</option>';};
	var authTypeSelect =  '<select id="selectAuthType">'
						 +'<option value="">全部</option>'
						 +'<option value="M模式">M模式</option>'
						 +'<option value="MC模式">MC模式</option>'
						 +'<option value="MCA模式">MCA模式</option>'
						 +'<option value="MCAB模式">MCAB模式</option>'
						 +'<option value="NAME模式">NAME模式</option>'
						 +'<option value="URL模式">URL模式</option>'
						 +'</select>';
	$("<tr>"
	+'<th  colspan="1"><select id="selectGroup">'
		+'<option value="">全部</option>'
		+ options
		+'</select></th>'
	+'<th>'
	+authTypeSelect
	+'</th>'
	+'<th colspan="3"></th>'
	+"</tr>").prependTo(data_list_sel+' thead');

	$("#selectGroup").change( function () {
        oTable.fnFilter( this.value, 0 );
    } );
	$("#selectAuthType").change( function () {
        oTable.fnFilter( this.value, 1 );
    } );

}


function check_form() {
	var nodes=oTable.$("tr.active");
	var ids=new Array();
	for(var i=0,len=nodes.length;i<len;i++){
		var data = oTable.fnGetData( nodes[i] );
		ids.push(data.id);
	}
	var strIds=implode(",",ids);
	$("#the_sels").val(strIds);
	return true;
}