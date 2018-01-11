 
root_dir="../";
web_url="http://www.andesgame.com";
sample_web_url="http://andesgame.com";

$(function($) {
	
	
	setCurActivesMenu();
	
//	setMainBreadcrumbs();
	
});


function setMainBreadcrumbs(){
	var html = '<li><i class="ace-icon fa fa-home home-icon"></i><a href="?m=index">首页</a></li>';
	var current = $("li.mmenu_leaf_active"),
		parents	= current.parents("li.mmenu");
		
	var size = parents.size();
	
	for(var i=size-1 ; i >= 0 ; i--){
		var cur = $("#" + parents[i]['id']);
		var text = cur.find(" > a >span").text().trim();
//		var href = cur.find(" > a").attr("href").trim();
// 		if(i!=0)	html += '<li><a href="'+href+'">'+text+'</a></li>';
//  	else 
 			html += '<li>'+text+'</li>';
 		
	}
	var curText=current.text().trim();
	if(curText!="首页")html += '<li>'+curText+'</li>';
	$("#breadcrumbs_nav").html(html);
}


//选中左侧主菜单中当前菜单——废弃，在服务器端计算
function setCurActivesMenu(){
	var url = location.href;  
	var paraString = url.substring(
							url.indexOf("?")+1,
							url.length
						).split("&");  
	var paraObj = {}  
	for (i=0; j=paraString[i]; i++){ 
		paraObj[
			j.substring( 0, j.indexOf("=") ).toLowerCase()] = j.substring(j.indexOf("=")+1,j.length);  
	}  
	
	var m = paraObj["m"],
		c = paraObj["c"],
		a = paraObj["a"],
		urlType = "mca";
		
	if(typeof(a)=="undefined"){ a = "index"; urlType="mc";}
	if(typeof(c)=="undefined"){ c = "index"; urlType="m";}
	if(typeof(m)=="undefined"){ m = "index"; urlType="m";}
	//alert("#left_menu_"+m);
	
	var selMCA = ".mmenu_" + m + "_" + c + "_" + a,
		selMC  = ".mmenu_" + m + "_" + c ,
		selM   = ".mmenu_" + m ;


	var menusLi = $("li.mmenu");
	var mcaLi	= $(selMCA);
	var mcLi	= $(selMC);
	var mLi		= $(selM);
	
	function setCurActivesMenuBreadcrumbs(parents,current){
		var size = parents.size();
	
		var html = '<li><i class="ace-icon fa fa-home home-icon"></i><a href="?m=index">首页</a></li>';
	
		for(var i=size-1 ; i >= 0 ; i--){
			var cur = $("#" + parents[i]['id']);
			var text = cur.find(" > a >span").text().trim();
	//		var href = cur.find(" > a").attr("href").trim();
	// 		if(i!=0)	html += '<li><a href="'+href+'">'+text+'</a></li>';
	//  	else 
				html += '<li>'+text+'</li>';
		
		}
		var curText=current.find(" > a >span").text().trim();
		if(curText!="首页")html += '<li>'+curText+'</li>';
		$("#breadcrumbs_nav").html(html);
	}

	//if else 先mca再 mc 再 m	
	if(mcaLi.length > 0){
		menusLi.removeClass("open active");
		mcaLi.addClass("active").removeClass("open");
		var parents = mcaLi.parents("li.mmenu").addClass("active open");
		setCurActivesMenuBreadcrumbs(parents,mcaLi);
	}else if(mcLi.length > 0){
		menusLi.removeClass("open active");
		mcLi.addClass("active").removeClass("open");
		var parents = mcLi.parents("li.mmenu").addClass("active open");
		setCurActivesMenuBreadcrumbs(parents,mcLi);
		
	}else if(mLi.length > 0){
		menusLi.removeClass("open active");
		mLi.addClass("active").removeClass("open");
		var parents = mLi.parents("li.mmenu").addClass("active open");
		setCurActivesMenuBreadcrumbs(parents,mLi);
	}

	
}






