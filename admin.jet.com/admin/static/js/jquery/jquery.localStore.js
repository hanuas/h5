
(function(window, undefined){
	if( window.localStorage )
	 return;
	/*
	* IE系列
	*/
	var userData = {
	 file : window.location.hostname || "localStorage",
	 keyCache : "localStorageKeyCache",
	 keySplit : ",",
	 o : null,
	 init : function(){
		 if(!this.o){
			 try{
				 var box = document.body || document.getElementsByTagName("head")[0] || document.documentElement, o = document.createElement('input');
				 o.type = "hidden";
				 o.addBehavior ("#default#userData");
				 box.appendChild(o);
				 var d = new Date();
				 d.setDate(d.getDate()+365);
				 o.expires = d.toUTCString();
				 this.o = o;
				 window.localStorage.length = this.cacheKey(0,4);
			 }catch(e){
				 return false;
			 }
		 };
		 return true;
	 },
	 cacheKey : function( key, action ){
		 if( !this.init() )return;
		 var o = this.o;
		 o.load(this.keyCache);
		 var str = o.getAttribute("keys") || "",
			 list = str ? str.split(this.keySplit) : [],
			 n = list.length, i=0, isExist = false;
		 if( action === 3 )
			 return list;
		 if( action === 4 )
			 return n;
		 key = key.toLowerCase();
		 for(; i<n; i++){
			 if( list[i] === key ){
				 isExist = true;
				 if( action === 2 ){
					 list.splice(i,1);
					 n--; i--;
				 }
			 }
		 }
		 if( action === 1 && !isExist )
			 list.push(key);
		 o.setAttribute("keys", list.join(this.keySplit));
		 o.save(this.keyCache);
	 },
	 item : function(key, value){
		 if( this.init() ){
			 var o = this.o;
			 if(value !== undefined){
				 this.cacheKey(key, value === null ? 2 : 1);
				 o.load(this.file);
				 value === null ? o.removeAttribute(key) : o.setAttribute(key, value+"");
				 o.save(this.file);
			 }else{
				 o.load(this.file);
				 return o.getAttribute(key) || null;
			 }
			 return value;
		 }else{
			 return null;
		 }
		 return value;
	 },
	 clear : function(){
		 if( this.init() ){
			 var list = this.cacheKey(0,3), n = list.length, i=0;
			 for(; i<n; i++)
				 this.item(list[i], null);
		 }
	 }
	};
	window.localStorage = {
	 setItem : function(key, value){userData.item(key, value); this.length = userData.cacheKey(0,4)},
	 getItem : function(key){return userData.item(key)},
	 removeItem : function(key){userData.item(key, null); this.length = userData.cacheKey(0,4)},
	 clear : function(){userData.clear(); this.length = userData.cacheKey(0,4)},
	 length : 0,
	 key : function(i){return userData.cacheKey(0,3)[i];},
	 isVirtualObject : true
	};
})(window);
 /*二次封装*/
(function(window,localStorage,undefined){
	var LS = {
		set : function(key, value){
			if( this.get(key) !== null )
				this.remove(key);
			localStorage.setItem(key, value);
		},
		get : function(key){
		 var v = localStorage.getItem(key);
		 return v === undefined ? null : v;
		},
		remove : function(key){ localStorage.removeItem(key); },
		clear : function(){ localStorage.clear(); },
		each : function(fn){
			var n = localStorage.length, i = 0, fn = fn || function(){}, key;
			for(; i<n; i++){
				 key = localStorage.key(i);
				 if( fn.call(this, key, this.get(key)) === false )
					 break;
				 if( localStorage.length < n ){
					 n --;
					 i --;
				 }
			}
		}
	},
	j = window.jQuery, c = window.Core;
	window.LS = window.LS || LS;
	if(j) j.LS = j.LS || LS;
	if(c) c.LS = c.LS || LS;
})(window,window.localStorage);

