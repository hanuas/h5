
function getValByJsonstr(jstr,key){
	jstr=jstr.slice(jstr.indexOf("{")+1);
	jstr=jstr.slice(0,jstr.lastIndexOf("}"));
	ar=jstr.split(",");
	for(i=0;i<ar.length;i++){
		pair=ar[i].split(":");
		k=pair[0];
		if(k.lastIndexOf("\"")>0){
			k=k.slice(k.indexOf("\"")+1);
			k=k.slice(0,k.lastIndexOf("\""));
		}
		if(k==key){
			v=pair[1];
			if(v.lastIndexOf("\"")>0){
				v=v.slice(v.indexOf("\"")+1);
				v=v.slice(0,v.lastIndexOf("\""));
			}
			return v;
		}
	}
	return "";
}

function getUploadifyTemplate(){
	var template='<div id="${fileID}" class="uploadify-queue-item clearfix photoItem">\
				<div class="img"><a target="_blank"><img /></a>\
				</div>\
				<div class="info">\
					  <div class="cancel"> \
						<a href="javascript:$(\'#${instanceID}\').uploadify(\'cancel\', \'${fileID}\');onServerCancel(\'${fileID}\',\'${fileName}\',\'\')">X</a>\
					  </div>\
					  <span class="fileName">${fileName} (${fileSize})</span><span class="data"></span>\
					  <div class="uploadify-progress">\
						<div class="uploadify-progress-bar"></div>\
					  </div>\
				</div>\
				<div style="clear:both;"></div>\
			</div>';
	return  template;
}


function getUploadifyInitTemplate(db_id,fileid,picName,img,thumb){
	var template='<div id="'+fileid+'" class="uploadify-queue-item clearfix photoItem">\
			<div class="img"><a href="'+img+'" target="_blank"><img src="'+thumb+'"/></a>\
	    	</div>\
			<div class="info">\
				  <div class="cancel"> \
				  	<a href="javascript:$(\'#file_upload\').uploadify(\'cancel\', \''+fileid+'\');onServerCancel(\''+fileid+'\',\''+picName+'\',\''+db_id+'\')">X</a>\
				  </div>\
				  <span class="fileName">'+picName+'<span class="data"> - 完成</span></span>\
				  <div class="uploadify-progress">\
					<div class="uploadify-progress-bar" style="width: 100%; "></div>\
				  </div>\
			</div>\
			<div style="clear:both;"></div>\
		</div>';
	return  template;
}


function onServerCancel(fileID,fileName,serverItemID){
	action="?m=upload&c=image&a=delete";
	//if(mode=="edit")action="edit/uploadifydel";
	$.post(action, {id : serverItemID,name : fileName}, function(data) {
		//$("body").append(data+"<hr>");
		if(data.code==true){
			alert("成功:"+data.msg);
		}else{
			alert("出错了:"+data.msg);
		}
	},"json");//
}