/*================================STR===================================*/
/**
* 工具 字符串扩展
*/
//计算字符串长度
String.prototype.strLen = function() {
    var len = 0;
    for (var i = 0; i < this.length; i++) {
        if (this.charCodeAt(i) > 255 || this.charCodeAt(i) < 0) len += 2; else len ++;
    }
    return len;
}
//将字符串拆成字符，并存到数组中
String.prototype.strToChars = function(){
    var chars = new Array();
    for (var i = 0; i < this.length; i++){
        chars[i] = [this.substr(i, 1), this.isCHS(i)];
    }
    String.prototype.charsArray = chars;
    return chars;
}
//判断某个字符是否是汉字
String.prototype.isCHS = function(i){
    if (this.charCodeAt(i) > 255 || this.charCodeAt(i) < 0) 
        return true;
    else
        return false;
}
//截取字符串（从start字节到end字节）
String.prototype.subCHString = function(start, end){
    var len = 0;
    var str = "";
    this.strToChars();
    for (var i = 0; i < this.length; i++) {
        if(this.charsArray[i][1])
            len += 2;
        else
            len++;
        if (end < len)
            return str;
        else if (start < len)
            str += this.charsArray[i][0];
    }
    return str;
}
//截取字符串（从start字节截取length个字节）
String.prototype.subCHStr = function(start, length){
    return this.subCHString(start, start + length);
}

//
String.prototype.beginwith = function(s){
	return	this.subCHStr(0,s.strLen())==s;
}
//
String.prototype.cutSameLeft = function(s){
	if(this.beginwith(s)){
		return this.subCHString(s.strLen(),this.strLen());
	};
	return this;
}


//截取字符串
function left(mainStr,lngLen) { 
	 if (lngLen>0) {return mainStr.substring(0,lngLen)} 
	 else{return null} 
}  

function implode (glue, pieces) {
  // http://kevin.vanzonneveld.net
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Waldo Malqui Silva
  // +   improved by: Itsacon (http://www.itsacon.net/)
  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
  // *     example 1: implode(' ', ['Kevin', 'van', 'Zonneveld']);
  // *     returns 1: 'Kevin van Zonneveld'
  // *     example 2: implode(' ', {first:'Kevin', last: 'van Zonneveld'});
  // *     returns 2: 'Kevin van Zonneveld'
  var i = '',
    retVal = '',
    tGlue = '';
  if (arguments.length === 1) {
    pieces = glue;
    glue = '';
  }
  if (typeof pieces === 'object') {
    if (Object.prototype.toString.call(pieces) === '[object Array]') {
      return pieces.join(glue);
    }
    for (i in pieces) {
      retVal += tGlue + pieces[i];
      tGlue = glue;
    }
    return retVal;
  }
  return pieces;
}


function explode (delimiter, string, limit) {

    // *     example 1: explode(' ', 'Kevin van Zonneveld');
    // *     returns 1: {0: 'Kevin', 1: 'van', 2: 'Zonneveld'}
    // *     example 2: explode('=', 'a=bc=d', 2);
    // *     returns 2: ['a', 'bc=d']
 
    var emptyArray = { 0: '' };
    
    // third argument is not required
    if ( arguments.length < 2 ||
        typeof arguments[0] == 'undefined' ||
        typeof arguments[1] == 'undefined' ) {
        return null;
    }
 
    if ( delimiter === '' ||
        delimiter === false ||
        delimiter === null ) {
        return false;
    }
 
    if ( typeof delimiter == 'function' ||
        typeof delimiter == 'object' ||
        typeof string == 'function' ||
        typeof string == 'object' ) {
        return emptyArray;
    }
 
    if ( delimiter === true ) {
        delimiter = '1';
    }
    
    if (!limit) {
        return string.toString().split(delimiter.toString());
    } else {
        // support for limit argument
        var splitted = string.toString().split(delimiter.toString());
        var partA = splitted.splice(0, limit - 1);
        var partB = splitted.join(delimiter.toString());
        partA.push(partB);
        return partA;
    }
}
function in_array(stringToSearch, arrayToSearch) {
	for (s = 0; s < arrayToSearch.length; s++) {
		thisEntry = arrayToSearch[s].toString();
		if (thisEntry == stringToSearch) {
			return true;
		}
	}
	return false;
}
/*================================GPID.js===================================*/
/**
 * @author 乔成磊
 * 工具
 * 针对 MySql中BIGINT数据结构设计的一种分组ID（gid），ID中包含父子关系及分组关系。
 * 最高位固定是1，代表根类，处于level0，其它的从个位算起，每三位一个层级，共六层。
 * 如：1,000……是根类，level为0,    1,001……~1,999……处于level1，它们的父类是1000……， 1,001……~1999……属于同一层级
 * 同理，1001,000……是1001,001……~1,001,999……的父类
 */
GPID_maxLevel=6;
GPID_getRoot=function(){ return "1000000000000000000";}
GPID_getSeg=function(gid,level){
        var level=(arguments[1]===undefined? GPID_getLevel(gid):level);
    if(level==0)/*最高位*/return parseInt(gid.substr(0,1));
    if(level>GPID_maxLevel)return false;
    gid+="";
    var seg=gid.substr(3*level-2, 3);
    return parseInt(seg);
}
  