(function($){
	var isCache=true,
        minutes =  1000*60,
        now = new Date;
		now=now.getTime();
        try {
              localStorage.setItem('cache','test');
        }catch (e){isCache= false;}
    resetCache = function(time){//定时清理
        if(!isCache)return ;
        var expires, day= minutes*60*24;
        time = time || 0;
        if((expires=localStorage.getItem('_expires')) && expires>now){
            return false;
        }
       try{
	        var len= localStorage.length,item,key,t;
	        for(var i=0; i<len; i++){
	            key= localStorage.key(i);
	            item=localStorage.getItem(key);
	
	            
	            if(item && item.indexOf('_expires')!=-1){
	                t=item.match(/_expires":(\d+)/)[1];
	                if(now<t){
	                    continue;
	                }
	            }
	            localStorage.removeItem(key);
	        }
       }catch(e){}
        return localStorage.setItem('_expires',now + day*time);
    }
    resetCache(0);/*2个月检测一遍*/
    $.localStorage = function(name, value, time){
		var getType=function (o){var _t;return ((_t = typeof(o)) == "object" ? o==null && "null" || Object.prototype.toString.call(o).slice(8,-1):_t).toLowerCase();}
        if(!isCache)
            return false;        
        if (typeof value != 'undefined'){//set
			if(value===null){return localStorage.removeItem(name);}
			if(!isNaN(+time)){
				value = {value: value, _expires : now+time*minutes};
			}
			localStorage.setItem(name,JSON.stringify(value));  
			return value.value || value;
         }else{//get
			var localValue = null,st,et;
			localValue = localStorage.getItem(name);
			try {
				localValue = JSON.parse(localValue);
			}catch (e){return localValue;}
			if(getType(localValue)=="object" && (et=localValue._expires) ){
				if(now > et){
					localStorage.removeItem(name);
					localValue=null;
				}else{localValue =  localValue['value'];}
			}
			return localValue;
        }
    };
})(jQuery);

function dateStrDelimiterConvert(val){
  return val.replace('-','/').replace('-','/');
}



/*		LOCAL		*/

function getRootCid(){return "1000000000000000000";}
function getDefaultCid(){return getRootCid();}
function isClassifyidAll(cid){return cid==getRootCid();}
//function isClassifyidAll(id){return id=='all' || id=='';}

var g_fnCalItemAdded=function(itm){return addCalItemLocal(itm);}
var g_fnCalItemEdited=function(itm){return updateCalItemLocal(itm);}

function getLocalKey_calMonth(year,month/*0起*/,cidInfo){
	var y=g_curYear,m=g_curMonth,_cidInfo={cid:getDefaultCid(),cite:true};
	if(arguments[0])y=year;
	if(arguments[1])m=month;
	if(arguments[3])_cidInfo=cidInfo;
	var key=wrapLocalKey( "cal"+y + "-" +(m+1)+"_cid_"+_cidInfo.cid+(_cidInfo.cite?"cite":"nocite"));
	return key;
}
function getLocalKey_calSt(year,month/*0起*/,cidInfo){
	var y=g_curYear,m=g_curMonth,_cidInfo={cid:getDefaultCid(),cite:true};
	if(arguments[0])y=year;
	if(arguments[1])m=month;
	if(arguments[3])_cidInfo=cidInfo;
	var key=wrapLocalKey( "cal_st"+y + "-" +(m+1)+"_cid_"+_cidInfo.cid+(_cidInfo.cite?"cite":"nocite"));
	return key;
}
function getLocalKey_calDl(day,year,month/*0起*/,cidInfo){
	var y=g_curYear,m=g_curMonth,_cidInfo={cid:getDefaultCid(),cite:true};
	if(arguments[1])y=year;
	if(arguments[2])m=month;
	if(arguments[3])_cidInfo=cidInfo;

	var key=wrapLocalKey( "cal_day"+y + "-" +(m+1)+"-"+day+"_cid_"+_cidInfo.cid+(_cidInfo.cite?"cite":"nocite"));
	return key;
}

function operateCalItemLocal(m_key ,dl_key ,itm ,fnM ,fnEM ,fnD ,fnED){
	//month
	var mJson=$.localStorage(m_key);
	var findInMonthFace=false;
	var callEnd=true;
	var opered=false;
	if(fnM &&mJson){
		for(var i in mJson){
			if(itm.id==mJson[i].id){
				callEnd=fnM(mJson,i,itm);
				opered=true;
				break;
			}
		}
	}
	if(callEnd&&fnEM){fnEM(mJson,itm);opered=true;}
	if(opered)
		$.localStorage(m_key,mJson,60*2);
	//dl
	var jsondata=$.localStorage(dl_key);
	if(!jsondata)return false;
	json=jsondata.datas;
	callEnd=true;
	opered=false;
	if(fnD &&json){
		for(var i in json){
			if(itm.id==json[i].id){
				callEnd=fnD(json,i,itm);
				opered=true;
				break;
			}
		}
	}
	if(callEnd && fnED){fnED(json,itm);opered=true;}
	if(opered)
		$.localStorage(dl_key,jsondata,60*2);
	return true;
}
function operateCalStItemLocal(isAdd,st_key,classid ,nDate, dtNsum,dtICnt,dtPCnt){
	var opered=false;
	var json=$.localStorage(st_key);
	if(!json&& isAdd){
		json=[];
	}
	if(json){
		for(var i in json){
			if(json[i].classid==classid && json[i].numeric_date*1==nDate){
				json[i].numsum	=	json[i].numsum*1		+	dtNsum*1;
				json[i].item_count=	json[i].item_count*1	+	dtICnt*1;
				json[i].pic_count	=	json[i].pic_count*1	+	dtPCnt*1;
				opered=true;
			}
		}
	}
	 if (isAdd && !opered){
			json[json.length]={
					classid:classid
					,numeric_date:nDate+""
					,numsum:dtNsum
					,item_count:dtICnt
					,pic_count:dtPCnt
					};
			opered=true;
	 }
	if(opered)
		$.localStorage(st_key,json,60*2);
	
}
function delCalItemLocal(itm){
	var jDate=new Date(dateStrDelimiterConvert(itm.date));
	var m_key=getLocalKey_calMonth(jDate.getFullYear(),jDate.getMonth())
		,dl_key=getLocalKey_calDl(jDate.getDate(),jDate.getFullYear(),jDate.getMonth())
		,st_key=getLocalKey_calSt(jDate.getFullYear(),jDate.getMonth());
	var ret= operateCalItemLocal(m_key,dl_key,itm,
		function(arr,i,itm){
			arr.splice(i,1); return false;
		},null,
		function(arr,i,itm){
			arr.splice(i,1);return false;
		},null);
	var nDate=jDate.getFullYear()*10000+(jDate.getMonth()+1)*100+jDate.getDate();
	var dtNsum=-itm.num,dtICnt=-1,dtPCnt=( itm.picture&& itm.picture!="" ? -1:0);
	operateCalStItemLocal(false,st_key,itm.classifyid ,nDate, dtNsum,dtICnt,dtPCnt);
}
function updateCalItemLocal(itm){
	var jDate=new Date(dateStrDelimiterConvert(itm.date));
	var m_key=getLocalKey_calMonth(jDate.getFullYear(),jDate.getMonth())
	,dl_key=getLocalKey_calDl(jDate.getDate(),jDate.getFullYear(),jDate.getMonth())
	,st_key=getLocalKey_calSt(jDate.getFullYear(),jDate.getMonth());
	var ret= operateCalItemLocal(m_key,dl_key,itm,
		function(arr,i,itm){
			arr[i]=itm;return false;
		},null,
		function(arr,i,itm){
			arr[i]=itm;return false;
		},null);
	$.localStorage(st_key,null);
}
function addCalItemLocal(itm){
	var jDate=new Date(dateStrDelimiterConvert(itm.date));
	var m_key=getLocalKey_calMonth(jDate.getFullYear(),jDate.getMonth())
		,dl_key=getLocalKey_calDl(jDate.getDate(),jDate.getFullYear(),jDate.getMonth())
		,st_key=getLocalKey_calSt(jDate.getFullYear(),jDate.getMonth());
	
	var ret= operateCalItemLocal(m_key,dl_key,itm,
		function(arr,i,itm){return false;},/*找到了就不添加*/
		function(arr,itm){if( itm.facetype==1)arr[arr.length]=itm;},
		function(arr,i,itm){return false;},
		function(arr,itm){arr[arr.length]=itm;}
		);
	var nDate=jDate.getFullYear()*10000+(jDate.getMonth()+1)*100+jDate.getDate();
	var dtNsum=itm.num,dtICnt=1,dtPCnt=( itm.picture&& itm.picture!="" ? 1:0);
	operateCalStItemLocal(true,st_key,itm.classifyid ,nDate, dtNsum,dtICnt,dtPCnt);
}
function updateFacetypeCalItemLocal(itm){
	var jDate=new Date(dateStrDelimiterConvert(itm.date));
	var m_key=getLocalKey_calMonth(jDate.getFullYear(),jDate.getMonth())
	,dl_key=getLocalKey_calDl(jDate.getDate(),jDate.getFullYear(),jDate.getMonth());
	
	return operateCalItemLocal(m_key,dl_key,itm,
		function(arr,i,itm){
			if(itm.facetype==0)/*取消了FACE*/
				arr.splice(i,1);
			else/*face状态未改变，应该不会进入*/
				arr[i]=itm;
			return false;
		},
		function(arr,itm){/*上一个没执行*/
			if( itm.facetype==1) arr[arr.length]=itm;
		},
		function(arr,i,itm){
			arr[i].facetype=itm.facetype;
			return false;
		},
		null
		);
}
/*********************************************************/

function getPageSize_story(){return 3;}
function getPageSize_search(){return 3;}
/*加全局变量，目的在于预防LocalStorage不能用的情况*/
var Today = new Date();
var g_tY = Today.getFullYear();
var g_tM = Today.getMonth();
var g_tD = Today.getDate();
var g_curYear = null;
var g_curMonth= null;
var g_curClassifyid=null;
var g_curSelfonly=null;

var g_curUserId="";
function getCurUserid(){return g_curUserId;}
function setCurUserid(uid){g_curUserId= uid;}
function wrapLocalKey(key){return getCurUserid()+key;}

function getLocalCommonDefFn(key,fnDefault,timeOut){//分钟
	var _timeOut=arguments[2]?timeOut:(60*2);
	key=wrapLocalKey(key);
	var v=$.localStorage(key);
	if(v!==null ){
		return v;
	}else{
		v=fnDefault();
		$.localStorage(key,v,_timeOut);
		return  v;
	}
	return v;
}
function getLocalCommon(key,def,timeOut){//分钟
	return getLocalCommonDefFn(key,function(){return def},arguments[2]);;
}
function setLocalCommon(key,v,timeOut){
	var _timeOut=arguments[2]?timeOut:(60*2);
	key=wrapLocalKey(key);
	$.localStorage(key,v,_timeOut);
	return  v;
}


function getCurYear(){
	g_curYear= getLocalCommonDefFn("localkey_cur_year",function(){if(!g_curYear )g_curYear=g_tY;return g_curYear;});
	return g_curYear;
}
function setCurYear(y){g_curYear= y;return setLocalCommon("localkey_cur_year",y);}

function getCurMonth(){
	g_curMonth= getLocalCommonDefFn("localkey_cur_month",function(){if(g_curMonth===null)g_curMonth=g_tM;return g_curMonth;});
	return g_curMonth;
}
function setCurMonth(m){g_curMonth= m;return setLocalCommon("localkey_cur_month",m);}

function getCurClassid(){
	g_curClassifyid= getLocalCommonDefFn("localkey_cur_classid",function(){if(g_curClassifyid===null)g_curClassifyid=getDefaultCid();return g_curClassifyid;});
	return g_curClassifyid;
}
function setCurClassid(id){g_curClassifyid= id;return setLocalCommon("localkey_cur_classid",id);}


function getCurSelfonly(){
	g_curSelfonly= getLocalCommonDefFn("localkey_cur_selfonly",function(){if(g_curSelfonly===null)g_curSelfonly=0;return g_curSelfonly;});
	return g_curSelfonly*1;
}
function setCurSelfonly(selfOnly){g_curSelfonly=selfOnly;return setLocalCommon("localkey_cur_selfonly",selfOnly);}

function getCurDate(){
	var date= getLocalCommonDefFn("localkey_cur_date",function(){return g_tY+"-"+(g_tM+1)+"-"+g_tD});
	return date;
}

function setCurDate(sDate){return setLocalCommon("localkey_cur_date",sDate);}

function getCurDateStart(){
	var date= getLocalCommonDefFn("localkey_cur_dateStart",function(){return g_tY+"-"+(g_tM+1)+"-"+g_tD});
	return date;
}
function setCurDateStart(sDate){return setLocalCommon("localkey_cur_dateStart",sDate);}

function getCurDateEnd(){
	var date= getLocalCommonDefFn("localkey_cur_dateStart",function(){return g_tY+"-"+(g_tM+1)+"-"+g_tD});
	var jDate=new Date(dateStrDelimiterConvert(date));
	jDate.setTime(jDate.getTime()+3600000*24*30);
	date=jDate.getFullYear()+"-"+(jDate.getMonth()+1)+"-"+jDate.getDate();
	return date;
}
function setCurDateEnd(sDate){return setLocalCommon("localkey_cur_dateEnd",sDate);}

function getStoryMCType(){return getLocalCommonDefFn("localkey_StoryMCType",function(){return 0});}
function setStoryMCType(t){return setLocalCommon("localkey_StoryMCType",t);}

function getStatisticMCType(){return getLocalCommonDefFn("localkey_StatisticMCType",function(){return 0});}
function setStatisticMCType(t){return setLocalCommon("localkey_StatisticMCType",t);}
///////////
function setStChartByclass(byclass){return setLocalCommon("localkey_StChartByclass",(byclass?1:0));}
function isStChartByclass(){return getLocalCommonDefFn("localkey_StChartByclass",function(){return 1});}

function getCurHavecite(){return getLocalCommonDefFn("localkey_cur_havecite",function(){return 0;});}
function setCurHavecite(withcite){return setLocalCommon("localkey_cur_havecite", (withcite?1:0));}

function getCurInCtieTree(){return getLocalCommonDefFn("localkey_cur_incite",function(){return 0;});}
function setCurInCtieTree(incite){return setLocalCommon("localkey_cur_incite", (incite?1:0));}

function getStChartType(){return getLocalCommonDefFn("localkey_cur_StChartType",function(){return "pie";});}
function setStChartType(t){return setLocalCommon("localkey_cur_StChartType", t);}

function getCurStoryPage(){return getLocalCommonDefFn("localkey_cur_StoryPage",function(){return 0;});}
function setCurStoryPage(v){return setLocalCommon("localkey_cur_StoryPage",v);}
function isStoryRefreshed(){return getLocalCommonDefFn("localkey_StoryRefreshed",function(){return 0;});}
function setStoryRefreshed(v){return setLocalCommon("localkey_StoryRefreshed", (v?1:0));}
//function getCiteTreeRoot(){return getLocalCommonDefFn("localkey_cur_citeTreeRoot",function(){return 0;});}
//function setCiteTreeRoot(cid){return setLocalCommon("localkey_cur_citeTreeRoot", cid);}