var extension = "*.gif;*.jpg;*.jpeg;*.png" ;
var needAlert = false;
var alertMsg = '';
function applyImgUploadify( selectorId,queueId,btnText,session_id,url,extension,needAlert,alertMsg){
	var url = url+"&sessionid="+session_id; 
	var  fnTimestamp=function(){return Math.round(new Date().getTime()/1000);}
	var timestamp=fnTimestamp();
	$('#'+selectorId).uploadify({
		formData     : {
			'timestamp' : timestamp,
			'token'     : hex_md5('unique_salt' + timestamp)
		},
		height: 30,
		width: 150,
		//	fileSizeLimit   : 4096,  
		langFile:'static/js/uploadify/uploadifyLang_cn.js',
		swf      : 'static/js/uploadify/uploadify.swf',
		uploader : url,
		//checkScript :'check-exists',
		fileTypeExts:extension,
		checkExisting:true,
		auto :true,
		multi: false,
		removeCompleted:true,
		queueID  : queueId,
		buttonText:btnText,
		onUploadSuccess : function(file, data, response) {
			//document.write(data);
			//alert(data);
			/*alert("消息", 'id: ' + file.id
		　　+ ' - 索引: ' + file.index
		　　+ ' - 文件名: ' + file.name
		　　+ ' - 文件大小: ' + file.size
		　　+ ' - 类型: ' + file.type
		　　+ ' - 创建日期: ' + file.creationdate
		　　+ ' - 修改日期: ' + file.modificationdate
		　　+ ' - 文件状态: ' + file.filestatus);///*///
			var ret = jQuery.parseJSON(data);
			if(ret.code == 0){ //上传失败
				alert(ret.message);
			}else{
				var imgurl = ret.url;
				$('.'+selectorId).val(ret.url);
				$('.'+selectorId).attr('src',ret.url);
				$('.'+selectorId).parent('a').attr('href',ret.url);
				$('.'+selectorId).attr('href',ret.url);
				if(needAlert){
					alert(alertMsg);
				}
				
			}

		},
		onDestroy : function() {
			alert('上传插件已被销毁');
		},
		onCancel : function(file) {//上传完成后再点取消无效
			alert('文件：' + file.name + ' 已取消。');
		},
		onUploadError : function(file, errorCode, errorMsg, errorString) {
			alert('文件' + file.name + ' 不能上传：' + errorString);
		},
		 onFallback : function() {
			alert('未找到Flash文件');
		},
		onDisable : function() {
			alert('您已经取消上载插件');
		}
		//itemTemplate : getUploadifyTemplate()
	});
}

function FileUploadify(selectorId,queueId,btnText,session_id,url,extension){
	var url = url+"&sessionid="+session_id; 
	var  fnTimestamp=function(){return Math.round(new Date().getTime()/1000);}
	var timestamp=fnTimestamp();
	$('#'+selectorId).uploadify({
		formData     : {
			'timestamp' : timestamp,
			'token'     : hex_md5('unique_salt' + timestamp)
		},
		height: 30,
		width: 150,
		fileSizeLimit   : 524288000,  
		langFile:'static/js/uploadify/uploadifyLang_cn.js',
		swf      : 'static/js/uploadify/uploadify.swf',
		uploader : url,
		//checkScript :'check-exists',
		fileTypeExts:extension,
		checkExisting:true,
		auto :true,
		multi: false,
		removeCompleted:true,
		queueID  : queueId,
		buttonText:btnText,
		onUploadSuccess : function(file, data, response) {
			//document.write(data);
			/*alert("消息", 'id: ' + file.id
		　　+ ' - 索引: ' + file.index
		　　+ ' - 文件名: ' + file.name
		　　+ ' - 文件大小: ' + file.size
		　　+ ' - 类型: ' + file.type
		　　+ ' - 创建日期: ' + file.creationdate
		　　+ ' - 修改日期: ' + file.modificationdate
		　　+ ' - 文件状态: ' + file.filestatus);///*///
			var ret = jQuery.parseJSON(data);
			if(ret.code == 0){ //上传失败
				alert(ret.message);
			}else{
				var imgurl = ret.url;
				$('.'+selectorId).val(ret.url);
				$('.'+selectorId).html(ret.url);
				$('.'+selectorId).attr('href',ret.url);
				alert("上传成功");
			}

		},
		onDestroy : function() {
			alert('上传插件已被销毁');
		},
		onCancel : function(file) {//上传完成后再点取消无效
			alert('文件：' + file.name + ' 已取消。');
		},
		onUploadError : function(file, errorCode, errorMsg, errorString) {
			alert('文件' + file.name + ' 不能上传：' + errorString);
		},
		 onFallback : function() {
			alert('未找到Flash文件');
		},
		onDisable : function() {
			alert('您已经取消上载插件');
		}
		//itemTemplate : getUploadifyTemplate()
	});
}