GPID_replaceWithSeg= function (gid ,newSeg,level){
        var level=(arguments[1]===undefined? GPID_getLevel(gid):level);
    if(level > GPID_maxLevel || newSeg>999||newSeg<0)return false;
    var sSeg=newSeg+'';
    switch(sSeg.length){
        case 1:sSeg="00"+sSeg;break;
        case 2:sSeg="0"+sSeg;   break;
    }
    if(level==0){ return newSeg+gid.substring(1);}
    return gid.substring(0,3*level-2)+sSeg+gid.substring(3*level+1);
    //if(level==0) return gid.replace(/d{1})(d*)/,newSeg+"$2");
    //return gid.replace(new RegExp("(d{"+(3*(level)-2)+"})(d{3})(d*)"), "$1"+sSeg+"$3");
}
GPID_nextSibGid=function(gid ,level){
    var level=(arguments[1]===undefined? GPID_getLevel(gid):level);
   var newSeg=GPID_getSeg(gid ,level)+1;
   return GPID_replaceWithSeg(gid,newSeg,level);
}
  
GPID_getParendGid=function(gid,level){
        var level=(arguments[1]===undefined? GPID_getLevel(gid):level);
    if(level>GPID_maxLevel)return false;
    if(level==0)return false;
    if(gid===undefined)return false;
    var parent=gid.substring(0,3*level-2);
    for(var i=0;i<=GPID_maxLevel-level;i++)parent+="000";
    return parent;
}
GPID_getLevel=function(gid){
        gid=gid+"";
    var level=GPID_maxLevel;
    while(0==parseInt(gid.substr(3*level-2, 3))&& level>0)level--;
    return level;
}
 

/*================================ funcs ===================================*/
function cancelBubble(){ //在点击处理事件函数里调用 
	var e = window.event ; 

	if ( e.stopPropagation ){ //如果提供了事件对象，则这是一个非IE浏览器 
		e.stopPropagation(); 
	}else{  
		window.event.cancelBubble = true; 
	}
}

function jump(url){
  if( url*1 <0) {location.href = 'javascript:history.go('+url+')';return;}
	window.location.href = url;
}

function toRelativeAddr(url) {
	url=url.cutSameLeft(web_url+"/");
	url=url.cutSameLeft(sample_web_url+"/");
	return url;
}

function toPlainText(htmlStr){

	var regx=/<[^>]*>|<\/[^>]*>/gm;

    var str=htmlStr.replace(regx,"");

    return str;

}

function modify_table_ctl() { 
	var obj=$('.table_grid');
	var firstPage='<button type="button" title="第一页" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon pq-page-first"></span><span class="ui-button-text"></span></button>'
	obj.find('button[title="第一页"]').replaceWith(firstPage);
}



/*================================ datatables ===================================*/
//MARK: Datatables相关
function dtRender_cutStr(valMaxLen){
	var valMaxLen=(valMaxLen===undefined ? 20:valMaxLen);
	var cutStr=function ( val, type, row ) {
		if ( val ) {

			return val.subCHString(0,valMaxLen)+(val.strLen()>valMaxLen?'···':'');
		}
		return "";
	}
	return cutStr;
}
function dtRender_img(max_height) {
	var max_height=(max_height===undefined?"30px":max_height);
	var ret=function ( val, type, row ) {
			if ( val ) {
				//alert(url);
				return '<img style="max-height:'+max_height+'" src="'+root_dir+toRelativeAddr(val)+'" alt="'+val.subCHStr(0,20)+'">';
			}
			return "";
		}
	return ret;
}
function dtRender_pic(public_site,table_name,max_height) {
	var max_height=(max_height===undefined?"30px":max_height);
	var ret=function ( val, type, row ) {
			var arr=(table_name===undefined? row : row[table_name] );
			if ( val ) {
				//alert(url);
				return '\
					<a style="max-height:'+max_height+'"  href="'+public_site+"/"+(arr["img"])+'" alt="'+val.subCHStr(0,20)+'" target="_blank">\
						<img style="max-height:'+max_height+'" src="'+public_site+"/"+(arr["thumb"])+'" alt="'+val.subCHStr(0,20)+'">\
					</a>';
			}
			return "";
		}
	return ret;
}

