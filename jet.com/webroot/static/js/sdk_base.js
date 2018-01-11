/* 游戏公共库：CLIENT */
(function() {
    var sdk = window.JET_SDK || {};
    sdk.clientVersion = "4.0 build 201709181450";
    sdk.gameId = 0;
    sdk.token = null;
    sdk.frames = {};
    sdk.gameInfo = null;
    sdk.shareQRInfo = null;
    sdk.headImg = null;
    sdk.shareCB = null;
    sdk.payCB = null;

    sdk.init = function () {
        sdk.token = sdk.getURLVar("token");
    };

// 微信相关初始化
    sdk.wxInit = function (args) {
        sdk.postTopMessage({cmd: "wxInit", args: args}, "*");
    };

// 配置SDK
    sdk.config = function (gameId, payCallback) {
        if (gameId) {
            sdk.gameId = gameId;
        }

        if (payCallback) {
            sdk.payCB = payCallback;
        }
        window.addEventListener("message", function (event) {
            switch (event.data.cmd) {
                case "onPay": {
                    if (sdk.payCB) {
                        sdk.payCB(event.data.args);
                    }
                    break;
                }
            }
        }, false);
        sdk.postTopMessage({cmd: "config"}, "*");
    };



// 设置支付回调
    sdk.setPayCB = function (payCallback) {
        sdk.payCB = payCallback;
    };

// 登出
    sdk.logout = function () {
        sdk.postTopMessage({cmd: "logout"}, "*");
    };
// 支付
    sdk.pay = sdk.jumpPay = function (args) {
        if (!sdk.isAllowPay()) {
            alert("支付系统暂不可用！");
        } else {
            sdk.postTopMessage({cmd: "pay", args: args}, "*");
        }
    };
//分享
	sdk.appShare = function (args){
		sdk.postTopMessage({cmd: "appShare", args: args}, "*");
	}

// 复制
    sdk.copy = function (text) {
        sdk.postTopMessage({cmd: "copy", args: text}, "*");
    };

// 显示顶层图片
    sdk.showTopImg = function (args) {
        sdk.postTopMessage({cmd: "showTopImg", args: args}, "*");
    };


// 下载微端
    sdk.downJetApp = function () {
        sdk.postTopMessage({cmd: "downJetApp"}, "*");
    };

// 刷新主页面
    sdk.refresh = function () {
        sdk.postTopMessage({cmd: "refresh"}, "*");
    };


// 判断是否是QQ环境
    sdk.isQQ = function () {
        return (navigator.userAgent.toLowerCase().match(/\bqq\b/i) == "qq");
    };

// 判断是否是微信环境
    sdk.isWeixin = function () {
        return (navigator.userAgent.toLowerCase().match(/MicroMessenger/i) == "micromessenger");
    };

// 判断是否是安卓设备
    sdk.isAndroid = function () {
        return navigator.userAgent.indexOf("Android") > -1 || navigator.userAgent.indexOf("Linux") > -1;
    };

// 判断是否是IOS设备
    sdk.isiOS = function () {
        return !!navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
    };

// 判断是否是PC微信环境
    sdk.isPCWeixin = function () {
        return (navigator.userAgent.toLowerCase().match(/WindowsWechat/i) == "windowswechat");
    };

// 是否是移动设备
    sdk.isMobile = function () {
        var userAgent = navigator.userAgent.toLowerCase();
        var agents = ["android", "iphone", "symbianos", "windows phone", "ipad", "ipod"];
        for (var v = 0; v < agents.length; v++) {
            if (userAgent.indexOf(agents[v]) > 0) {
                return true;
            }
        }
        return false;
    };

// 判断是否是Jet APP
    sdk.isJetAPP = function () {
        return (navigator.userAgent.toLowerCase().match(/Jet/i) == "jet");
    };

// 判断是否是jet盒子APP
    sdk.isJetboxAPP = function () {
        return (navigator.userAgent.toLowerCase().match(/Jetbox/i) == "jetbox");
    };

// 判断是否允许支付
    sdk.isAllowPay = function () {
        return navigator.userAgent.indexOf("Nopay") == -1;
    };

// 判断是否是safari浏览器
    sdk.isSafari = function () {
        return (navigator.userAgent.indexOf("Safari") > -1);
    };

// 判断是否是主屏幕微端（ios）
    sdk.isDesktopApp = function () {
        return (navigator.standalone);
    };

// 保存游戏至桌面
    sdk.saveGameDesktop = function () {
        sdk.postTopMessage({cmd: "saveGame"}, "*");
    };

// 获取URL中所有参数对象
    sdk.getURLQuery = function (url) {
        var query = {};
        if (url) {
            var search = url.split("?")[1];
            if (search) {
                var pairs = search.split("&");
                for (var i = 0; i < pairs.length; i++) {
                    query[pairs[i].split("=")[0]] = unescape(pairs[i].split("=")[1]);
                }
            }
        }
        return query;
    };

// 获取URL中的参数
    sdk.getURLVar = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        return r != null ? unescape(r[2]) : null;
    };

// 设置URL中的参数
    sdk.setURLVar = function (url, key, value) {
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
            if (value) {
                params[key] = value;
            } else {
                params[key] = null;
                delete params[key];
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

// 清空URL中的参数
    sdk.cleanURLVar = function (url) {
        if (url) {
            return url.split("?")[0];
        }
        return "";
    };

// 设置本地存储
    sdk.setItem = function (key, value) {
        if (window.localStorage) {
            try {
                window.localStorage.setItem("yg_" + key, value);
            } catch (err) {
            }
        } else {
            var exp = new Date();
            exp.setTime(exp.getTime() + 365 * 24 * 60 * 60 * 1000);
            document.cookie = "yg_" + key + "=" + escape(value) + ";expires=" + exp.toGMTString();
        }
    };

// 获取本地存储
    sdk.getItem = function (key) {
        if (window.localStorage) {
            return window.localStorage.getItem("yg_" + key);
        } else {
            var arr = document.cookie.match(new RegExp("(^| )yg_" + key + "=([^;]*)(;|$)"));
            if (arr != null) {
                return unescape(arr[2]);
            }
        }
        return null;
    };

// 移除本地存储
    sdk.removeItem = function (key) {
        if (window.localStorage) {
            window.localStorage.removeItem("yg_" + key);
        } else {
            var exp = new Date();
            exp.setTime(exp.getTime() - 1);
            var cval = sdk.getItem(key);
            if (cval != null) {
                document.cookie = "yg_" + key + "=" + cval + ";expires=" + exp.toGMTString();
            }
        }
    };

// 设置本地对话
    sdk.setSession = function (key, value) {
        if (window.sessionStorage) {
            window.sessionStorage.setItem("yg_" + key, value);
        }
    };

// 获取本地对话
    sdk.getSession = function (key) {
        if (window.sessionStorage) {
            return window.sessionStorage.getItem("yg_" + key);
        }
        return null;
    };

// 移除本地对话
    sdk.removeSession = function (key) {
        if (window.sessionStorage) {
            window.sessionStorage.removeItem("yg_" + key);
        }
    };

// 拼接创建一个URL
    sdk.buildURL = function (url, args) {
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
            for (var i in args) {
                if (args[i]) {
                    params[i] = args[i];
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

// 随机一个字符串
    sdk.randomString = function (len) {
        len = len || 32;
        var allChars = "abcdefghijklmnopqrstuvwxyz";
        var count = allChars.length;
        var str = '';
        for (var i = 0; i < len; i++) {
            str += allChars.charAt(Math.floor(Math.random() * count));
        }
        return str;
    };

// 随机一个数字串
    sdk.randomNumber = function (len) {
        len = len || 8;
        var allChars = "0123456789";
        var count = allChars.length;
        var str = '';
        for (var i = 0; i < len; i++) {
            str += allChars.charAt(Math.floor(Math.random() * count));
        }
        return str;
    };

// 创建一个iFrame
    sdk.createFrame = function (name, src) {
        if (!sdk.frames[name]) {
            sdk.frames[name] = document.createElement("iframe");
            sdk.frames[name].name = name;
            sdk.frames[name].src = src;
            sdk.frames[name].style.display = "none";
            document.body.appendChild(sdk.frames[name]);
        } else {
            sdk.frames[name].src = src;
        }
    };

// 加载单个JS文件
    sdk.loadSingleScript = function (src, callback) {
        var node = document.createElement("script");
        node.src = src;
        if (node.hasOwnProperty("async")) {
            node.async = false;
        }
        node.addEventListener("load", function () {
            this.removeEventListener("load", arguments.callee, false);
            if (callback) {
                callback();
            }
        }, false);
        document.body.appendChild(node);
    };

// 向DOM追加单个JS文件引用
    sdk.appendSingleScript = function (src, isBody) {
        var parentNode = document.getElementsByTagName(isBody ? "body" : "head").item(0);
        var node = document.createElement("script");
        node.type = "text/javascript";
        node.src = src;
        parentNode.appendChild(node);
    };

// 生成二维码
    sdk.createQRCode = function (url, width, height, callback) {
        function __createQRCode() {
            var div = document.createElement("div");
            var qrcode = new QRCode(div, {width: width, height: height, typeNumber: -1});
            qrcode.makeCode(url);
            var img = div.getElementsByTagName("img")[0];
            img.onload = function () {
                img.onload = null;
                callback(img.src);
            }
        }

        if (window.QRCode) {
            __createQRCode();
        } else {
            sdk.loadSingleScript("http://api.jet.netkingol.com/static/js/qrcode.min.js", function () {
                __createQRCode();
            });
        }
    };

// 发送到桌面回调
    sdk.__onAddShortcut = function (event) {
        if (event.data.cmd == "onAddShortcut") {
            window.removeEventListener("message", sdk.__onAddShortcut, false);
            sdk.__onAddShortcutCB();
        }
    };

// 创建桌面快捷方式
    sdk.addShortcut = function (callback) {
        sdk.__onAddShortcutCB = callback;
        window.removeEventListener("message", sdk.__onAddShortcut, false);
        window.addEventListener("message", sdk.__onAddShortcut, false);
        sdk.postTopMessage({cmd: "addShortcut"}, "*");
    };

// HTTP GET请求
    sdk.httpGet = function (url, callback, option) {
        var request = null;
        if (window.XMLHttpRequest) {
            request = new XMLHttpRequest();
        } else {
            request = new ActiveXObject("Microsoft.XMLHTTP");
        }
        if (request) {
            request.onreadystatechange = function () {
                if (request.readyState == 4) {
                    callback(option == "json" ? JSON.parse(request.responseText) : request.responseText);
                }
            };
            request.open("GET", url, true);
            request.send();
        }
    };

// 向上发送信息
    sdk.postTopMessage = function (message, target) {
        window.top.postMessage(message, target);
    };
    sdk.init();
    window.JET_SDK = sdk;
})();
var sdk = window.JET_SDK;