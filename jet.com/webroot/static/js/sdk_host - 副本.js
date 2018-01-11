/* 游戏公共库：HOST */
(function() {
    var sdk = window.JET_SDK || {};
    sdk.hostVersion = "1.0 build 2017092013";
    sdk.wxappId = "wx0437200439052cde";
    sdk.wxopenId = "wx22f69b39568e9cb3";
    sdk.qqappId = "200516427";
    sdk.gameId = 0;
    sdk.shareCallback = null;
    sdk.readyCallback = null;
    sdk.readyMsgList = [];
    sdk.code = null;
    sdk.token = null;
    sdk.uid = null;
    sdk.shareDatas = null;
    sdk.isFocus = false;
    sdk.isHideFocus = false;
    sdk.isBridgeShareReady = false;
    sdk.channelInfo = null;
    sdk.getShareDomain = null;
    sdk.getPreventInfo = null;
    sdk.isTrial = 1; //试玩账号（1：未绑定，0：已绑定）
	sdk.apiHost = "http://api.jet.netkingol.com";
	sdk.userHost = "http://api.jet.netkingol.com";
	sdk.loginHost =  "http://api.jet.netkingol.com";
	sdk.webHost =   "http://api.jet.netkingol.com";
	sdk.cdnHost =   "http://api.jet.netkingol.com";
	sdk.onlineHost =   "http://api.jet.netkingol.com";
	sdk.playHost =   "http://api.jet.netkingol.com";
	sdk.gameHost =   "http://api.jet.netkingol.com";
	
	// 获取后台接口的公共参数 LEX
    sdk.getCommonArgs = function () {
    	var osType = "H5";
		if (sdk.isWeixin()){
			osType = "Weixin";
		}else if(sdk.isQQ()){
			osType = "QQ";
		}else if(sdk.isJetAPP()) {
			if (sdk.isiOS()) {
				osType = "iOS";
			} else if (sdk.isAndroid()) {
				osType = "Android";
			}else{
				osType = "Jet";
			}
		}
		
    	var osVersion = "1.0";
    	var devId = "QWERASSDALKJDSKLDJAAQ112";
    	var adv_channel = "official";
    	var app_version = "1.0";
    	
    	
   		var params = {
			"game_id": sdk.gameId,  
			"os_type": osType,  
			"net_type":0,  
			"device": devId,  
			"sdk_version": sdk.hostVersion, // sdk.clientVersion   
			"adv_channel":adv_channel,  
			"os_version":osVersion,  
			"carrier": 0 ,//运营商类型,未知:0 , 中国移动:10086 , 中国联通:10010 , 中国电信:10000 ，默认为0  
			"app_version": app_version
		};
   		
		return params;
    };
    
	// 追加参数 LEX
    sdk.extendArgs = function (args, exArgs) {
   		var params = {};
   		for(var key in args){
			params[key] = args[key];
		}
        for(var key in exArgs){
			params[key] = exArgs[key];
		}
		return args;
    };
    
	// 设置URL中的参数 LEX
    sdk.setURLArgs = function (url,args) {

        if (url) { 
            var urlList = url.split("#");
            var params = {};
            var query = urlList[0].split("?")[1];
            var result = urlList[0].split("?")[0] + "?";
            if (query) {
                query = query.split("&");
                for (var i in query) {
                    var vars = query[i].split("=");
                    params[vars[0]] = vars[1];
                }
            }
            // 与 sdk.setURLVar 区别的部分
			for(var key in args){
				if (args[key]) {
					params[key] = args[key];
				} else {
					params[key] = null;
					delete params[key];
				}
			}
			
            var first = true;
            for (var i in params) {
                result += ((first ? "" : "&") + i + "=" + (params[i] ? params[i] : ""));
                first = false;
            }
            return result + (urlList[1] ? ("#" + urlList[1]) : "");
        }
        return "";
    };

    
    // 初始化
    sdk.initHost = function (callback) {
        if (!sdk.code) {
            sdk.code = sdk.getURLVar("code");
        }
        if (!sdk.gameId) {
            sdk.gameId = sdk.getURLVar("gameid");
        }

        sdk.token = sdk.getItem("token");

        function __checkSuccess() {
            var fuid = sdk.getURLVar("fuid");
            if (fuid) {
                // 建立好友申请
                $.get(sdk.userHost + "/api/user/wechat?cmd=applyFriend&token=" + sdk.token + "&fuid=" + fuid + "&" + Date.now(), function (data) {
                });
            }

            if (sdk.isJetAPP()) {
                if (sdk.isiOS()) {
                    sdk.initWebViewJavascriptBridge(function () {
                        if (window.WebViewJavascriptBridge) {
                            window.WebViewJavascriptBridge.callHandler("getUserAccount", function (data) {
                                data = data || {};
                                data.uid = sdk.uid;
                                data.token = sdk.token;
                                window.WebViewJavascriptBridge.callHandler("setUserAccount", data);
                            });
                            window.WebViewJavascriptBridge.callHandler("setTitle", document.title);
                        }
                    });
                } else if (sdk.isAndroid()) {
                    try{
                        sdk.initAndroidAPPShare();
                        var data = window.android.getUserAccount();
                        data = data ? JSON.parse(data) : {};
                        data.uid = sdk.uid;
                        data.token = sdk.token;
                        window.android.setUserAccount(JSON.stringify(data));
                        window.android.setTitle(document.title);
                    }catch(e){}
                }
            }
            sdk.statOnline();
            callback();
        }

        function __checkCode() {
            sdk.checkCode(sdk.code, function (data) {
                sdk.setItem("code", sdk.code);
                if (data["token"]) {
                    sdk.token = data["token"];
                    sdk.uid = data["uid"];
                    sdk.isFocus = data["focus"] > 0;
                    sdk.isTrial = data["trial"];
                    sdk.setItem("token", sdk.token);
                    __checkSuccess();
                } else {
                    if (sdk.token) {
                        __checkToken();
                    } else {
                        sdk.auth();
                    }
                }
            });
        }

        function __checkToken() {
            sdk.checkToken(sdk.token, function (data) {
                if (data["uid"]) {
                    sdk.uid = data["uid"];
                    sdk.isFocus = data["focus"] > 0;
                    sdk.isTrial = data["trial"];
                    __checkSuccess();
                } else {
                    if (sdk.getURLVar("tokenkey") && sdk.isDesktopApp()) {
                        __checkTokenKey();
                    } else {
                        sdk.removeItem("token");
                        sdk.auth();
                    }
                }
            });
        }

        function __checkTokenKey() {
            $.get(sdk.apiHost + "/api/login/getTokenByTokenKey?tokenkey=" + sdk.getURLVar("tokenkey"), function (data) {
                if (data.token) {
                    sdk.setItem("token", data.token);
                    sdk.token = sdk.getItem("token");
                    __checkToken();
                } else {
                    sdk.confirmDialog(data.error);
                }
            }, "json")
        }

        if (sdk.getURLVar("tokenkey") && sdk.isDesktopApp()) {
            if (sdk.token) {
                __checkToken();
            } else {
                __checkTokenKey();
            }
        } else {
            if (sdk.code && (sdk.code != sdk.getItem("code"))) { //先验证链接是否有code
                __checkCode();
            } else if (sdk.token) { //再验证是否有token
                __checkToken();
            } else {
                sdk.auth();
            }
        }
    };

    // 授权
    sdk.auth = function (nocache) {
        if (sdk.isWeixin()) {
            sdk.redirectAuth("wx", sdk.gameId, nocache);
        } else if (sdk.isQQ()) {
            sdk.redirectAuth("mqq", sdk.gameId, nocache);
        } else {
            if (sdk.getURLVar("trial")) {
            	var server_url = sdk.loginHost + '/api/login/getTrialUid?gameid=' + sdk.gameId;
            	server_url = sdk.setURLArgs(server_url,sdk.getCommonArgs());
                $.get(server_url , function (data) {
                    var url = location.href;
                    if (sdk.getURLVar('chid')) {
                        url = sdk.buildURL(url, {chid: sdk.getURLVar('chid')});
                    }
                    sdk.createCode(data["token"], function (code) {
                        if (code.code) {
                            url = sdk.buildURL(url, {
                                "code": code.code
                            });
                        }
                        location.href = url;
                    });
                }, 'json')
            } else {
                // 多种登陆方式
                if (sdk.isJetAPP()) {
                    // APP里面
                    if (sdk.isiOS()) {
                        sdk.initWebViewJavascriptBridge(function () {
                            if (window.WebViewJavascriptBridge) {
                                window.WebViewJavascriptBridge.callHandler("setTitle", "登录多纷游戏");
                            }
                        });
                    } else if (sdk.isAndroid()) {
                        window.android.setTitle("登录多纷游戏");
                    }
                    sdk.loadLoginBox(sdk.wxAppLogin, null, sdk.qqLogin, sdk.sinaLogin, sdk.jetLogin, sdk.jetLogin);
                } else {
                    var chid = parseInt(sdk.getURLVar("chid"));
                    if (chid) {
                        sdk.getChannelInfo(chid, function (data) {
                            if (typeof (data.loginHideOption) == 'undefined') {
                                sdk.loadLoginBox(null, sdk.wxQrcodeLogin, sdk.qqLogin, sdk.sinaLogin, sdk.jetLogin, sdk.jetLogin);
                            } else {
                                var loginOption = data.loginHideOption.split(',');
                                sdk.loadLoginBox(null, loginOption[0] == 0 ? sdk.wxQrcodeLogin : null, loginOption[1] == 0 ? sdk.qqLogin : null, loginOption[2] == 0 ? sdk.sinaLogin : null, loginOption[3] == 0 ? sdk.jetLogin : null, loginOption[4] == 0 ? sdk.jetLogin : null);
                            }
                        });
                    } else {
                        sdk.loadLoginBox(null, sdk.wxQrcodeLogin, sdk.qqLogin, sdk.sinaLogin, sdk.jetLogin, sdk.jetLogin);
                    }
                }
            }
        }
    };

    // 微信扫码登陆
    sdk.wxQrcodeLogin = function () {
        sdk.redirectAuth("wxqrcode", sdk.gameId);
    };

    // QQ授权登录
    sdk.qqLogin = function () {
        sdk.redirectAuth("qq", sdk.gameId);
    };

    // 新浪微博授权登录
    sdk.sinaLogin = function () {
        sdk.redirectAuth("sina", sdk.gameId);
    };

    // 微信APP授权登陆
    sdk.wxAppLogin = function () {
        if (sdk.isiOS()) {
            sdk.initWebViewJavascriptBridge(function () {
                if (window.WebViewJavascriptBridge) {
                    WebViewJavascriptBridge.callHandler("wxAuth", function (data) {
                        if (data) {
                            // 授权成功
                            location.href = sdk.buildURL(location.href, {
                                token: data["token"],
                                uid: data["uid"],
                                code: null
                            });
                        } else {
                            sdk.confirmDialog("已取消授权登录");
                        }
                    });
                }
            });
        } else if (sdk.isAndroid()) {
            window.__jetAuthCallback = function (data) {
                if (data) {
                    // 授权成功
                    data = JSON.parse(data);
                    location.href = sdk.buildURL(location.href, {
                        token: data["token"],
                        uid: data["uid"],
                        code: null
                    });
                } else {
                    sdk.confirmDialog("已取消授权登录");
                }
            };
            window.android.wxAuth("__jetAuthCallback");
        }
    };

    // 重定向至授权:
    // pf 1 微信 2 微信二维码 3 手机QQ
    sdk.redirectAuth = function (pf, gameid, nocache) {
        var url = sdk.loginHost + "/api/login/redirectAuth?pf=" + pf + "&gameid=" + gameid;
        url = sdk.setURLArgs(url,sdk.getCommonArgs());
        if (nocache) {
            url += ("&nocache=1");
        }
        if (!gameid) {
            var _url = location.href;
            if (_url.indexOf('?') >= 0) {
                _url = sdk.cleanURLVar(_url);
            } else if (_url.indexOf('#') >= 0) {
                _url = _url.split('#')[0];
            }
            url += ("&back_url=" + encodeURIComponent(_url));
        }
        if (sdk.getURLVar("trial")) {
            url += ("&trial=1&trialBind=1&token=" + sdk.token);
        }
        var trans = ["chid", "subchid", "fuid", "share_from"];
        var args = {};
        var query = sdk.getURLQuery(location.href);
        var paramKeys = Object.keys(query);
        for (var i in paramKeys) {
            var key = paramKeys[i];
            if (trans.indexOf(key) >= 0 || key.indexOf("cp_") == 0) {
                args[key] = query[key];
            }
        }
        url = sdk.buildURL(url, args);
        location.href = url;
    };

    // token交换code
    sdk.createCode = function (token, callback) {
        $.get(sdk.apiHost + "/api/login/getCodeByToken?token=" + token + "&" + Date.now(), function (data) {
            callback(data);
        }, "json");
    };

    // code交换token与uid
    sdk.checkCode = function (code, callback) {
        $.get(sdk.apiHost + "/api/login/checkCode?code=" + code + "&" + Date.now(), function (data) {
            callback(data);
        }, "json");
    };

    // token交换userToken与uid
    sdk.getUserToken = function (token, callback) {
        $.get(sdk.apiHost + "/api/login/getUserToken?token=" + token + "&" + Date.now(), function (data) {
            callback(data);
        }, "json");
    };

    // token交换uid
    sdk.checkToken = function (token, callback) {
        $.get(sdk.apiHost + "/api/login/checkToken?token=" + token + "&" + Date.now(), function (data) {
            callback(data);
        }, "json");
    };

    //获取CPS配置
    sdk.getChannelInfo = function (chid, callback) {
        if (sdk.channelInfo) {
            callback(sdk.channelInfo);
        } else {
            $.get(sdk.apiHost + "/api/conf/getCpsConfig?chid=" + chid + "&" + Date.now(), function (data) {
                sdk.channelInfo = data.config;
                callback(sdk.channelInfo);
            }, "json");
        }
    };

    //获取设备类型
    sdk.checkDevice = function () {
        $.get(sdk.apiHost + '/conf?cmd=checkDevice&token=' + sdk.token + "&" + Date.now(), function () {
        }, 'json');
    };

    // 初始化完成
    sdk.wxReady = function () {
        if (sdk.readyCallback) {
            sdk.readyCallback();
        }
        sdk.readyCallback = null;
        for (var i in sdk.readyMsgList) {
            sdk.execMessage(sdk.readyMsgList[i]);
        }
        sdk.readyMsgList = null;
    };

    // 初始化环境
    sdk.wxInit = function (shareCallback, readyCallback) {
        sdk.shareCallback = shareCallback;
        sdk.readyCallback = readyCallback;

        $.get(sdk.webHost + "/api/share/getShareDomain?gameid=" + sdk.gameId + "&token=" + sdk.token + "&" + Date.now(), function (data) {
            var shareURL = data["shareURL"] || "";
            var shareTitle = decodeURIComponent(data["title"]);
            var shareDesc = decodeURIComponent(data["desc"]);
            var shareImg = data["img"];
            var entryURL = data["entry_url"];
            var timeline = data["timeline"];
            var isMsgUseShareURL = false;

            if (shareURL.indexOf("@") == 0) {
                shareURL = shareURL.replace("@", "");
                isMsgUseShareURL = true;
            } else if (shareURL) {
                shareURL = sdk.setURLVar(shareURL, "gameid", sdk.gameId);
            }

            var msgURL = sdk.setURLVar(entryURL, "share_from", "msg");
            if (sdk.uid) {
                if (shareURL) {
                    shareURL = sdk.setURLVar(shareURL, "fuid", sdk.uid);
                }
                msgURL = sdk.setURLVar(msgURL, "fuid", sdk.uid);
            }

            var chid = parseInt(sdk.getURLVar("chid"));
            if (chid > 0) {
                if (shareURL) {
                    shareURL = sdk.setURLVar(shareURL, "chid", chid);
                }
                msgURL = sdk.setURLVar(msgURL, "chid", chid);
                var subchid = sdk.getURLVar("subchid");
                if (subchid) {
                    if (shareURL) {
                        shareURL = sdk.setURLVar(shareURL, "subchid", subchid);
                    }
                    msgURL = sdk.setURLVar(msgURL, "subchid", subchid);
                }
            }
            //额外存储一份分享数据
            sdk.getShareDomain = {
                title: shareTitle,
                text: shareDesc,
                image: shareImg
            };

            // 默认分享数据
            sdk.shareDatas = [{
                wxsession: true,
                wxtimeline: ((timeline && shareURL) ? true : false),
                qq: false,
                qzone: false
            }, {
                title: shareTitle,
                text: shareDesc,
                image: shareImg,
                url: msgURL,
                wxsession: ((isMsgUseShareURL && shareURL) ? sdk.setURLVar(shareURL, "share_from", "msg") : msgURL),
                wxtimeline: shareURL ? sdk.setURLVar(shareURL, "share_from", "timeline") : msgURL,
                qq: msgURL,
                qzone: msgURL
            }];

            if (sdk.isWeixin()) {
                $.get(sdk.apiHost + "/api/share/getWechatJsTicket?url=" + encodeURIComponent(location.href) + "&gameid=" + sdk.gameId + "&" + Date.now(), function (data) {
                    data = data["data"];
                    wx.config({
                        debug: false,
                        appId: data["appid"] ? data["appid"] : sdk.wxappId,
                        timestamp: data["timestamp"],
                        nonceStr: data["noncestr"],
                        signature: data["sign"],
                        jsApiList: ["onMenuShareTimeline", "onMenuShareAppMessage", "onMenuShareQQ", "onMenuShareWeibo", "hideOptionMenu", "showOptionMenu", "hideMenuItems", "showMenuItems", "chooseWXPay", "closeWindow",
                            "startRecord", "stopRecord", "playVoice", "stopVoice", "onVoicePlayEnd", "uploadVoice", "downloadVoice"]
                    });
                    wx.ready(function () {
                        var isFavorite = false;
                        sdk.shareDatas = [{
                            title: sdk.getShareDomain.text,
                            link: sdk.setURLVar(shareURL, "share_from", "timeline"),
                            imgUrl: sdk.getShareDomain.image,
                            trigger: function (res) {
                            },
                            success: function (res) {
                                sdk.confirmShare();
                                if (sdk.shareCallback) {
                                    sdk.shareCallback("onMenuShareTimeline");
                                }
                            },
                            cancel: function (res) {
                            },
                            fail: function (res) {
                            }
                        }, {
                            title: shareTitle,
                            desc: shareDesc,
                            link: ((isMsgUseShareURL && shareURL) ? sdk.setURLVar(shareURL, "share_from", "msg") : msgURL),
                            imgUrl: shareImg,
                            trigger: function (res) {
                                isFavorite = (res.shareTo == "favorite");
                            },
                            success: function (res) {
                                if (!isFavorite) {
                                    if (sdk.shareCallback) {
                                        sdk.shareCallback("onMenuShareAppMessage");
                                    }
                                }
                            },
                            cancel: function (res) {
                            },
                            fail: function (res) {
                            }
                        }, {
                            title: shareTitle,
                            desc: shareDesc,
                            link: msgURL,
                            imgUrl: shareImg,
                            trigger: function (res) {
                            },
                            complete: function (res) {
                            },
                            success: function (res) {
                            },
                            cancel: function (res) {
                            },
                            fail: function (res) {
                            }
                        }, {
                            title: shareTitle,
                            desc: shareDesc,
                            link: msgURL,
                            imgUrl: shareImg,
                            trigger: function (res) {
                            },
                            complete: function (res) {
                            },
                            success: function (res) {
                            },
                            cancel: function (res) {
                            },
                            fail: function (res) {
                            }
                        }];
                        wx.onMenuShareTimeline({
                            title: sdk.getShareDomain.text,
                            link: sdk.setURLVar(shareURL, "share_from", "timeline"),
                            imgUrl: sdk.getShareDomain.image,
                            trigger: function (res) {
                            },
                            success: function (res) {
                                sdk.confirmShare();
                                if (sdk.shareCallback) {
                                    sdk.shareCallback("onMenuShareTimeline");
                                }
                            },
                            cancel: function (res) {
                            },
                            fail: function (res) {
                            }
                        });
                        wx.onMenuShareAppMessage(sdk.shareDatas[1]);
                        wx.onMenuShareQQ(sdk.shareDatas[2]);
                        wx.onMenuShareWeibo(sdk.shareDatas[3]);

                        var hideMenuList = ["menuItem:share:weiboApp", "menuItem:share:facebook", "menuItem:share:qq", "menuItem:share:QZone"];
                        if (!timeline || !shareURL) {
                            hideMenuList.push("menuItem:share:timeline");
                        }
                        wx.hideMenuItems({
                            menuList: hideMenuList
                        });
                        sdk.wxReady();
                    });
                }, "json");
            } else if (sdk.isQQ()) {
                $.get(sdk.apiHost + "/api/share/getMobileQQJsTicket?url=" + encodeURIComponent(location.href) + "&" + Date.now(), function (data) {
                    data = data["data"];
                    mqq.config({
                        debug: false,
                        appId: sdk.qqappId,
                        timestamp: data["timestamp"],
                        nonceStr: data["noncestr"],
                        signature: data["sign"],
                        jsApiList: ["onMenuShareTimeline", "onMenuShareAppMessage", "onMenuShareQQ", "onMenuShareQzone", "hideOptionMenu", "showOptionMenu", "hideMenuItems", "showMenuItems", "closeWindow"]
                    });
                    mqq.error(function (res) {
                        console.log("mqqerr:" + JSON.stringify(res));
                    });
                    mqq.ready(function () {
                        mqq.hideMenuItems({
                            menuList: ["menuItem:share:appMessage", "menuItem:share:timeline", "menuItem:share:QZone"]
                        });
                        sdk.shareDatas = [{
                            title: shareDesc,
                            link: shareURL ? sdk.setURLVar(shareURL, "share_from", "timeline") : msgURL,
                            imgUrl: shareImg,
                            success: function (res) {
                                sdk.confirmShare();
                                if (sdk.shareCallback) {
                                    sdk.shareCallback("onMenuShareTimeline");
                                }
                            },
                            cancel: function (res) {
                            }
                        }, {
                            title: shareTitle,
                            desc: shareDesc,
                            link: ((isMsgUseShareURL && shareURL) ? sdk.setURLVar(shareURL, "share_from", "msg") : msgURL),
                            imgUrl: shareImg,
                            success: function (res) {
                                if (sdk.shareCallback) {
                                    sdk.shareCallback("onMenuShareAppMessage");
                                }
                            },
                            cancel: function (res) {
                            }
                        }, {
                            title: shareTitle,
                            desc: shareDesc,
                            link: ((isMsgUseShareURL && shareURL) ? sdk.setURLVar(shareURL, "share_from", "msg") : msgURL),
                            imgUrl: shareImg,
                            success: function (res) {
                                if (sdk.shareCallback) {
                                    sdk.shareCallback("onMenuShareQQ");
                                }
                            },
                            cancel: function (res) {
                            }
                        }, {
                            title: shareTitle,
                            desc: shareDesc,
                            link: msgURL,
                            imgUrl: shareImg,
                            success: function (res) {
                                if (sdk.shareCallback) {
                                    sdk.shareCallback("onMenuShareQzone");
                                }
                            },
                            cancel: function (res) {
                            }
                        }];
                        mqq.onMenuShareTimeline(sdk.shareDatas[0]);
                        mqq.onMenuShareAppMessage(sdk.shareDatas[1]);
                        mqq.onMenuShareQQ(sdk.shareDatas[2]);
                        mqq.onMenuShareQzone(sdk.shareDatas[3]);
                        sdk.wxReady();
                    });
                }, "json");
            } else {
                if (sdk.isJetAPP()) {
                    sdk.wxAppInit();
                } else {
                    sdk.wxReady();
                }
            }
        }, "json");
    };

    // 初始化APP分享环境
    sdk.wxAppInit = function () {
        if (sdk.isiOS()) {
            sdk.initWebViewJavascriptBridge(function () {
                if (window.WebViewJavascriptBridge) {
                    window.WebViewJavascriptBridge.callHandler("setShare", sdk.shareDatas[0]);
                    window.WebViewJavascriptBridge.callHandler("setShareContent", sdk.shareDatas[1]);
                    sdk.wxReady();
                }
            });
        } else if (sdk.isAndroid()) {
            window.android.setShare(JSON.stringify(sdk.shareDatas[0]));
            window.android.setShareContent(JSON.stringify(sdk.shareDatas[1]));
            sdk.wxReady();
        }
    };

    // 记录最后一次玩的游戏
    sdk.userPlay = function () {
        if (sdk.token) {
            var url = sdk.apiHost + "/api/busi/userPlay?cmd=add&gameid=" + sdk.gameId + "&token=" + sdk.token;
            var chid = sdk.getURLVar("chid");
            var subchid = sdk.getURLVar("subchid");
            var from = sdk.getURLVar('share_from');
            if (chid) {
                url += ("&chid=" + chid);
            }
            if (subchid) {
                url += ("&subchid=" + subchid);
            }
            if (from) {
                url += ("&from=" + from);
            }
            $.get(url + "&" + Date.now(), function (data) {
            });
        }
    };

    // 分享统计
    sdk.confirmShare = function () {
        $.get(sdk.webHost + "/api/share/confirmShare?gameid=" + sdk.gameId + "&" + Date.now(), function (data) {
        }, "json");
    };

    // 执行显示关注功能
    sdk.showFocus = sdk.focus = function (args) {
        function loadFocus(e) {
            e = e || {};
            if (!$("#focus").length) {
                if (e.title) {
                    switch (parseInt(e.title)) {
                        case 1:
                            sdk.focus_title = '进入“多纷游戏Plus”';
                            break;
                    }
                }
                if (sdk.channelInfo) {
                    if (sdk.channelInfo.replaceQrcodeUrl) {
                        sdk.qrcode_default = sdk.channelInfo.replaceQrcodeUrl;
                        sdk.focus_title = "更多精彩 请关注公众号";
                    }
                } else if (e.url) {
                    sdk.qrcode_default = e.url;
                }
                var t = '<div id="focus" class="mask focus">';
                t += '<div>';
                t += '<div>';
                if (!sdk.channelInfo) {
                    t += '<img src=" ' + (sdk.logo_default ? sdk.logo_default : sdk.cdnHost + '/static/image/focus/banner_logo.png') + '" >';
                }
                t += '</div>';
                t += '<div><h1>' + (sdk.focus_title ? sdk.focus_title : '关注“多纷游戏Plus”') + '</h1><img src="' + (sdk.qrcode_default ? sdk.qrcode_default : sdk.cdnHost + '/static/image/qrcode_for_jet.png') + '" ><span>';
                if (e.desc) {
                    t += e.desc;
                } else {
                    if (sdk.isQQ() || sdk.isWeixin()) {
                        t += '长按识别二维码'
                    } else {
                        t += '微信扫一扫二维码';
                    }
                }
                t += '</span></div>';
                t += '</div></div>';
                $("body").append(t);
                $("#focus").off().click(function () {
                    $("#focus").remove();
                });
            }
        }

        if (args) {
            loadFocus(args);
        } else {
            if (sdk.isQQ()) {
                location.href = "https://share.mp.qq.com/cgi/share.php?uin=xxx&account_flag=xx&jumptype=1&card_type=public_account";
            } else {
                loadFocus();
            }
        }
    };

    // 执行显示分享层功能
    sdk.showShare = sdk.share = function (args) {
        args = args || {};
        var t = '<div id="share-square" onclick="sdk.hideShare()" class="mask share-square">';
        t += '<div>';
        t += '<img src="' + (args.finger ? args.finger : sdk.cdnHost+'/static/image/share_finger.png') + '">';
        t += '<img src="' + (args.body ? args.body : sdk.cdnHost+'/static/image/share_finger_body.png') + '">';
        t += '<span><i class="icon-share"></i>';
        if (sdk.isQQ()) {
            t += '发送到QQ群';
        } else {
            t += '发送到微信群';
        }
        t += '</span>';
        t += '</div></div>';
        $("body").append(t);
    };

    // 执行隐藏分享层功能
    sdk.hideShare = function () {
        $("#share-square").remove();
    };

    // 执行登出功能
    sdk.logout = function () {
        sdk.removeItem("token");
        sdk.auth(true);
    };

    // 执行显示好友功能
    sdk.showFriend = function () {
        $("body").append('<div id="friendFrameDiv" style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px;"><iframe id="friendFrame" name="gameIframe" src="'+sdk.cdnHost+'/friend/friend.html?v=' + Date.now() + '&token=' + sdk.token + '" frameborder="no" border="0px" marginwidth="0px" marginheight="0px" scrolling="auto" style="width: 100%; height: 100%;"></iframe></div>');
    };

    // 执行隐藏好友功能
    sdk.hideFriend = function () {
        $("#friendFrameDiv").remove();
    };

    // 执行设置分享语功能
    sdk.shareDesc = function (desc) {
        if (sdk.shareDatas) {
            if (sdk.isWeixin() || sdk.isQQ()) {
                for (var i = 0; i < sdk.shareDatas.length; i++) {
                    if (sdk.shareDatas[i]) {
                        if (sdk.shareDatas[i].desc) {
                            sdk.shareDatas[i].desc = desc;
                        } else {
                            sdk.shareDatas[i].title = desc;
                        }
                    }
                }
                if (sdk.isQQ()) {
                    mqq.onMenuShareTimeline(sdk.shareDatas[0]);
                    mqq.onMenuShareAppMessage(sdk.shareDatas[1]);
                    mqq.onMenuShareQQ(sdk.shareDatas[2]);
                    mqq.onMenuShareQzone(sdk.shareDatas[3]);
                }
            } else if (sdk.isJetAPP()) {
                sdk.shareDatas[1].text = desc;
                sdk.wxAppInit();
            }
        }
    };

    // 执行设置分享标题
    sdk.shareTitle = function (title) {
        if (sdk.shareDatas) {
            if (sdk.isWeixin() || sdk.isQQ()) {
                for (var i = 0; i < sdk.shareDatas.length; i++) {
                    if (sdk.shareDatas[i]) {
                        sdk.shareDatas[i].title = title;
                    }
                }
                if (sdk.isQQ()) {
                    mqq.onMenuShareTimeline(sdk.shareDatas[0]);
                    mqq.onMenuShareAppMessage(sdk.shareDatas[1]);
                    mqq.onMenuShareQQ(sdk.shareDatas[2]);
                    mqq.onMenuShareQzone(sdk.shareDatas[3]);
                }
            } else if (sdk.isJetAPP()) {
                sdk.shareDatas[1].title = title;
                sdk.wxAppInit();
            }
        }
    };

    // 执行设置分享图标
    sdk.shareIcon = function (url) {
        if (!url) {
            if (sdk.getShareDomain) {
                url = sdk.getShareDomain.image;
            }
        }
        if (sdk.shareDatas) {
            if (sdk.isWeixin() || sdk.isQQ()) {
                for (var i = 0; i < sdk.shareDatas.length; i++) {
                    if (sdk.shareDatas[i]) {
                        sdk.shareDatas[i].imgUrl = url;
                    }
                }
                if (sdk.isQQ()) {
                    mqq.onMenuShareTimeline(sdk.shareDatas[0]);
                    mqq.onMenuShareAppMessage(sdk.shareDatas[1]);
                    mqq.onMenuShareQQ(sdk.shareDatas[2]);
                    mqq.onMenuShareQzone(sdk.shareDatas[3]);
                }
            } else if (sdk.isJetAPP()) {
                sdk.shareDatas[1].image = url;
                sdk.wxAppInit();
            }
        }
    };

    // 执行设置分享参数功能
    sdk.shareParams = function (params) {
        if (sdk.shareDatas) {
            var args = {};
            var paramKeys = Object.keys(params);
            for (var i in paramKeys) {
                var key = paramKeys[i];
                if (key.indexOf("cp_") == 0) {
                    args[key] = params[key];
                }
            }
            if (Object.keys(params).length) {
                if (sdk.isWeixin() || sdk.isQQ()) {
                    for (var i = 0; i < sdk.shareDatas.length; i++) {
                        sdk.shareDatas[i].link = sdk.buildURL(sdk.shareDatas[i].link, args);
                    }
                    if (sdk.isQQ()) {
                        mqq.onMenuShareTimeline(sdk.shareDatas[0]);
                        mqq.onMenuShareAppMessage(sdk.shareDatas[1]);
                        mqq.onMenuShareQQ(sdk.shareDatas[2]);
                        mqq.onMenuShareQzone(sdk.shareDatas[3]);
                    }
                } else if (sdk.isJetAPP()) {
                    sdk.shareDatas[1].url = sdk.buildURL(sdk.shareDatas[1].url, args);
                    sdk.shareDatas[1].wxsession = sdk.buildURL(sdk.shareDatas[1].wxsession, args);
                    sdk.shareDatas[1].wxtimeline = sdk.buildURL(sdk.shareDatas[1].wxtimeline, args);
                    sdk.shareDatas[1].qq = sdk.buildURL(sdk.shareDatas[1].qq, args);
                    sdk.shareDatas[1].qzone = sdk.buildURL(sdk.shareDatas[1].qzone, args);
                    sdk.wxAppInit();
                }
            }
        }
    };

    // 执行统计功能
    sdk.stat = function (item, sub_item, sub_sec_item) {
    };

    // 执行显示顶层图片
    sdk.showTopImg = function (args) {
        if (args.renew) {
            $("div#showTopImg").remove();
        }
        if (!$("#showTopImg").length) {
            var html = '<div id="showTopImg" class="mask showTopImg">';
            html += '<div>';
            if (args.pos == 'reverse') {
                html += '<img class="r5" src="' + args.src + '">';
                if (args.title) {
                    html += '<div>' + args.title + '</div>';
                }
            } else {
                if (args.title) {
                    html += '<div>' + args.title + '</div>';
                }
                html += '<img class="r5" src="' + args.src + '">';
            }
            html += '</div></div>';
            $("body").append(html);
            $("#showTopImg").click(function () {
                $("div#showTopImg").remove();
            });
        }
    };

    //复制
    sdk.copy = function (string, txt) {
        var __copy = function () {
            sdk.confirmDialog(txt ? txt : '复制成功', null, '确认', null, function () {
                setTimeout(function () {
                    clipboard.destroy();
                }, 10);
            }, '复制提示');

            var clipboard = new Clipboard('.dialog-box a.unselect', {
                text: function () {
                    return string;
                }
            });
            clipboard.on('success', function (e) {
                e.clearSelection();
            });
            clipboard.on('error', function (e) {
                var t = '<div class="flex gift-dialog"><label>SN：</label><div class="r3">' + string + '</div></div><p class="gift-dialog-info">长按可复制</p>';
                sdk.confirmDialog(t, null, null, null, null, '手动复制提示');
            });
        };

        var __loadClipboard = function () {
            if (window.Clipboard) {
                __copy();
            } else {
                sdk.loadSingleScript(sdk.cdnHost + "/static/js/sdk_copy.min.js", function () {
                    __copy();
                });
            }
        };
        __loadClipboard();
    };

    // 执行绑定提现
    sdk.bindCash = function () {
        location.href = sdk.playHost + "/cashbind/";
    };

    // 显示LOADING遮罩层
    sdk.showLoading = function () {
        if (!$("#loadingBox").length) {
            var html = '<div id="loadingBox" class="mask loading"><div><div></div><div></div><div></div><div></div><div></div></div></div>';
            $("body").append(html);
        }
    };

    // 隐藏LOADING遮罩层
    sdk.hideLoading = function () {
        $("#loadingBox").remove();
    };

    // 关闭支付弹出层
    sdk.closePayBox = function () {
        $("#payBox").remove();
    };

    // 关闭条例
    sdk.closePayRule = function () {
        $("#pay-rule").hide();
    };

    // 唤醒支付弹出层
    sdk.loadPayBox = function (args, wxpayCallback, alipayCallback, qqpayCallback) {
        if (!$("#payBox").length) {
            var t = '<div id="payBox" class="mask pay-box">';
            t += '<div class="r5">';
            t += '<div class="pay-head">确认支付方式<i class="icon-cancel" onclick="sdk.closePayBox()"></i></div>';
            t += '<div class="pay-info">';
            t += '<p id="pname"></p>';
            t += '<p id="price"></p>';
            t += '</div>';
            t += '<div class="pay-mode">';
            t += '<ol id="pay-list"></ol>';
            t += '</div>';
            t += '<a href="javascript:;" class="btn" id="readyPay">立即支付</a>';
            t += '<div class="flex pay-tip"><span class="flex-list" id="puid"></span><p class="active"><span>同意</span><a href="javascript:;">多纷游戏玩家条例</a></p></div>';
            t += '</div></div>';
            $("body").append(t);
        }
        $.get(sdk.apiHost + "/conf?cmd=getProductInfo&pid=" + args["pid"] + "&" + Date.now(), function (data) {
            $("#pname").html(data["productName"] + '（' + data["des"] + '）');
            if (args["product_count"]) {
                data["cost"] = data["cost"] * args["product_count"];
            }
            if (Math.floor(data["cost"]) == data["cost"]) {
                $("#price").html('¥ ' + data["cost"] + ".00");
            } else {
                $("#price").html('¥ ' + data["cost"]);
            }
        }, "json");
        if (wxpayCallback) {
            if (sdk.channelInfo) {
                if (!parseInt(sdk.channelInfo.hideWxPay)) {
                    $("#pay-list").append('<li data-type="wxpay" class="flex"><i class="icon-wxpay"></i><span class="flex-list">微信支付</span></li>');
                }
            } else {
                $("#pay-list").append('<li data-type="wxpay" class="flex"><i class="icon-wxpay"></i><span class="flex-list">微信支付</span></li>');
            }
        }
        if (alipayCallback) {
            $("#pay-list").append('<li data-type="alipay" class="flex"><i class="icon-alipay"></i><span class="flex-list">支付宝支付</span></li>');
        }
        if (qqpayCallback) {
            $("#pay-list").append('<li data-type="qqpay" class="flex"><i class="icon-qqpay"></i><span class="flex-list">QQ钱包支付</span></li>');
        }
        $("#puid").html('UID:' + sdk.uid);

        var _type = null, _isReady = true;

        $("#pay-list").off().on("click", "li", function () {
            _type = $(this).attr("data-type");
            $(this).addClass("active").siblings().removeClass("active");
        });

        $("#pay-list li").eq(0).trigger("click");

        $(".pay-tip>p").off().click(function (e) {//条例同意/取消
            if ($(e.target).html() != "多纷游戏玩家条例") {
                if ($(this).attr("class").indexOf("active") >= 0) {
                    $(this).removeClass("active");
                    _isReady = false;
                } else {
                    $(this).addClass("active");
                    _isReady = true;
                }
            }
        });

        $(".pay-tip>p a").off().click(function () {//查看多纷游戏玩家条例
            if (!$("#pay-rule").length) {
                var t = '<div id="pay-rule" class="mask pay-box pay-rule">';
                t += '<div class="r5">';
                t += '<div class="pay-head">多纷游戏玩家条例<i class="icon-cancel" onclick="sdk.closePayRule()"></i></div>';
                t += '<div id="pay-rule-box"></div>';
                t += '</div>';
                t += '</div>';
                $("body").append(t);
                $("#pay-rule-box").load('./contact.html .wrap', function () {
                });
            } else {
                $("#pay-rule").show();
            }
        });

        $("#readyPay").off().click(function () {
            if (!_isReady) {
                sdk.confirmDialog('支付前请先同意多纷游戏玩家条例！');
                return;
            }
            if (_type == "wxpay") {
                //微信支付
                wxpayCallback();
            } else if (_type == "alipay") {
                //支付宝支付
                alipayCallback();
            } else if (_type == "qqpay") {
                //QQ钱包支付
                qqpayCallback();
            }
            sdk.closePayBox();
            sdk.showLoading();
        });
    };

    // 执行支付功能
    sdk.pay = sdk.jumpPay = function (args, callback) {
        if (!sdk.isAllowPay()) {
            sdk.confirmDialog("支付系统暂不可用！");
        } else {
            args = args || {};
            if (args["pid"]) {
                if (1 || sdk.isJetAPP()) {
                    // 多纷游戏APP环境
                    sdk.loadPayBox(args, function () {
                        sdk.wxAppPay(args, callback);
                    }, function () {
                        sdk.aliAppPay(args, callback);
                    }, null);
                } else if (sdk.isWeixin()) {
                    // 微信环境
                    sdk.showLoading();
                    if (sdk.isPCWeixin()) {
                        // PC上的微信环境
                        sdk.wxqrcodePay(args, callback);
                    } else {

                        if (sdk.channelInfo && parseInt(sdk.channelInfo.alih5pay)) {
                            sdk.loadPayBox(args, function () {
                                sdk.wxPay(args, callback);
                            }, function () {
                                sdk.alih5Pay(args, callback);
                            }, null);
                            sdk.hideLoading();
                        } else {
                            sdk.wxPay(args, callback);
                        }
                    }
                } else if (sdk.isQQ()) {
                    // 手机QQ环境
                    // sdk.loadPayBox(args, null, function () {
                    //     sdk.aliPay(args, callback);
                    // }, function () {
                    //     sdk.qqPay(args, callback);
                    // });
                    sdk.qqPay(args, callback);
                } else {
                    // 浏览器环境
                    if (sdk.channelInfo && parseInt(sdk.channelInfo.hideWxPay)) {
                        sdk.loadPayBox(args, null, function () {
                            sdk.aliPay(args, callback);
                        }, null);
                    } else {
                        sdk.loadPayBox(args, function () {
                            if (sdk.isMobile()) {
                                sdk.wxh5Pay(args, callback);
                            } else {
                                sdk.wxqrcodePay(args, callback);
                            }
                        }, function () {
                            sdk.aliPay(args, callback);
                        }, null);
                    }
                }
            } else {
                args.uid = sdk.uid;
                args.gameid = sdk.gameId;
                var url = sdk.loginHost + "/pay/pay/redirectPay?";
                for (var i in args) {
                    url += (i + "=" + args[i] + "&");
                }
                location.href = url;
            }
        }
    };

    // 微信支付
    sdk.wxPay = function (args, callback) {
        if (args) {
            if (sdk.channelInfo && parseInt(sdk.channelInfo.wxh5pay)) {
                sdk.wxh5Pay(args, null, function (data) {
                    window.location.href = sdk.buildURL(sdk.playHost + "/pay/wxpay/wxpay", {
                        gameid: sdk.gameId,
                        trans_id: data.trans_id,
                        url: encodeURIComponent(data.mweb_url)
                    });
                });
            } else {
                var pid = args["pid"];
                var userdata = args["userdata"];
                var txid = args["txid"];
                var number = args["product_count"];
                var url = sdk.loginHost + "/pay/wxpay/js_api_call?" + "gameid=" + sdk.gameId + "&uid=" + sdk.uid + "&product_id=" + pid;
                if (userdata) {
                    url += ("&userdata=" + userdata);
                }
                if (txid) {
                    url += ("&txid=" + txid);
                }
                if (number) {
                    url += ("&product_count=" + number);
                }
                $.get(url, function (data) {
                    var payData = data;
                    if (payData["error"]) {
                        return alert("支付生成订单失败[" + payData["error"] + "]，请稍候再试!");
                    }
                    sdk.hideLoading();
                    wx.chooseWXPay({
                        timestamp: payData["timestamp"],
                        nonceStr: payData["nonceStr"],
                        package: payData["package"],
                        signType: payData["signType"],
                        paySign: payData["paySign"],
                        success: function (res) {
                            if (callback) {
                                callback();
                            }
                        },
                        fail: function (err) {
                            sdk.wxh5Pay(args, null, function (data) {
                                window.location.href = sdk.buildURL(sdk.playHost + "/pay/wxpay/wxpay", {
                                    gameid: sdk.gameId,
                                    trans_id: data.trans_id,
                                    url: encodeURIComponent(data.mweb_url)
                                });
                            });
                        },
                        cancel: function () {
                            sdk.confirmDialog("支付订单已取消");
                        }
                    });
                }, "json");
            }
        } else {
            sdk.hideLoading();
        }
    };

    // 检查订单支付状态：true 完成 false 未支付
    sdk.checkTransStatus = function (trans_id, callback) {
        $.get(sdk.loginHost + "/pay/paygate/isTransPaied?trans_id=" + trans_id + "&" + Date.now(), function (data) {
            callback(data == "YES");
        });
    };

    // 微信APP支付
    sdk.wxAppPay = function (args, callback) {
        var url = sdk.buildURL(sdk.isJetboxAPP() ? sdk.loginHost + "/pay/wxapp_jetbox/js_api_call" : sdk.loginHost + "/pay/weixinapp/js_api_call", {
            gameid: sdk.gameId,
            uid: sdk.uid,
            product_id: args["pid"],
            userdata: args["userdata"],
            txid: args["txid"],
            app_pay: 1,
            _v: Date.now()
        });
        var number = args["product_count"];
        if (number) {
            url += ("&product_count=" + number);
        }

        window.__androidPayCallback = function (errCode) {
            sdk.hideConfirmDialog();
            var errCode = Number(errCode);
            if (errCode) {
                if (errCode == -2) {
                    sdk.confirmDialog("支付订单已取消");
                } else {
                    sdk.confirmDialog("支付失败" + errCode);
                }
            } else {
                // 支付成功
                if (callback) {
                    callback();
                }
            }
        };

        $.get(url, function (data) {
            var payData = {
                partnerId: sdk.isJetboxAPP() ? "1480877232" : "1251302701",
                prepayId: data.prepayId,
                package: "Sign=WXPay",
                nonceStr: data.nonceStr,
                timeStamp: data.timestamp,
                sign: data.appsign
            };
            sdk.hideLoading();
            setTimeout(function () {
                sdk.confirmDialog("确认支付完成了吗？", "确定", "取消", function () {
                    sdk.checkTransStatus(data.trans_id, function (status) {
                        if (status) {
                            // 支付成功
                            if (callback) {
                                callback();
                            }
                        } else {
                            sdk.confirmDialog("支付没有完成，如果确认已支付但游戏内未显示，请尝试刷新游戏，避免重复支付！");
                        }
                    });
                }, function () {
                });
            }, 500);
            if (sdk.isAndroid()) {
                return window.android.wxPay(JSON.stringify(payData), "__androidPayCallback");
            } else {
                sdk.initWebViewJavascriptBridge(function () {
                    window.WebViewJavascriptBridge.callHandler("wxPay", payData, function (errCode) {
                        sdk.hideConfirmDialog();
                        if (errCode) {
                            // 支付失败
                            // -1 错误  可能的原因：签名错误、未注册APPID、项目设置APPID不正确、注册的APPID与设置的不匹配、其他异常等。
                            // -2 用户取消  无需处理。发生场景：用户不支付了，点击取消，返回APP。
                            if (errCode == -2) {
                                sdk.confirmDialog("支付订单已取消");
                            } else {
                                sdk.confirmDialog("支付失败" + errCode);
                            }
                        } else {
                            // 支付成功
                            if (callback) {
                                callback();
                            }
                        }
                    });
                });
            }
        }, "json");
    };

    // QQ钱包支付
    sdk.qqPay = function (args, callback) {
        function __qqPay() {
            var url = sdk.buildURL(sdk.loginHost + "/pay/qpay/payRequest", {
                gameid: sdk.gameId,
                uid: sdk.uid,
                product_id: args["pid"],
                userdata: args["userdata"],
                txid: args["txid"],
                _v: Date.now()
            });
            var number = args["product_count"];
            if (number) {
                url += ("&product_count=" + number);
            }

            $.get(url, function (data) {
                sdk.hideLoading();
                if (data.error) {
                    return alert('建立订单失败：' + data.error + ', 请选择其他支付方式。');
                }
                mqq.tenpay.pay({
                    tokenId: data.token_id,
                    pubAcc: "",
                    pubAccHint: ""
                }, function (result) {
                    if (typeof result == "string") {
                        sdk.confirmDialog("支付失败: " + result);
                    } else {
                        if (result.resultCode == 0) {
                            // 支付成功
                            if (callback) {
                                callback();
                            }
                        } else {
                            sdk.confirmDialog("支付失败[" + result.resultCode + "]！");
                        }
                    }
                });
            }, "json");
        }

        if (window.mqq && window.mqq.tenpay && window.mqq.tenpay.pay) {
            __qqPay();
        } else {
            sdk.loadSingleScript("https://pub.idqqimg.com/qqmobile/qqapi.js?_bid=xxx", function () {
                __qqPay();
            });
        }
    };

    // 微信H5支付
    sdk.wxh5Pay = function (args, callback, transCallback) {
        //var winRef = window.open("");
        var url = sdk.buildURL(sdk.loginHost + "/pay/weixin/h5", {
            gameid: sdk.gameId,
            uid: sdk.uid,
            product_id: args["pid"],
            userdata: args["userdata"],
            txid: args["txid"],
            _v: Date.now()
        });
        var number = args["product_count"];
        if (number) {
            url += ("&product_count=" + number);
        }
        $.get(url, function (data) {
            sdk.hideLoading();
            var intervalId = setInterval(function () {
                sdk.checkTransStatus(data.trans_id, function (status) {
                    if (status) {
                        // 支付成功
                        clearInterval(intervalId);
                        if (callback) {
                            callback();
                        }
                    }
                });
            }, 5000);
            //winRef.location = data.mweb_url;
            if (transCallback) {
                transCallback(data);
            } else {
                window.location.href = data.mweb_url;
            }
        }, "json");
    };

    // 微信二维码支付
    sdk.wxqrcodePay = function (args, callback) {
        var url = sdk.buildURL(sdk.loginHost + "/pay/weixin/native_call_qrcode", {
            gameid: sdk.gameId,
            uid: sdk.uid,
            product_id: args["pid"],
            userdata: args["userdata"],
            txid: args["txid"],
            _v: Date.now()
        });
        var number = args["product_count"];
        if (number) {
            url += ("&product_count=" + number);
        }
        $.get(url, function (data) {
            sdk.hideLoading();
            var url = data.url;
            var trans_id = data.trans_id;
            if (url.indexOf("weixin://") < 0) {
                return alert("系统错误");
            }
            var intervalId = 0;
            var t = '<div class="mask pay-qrcode">';
            t += '<div class="r5">';
            t += '<div class="pay-head">微信扫码支付 <i class="icon-cancel"></i></div>';
            t += '<div id="qrcode"></div>';
            t += '<div>请使用<strong>[微信扫一扫]</strong>支付</div>';
            t += '</div>';
            t += '</div>';
            $("body").append(t);

            $(".pay-qrcode,pay-qrcode .icon-cancel").off().click(function () {
                __hideQRCodePay();
            });

            $("#qrcode").empty();

            function __hideQRCodePay() {
                $("div.pay-qrcode").remove();
                if (intervalId) {
                    clearInterval(intervalId);
                }
            }

            function __showQRCodePay() {
                var qrcode = new QRCode(document.getElementById("qrcode"), {
                    width: 196,//设置宽高
                    height: 196
                });
                qrcode.makeCode(url);
                intervalId = setInterval(function () {
                    sdk.checkTransStatus(trans_id, function (status) {
                        if (status) {
                            // 支付成功
                            __hideQRCodePay();
                            if (callback) {
                                callback();
                            }
                        }
                    });
                }, 3000);
            }

            if (window.QRCode) {
                __showQRCodePay();
            } else {
                sdk.loadSingleScript(sdk.cdnHost + "/static/js/qrcode.min.js", function () {
                    __showQRCodePay();
                });
            }
        }, "json");
    };

    sdk.alih5Pay = function (args, callback) {
        sdk.aliPay(args, callback, function (url) {
            window.location.href = sdk.buildURL(sdk.playHost + "/pay/alipay", {
                gameid: sdk.gameId,
                url: encodeURIComponent(url)
            });
        });
    };

    // 支付宝支付
    sdk.aliPay = function (args, callback, transCallback) {
        sdk.hideLoading();
        var url = sdk.buildURL(sdk.loginHost + "/pay/alipay2/alipayapi", {
            gameid: sdk.gameId,
            uid: sdk.uid,
            product_id: args["pid"],
            //userdata: args["userdata"],
            txid: args["txid"],
            backurl: location.href,
            _v: Date.now()
        });
        var number = args["product_count"];
        if (number) {
            url += ("&product_count=" + number);
        }
        if (transCallback) {
            transCallback(url);
        } else {
            window.location.href = url;
        }
    };

    // 支付宝APP支付
    sdk.aliAppPay = function (args, callback) {
        //var appVer = Math.floor(((navigator.userAgent.toLowerCase()).match(/jet\/[\d.]+/gi) + "").replace(/[^0-9.]/ig, ""));
        sdk.aliPay(args, callback);
    };

    // 检查是否有通知
    sdk.checkNotify = function () {
        $.get(sdk.apiHost + "/api/busi/getNotice?gameid=" + sdk.gameId + "&" + Date.now(), function (data) {
            for (var i in data) {
                var noticeData = data[i];
                if (noticeData["type"] == 1) {
                    alert(noticeData["content"]);
                    if (noticeData["link"][0]) {
                        location.href = noticeData["link"][0];
                    }
                } else if (noticeData["type"] == 2) {
                    if (confirm(noticeData["content"])) {
                        if (noticeData["link"][0]) {
                            location.href = noticeData["link"][0];
                        }
                    } else {
                        if (noticeData["link"][1]) {
                            location.href = noticeData["link"][1];
                        }
                    }
                }
            }
        }, "json");
    };

    // 初始化苹果APP
    sdk.initWebViewJavascriptBridge = function (callback) {
        function setupWebViewJavascriptBridge(cb) {
            if (window.WebViewJavascriptBridge) {
                return cb(WebViewJavascriptBridge);
            }
            if (window.WVJBCallbacks) {
                return window.WVJBCallbacks.push(cb);
            }
            window.WVJBCallbacks = [cb];
            var WVJBIframe = document.createElement("iframe");
            WVJBIframe.style.display = "none";
            WVJBIframe.src = "wvjbscheme://__BRIDGE_LOADED__";
            document.documentElement.appendChild(WVJBIframe);
            setTimeout(function () {
                document.documentElement.removeChild(WVJBIframe);
            }, 0);
        }

        if (sdk.isJetAPP()) {
            setupWebViewJavascriptBridge(function (bridge) {
                if (!sdk.isBridgeShareReady) {
                    sdk.isBridgeShareReady = true;
                    bridge.registerHandler("shareComplete", function (data, responseCallback) {
                        if (data == "wxtimeline") {
                            sdk.confirmShare();
                        }
                        if (sdk.shareCallback) {
                            sdk.shareCallback(data);
                        }
                    });
                }
                if (callback) {
                    callback(bridge);
                }
            });
        }
    };

    // 初始化安卓APP分享
    sdk.initAndroidAPPShare = function () {
        window.__shareComplete = function (data) {
            if (data == "onMenuShareTimeline") {
                sdk.confirmShare();
            }
            if (sdk.shareCallback) {
                sdk.shareCallback(data);
            }
        };
        if (!sdk.isBridgeShareReady) {
            sdk.isBridgeShareReady = true;
            window.android.shareComplete("__shareComplete");
        }
    };

    // 应用宝：支付
    sdk.yybPay = function (args, callback) {
        if (args && args["pid"]) {
            $.get(sdk.loginHost + "/pay/midas/check_token?gameid=" + sdk.gameId + "&openid=" + sdk.getURLVar("openid") + "&openkey=" + sdk.getURLVar("openkey") + "&" + Date.now(), function (data) {
                if (data["error"]) {
                    sdk.confirmDialog("获取登陆状态错误：" + data["error"]);
                } else if (data["ok"]) {
                    var url = sdk.loginHost + "/pay/midas/trans?gameid=" + sdk.gameId + "&product_id=" + args["pid"] + "&uid=" + sdk.uid;
                    if (args["txid"]) {
                        url += ("&txid=" + args["txid"]);
                    }
                    if (args["userdata"]) {
                        url += ("&userdata=" + args["userdata"]);
                    }
                    $.get(url + "&" + Date.now(), function (data) {
                        if (data["error"]) {
                            sdk.confirmDialog("生成订单错误：" + data["error"]);
                        } else {
                            var trans_id = data["trans_id"];
                            $.get(sdk.apiHost + "/api/conf/getProductInfo?pid=" + args["pid"] + "&" + Date.now(), function (data) {
                                if (data["error"]) {
                                    sdk.confirmDialog("获取商品信息错误：" + data["error"]);
                                } else {
                                    window["H5YSDK"].pay({
                                        saveValue: data["cost"] * data["moneyRate"],
                                        zoneId: args["zid"] ? args["zid"] : "1",
                                        offerid: sdk.getURLVar("offerid"),
                                        openid: sdk.getURLVar("openid"),
                                        openkey: sdk.getURLVar("openkey"),
                                        pf: sdk.getURLVar("pf"),
                                        pfkey: sdk.getURLVar("pfkey"),
                                        onError: function (ret) {
                                            console.log(ret);
                                        },
                                        onSuccess: function (ret) {
                                            $.get(sdk.loginHost + "/pay/midas/notify?trans_id=" + trans_id + "&appid=" + sdk.getURLVar("appid") + "&openid=" + sdk.getURLVar("openid") + "&openkey=" + sdk.getURLVar("openkey") + "&pf=" + sdk.getURLVar("pf") + "&pfkey=" + sdk.getURLVar("pfkey") + "&" + Date.now(), function (data) {
                                                if (callback) {
                                                    callback();
                                                }
                                                sdk.confirmDialog("购买成功，即将重新进入游戏...");
                                                window.top.location.reload();
                                            }, "json");
                                        }
                                    });
                                }
                            }, "json");
                        }
                    }, "json");
                } else {
                    sdk.confirmDialog("登陆状态失效，请重新进入游戏！");
                    sdk.yybLogout();
                }
            }, "json");
        }
    };

    // 应用宝：直购支付
    sdk.yybPay2 = function (args, callback) {
        if (args && args["pid"]) {
            $.get(sdk.loginHost + "/pay/midas/check_token?gameid=" + sdk.gameId + "&openid=" + sdk.getURLVar("openid") + "&openkey=" + sdk.getURLVar("openkey") + "&" + Date.now(), function (data) {
                if (data["error"]) {
                    sdk.confirmDialog("获取登陆状态错误：" + data["error"]);
                } else if (data["ok"]) {
                    var url = sdk.buildURL(sdk.loginHost + "/pay/midas/buy", {
                        gameid: sdk.gameId,
                        uid: sdk.uid,
                        product_id: args["pid"],
                        txid: args["txid"],
                        userdata: args["userdata"],
                        openid: sdk.getURLVar("openid"),
                        openkey: sdk.getURLVar("openkey"),
                        pf: sdk.getURLVar("pf"),
                        pfkey: sdk.getURLVar("pfkey")
                    });
                    $.get(url + "&" + Date.now(), function (data) {
                        if (data["error"]) {
                            sdk.confirmDialog("生成订单错误：" + data["error"]);
                        } else {
                            var trans_id = data["trans_id"];
                            var url_params = data["url_params"];
                            $.get(sdk.apiHost + "/api/conf/getProductInfo?pid=" + args["pid"] + "&" + Date.now(), function (data) {
                                if (data["error"]) {
                                    sdk.confirmDialog("获取商品信息错误：" + data["error"]);
                                } else {
                                    var reqData = {
                                        saveValue: data["cost"] * data["moneyRate"],
                                        zoneId: args["zid"] ? args["zid"] : "1",
                                        offerId: sdk.getURLVar("offerid"),
                                        openId: sdk.getURLVar("openid"),
                                        openKey: sdk.getURLVar("openkey"),
                                        sessionId: "openid",
                                        sessionType: "openkey",
                                        goodsTokenUrl: url_params,
                                        pf: sdk.getURLVar("pf"),
                                        pfKey: sdk.getURLVar("pfkey"),
                                        isCanChange: false,
                                        unit: "",
                                        isShowSaveNum: true,
                                        callback: function (code) {
                                            if (code == window["H5YSDK"].PAY_STATE.SUCCESS) {
                                                if (callback) {
                                                    callback();
                                                }
                                                sdk.confirmDialog("购买成功！");
                                            } else if (code == 2 || code == window["H5YSDK"].PAY_STATE.USERCANCEL) {
                                                sdk.confirmDialog("取消了支付");
                                            } else {
                                                sdk.confirmDialog("支付失败: " + code);
                                            }
                                        }
                                    };
                                    window["H5YSDK"].requestPayForGood(reqData);
                                }
                            }, "json");
                        }
                    }, "json");
                } else {
                    sdk.confirmDialog("登陆状态失效，请重新进入游戏！");
                    sdk.yybLogout();
                }
            }, "json");
        }
    };

    // 应用宝：登出
    sdk.yybLogout = function () {
        window.top.location.href = "https://qzs.qq.com/open/mobile/h5gamesdk/autologin.html?appid=" + sdk.getURLVar("appid");
    };

    // 应用宝：添加桌面
    sdk.yybAddShortCut = function (callback) {
        window["H5YSDK"].addGameDesktopShortCut(function (state) {
            if (state == H5YSDK.STATE.SUCCESS) {
                if (callback) {
                    callback();
                }
            }
        });
    };

    // 获取分享二维码信息
    sdk.getShareQRInfo = function (callback, args) {
        if (sdk.shareQRInfo) {
            callback(sdk.shareQRInfo);
        } else {
            sdk.httpGet(sdk.webHost + "/api/share/getShareQRDomain?gameid=" + sdk.gameId + "&" + Date.now(), function (data) {
                sdk.shareQRInfo = data;
                sdk.shareQRInfo.shareURL = sdk.buildURL(sdk.shareQRInfo.shareURL.replace(/@/g, ''), {
                    "fuid": sdk.uid,
                    "rnd": sdk.randomString(args ? args.rnd : 16),
                    "share_from": "qrcode"
                });
                callback(sdk.shareQRInfo);
            }, "json");
        }
    };

    //备份的分享信息
    sdk.shareDomain = function (callback) {
        if (sdk.getShareDomain) {
            callback(sdk.getShareDomain);
        }
    };

    // 开始侦听消息
    sdk.execMessage = function (msg) {
        switch (msg.cmd) {
            case "showFocus": {
                sdk.showFocus(msg.args);
                break;
            }
            case "logout": {
                sdk.logout();
                break;
            }
            case "showFriend": {
                sdk.showFriend();
                break;
            }
            case "hideFriend": {
                sdk.hideFriend();
                break;
            }
            case "shareTitle": {
                if (sdk.readyCallback) {
                    sdk.readyMsgList.push(msg);
                } else {
                    sdk.shareTitle(msg.args);
                }
                break;
            }
            case "shareIcon": {
                if (sdk.readyCallback) {
                    sdk.readyMsgList.push(msg);
                } else {
                    sdk.shareIcon(msg.args);
                }
                break;
            }
            case "shareDesc": {
                if (sdk.readyCallback) {
                    sdk.readyMsgList.push(msg);
                } else {
                    sdk.shareDesc(msg.args);
                }
                break;
            }
            case "shareParams": {
                if (sdk.readyCallback) {
                    sdk.readyMsgList.push(msg);
                } else {
                    sdk.shareParams(msg.args);
                }
                break;
            }
            case "stat": {
                //sdk.execStat(msg.args["item"], msg.args["sub_item"], msg.args["sub_sec_item"]);
                break;
            }
            case "showTopImg": {
                sdk.showTopImg(msg.args);
                break;
            }
            case "copy": {
                sdk.copy(msg.args);
                break;
            }
            case "bindCash": {
                sdk.bindCash();
                break;
            }
            case "downJetApp": {
                sdk.downJetApp();
                break;
            }
            case "refresh": {
                window.location.reload();
                break;
            }
            case "chatPrevent": {
                sdk.getPreventInfo = msg.args;
                break;
            }
            case "saveGame": {
                sdk.saveGameDesktop();
                break;
            }
        }
    };

    // 开始录音
    sdk.startRecord = function (callback) {
        wx.startRecord({
            success: function () {
                callback(true);
            },
            cancel: function () {
                callback(false);
            },
            fail: function () {
                callback(false);
            }
        });
    };

    // 结束录音
    sdk.stopRecord = function (callback) {
        wx.stopRecord({
            success: function (res) {
                callback(res.localId);
            }
        });
    };

    // 上传录音
    sdk.uploadVoice = function (localId, callback) {
        wx.uploadVoice({
            localId: localId,
            isShowProgressTips: 0,
            success: function (res) {
                callback(res.serverId);
            }
        });
    };

    // 下载语音
    sdk.downloadVoice = function (serverId, callback) {
        wx.downloadVoice({
            serverId: serverId,
            isShowProgressTips: 0,
            success: function (res) {
                callback(res.localId);
            }
        });
    };

    // 播放语音
    sdk.playVoice = function (localId, callback) {
        wx.onVoicePlayEnd({
            success: function (res) {
                callback(res.localId);
            }
        });
        wx.playVoice({
            localId: localId
        });
    };

    // 停止播放
    sdk.stopVoice = function (localId) {
        wx.stopVoice({
            localId: localId
        });
    };

    // 微端下载
    sdk.downJetApp = function () {
        if (sdk.isTrial) {
            sdk.loadTrialLogin();
        } else {
            if (sdk.isAndroid()) {
                location.href = 'https://a.app.qq.com/o/simple.jsp?pkgname=com.jeticon.app';
            } else if (sdk.isiOS()) {
                location.href = 'https://itunes.apple.com/cn/app/多纷游戏/id1101086057?mt=8';
            } else {
                $("body").append('<iframe style="display:none;" src= "'+sdk.cdnHost +'/static/app/android/dfgame_1.0.apk"></iframe>');
            }
        }
    };

    //保存到桌面
    sdk.saveGameDesktop = function () {
        if (sdk.isiOS() && sdk.isMobile()) {
            $.get(sdk.apiHost + '/api/login/getTokenKey?token=' + sdk.token, function (data) {
                if (data.tokenkey) {
                    location.href = sdk.buildURL(location.href, {tokenkey: data.tokenkey});
                } else {
                    sdk.confirmDialog(data.error);
                }
            }, 'json');
        }
    };

    // 统计在线
    sdk.statOnline = function () {
        function __statFunc() {
            $.get(sdk.onlineHost + "/api/stat/online?gameid=" + sdk.gameId + "&token=" + sdk.token + "&" + Date.now(), function (data) {
            });
        }

        setInterval(function () {
            __statFunc();
        }, 30000);
        __statFunc();
    };

    //关闭试玩界面
    sdk.hideTrialBox = function () {
        $("#trialBox").remove();
    };

    //加载试玩个人信息界面
    sdk.loadTrialInfo = function () {
        var t = '<div id="trialBox" class="mask trial-box">';
        t += '<div class="r5">';
        t += '<div class="trial-head"><span>个人信息</span><i onclick="sdk.hideTrialBox()" class="icon-cancel"></i></div>';
        t += '<div class="trial-body">';
        t += '<div class="flex flex-x-center">';
        t += '<img id="trial-headimg" class="r50" src="" >';
        t += '<p class="flex flex-v">';
        t += '<span>昵称：<em id="trial-nickname"></em></span>';
        t += '<span>UID：<em id="trial-uid"></em></span>';
        t += '</p>';
        t += '</div>';
        t += '<a href="javascript:;" class="r3" id="trial-tab">切换账号</a>';
        t += '</div>';
        t += '</div>';
        t += '</div>';
        $("body").append(t);

        $.get(sdk.apiHost + '/api/login/getUserByToken?token=' + sdk.token, function (data) {
            if (data.nickname) {
                try {
                    data.nickname = decodeURIComponent(data.nickname);
                } catch (e) {
                    data.nickname = '微游玩家';
                }
            } else {
                data.nickname = '微游玩家';
            }
            if (!data.headimgurl) {
                data.headimgurl =sdk.cdnHost+ '/static/image/jeticon.png';
            }
            $("#trial-headimg").attr('src', data.headimgurl);
            $("#trial-nickname").html(data.nickname);
            $("#trial-uid").html(data.uid);
        }, 'json');

        $("#trial-tab").off().click(function () {
            $("#trialBox").remove();
            sdk.loadTrialLogin();
        });
    };

    //加载试玩登录界面
    sdk.loadTrialLogin = function () {
        var t = '<div id="trialBox" class="mask trial-box">';
        t += '<div class="r5">';
        t += '<div class="trial-head"><span>游戏登录</span><i onclick="sdk.hideTrialBox()" class="icon-cancel"></i></div>';
        t += '<div class="trial-body">';
        t += '<div>';
        t += '<div id="userBox" class="r3 flex"><i class="icod-mobile"></i><input class="flex-list" id="user" type="number" placeholder="请输入手机号码"></div>';
        t += '<div id="pwdBox" class="r3 flex"><i class="icod-lock"></i><input class="flex-list" id="pwd" type="password" placeholder="请填写密码"></div>';
        t += '</div>';
        t += '<a href="javascript:;" id="mBtn">登录/一键注册</a>';
        t += '<div class="flex flex-x-center">';
        t += '<a href="javascript:;" class="r3" onclick="sdk.qqLogin()"><i class="icod-qq"></i> QQ登录</a>';
        t += '<a href="javascript:;" class="r3" onclick="sdk.sinaLogin()"><i class="icod-sina"></i> 微博登录</a>';
        t += '</div>';
        t += '</div>';
        t += '</div>';
        t += '</div>';
        $("body").append(t);

        if (sdk.getItem("trial_user")) {
            $("#user").val(sdk.getItem("trial_user"));
            $("#pwd").val(sdk.getItem("trial_pwd"));
        } else {
            $("#user,#pwd").val('');
        }

        //登录
        $("#mBtn").off().click(function () {
            var _user = $.trim($("#user").val()), //账号
                _pwd = $.trim($("#pwd").val()), //密码
                _imgCode = $.trim($("#imgCode").val()),
                _smsCode = $.trim($("#smsCode").val()),
                cls = $(this).attr("class");
            switch (cls) {
                case "":
                case undefined: //登录
                    sdk.mobileParamsCheck({"phone": _user, "pwd": _pwd}, function () {
                        if (sdk.jetLoginHasCode) {//需要验证码
                            if (!_imgCode) {
                                sdk.confirmDialog('请填写验证码');
                                return;
                            }
                            sdk.jetLogin({
                                "uid": _user,
                                "password": _pwd,
                                "verify_code": _imgCode,
                                "verify_session": sdk.getItem('verify_session')
                            }, function () {
                                sdk.setItem("trial_user", _user);//登入成功写入缓存
                                sdk.setItem("trial_pwd", _pwd);
                                sdk.jetLoginHasCode = null;
                            });
                        } else {
                            sdk.jetLogin({"uid": _user, "password": _pwd}, function () {
                                sdk.setItem("trial_user", _user);//登入成功写入缓存
                                sdk.setItem("trial_pwd", _pwd);
                            });
                        }
                    });
                    break;
                case "getVerify":
                    sdk.mobileParamsCheck({"imgCode": _imgCode, "smsCode": _smsCode}, function () {
                        sdk.mobileObjectBase({
                            "d": "bindUidForPhone",
                            "phone": _user,
                            "smsCode": _smsCode,
                            "passwd": _pwd,
                            "token": sdk.token,
                            "uid": sdk.uid,
                            "trial": 1
                        }, function (data) {
                            sdk.hideLoading();
                            sdk.setItem("trial_user", _user);//登入成功写入缓存
                            sdk.setItem("trial_pwd", _pwd);
                            location.href = sdk.buildURL(location.href, {
                                token: sdk.token,
                                code: null
                            });
                        });
                    });
                    break;
            }
        });
    };

    // 启用返回Banner
    sdk.enableExitBanner = function () {
        var $history = $('<script />', {
            src: sdk.cndHost + '/static/js/sdk_history.js?v=' + Date.now()
        });
        if ((new Date().getDate() != sdk.getItem("history_date")) || !sdk.getItem("history_date")) {
            $("body").append($history);
        }
    };

    // 启用小浮窗按钮
    sdk.enableFloatBox = function () {
        var $float = $('<script />', {
            src: sdk.cndHost + '/static/js/sdk_float.js?v=' + Date.now(),
            id: 'ftBoxJs'
        });
        if (!$("#ftBoxJs").length) {
            $("body").append($float);
        }
    };

    //多纷账号登录错误码
    sdk.jetLoginError = function (error) {
        switch (error) {
            case 101:
                sdk.confirmDialog('参数错误');
                break;
            case 102:
                sdk.confirmDialog('系统错误');
                break;
            case 201:
                sdk.confirmDialog('账户不存在');
                break;
            case 202:
                sdk.confirmDialog('账户已存在');
                break;
            case 203:
                sdk.confirmDialog('账户不合法');
                break;
            case 204:
                sdk.confirmDialog('账户被冻结');
                break;
            case 205:
                sdk.confirmDialog('账号或密码错误');
                break;
        }
    };

    //手机登录*异常处理
    sdk.mobileCheckError = function (error) {
        switch (parseInt(error)) {
            case 1:
                sdk.confirmDialog('操作失败！');
                break;
            case 2:
            case 3:
                sdk.confirmDialog('验证码错误！');
                sdk.getVerifyImg("#refreshCode");
                $("#getCode").attr('class', '');
                break;
            case 4:
                sdk.confirmDialog('短信验证码参数错误！');
                break;
            case 5:
                sdk.confirmDialog('参数错误！');
                break;
            case 6:
                sdk.confirmDialog('网络出了点小状况！');
                break;
            case 7:
                sdk.confirmDialog('账户被冻结！');
                break;
            case 8:
                sdk.confirmDialog('参数失效！');
                break;
            case 9:
                sdk.confirmDialog('参数失效！');
                break;
            case 10:
                sdk.confirmDialog('手机号已经绑定或已注册！');
                break;
            case 11:
                sdk.confirmDialog('短信验证码错误！');
                break;
            case 12:
                sdk.confirmDialog('短信验证码超时！');
                break;
            case 13:
                sdk.confirmDialog('该手机号尚未绑定，请绑定后再执行操作，点击确认后重新登录', '确定', null, function () {
                    sdk.closeLoginBox();
                    sdk.smsCountDown(false, '#getCode');
                    sdk.auth();
                });
                break;
            case 1013:
                sdk.confirmDialog('短信请求过于频繁，请稍后再试');
                break;
        }
    };

    //自定义账号登录
    sdk.jetLoginHasCode = null;//是否需要验证码
    sdk.jetLogin = function (params, callback) {
    	var server_url = sdk.loginHost + '/api/accountLogin/auth';
		server_url = sdk.setURLArgs(server_url,sdk.getCommonArgs());
        $.post(server_url , params, function (data) {
            if (data) {
                if (data.error) {
                    if (data.error == 103) {//获取验证码
                        if (data.flag == 1) {
                            sdk.confirmDialog('密码错误，尝试次数过多！');
                        }
                        if (data.flag == 5) {
                            if ($("#imgBox").css("display") == 'none') {
                                sdk.confirmDialog('为了您的账户安全，请输入验证码');
                            } else {
                                sdk.confirmDialog('验证码错误');
                            }
                        }
                        sdk.jetLoginHasCode = true;
                        $("#imgBox").removeClass("login-hide");
                        if (sdk.getURLVar("trial")) {
                            var t = '<div id="imgBox" class="code flex"><i class="icod-subyzm"></i><input id="imgCode" type="text" placeholder="输入图片验证码"><img id="refreshCode" src="" alt="" style="width:79px;height:37px"></div>';
                            $("#trialBox .trial-body>div:nth-child(1)").append(t);
                        }
                        sdk.getVerifyImg("#refreshCode");
                    } else if (data.error == 201 && sdk.getURLVar("trial")) {//试玩：账号不存在，开始绑定
                        $("#userBox,#pwdBox").hide();
                        var t = '<div id="imgBox" class="code flex"><i class="icod-subyzm"></i><input id="imgCode" type="text" placeholder="输入图片验证码"><img id="refreshCode" src="" alt=""></div>';
                        t += '<div id="smsBox" class="code flex"><i class="icod-yzm"></i><input id="smsCode" type="number" pattern="[0-9]*" placeholder="请输入短信验证码"><a href="javascript:;" class="" id="getCode">获取验证码</a></div>';
                        $("#trialBox .trial-body>div:nth-child(1)").append(t);
                        $("#mBtn").attr('class', 'getVerify');
                        sdk.getVerifyImg("#refreshCode");

                        $("#getCode").off().click(function () {
                            if ($(this).attr("class").indexOf("active") == -1) {
                                sdk.mobileParamsCheck({"imgCode": $.trim($("#imgCode").val())}, function () {
                                    sdk.getSmsCode({
                                        "phone": $.trim($("#user").val()),
                                        "imgCode": $.trim($("#imgCode").val()),
                                        "type": 3
                                    }, function () {
                                        sdk.smsCountDown(true, '#getCode');
                                    });
                                })
                            }
                        });
                    } else {
                        sdk.jetLoginError(parseInt(data.error));
                    }
                } else {
                    //登录成功
                    if (callback) {
                        callback();
                    }
                    sdk.createCode(data["token"], function (code) {
                        if (code.code) {
                            location.href = sdk.buildURL(location.href, {
                                "code": code.code
                            });
                        }
                    });
                }
            } else {
                sdk.confirmDialog("登录失败，请稍后重试！")
            }
        }, 'json');
    };

    //手机登录注册绑定接口
    sdk.mobileObjectBase = function (args, callback) {
    	var server_url = sdk.loginHost + '/api/accountPhone?';
		server_url = sdk.setURLArgs(server_url,sdk.getCommonArgs());
        $.post(server_url, args, function (data) {
            if (data.error == 0 || data.image) {
                callback(data);
            } else {
                sdk.mobileCheckError(data.error);
                sdk.hideLoading();
            }
        }, 'json');
    };

    //获取验证码
    sdk.getVerifyImg = function (obj) {
        sdk.mobileObjectBase({"d": "getVerifyImg", "getBase64": 1}, function (data) {
            sdk.setItem('verify_session', data.verify_session);
            $(obj).attr("src", 'data:image/png;base64,' + data.image);
        });

        //刷新事件注册
        $(obj).off().click(function () {
            sdk.getVerifyImg(obj);
        });
    };

    //获取短信验证码
    sdk.getSmsCode = function (args, callback) {
        sdk.showLoading();
        sdk.mobileObjectBase({
            "d": "verifyImgAndGetSms",
            "phone": args.phone,
            "verify_code": args.imgCode,
            "type": args.type,
            "verify_session": sdk.getItem("verify_session")
        }, function () {
            callback();
            sdk.hideLoading();
        });
    };

    //统一短信验证码倒计时
    sdk.smsCountDown = function (type, e) {
        var _time = 60;
        var auto = setInterval(function () {
            if (_time) {
                _time--;
                $(e).addClass('active').html(_time + 'S 再次获取');
            } else {
                clearInterval(auto);
                $(e).attr('class', '').removeClass('active').html('重新获取');
            }
        }, 1000);
        if (!type) {
            clearInterval(auto);
        }
    };

    //绑定登录参数检查
    sdk.mobileParamsCheck = function (args, callback) {
        if (!args.jetUser && args.jetUser !== undefined) {
            sdk.confirmDialog('请输入账号！');
            return;
        } else {
            if (!args.phone && args.phone !== undefined) {
                sdk.confirmDialog('请输入手机号！');
                return;
            }
            if (!(/^1[3|4|5|7|8]\d{9}$/.test(args.phone)) && args.phone !== undefined) {
                sdk.confirmDialog('请填入有效的手机号码！');
                return;
            }
        }
        if (!args.pwd && args.pwd !== undefined) {
            sdk.confirmDialog('请输入密码！');
            return;
        }
        if (!args.imgCode && args.imgCode !== undefined) {
            sdk.confirmDialog('请输入图片验证码！');
            return;
        }
        if (!args.smsCode && args.smsCode !== undefined) {
            sdk.confirmDialog('请输入短信验证码！');
            return;
        }
        callback();
    };

    //唤醒登录窗口
    sdk.loadLoginBox = function (wxLgCallback, qrcodeLgCallback, qqLgCallback, sinaLgCallback, jetLgCallback, mbLgCallback) {
        if (!$("#loginBox").length) {
            var html = '<div id="loginBox" class="mask login-box">';
            html += '<div class="r5">';
            html += '<div class="login-head"><img src= "'+sdk.cdnHost +'/static/image/jeticon_logo.png" alt="多纷游戏"><span>游戏登录</span></div>';
            html += '<div class="login-body login-hide">';
            html += '<div>';
            html += '<div id="userBox" class="flex"><i class="icod-user"></i><input class="flex-list" id="user" type="text" value="" placeholder="请输入多纷账号"></div>';
            html += '<div id="pwdBox" class="flex"><i class="icod-lock"></i><input class="flex-list" id="pwd" type="password" value="" placeholder="请填写密码"></div>';
            html += '<div id="imgBox" class="code flex login-hide"><i class="icod-yzm"></i><input id="imgCode" type="text" placeholder="输入图片验证码"><img id="refreshCode" src="" alt="" style="width:79px;height:37px"><em class="icod-load"></em></div>';
            html += '<div id="smsBox" class="code flex login-hide"><i class="icod-subyzm"></i><input id="smsCode" type="number" placeholder="请输入短信验证码"><a href="javascript:;" id="getCode">获取验证码</a></div>';
            html += '</div>';
            html += '<a href="javascript:;" class="btn" id="mBtn">登录</a>';
            html += '<p id="suBtn" class="login-hide">通过短信验证码登录</p>';
            html += '<div id="loginOption" class="flex flex-x-between login-hide"><a href="javascript:;" id="forgetPwd">忘了密码?</a><a href="javascript:;" id="otherLogin">其他方式登录</a></div>';
            html += '<fieldset><legend>其他登录方式</legend></fieldset>';
            html += '</div>';
            html += '<div class="login-mode">';
            html += '<ol class="flex">';
            html += '<li class="flex-list ' + (wxLgCallback ? 'list-show' : '') + '" id="wxLogin" title="微信登录"><div class="r50"><i class="icod-wechat"></i></div></li>';
            html += '<li class="flex-list ' + (qrcodeLgCallback ? 'list-show' : '') + '" id="wxQcLogin" title="微信扫码登录"><div class="r50"><i class="icod-wechat"></i></div></li>';
            html += '<li class="flex-list qq ' + (qqLgCallback ? 'list-show' : '') + '" id="qqLogin" title="QQ登录"><div class="r50"><i class="icod-qq"></i></div></li>';
            html += '<li class="flex-list sina ' + (sinaLgCallback ? 'list-show' : '') + '" id="sinaLogin" title="新浪微博登录"><div class="r50"><i class="icod-sina"></i></div></li>';
            html += '<li class="flex-list mobile ' + (mbLgCallback ? 'list-show' : '') + '" id="mobileLogin" title="手机登录"><div class="r50"><i class="icod-mobile"></i></div></li>';
            html += '</ol>';
            if (jetLgCallback) {
                html += '<a id="tagJetLogin" href="javascript:;">多纷账号登录</a>';
            }
            html += '</div>';
            html += '</div>';
            html += '</div>';
            $("body").append(html);
        }

        //input事件
        $(".login-body>div>div").on('click', 'input', function () {
            var id = $(this).attr('id').replace(/#/g, '');
            var input = document.getElementById(id);
            if (input) {
                input.setSelectionRange(0, input.value.length);
            }
            $(this).parent().addClass('active').siblings().removeClass('active');
        });

        //登录方式
        $("#loginBox .login-mode ol").off().on("click", "li", function () {
            var _index = $(this).index();
            if (_index == 0) { //微信登录
                wxLgCallback();
            } else if (_index == 1) {//微信扫码登录
                qrcodeLgCallback();
            } else if (_index == 2) {//QQ登录
                qqLgCallback();
            } else if (_index == 3) {//新浪微博登录
                sinaLgCallback();
            } else if (_index == 4) {//手机登录切换
                $("#loginBox .icod-user").addClass('icod-mobile');
                if (!(navigator.platform.indexOf('Mac') >= 0 || navigator.platform.indexOf('Win') >= 0)) {
                    $("#user").attr('type', 'number');
                }
                $("#user").attr('placeholder', '请输入手机号码');
                $("#mBtn").attr('data-type', 'phoneLogin');
                $("#suBtn").attr('data-type', 'smsLogin');
                $("#user,#pwd").val('');
                $("#loginBox fieldset,#loginBox .login-mode").addClass('login-hide');
                $("#loginOption,#loginBox .login-body,#suBtn").removeClass("login-hide");
                if (sdk.getItem('jet_user_phone')) {
                    $("#userBox input").val(sdk.getItem('jet_user_phone'));
                }
                if (sdk.getItem('jet_pwd_phone')) {
                    $("#pwdBox input").val(sdk.getItem('jet_pwd_phone'));
                }
            }
        });

        //多纷账号登录切换
        $("#tagJetLogin").off().click(function () {
            $("#tagJetLogin,#loginOption,#suBtn").addClass('login-hide');
            $("#loginBox .login-body,#loginBox fieldset").removeClass('login-hide');
            $("#mBtn").attr('data-type', 'jetLogin');
            $("#loginBox .icod-user").removeClass('icod-mobile');
            $("#user").attr('placeholder', '请输入多纷账号').attr('type', 'text');
            $("#loginBox .login-mode ol").addClass("active");
            $("#user,#pwd").val('');
            if (sdk.getItem('jet_user')) {
                $("#userBox input").val(sdk.getItem('jet_user'));
            }
            if (sdk.getItem('jet_pwd')) {
                $("#pwdBox input").val(sdk.getItem('jet_pwd'));
            }
        });

        //其他登录方式
        $("#otherLogin").off().click(function () {
            $("#loginBox .login-mode,#tagJetLogin").removeClass('login-hide');
            $("#loginBox .login-mode ol").removeClass("active");
            $("#loginBox .login-body").addClass('login-hide');
        });

        //忘了密码
        $("#forgetPwd").off().click(function () {
            $("#pwdBox,#loginOption").addClass('login-hide');
            $("#imgBox,#smsBox").removeClass('login-hide');
            $("#mBtn").attr('data-type', 'modifyNext').text('下一步');
            $("#suBtn").attr('data-type', 'back').text('返回登录');
            $("#getCode").attr('data-type', 1);
            sdk.getVerifyImg("#refreshCode");
        });

        //获取验证码
        $("#getCode").off().click(function () {
            if ($(this).attr('class') != 'active') {
                var _type = $(this).attr('data-type');
                sdk.mobileParamsCheck({
                    "phone": $.trim($("#user").val()),
                    "imgCode": $.trim($("#imgCode").val())
                }, function () {
                    //获取短信验证码
                    $(this).attr('class', 'active');
                    sdk.getSmsCode({
                        "phone": $.trim($("#user").val()),
                        "imgCode": $.trim($("#imgCode").val()),
                        "type": _type
                    }, function () {
                        sdk.smsCountDown(true, '#getCode');
                    });
                });
            }
        });
        //主按钮事件
        $("#mBtn").off().click(function () {
            var _user = $.trim($("#user").val()),       //账号
                _pwd = $.trim($("#pwd").val()),         //密码
                _imgCode = $.trim($("#imgCode").val()), //图片验证码
                _smsCode = $.trim($("#smsCode").val()), //手机验证码
                _type = $(this).attr("data-type");      //操作类型
            switch (_type) {
                case "jetLogin"://多纷账号登录
                    sdk.mobileParamsCheck({"user": _user, "pwd": _pwd}, function () {
                        if (sdk.jetLoginHasCode) {//需要验证码
                            if (!_imgCode) {
                                sdk.confirmDialog('请填写验证码');
                                return;
                            }
                            jetLgCallback({
                                "uid": _user,
                                "password": _pwd,
                                "verify_code": _imgCode,
                                "verify_session": sdk.getItem('verify_session')
                            }, function () {
                                sdk.setItem("jet_user", _user);//登入成功写入缓存
                                sdk.setItem("jet_pwd", _pwd);
                                sdk.jetLoginHasCode = null;
                            });
                        } else {
                            jetLgCallback({"uid": _user, "password": _pwd}, function () {
                                sdk.setItem("jet_user", _user);//登入成功写入缓存
                                sdk.setItem("jet_pwd", _pwd);
                            });
                        }
                    });
                    break;
                case 'phoneLogin'://手机账号密码登录
                    sdk.mobileParamsCheck({"phone": _user, "pwd": _pwd}, function () {
                        if (sdk.jetLoginHasCode) {//需要验证码
                            if (!_imgCode) {
                                sdk.confirmDialog('请填写验证码');
                                return;
                            }
                            mbLgCallback({
                                "uid": _user,
                                "password": _pwd,
                                "verify_code": _imgCode,
                                "verify_session": sdk.getItem('verify_session')
                            }, function () {
                                sdk.setItem("jet_user_phone", _user);//登入成功写入缓存
                                sdk.setItem("jet_pwd_phone", _pwd);
                                sdk.jetLoginHasCode = null;
                            });
                        } else {
                            mbLgCallback({"uid": _user, "password": _pwd}, function () {
                                sdk.setItem("jet_user_phone", _user);//登入成功写入缓存
                                sdk.setItem("jet_pwd_phone", _pwd);
                            });
                        }
                    });
                    break;
                case "smsLogin"://短信验证码登录
                    sdk.mobileParamsCheck({"smsCode": _smsCode}, function () {
                        sdk.mobileObjectBase({
                            "d": "phoneSmsLogin",
                            "phone": _user,
                            "smsCode": _smsCode
                        }, function (data) {
                            sdk.createCode(data["token"], function (code) {
                                if (code.code) {
                                    location.href = sdk.buildURL(location.href, {
                                        "code": code.code
                                    });
                                }
                            });
                        });
                    });
                    break;
                case "modifyNext"://修改下一步
                    sdk.mobileParamsCheck({"phone": _user, "imgCode": _imgCode, "smsCode": _smsCode}, function () {
                        $("#userBox,#imgBox,#smsBox").addClass('login-hide');
                        $("#pwdBox").removeClass('login-hide').find("input").val('').attr('placeHolder', '不能少于6位,且必须包含字母数字');
                        $("#mBtn").attr('data-type', 'modify').text('完成修改');
                    });
                    break;
                case "modify": //完成修改
                    if (_pwd.length < 6) {
                        sdk.confirmDialog('密码过于简单，请设置长度不少于6位的密码', null, '好的');
                        return;
                    }
                    if (_pwd.length > 20) {
                        sdk.confirmDialog('密码长度不能大于20位', null, '好的');
                        return;
                    }
                    var pwdPatrn = /^(?![^a-zA-Z]+$)(?!\D+$).{6,20}$/;
                    if(!pwdPatrn.exec(_pwd)){
                        sdk.confirmDialog('密码长度6-20位,且必须包含字母和数字', null, '好的');
                        return;
                    }

                    sdk.mobileParamsCheck({"pwd": _pwd}, function () {
                        sdk.mobileObjectBase({
                            "d": "chgPasswd",
                            "phone": _user,
                            "passwd": _pwd,
                            "smsCode": _smsCode
                        }, function () {
                            sdk.confirmDialog('密码修改成功，请重新登录', null, '好的', null, function () {
                                sdk.removeItem("jet_pwd_phone");
                                $("#suBtn").trigger('click');
                                $("#userBox").removeClass('login-hide');
                                $("#pwdBox input").attr('placeHolder', '请输入密码').val('');
                            });
                        });
                    });
                    break;
            }
        });


        // //二级按钮事件
        $("#suBtn").off().click(function () {
            var _type = $(this).attr("data-type");
            switch (_type) {
                case "back":
                    $("#mBtn").attr('data-type', 'phoneLogin').text('登录');
                    $("#suBtn").attr('data-type', 'smsLogin').text('通过短信验证码登录');
                    $("#imgBox,#smsBox").addClass('login-hide');
                    $("#loginOption,#pwdBox,#userBox").removeClass('login-hide');
                    $("#pwdBox input").attr('placeHolder', '请输入密码');
                    break;
                case "smsLogin"://手机验证码登录
                    $("#pwdBox,#loginOption").addClass('login-hide');
                    $("#imgBox,#smsBox").removeClass('login-hide');
                    $("#mBtn").attr('data-type', 'smsLogin');
                    $("#suBtn").attr('data-type', 'back').text('返回登录');
                    $("#getCode").attr('data-type', 2);
                    sdk.getVerifyImg("#refreshCode");
                    break;
            }
        });
    };

    //关闭登录窗口
    sdk.closeLoginBox = function () {
        $("#loginBox").remove();
    };


    // 选择弹窗
    sdk.confirmDialog = function (txt, oklabel, cancellabel, okcallback, cancelcallback, title, callback) {
        var html = '<div id="confirmDialog" class="mask dialog-box">';
        html += '<div class="r5">';
        html += '<div>' + (title ? title : '提示信息') + '</div>';
        html += '<div>' + txt + '</div>';
        html += '<div class="flex">';
        html += '<a href="javascript:;" data-type="cancel" class="flex-list unselect">' + (cancellabel ? cancellabel : '关闭') + '</a>';
        if (oklabel) {
            html += '<a href="javascript:;" data-type="ok" class="flex-list unselect ok">' + oklabel + '</a>';
        }
        html += '</div></div></div>';
        $("body").append(html);
        if (callback) {
            callback();
        }
        $("#confirmDialog .flex a").click(function () {
            var type = $(this).attr("data-type");
            if (type == 'cancel') {
                if (cancelcallback) {
                    cancelcallback();
                }
            } else if (type == 'ok') {
                if (okcallback) {
                    okcallback();
                }
            }
            $(this).parents("#confirmDialog").remove();
        });
    };

    // 关闭选择弹窗
    sdk.hideConfirmDialog = function () {
        $("#confirmDialog").remove();
    };

    //微信头像加载 8*
    sdk.loadHeadImg = function (url, size) {
        if (url.substr(-2) == '/0') {
            url = url.replace(/\/0/g, '/' + size + '');
        }
        return url;
    };

    window.JET_SDK = sdk;
    $("#share_logo").remove();
    $("body").prepend('<div id="share_logo" style="margin:0 auto;display:none;"><img src= "'+sdk.cdnHost +'/static/image/share_logo.jpg" /></div>');
})();
var sdk = window.JET_SDK;