function generateEditors(domTable,datalist_ajax_url,fields){
	
	var editor = new $.fn.dataTable.Editor( {
		"ajaxUrl": datalist_ajax_url,
		"domTable": domTable,
		"fields": fields,
		//"i18n":{"sUrl": "js/DataTablesExt/dataTables.editor.zh_CN.json"}
	} );
	
	
	//添加编辑时禁止更改功能  
	for (var i = 0; i < fields.length; i++) {
		if(fields[i]['editable'] === false){
			fields[i]['type'] = 'readonly';
		}
        //console.log(i+":"+fields[i]);
    };
    
	var editor_edit = new $.fn.dataTable.Editor( {
		"ajaxUrl": datalist_ajax_url,
		"domTable": domTable,
		"fields": fields,
		//"i18n":{"sUrl": "js/DataTablesExt/dataTables.editor.zh_CN.json"}
	} );
	return [editor, editor_edit];
}


function getBranchButtons(js_privilege,editors, allow_print ){ 
	var addcode = 1;
	var delcode = 2;
	var updatecode = 4 ;
	var privilege_code=js_privilege["privilege_code"]===undefined? 0:js_privilege["privilege_code"] ;
	
	
	if(editors instanceof Array){
		editor_edit = editors[1];
		editor = editors[0];
	}else{
		editor_edit = false;
		editor = editors;
	}
	
	var branchButtons =[];
	
	if(addcode & privilege_code){
		branchButtons.push({ "sExtends": "editor_create", "editor": editor });
	 
	}
	if(updatecode & privilege_code){
		branchButtons.push({ "sExtends": "editor_edit", "editor": (!!editor_edit)? editor_edit : editor });
	}
	
	if(delcode & privilege_code){
		branchButtons.push({ "sExtends": "editor_remove", "editor": editor });
	}
	if(!!allow_print){
		branchButtons.push({ "sExtends": "print", "editor": editor });
	}
	return branchButtons;
	
}


/*
*	@author 乔成磊
*	@功能 生成 Datatables+Ediror 表格
*
*	ajax_url
*	fields 字段配置，数组类型
*		[{
*			name		必选
*			label		必选
*			label_lan	可选，如：LAN_SomeName
*			type  		可选
*			def  		可选 默认值
*			editable    可选，默认true
*			render		可选，在官方基础上做了扩展，
*							可以配置成key-value对的对象。如：{0:"所有","1":"第一类","2":"第二类"}，此时对Table和Editor都有效	
*			in_table 	可选，是否在table里显示，默认true
*			in_editor 	可选，是否在table里显示，默认true
*			width   	可选，显示行宽度 如：10%
*			search  	可选，布尔类型，如：true 
							或下拉列表，如： {"0":"全部", "1":"值1"}, 
							或函数，如 ["text",function(oTable,  value, idx){ oTable.fnFilter(  value, idx ); }]
			
*			search_orign  	可选，优先于 search，系统默认search
*			format		可选，如：'YYYY-MM-DD hh:mm:ss'	
*			fieldInfo	
*		}]
*	
*	config: 对象类型
*		privilege		必选
*		data_list_sel  	可选，默认: '#data_list '
* 		allow_print    	可选，默认: false
*		pageLength	   	可选，默认 20
*		row_select	   	可选，默认 single
*		disable_keyup_search, 可选，默认 false
*		initComplete	可选
*		columnDefs		可选,如（禁用排序的列）：[{  orderable:false,   targets:[1,2,3,4,5,6,7]    }],
*		ordering		可选，默认 true
*		serverSide		可选,布尔类型
*		tableOnly		可选，默认 false
*		search		  	可选，table的search (尚未实现)
*		filter			可选，默认 true
*/
//function _lan_trans(key,def){return def;}

