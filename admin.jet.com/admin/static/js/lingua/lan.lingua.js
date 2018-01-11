


// wait until everything is done starting up...
$(document).ready(function () {
	var is_using_datatabls =(typeof using_datatabls)== "undefined"? false:using_datatabls;
	
	$.linguaInit('static/js/lingua/', 'admin');// initialization establishes path and the base filename

	var lanConf=getLatestLan();
	$("#lan_which_bs").html(lanConf[2]);

	$.linguaLoad(lanConf[1]);
	_lanUpdatePage();
	//alert(is_using_datatabls);
//	if(!is_using_datatabls)//只有当前面没有使用Datatables时才在此时做自动翻译（否则在Datatables的回调中触发翻译）
		$.linguaUpdateElements(); // manual updating of controls by ID
	
});


// 从 COOKIE 读取配置
function getLatestLan()
{
	var isRead=false;
	
	var lanCode=$.cookie("lan_code") || $.linguaGetLanguage();//读COOKIE，否则设置为浏览器的默认语言
	var lanName=$.cookie("lan_name") || "Language";
	if( lanName ==  "Language" ) isRead=true;
	
	return [isRead,lanCode,lanName];
}

function reloadLanguage(){
	$.linguaInit('static/js/lingua/', 'admin');
	
	var c=getLatestLan();
	$("#lan_which_bs").html(c[2]);
	reloadLanguage_bs(c[1],c[2]);
}

function reloadLanguage_bs(newlanguage,name)
{	
	$("#lan_which_bs").html(name);
	
	$.cookie("lan_code", newlanguage , { path: '/', expires: 10 });  //设置，单位：天
	$.cookie("lan_name", name		 , { path: '/', expires: 10 });  //设置，单位：天
	
	//alert(newlanguage);
	$.linguaLoadAutoUpdate(newlanguage); // this will also update controls by ID
	_lanUpdatePage();
}
function _lan_trans(key,defaultName){
	var val=$.lingua(key)
	if(typeof val=='undefined'){
		reloadLanguage();
		val=$.lingua(key)
	}
	if(!val)val=defaultName;
	return val;
}
function _lan(domID,defaultName){
	var retName=$.lingua(".LAN_"+domID);
	if(retName===undefined)retName=defaultName;
	return retName ;
}
function _getDataTablesLanguageUrl(){
	var code=getLatestLan()[1]+"";
	code=code.replace(/-/,"_");
//	alert(code)
	return "static/js/DataTablesExt/dataTables."+code+".json";
}

// function _getDataTablesEditorLanguageUrl(){
// 	return "js/DataTablesExt/dataTables.en.json";
// }

function _lanUpdatePage()
{
	//
	
	//alert($.lingua("mytitle"));
	$("#title").html($.lingua("mytitle"));
	$("#category").html($.lingua("mycategory"));
	$("#label").html($.lingua("mylabel"));
// 	
// 	var objs= Array();
// 	$(".languageToBeTrans").each(function(){
// 		$(this).html($.lingua($(this).text()));
// 		alert($.lingua($(this).text()));
// 		
// 	});
// 	//for(i in objs){
// 		var obj=objs[0];
// 		var newText=$.lingua(obj.text());
// 		alert(newText);
// 		obj.html($.lingua(obj.text()));
// 	//}
}