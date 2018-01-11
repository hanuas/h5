window.onload=function(){

	$("<tr>"
	+"<th style='text-align:center' width=15%><span class='LAN_UserName'>用户名</span></th>"
	+"<th  style='text-align:center' width=15%><span class='LAN_Type'>类型</span></th>"
	+"<th  style='text-align:center' width=30%><span class='LAN_OperationIntro'>操作概要</span></th>"
	+"<th  style='text-align:center' width=15%><span class='LAN_OperationTime'>操作时间</span></th>"
	+"<th  style='text-align:center' width=15%><span class='LAN_View'>查看</span></th>"
	+"</tr>").appendTo('#data_list thead');
	
	var datalist_ajax_url="?m=admin&c=actionLog&a=index_ajax";
	var data_list_sel='#data_list';

	var oTable =$(data_list_sel).dataTable( {
        "dom": "Tfrtip",
		"pageLength":20,
        "ajax": {
            url: datalist_ajax_url,
            type: "POST"
        },
		////"serverSide": true,
		"aoColumns": [
			{"mData": "user_name","sClass":""}, 
			{"mData": "type","sClass":""},
			{"mData": "title","sClass":""},
			{"mData": "time","sClass":""},
			{
                "mData": "id","sClass":"","mRender": function(val,type,row){return '<a href="?c=actionlog&a=browseLogDetail&id='+val+'"><span class="LAN_ViewDetails">查看详情</span></a>'},
            }
			
        ],
		"oTableTools": {
			//"sRowSelect": "single",
			"aButtons":[]
		    //[{"sExtends":"editor_edit"}]
		},
		
		//"fnInitComplete": function () {this.fnFilter( '美食', 2 );},
		"fnDrawCallback": function( oSettings ) {  if(oSettings.iDraw==2)reloadLanguage(); },//重新翻译语言
		"oLanguage": {	"sUrl": _getDataTablesLanguageUrl()} //本地化文件位置，函数位于 js/lingua/lan.lingua.js
} );
//字段搜索
	var allSegments=[],options='';
	for(var i in js_para){
		options+='<option value="'+js_para[i]+'">'+js_para[i]+'</option>';
	};
	$("<tr>"
	+'<th><input type="text" class="form-control" id="selectName" placeholder="User Name"></th>'
	+'<th><select class="form-control"><option value="" class="LAN_All">全部</option>'
	+options
	+'</select></th>'
	+'<th><input type="text" class="form-control" id="selectTitle" placeholder=""></th>'
	+'<th><input type="text" class="form-control" id="selectTime" placeholder=""></th>'
	//+'<th width="20px"  colspan="1"><input type="text" name="search_user" placeholder="用户ID或用户名" value="" class="search_init"></th>'
	+'<th colspan="3"></th>'
	+"</tr>").prependTo(data_list_sel+' thead');

	$(data_list_sel+" thead select").change( function () {
        oTable.fnFilter( this.value, 1 );
    } );
	$("#selectName").keyup(function(){
		oTable.fnFilter( this.value,0 );
	})
	$("#selectTitle").keyup(function(){
		oTable.fnFilter( this.value,2);
	})
	$("#selectTime").keyup(function(){
		oTable.fnFilter( this.value,3 );
	})
	// $(data_list_sel+'thead input[name="search_module"]').keyup( function () {
//         oTable.fnFilter( this.value, 1 );
//     } );
// 	$(data_list_sel+'thead input[name="search_user"]').keyup( function () {
//         oTable.fnFilter( this.value, 2 );
//     } );
//     
}
function dt_view(id){window.location.href="?m=actionlog&a=browseLogDetail&id="+id;}
function dt_del_line(id){
	if(confirm("确定要删除该操作记录吗?")){
		window.location.href="?m=actionlog&a=delLog&id="+id;
	}
}