function genEditorTable(data_list_sel,ajax_url, fields, config ){
	function in_array(stringToSearch, arrayToSearch) {
		for (s = 0; s < arrayToSearch.length; s++) {
			thisEntry = arrayToSearch[s].toString();
			if (thisEntry == stringToSearch) {
				return true;
			}
		}
		return false;
	}
	function isArray(o) {  
		return Object.prototype.toString.call(o) === '[object Array]';  
	}  
	//=========
	var header = "<tr>"; 
	var search = $("<tr>");
	var editor_fields = [];
	var table_fields = [];
	
	var e_index = 0;
	var t_index = 0;
	var s_index = 0;
	for(var i = 0 ; i< fields.length ; i++ ){
		var f = fields[i];
		
		var in_editor = true;
		if( f["in_editor"] === false ){
			 in_editor = false;
		}
		if(in_editor && !config['tableOnly'] ){ 
			if(!!f["label_editor"] ){
				var label_editor  = f["label_editor"] ;
			}else{
				var label_editor  = !!f["label_lan"] ? _lan_trans("." + f["label_lan"] , f["label"] ) : f["label"] ; 
			}
			
			var f_editor  = {
						"label": label_editor,
						"name": !!f["editor_name"] ? f["editor_name"] : f["name"] 
				};
		
			if( !! f["type"] ){
				f_editor["type"] = f["type"] ;
			}
			
			if( !! f["def"] ){
				f_editor["def"] = f["def"] ;
			}
		
			if( !! f["default"] ){
				f_editor["default"] = f["default"] ;
			}
			if( !! f["format"] ){
				f_editor["format"] = f["format"] ;
			}
			if( !! f["fieldInfo"] ){
				f_editor["fieldInfo"] = f["fieldInfo"] ;
			}
			if(  f["editable"] === false ){
				f_editor["editable"] = false ;
			}
		
			if( !! f["options"] ){
				f_editor["options"] = f["options"] ;
				
			} else if(  f["render"]  instanceof Object ){
				//render 是对象时也用于生成Editor的列表。
				var render = f["render"] ;
				var options = [];
				for(var key in render){
					var opItem = {};
					if(  !in_array(render[ key ] ,["All","全部","所有"]) ){
				
						options.push({"label": render[ key ] , "value":key });
					}
					
				}
				f_editor["options"] = options;
			}  
			editor_fields.push( f_editor );
			e_index ++;
		}
		
		var in_table = true;
		if(  f["in_table"] === false ){
			 in_table = false;
		}
		if(in_table){
			
			var f_table  = {"data": f["name"] };
			
			if( !! f["render"] ){ 
				var render_tab =  f["render"] ; 
				if(  f["render"] instanceof Object  &&  typeof f["render"]  != 'function'){
					var render_t_data =  f["render"] ; 
					render_tab = function ( val, type, row ) {
						if(render_t_data[val] !== undefined)
							return render_t_data[val];
						return val;
					};
				} 
				f_table["render"] = render_tab;
			}  
			
			if( !! f["class"] ){ f_table["class"] = f["class"] ; } 
			
			var width = "";
			if( !! f["width"] ){
				width = " width='"+ f["width"] +"' ";
			}
			header += "<th style='text-align:center' "+width+"><span class='" + 
					(!!f["label_lan"] ? f["label_lan"] : "" )  +  "'>"+ f["label"] +" </span></th>";
			
			 if( !! f["search"]){
			 	s_index++;
				var sconf = f["search"] ;
				placeholder = "";
				if(!!config['disable_keyup_search']){
					placeholder = ' placeholder="回车搜索" ';
				}
				if(isArray( sconf ) && typeof sconf[1] == "function" ){//自定义函数搜索
					 
					if(sconf[0] == "text"){
						var search_obj = $('<input type="text" id="dt_mysearch_'+t_index+'" '+placeholder+'   idx='+t_index+' /> ').appendTo( $( '<th  colspan="1"> ').appendTo( search )  );
						 
						if( !config['disable_keyup_search'] ){ 
							search_obj.keyup(  {"fnSearch": sconf[1]} , function ( event ) {  
								event.data.fnSearch(oTable, this.value, $(this).attr("idx")) 
							} ); 
							
						}else{
							search_obj.change(  {"fnSearch": sconf[1]} , function ( event ) {  
								event.data.fnSearch(oTable, this.value, $(this).attr("idx"))  
							} );
						}
					}
					
				}else if( sconf instanceof Object && !isArray( sconf )){ // 下拉列表搜索
					var search_obj = $('<select id="dt_mysearch_'+t_index+'"  idx='+t_index+' >').appendTo( $('<th  colspan="1">').appendTo( search )  );
							
					for(var value in sconf ){ 
						if(value=="_EMPTY_STRING_"){
							$('<option value="">'+sconf[ value ]+'</option>').prependTo(search_obj);
						}else{
							$('<option value="'+value+'">'+sconf[ value ]+'</option>').appendTo(search_obj);
						}
					}
					
					search_obj.change( function () {  
							oTable.fnFilter( this.value, $(this).attr("idx"), false); 
					} );
					
				}else{		//文本搜索
					var search_obj = $('<input type="text" id="dt_mysearch_'+t_index+'" '+placeholder+'   idx='+t_index+' />').appendTo( $( '<th  colspan="1"> ').appendTo( search )  );
					
					if( !config['disable_keyup_search'] ){
						search_obj.keyup( function ( ) {  
								oTable.fnFilter( this.value, $(this).attr("idx") );
						} );
					}else{
						search_obj.change( function () {
								oTable.fnFilter( this.value, $(this).attr("idx") );
						} );
					}
				}
 
			}else{ 
				$('<th  colspan="1"></th>').appendTo( search );
			} 
			//f_table["t_index"] = t_index;
			table_fields.push( f_table);
			t_index ++;
		}
		
	}// END for
	header+="</tr>";
	//search+="</tr>";
	$(header).appendTo(data_list_sel+' thead');
	
	var editors  = false ;
	var branchButtons = [];
	if(!config['tableOnly']){
		editors = generateEditors(data_list_sel, ajax_url, editor_fields );
	  
		branchButtons = getBranchButtons(config['privilege'],editors , 
			!!config["allow_print"]  ? config["allow_print"] : false
		);
	}
	
	var table_para = {
		"sDom": "Tfrtip",
		"pageLength": !!config["pageLength"] ? config["pageLength"] :20, 
		"aoColumns": table_fields,
		"oTableTools": { 
			"sRowSelect": !!config["row_select"] ? config["row_select"] :"single",
			"aButtons":branchButtons
		    //[{"sExtends":"editor_edit"}]
		}, 
		//"filter":false,
		"fnDrawCallback": function( oSettings ) {  reloadLanguage(); },//重新翻译语言
		"oLanguage": {	"sUrl": _getDataTablesLanguageUrl()} //本地化文件位置，函数位于 js/lingua/lan.lingua.js
		
	} ;
	
 	
    if(  !!config['initComplete'] ){ 
		//table_para['fnInitComplete'] = config['my_init_complete']  ;
		table_para['initComplete'] = config['initComplete']  ;
	}
 	
    if(   config['serverSide'] !== undefined ){  
		table_para['serverSide'] = config['serverSide']  ;
		table_para['ajax'] =  { url: ajax_url, type: "POST" } ;
	}else{
		table_para['ajaxSource'] = ajax_url; 
		//table_para['ajax'] =  { url: ajax_url, type: "POST" } ;
		 
	}
    if(   config['ordering'] !== undefined ){  
		table_para['ordering'] = config['ordering']  ;
	}
    
    if(  !!config['columnDefs'] ){  
		table_para['columnDefs'] = config['columnDefs']  ;
	}
	
	if(  config['filter'] !== undefined  ){  
		table_para['filter'] = config['filter']  ;
	} 
	if(  config['order'] !== undefined  ){  
		table_para['order'] = config['order']  ;
	} 
	var oTable = $(data_list_sel).dataTable(table_para);
	
	//字段搜索 
	//alert(s_index);
	if( s_index > 0 ){
		search.prependTo(data_list_sel+' thead');
	}
	
// 	for( var is =0 ; is < t_index; is++){  
// 		(function(is){ 
// 			srch=[];
// 			srch[is]=$("#dt_mysearch_"+is)
// 			if(!config['disable_keyup_search']){
// 				srch[is].keyup( function () {
// 					oTable.fnFilter( this.value,is);
// 				} );
// 			}
// 			srch[is].change( function () {
// 				oTable.fnFilter( this.value,is);
// 			} );
// 		})(is) 
// 	}
	 
	return [oTable,editors];
}





