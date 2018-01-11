window.onload=function(){

	$("<tr>"
	+"<th style='text-align:center' width=10%><span class='LAN_User'>用户</span>ID</th>"
	+"<th style='text-align:center' width=15%><span class='LAN_UserName'>用户名</span></th>"
	+"<th  style='text-align:center' width=15%><span class='LAN_Mobile'>电话</span></th>"
	+"<th  style='text-align:center' width=5%><span class='LAN_Email'>邮箱</span></th>"
	+"<th  style='text-align:center' width=10%><span class='LAN_Gender'>性别</span></th>"
	+"<th  style='text-align:center' width=10%><span class='LAN_UserStatus'>状态</span></th>"
	+"<th  style='text-align:center' width=10%><span class='LAN_Register'>注册</span><span class='LAN_Time'>时间</span></th>"
	+"<th  style='text-align:center' width=10%>更多信息</th>"
	+"<th  style='text-align:center' width=10%><span class='LAN_Operation'>操作</span></th>"
	+"</tr>").appendTo('#data_list thead');
	
	var datalist_ajax_url="index.php?m=user&c=index&a=index_ajax";
	var data_list_sel='#data_list ';
	var editor = new $.fn.dataTable.Editor( {
		"ajaxUrl": datalist_ajax_url,
		"domTable": "#data_list",
		"fields": [
		
			{"label": "用户id","name": "id","type":"hidden"},
			
			{"label": "用户名","name": "user_name"},
			{"label": "密码","name": "user_pwd","type":"password"},
			{"label": "电话号码","name": "phone"},
			{"label": "邮箱地址","name": "email"},
			{"label": "用户状态","name": "user_status",
				"type": "radio",
				"ipOpts": [
					{ "label": "正常", "value": "1" },
					{ "label": "封禁", "value": "0"} 
				]
			},
			{"label": "QQ","name": "qq"},
			{"label": "微博","name": "weibo"},
			{"label": "微信","name": "weixin"},
			{"label": "QQ微博","name": "qqweibo"},
			{"label": "积分","name": "score"},
			{"label": "生日","name": "birthday","type":"datetime"},
			{"label": "性别","name": "gender",
				"type": "radio",
				"ipOpts": [
					{ "label": "女士", "value": "female" },
					{ "label": "男士", "value": "male"} 
				]
			},
			{"label": "头像","name": "portrait"},
			{"label": "职业","name": "career"},
			{"label": "职业类别","name": "career_cat","type":"select"},
			{"label": "个性签名","name": "signature","type":"textarea"},
			{"label": "更多信息","name": "moreinfo","type":"textarea"},
			{"label": "绑定信息","name": "bindinfo","type":"checkbox"},	

		],
		//"i18n":{"sUrl": "js/DataTablesExt/dataTables.editor.zh_CN.json"}
	} );
	

	branchButtons = getBranchButtons(js_privilege,editor);

	var oTable = $(data_list_sel).dataTable({
        "dom": "Tfrtip",
		"pageLength":20,
        "ajax": {
            url: datalist_ajax_url,
            type: "POST"
        },
        
        //"ordering":false,
        columnDefs:[{
                 orderable:false,//禁用排序
                 targets:[1,2,3,4,5,6,7]   //指定的列
        }],

		"serverSide": true,
		"aoColumns": [
			{"mData": "id","sClass":"td_center" }, 
			
			{"mData": "user_name" ,"sClass": "td_center" , "mRender":function(val,type,row){
				
				var ret = val;
				if(row.nick_name !== undefined ){
					ret = val +  "<br>" + row.nick_name;
				}
				return ret;
			}}, 
			{"mData": "phone"},
			{"mData": "email"},
			{"mData": "gender"},
			{"mData": "user_status"},
			{"mData": "create_time"},
			{"mData": "moreinfo"},
			{"mData": "id",'mRender':function(val,type,row){
				return '<a href="?m=user&c=index&a=viewUserinfo&uid='+val+'" onclick="cancelBubble()">详情</a>'
			}}
			
		],
		"oTableTools": {
			"sRowSelect": "single",
			"aButtons":branchButtons
		    //[{"sExtends":"editor_edit"}]
		},
        "fnInitComplete": function( settings, json ) {
			editor.field('bindinfo').update( json.bindCode);
			editor.field('career_cat').update( json.careers);
        },
		"fnDrawCallback": function( oSettings ) {  reloadLanguage(); },//重新翻译语言
		"oLanguage": {	"sUrl": _getDataTablesLanguageUrl()} //本地化文件位置，函数位于 js/lingua/lan.lingua.js
		
	} );
	
	//字段搜索
	$("<tr>"
	+'<th  colspan="1"><input type="text" id="searchId" placeholder="" ></th>'
	+'<th  colspan="1"></th>'
	+'<th  colspan="1"><input type="text" id="searchPhone" placeholder="敲回车搜索" ></th>'+'<th colspan="6"></th>'
	+"</tr>").prependTo(data_list_sel+' thead');

	$("#searchId").keyup( function (e) {
		 if(e.which == 13) 	  oTable.fnFilter( this.value, 0 );
    } );
	$("#searchPhone").keyup( function (e) {
        if(e.which == 13) 	 oTable.fnFilter( this.value, 2 );
    } );
    

}









