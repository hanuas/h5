

window.onload=function(){

	var datalist_ajax_url="?m=user&c=advise&a=advise_ajax";
	var data_list_sel='#data_list ';
	var defSel	=	js_para['default'];
	defSel = defSel && (defSel !== undefined) ? defSel: false;
		//alert(defSel);
	$("#the_sels").val(defSel);
	var arrDef = explode(",",defSel);
	
	 
    		
    		
	$("<tr>"
	+"<th style='text-align:center' width=10%>类型</th>"
	+"<th style='text-align:center' width=7%>用户ID</th>"
	+"<th style='text-align:center' width=10%>平台/Ver</th>" 
	+"<th style='text-align:center' width=5%>时间</th>"
	+"<th style='text-align:center' width=5%>标题</th>"
	+"<th style='text-align:center' width=20%>内容</th>"
	+"<th style='text-align:center' width=5%>操作</th>"
	+"</tr>").appendTo('#data_list thead');
	 
	var editor = new $.fn.dataTable.Editor( {
		"ajaxUrl": datalist_ajax_url,
		"domTable": "#data_list",
		"fields": [
			{"label": "类型",		"name": "category", "type" : "select"}, 
			{"label": "用户ID",	 "name": "user_id" },
			{"label": "平台",		"name": "plat","type": "select",
				"ipOpts": [
					{ "label": "iOS", "value": "iOS"},
					{ "label": "Android", "value": "Android"},
					{ "label": "Web", "value": "Web"},
					{ "label": "Other", "value": "Other"}
				]
			},
			{"label": "版本",	 "name": "version" },	
			{"label": "时间",	"name": "time","type": "datetime",
                def:   function () { return new Date(); },
				format: 'YYYY-MM-DD h:mm:ss',
            },	
			{"label": "标题",	 "name": "title" },	
			{"label": "内容",	 "name": "content","type": "textarea"},	 
			 
		], //END fields
	} ); 
    		
    		
    		
    		
    		
    		
	branchButtons = getBranchButtons(js_privilege,editor);

	oTable = $(data_list_sel).dataTable({
        "dom": "Tfrtip",
		"pageLength":20,
        "ajax": {
            url: datalist_ajax_url,
            type: "POST"
        },
		"serverSide": true,
		"aoColumns": [
			{"mData": "category"		,"sClass": "td_center" },
			{"mData": "user_id"			,"sClass": "td_center" },
			{"mData": "plat"			,"sClass": "td_center" ,"mRender": 
				function ( val, type, row ) {
					return val + " / <span style='color:grey'>" + row.version +"</span>" ;
				}
			},
			{"mData": "time"			,"sClass": "td_center" },
			{"mData": "title"			,"sClass": "td_center" },
			{"mData": "content"			,"sClass": "td_center" },
			{"mData": "id",'mRender':function(val,type,row){
				return '<a href="?m=user&c=advise&a=replySms&uid='+row.user_id+'&id='+row.id+'" onclick="cancelBubble()">回复短信</a>'
			}},
			 
		],
		"oTableTools": {
			"sRowSelect": "multi",
			"aButtons":defSel? {} :branchButtons
		},
		"oLanguage": {	"sUrl": _getDataTablesLanguageUrl()}, //本地化文件位置，函数位于 js/lingua/lan.lingua.js
		"fnInitComplete": function ( settings, json ) {
			var nodes=oTable.fnGetNodes();
			for(var i=0,len=nodes.length;i<len;i++){
				var data = oTable.fnGetData( nodes[i] );
				 
				if(in_array(data.id,arrDef)){
					 $(nodes[i]).addClass("active");
				}
			}
			
			
			//
			editor.field('category').update( json.category_list);
			updateAdviseCategory( json.category_list);
		} 
	} );
	
	//字段搜索
	$("<tr>" 
	+'<th  colspan="1"><select id="S_Category">'
		+'<option value=""  >全部</option>' 
		+'</select></th>' 
		
	+'<th  colspan="1"><input type="text" id="S_UserID" placeholder=" " ></th>' 
	
	+'<th  colspan="1"><select id="S_Plat">'
		+'<option value=""  >全部</option>' 
		+'<option value="iOS"  >iOS</option>' 
		+'<option value="Android"  >Android</option>' 
		+'<option value="Web"  >Web</option>' 
		+'<option value="Other"  >Other</option>' 
		+'</select></th>' 
		
	+'<th  colspan="1"><input type="text" id="S_Time" placeholder=" " ></th>' 
	+'<th  colspan="3"> </th>'
	+"</tr>").prependTo(data_list_sel+' thead');

	$("#S_Category").change( function () {
        oTable.fnFilter( this.value, 0);
    } );
    $("#S_UserID").keyup( function () {
        oTable.fnFilter( this.value, 1);
    } )
    $("#S_Plat").change( function () {
        oTable.fnFilter( this.value, 2);
    } )
    $("#S_Time").keyup( function () {
        oTable.fnFilter( this.value, 3);
    } )
    
//     

}

function updateAdviseCategory(cate_list){
	var options = '<option value=""  >全部</option>' ;
	for(var i = 0, len = cate_list.length ; i < len ; i++){
		var node = cate_list[i];
		 options =  options +  '<option value="'+ node.value +'"  >'+ node.label +'</option>' ;
	}
	$("#S_Category").html(options);
}
//MARK:- oTable 一定要是全局的， check_form是FormBS初始化表格时自动会在onSubmit添加调用的函数
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