/**
*	BY 乔成磊 20131104 , 于2014/01/22重构、修复BUG——完美支持多实例
*	配置参数说明
*		source指服务器端的AJAX地址
*		rootId指级联下拉列表从哪项的子列表开始（并不包含rootId本身的项）
*		defaultId指默认选中项，如果是级联的则表示终的下拉列表的选中项

加载示例：
var casMenu=$("#cas_menu").cas_menu({
		fnUserOnchange:function(curSel){
			//alert(curSel);
			//alert(casMenu.getCurSel());
		},
		source			:	"http://tour/tour_server/admin/index.php?c=test&a=catalog_cas_menu",  
		rootId			:   '1000000000000000000',
		defaultId		:   '1001000000000000000'
	});
服务器端返回数据示例：
{
    "code": "succ",
    "list": [
        {
            "pairs": [
                {
                    "0": "1000000000000000000",
                    "1": "中国"
                }
            ],
            "def": "1000000000000000000"
        },
        {
            "pairs": [
                {
                    "0": "1001000000000000000",
                    "1": "北京"
                },
                {
                    "0": "1002000000000000000",
                    "1": "山东"
                }
            ],
            "def": "1001000000000000000"
        }
    ]
}

其中pairs也可为：
 "value": "1000000000000000000",
 "label": "中国"
 
*/