function createDropzone(id,uploadUrl,inputName,fileData){
    var prevFile;
    var mockFile;
    if(fileData){
        mockFile = {name:fileData.name,size:fileData.size};
        prevFile = mockFile;
    }
    new Dropzone("div#"+id, {
        //指定上传图片的路径
        url: uploadUrl,
        //dictDefaultMessage: '',
        paramName:inputName,

        //添加上传取消和删除预览图片的链接，默认不添加
        addRemoveLinks: false,

        //关闭自动上传功能，默认会true会自动上传
        //也就是添加一张图片向服务器发送一次请求
        autoProcessQueue: true,

        //允许上传多个照片
        uploadMultiple: false,

        //每次上传的最多文件数，经测试默认为2，坑啊
        //记得修改web.config 限制上传文件大小的节
        parallelUploads: 1,
        clickable: true,
        //previewsContainer:'#imgbox',
		dictDefaultMessage :'<i class="upload-icon ace-icon fa fa-cloud-upload blue fa-3x"></i>',
        //previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-details\">\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n    <div class=\"dz-size\" data-dz-size></div>\n    <img data-dz-thumbnail onclick=\"window.open(this.src)\" />\n  </div>\n  <div class=\"progress progress-small progress-striped active\"><div class=\"progress-bar progress-bar-success\" data-dz-uploadprogress></div></div>\n  <div class=\"dz-success-mark\"><span></span></div>\n  <div class=\"dz-error-mark\"><span></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n</div>",
        previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-image\"><img data-dz-thumbnail style=\"width:100%;height:100%\" /></div>\n  <div class=\"dz-details\">\n    <div class=\"dz-size\"><span data-dz-size></span></div>\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n  </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n  <div class=\"dz-success-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Check</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <path d=\"M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" stroke-opacity=\"0.198794158\" stroke=\"#747474\" fill-opacity=\"0.816519475\" fill=\"#FFFFFF\" sketch:type=\"MSShapeGroup\"></path>\n      </g>\n    </svg>\n  </div>\n  <div class=\"dz-error-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Error</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <g id=\"Check-+-Oval-2\" sketch:type=\"MSLayerGroup\" stroke=\"#747474\" stroke-opacity=\"0.198794158\" fill=\"#FFFFFF\" fill-opacity=\"0.816519475\">\n          <path d=\"M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" sketch:type=\"MSShapeGroup\"></path>\n        </g>\n      </g>\n    </svg>\n  </div>\n</div>",


        init: function () {
            var submitButton = document.querySelector("#submit-all")
            myDropzone = this; // closure
            
            if(mockFile){
                this.emit("addedfile", mockFile);
                this.emit("thumbnail", mockFile, fileData.path);
                this.emit("complete", mockFile);            
            }

            //当添加图片后的事件，上传按钮恢复可用
            this.on("addedfile", function (file) {
				//$(".dz-message").hide();
				$(".dz-image-preview").each(function(){
					if($(this).children(".dz-image").children("img").attr("src")){
						$(this).prev(".dz-message").hide();
					}
				})
                $("#submit-all").removeAttr("disabled");
                if(prevFile){
                    myDropzone.removeFile(prevFile);
                }
                prevFile = file;
            });

            //当上传完成后的事件，接受的数据为JSON格式
            this.on("complete", function (data) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
					var res = eval('(' + data.xhr.responseText + ')');
                    this.emit('thumbnail', prevFile, res.url);
                    // If it needs resizing:
                    this.createThumbnailFromUrl(prevFile, res.url);
                    $("."+id).val(res.url);
                    return ;
                }
            });

            //删除图片的事件，当上传的图片为空时，使上传按钮不可用状态
            this.on("removedfile", function () {
                if (this.getAcceptedFiles().length === 0) {
                    $("#submit-all").attr("disabled", true);
                }
            });
        }
    }

    );

}

(function($){
	//applyImgUploadify("file_upload",'file_upload_queue',session_id,"solution","test");
}(jQuery));