$.fn.dataTable.Editor.fieldTypes.ckeditor = $.extend( true, {}, $.fn.dataTable.Editor.models.fieldType, {
    "create": function ( conf ) {
        var that = this;
 
        conf._input = $('<div><textarea id="'+conf.id+'"></textarea></div>');
 
        // CKEditor needs to be initialised on each open call
        this.on( 'onOpen.ckEditInit-'+conf.id, function () {
            if ( $('#'+conf.id).length ) {
                conf._editor = CKEDITOR.replace( conf.id );
 
                if ( conf._initSetVal ) {
                    conf._editor.setData( conf._initSetVal );
                    conf._initSetVal = null;
                }
                else {
                    conf._editor.setData( '' );
                }
            }
        } );
 
        // And destroyed on each close, so it can be re-initialised on reopen
        this.on( 'onClose.ckEditInit-'+conf.id, function () {
            if ( $('#'+conf.id).length ) {
                conf._editor.destroy();
                conf._editor = null;
            }
        } );
 
        return conf._input;
    },
 
    "get": function ( conf ) {
        if ( ! conf._editor ) {
            return conf._initSetVal;
        }
 
        return conf._editor.getData();
    },
 
    "set": function ( conf, val ) {
        // If not ready, then store the value to use when the onOpen event fires
        if ( ! conf._editor ) {
            conf._initSetVal = val;
            return;
        }
        conf._editor.setData( val );
    },
 
    "enable": function ( conf ) {}, // not supported in CKEditor
 
    "disable": function ( conf ) {} // not supported in CKEditor
} );

// MARK: Editor-1.5.3 之前的版本需要添加如下代码
// $.fn.dataTable.Editor.fieldTypes.datetime = $.extend( true, {}, $.fn.dataTable.Editor.models.fieldType, {
//     "create": function ( conf ) {
//         var that = this;
//  
//         conf._input = $('<input/>')
//             .attr( $.extend( {
//                 id: conf.id,
//                 type: 'text',
//                 'class': 'datetimepicker'
//             }, conf.attr || {} ) )
//             .datetimepicker( $.extend( {}, conf.opts ) );
//  
//         return conf._input[0];
//     },
//  
//     "get": function ( conf ) {
//         return conf._input.val();
//     },
//  
//     "set": function ( conf, val ) {
//         conf._input.val( val );
//     },
//  
//     // Non-standard Editor methods - custom to this plug-in. Return the jquery
//     // object for the datetimepicker instance so methods can be called directly
//     inst: function ( conf ) {
//         return conf._input;
//     }
// } );




















//在插件中使用CasMenu对象
$.fn.cas_menu = function(options) {
	//创建CasMenu的实体
	var cas = new CasMenu(this, options);
	//调用其方法
	return cas.init();
};

var CasMenu = function(ele,options) {
	this.$element = ele;
	this.defaults = {  
		source			:	"http://tour/tour_server/admin/index.php?c=test&a=catalog_cas_menu",  
		rootId			:   '1000000000000000000',
		defaultId		:   '1001000000000000000',
		fnUserOnchange	:	function(curSel){},
		selectWrapper	:   "<li>",
		inputCtlName	:   "",//
		disabled		:	"",
		selectAllOption	:	{label:"全部",value:0},//如果为FALSE，则不添加
		lastLevel		:	0,
		selectSelClass			:   "cas_menu_select",
		itemWrapperSelClass		:   "cas_menu_wrapper",
		styleSelectClass		:	"form-control",
		styleItemWrapperClass	:	""
	}; 

    var settings = $.extend({},this.defaults,options); 
	settings.target=this;
	
	//$.fn.cas_menu.config=settings;
	this.config=settings;
	//this.each(function(){ this.build (this); }); 
	return this;
}
//定义Beautifier的方法
CasMenu.prototype = {
	init:function(){
		var me=this;
		this.$element.each(function(){ me.build (this); }); 
		return this;
	},
	requestServerDatas : function(paras){
		var conf=  this.config;
		var ret=false;
		$.ajax({async: false, type : "GET",url:conf.source,	data:paras, dataType : 'json',  
			success : function(data) {
				//ret=data;
				///*
				if(data.code=='succ'){ret=data;
					ret=data;
				}else{alert("获取服务器数据出错："+data.msg);
				}//*/
				return;
			} 
		});//END ajax
		return ret;
	},  
	build : function(t){
		var conf=  this.config;
		var $this = $(t);
		//$this.css("color","red");
		var inputName=conf.inputCtlName? 'name="'+conf.inputCtlName+'"':'';
		$this.prepend('<input class="cursel" type="hidden" '+inputName+'>');
		var data=this.requestServerDatas({rootId:conf.rootId,defaultId:conf.defaultId});
		var list=data.list;
	// 	if(list.length<=0){//2014/01/13 qcl 针对SERVER返回空的情况添加一项：全部
	// 		this.append(t,[],'');
	// 		
	// 	}else //run for
		for(var i=0;i<list.length;i++){
			this.append(t,list[i].pairs,list[i].def);
			this.setCurValue(list[i].def,$this);
		}

	
	},

	append : function(objParent,pairs,def){ 
	
	
		var conf=  this.config,
			def =(def !==undefined ? def : false);

		var selAll=conf.selectAllOption,
			newLastLevel=conf.lastLevel+1,
			classSel=conf.selectSelClass+"_"+newLastLevel;
		//var sel=$('<select class="'+conf.styleSelectClass+' '+classSel+' '+conf.selectSelClass+'" level="'+newLastLevel+'"></select>');
		var sel=$('<select '+conf.disabled+' class="'+conf.styleSelectClass+' '+classSel+' '+conf.selectSelClass+'" level="'+newLastLevel+'"></select>');
		if(conf.selectWrapper){
			var selWrapper=$(conf.selectWrapper);
			selWrapper.appendTo(objParent);
		
			selWrapper.addClass(conf.itemWrapperSelClass);
			selWrapper.addClass(conf.styleItemWrapperClass);
			selWrapper.attr("level",newLastLevel);
			sel.appendTo(selWrapper);
		}else{
			sel.addClass(conf.itemWrapperSelClass);
			sel.addClass(conf.styleItemWrapperClass);
			sel.attr("level",newLastLevel);
			sel.appendTo(objParent);
		}
		if(selAll){
			$('<option value="'+selAll.value+'">'+selAll.label+'</option>').appendTo(sel);
		}
		
		var me=this;
		var onChangeInner = function(e){ 
			//var conf=  this.config;
			if(conf.selectWrapper)
				var wrapper=$(this).parents("."+conf.itemWrapperSelClass).parent();
			else
				var wrapper=$(this).parent();
			var nxtPid	=	this.value, curLevel=$(this).attr("level");
			me.removeAfter (curLevel,wrapper);
			var curSel=this.value;
			if(conf.selectAllOption && conf.selectAllOption.value==this.value){//用户选择了全部直接返回
				//上一级的当前选中
				if(conf.selectWrapper)	curSel=$(this).parent().prev().find("select").val();
				else					curSel=$(this).prev().val();
			}else{
				var data=me.requestServerDatas({rootId:nxtPid,defaultId:nxtPid});
				if(data.list && data.list.length>0 && data.list[0].pairs &&  data.list[0].pairs.length>0)
					me.append(wrapper,data.list[0].pairs,data.list[0].def);
			}
			me.setCurValue(curSel,wrapper);
			if(conf.fnUserOnchange){conf.fnUserOnchange(curSel);}
		};
		sel.change(onChangeInner);
		for(var i=0;i<pairs.length;i++){
		
			var value=(pairs[i][0]!==undefined?pairs[i][0]:pairs[i].value),
				label=(pairs[i][1]!==undefined?pairs[i][1]:pairs[i].label),
				selected=(def==value? "selected":"");
			$('<option value="'+value+'" '+selected+'>'+label+'</option>').appendTo(sel);
		}
		conf.lastLevel=newLastLevel;

	
		return this;
	},

	removeAfter : function(fromLevel,wrapper){ 
		var conf=  this.config;
		fromLevel*=1;
		$("."+conf.itemWrapperSelClass,wrapper).each(function(){ 
			var curLevel=$(this).attr("level")*1;
			if(curLevel>fromLevel)$(this).remove();
		});
		conf.lastLevel= fromLevel;
		return this;
	},

	

	getCurSel : function(wrapper){  
		var conf=  this.config;
	
		var	wrapper=(wrapper===undefined)?conf.target:wrapper;
		var val=wrapper.attr("cursel");
		if(val===undefined)return false;
		return val;
	},

	setCurValue : function(val,wrapper){  
		var conf=  this.config,
			val =(val !==undefined ? val : false),
			wrapper=(wrapper===undefined)?conf.target:wrapper;
		//当前选择的值赋给父元素和隐藏元素
		wrapper.attr("cursel",val);
		$("input.cursel",wrapper).val(val);
	
	}
};//END CasMenu




