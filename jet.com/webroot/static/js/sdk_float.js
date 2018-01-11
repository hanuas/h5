(function() {
    var sdk = window['JET_SDK'] || {};
    sdk.ic = {
        version: "1.0 ",
        getUnreadUrl: sdk.apiHost +"/ic/mmsg?cmd=getUnread",
        getDotUrl: sdk.webHost +"/ic/api?cmd=getRemindDot&checkHasJh=1",
        getPointGiftUrl: sdk.apiHost +"/ic/game?",
        delDotUrl: sdk.webHost +"/ic/api?cmd=delRemindDot&type=1",
        giftCode: sdk.getItem("giftCode"),
        isTrial: sdk.getURLVar("trial"), //是否是试玩环境
        win: window,
        doc: document,
        isGift: false, //是否有礼包
        isNewGift: false, //是否有新礼包
        isMsg: false, //是否有历史消息
        isPointGift: false, //是否有积分礼包
        isNewPointGift: false, //是否有新的积分礼包
        isJoinChat: false, //打开侧边栏显示聊天界面
        msgCount: 0, //未读消息条数
        offsetX: 0,
        offsetY: 0,
        httpGet: function (url, callback) {
            $.get(url + '&token=' + sdk.token + '&gameid=' + sdk.gameId + '&v=' + Date.now(), function (data) {
                if (data.error) {
                    switch (parseInt(data.error)) {
                        case 1:
                            sdk.confirmDialog('出了一点小状况');
                            break;
                        case 2:
                            sdk.confirmDialog('缺少必要参数');
                            break;
                        case 3:
                            sdk.confirmDialog('请重新登录');
                            break;
                        case 4:
                            sdk.confirmDialog('非法参数');
                            break;
                        case 5:
                            sdk.confirmDialog('礼包不存在');
                            break;
                        case 6:
                            sdk.confirmDialog('购买礼包超过每日上限');
                            break;
                        case 7:
                            sdk.confirmDialog('购买礼包超过上限');
                            break;
                        case 8:
                            sdk.confirmDialog('积分不足');
                            break;
                        case 9:
                            sdk.confirmDialog('礼包不足');
                            break;
                        case 10:
                            sdk.confirmDialog('游戏礼包暂未开放');
                            break;
                        case 11:
                            sdk.confirmDialog('角色不存在');
                            break;
                        case 60001:
                            break;
                        default:
                            sdk.confirmDialog('未知错误，请联系客服');
                    }
                } else {
                    callback(data);
                }
            }, 'json')
        },
        iconResize: function (callback) { //icon复位
            sdk.ic.iconBtn.css("right", "0rem");
            sdk.ic.delay = setTimeout(function () {
                if (!sdk.ic.iconBtn.find(".open").length) {
                    sdk.ic.door.css("width", "1rem");
                }
                if (callback) {
                    callback();
                }
            }, 100);
        },
        click: function () { //icon点击事件
            if (sdk.ic.isTrial) { //试玩环境
                if (sdk.ic.iconBtn.find(".close").length) {
                    var _w = '6.4rem';
                    if (sdk.channelInfo) {
                        if (!sdk.channelInfo.downJetApp) {
                            _w = '5rem';
                        }
                    }
                    sdk.ic.door.addClass("flex-x-end open").css("width", _w);
                    setTimeout(function () {
                        sdk.ic.door.removeClass("close");
                    }, 200)
                } else {
                    sdk.ic.door.addClass("close").css("width", "2rem");
                    setTimeout(function () {
                        sdk.ic.door.removeClass("flex-x-end open");
                        sdk.ic.iconResize();
                    }, 100)
                }
            } else {
                if ($("#promptGiftCode").length) {
                    $("#promptGiftCode").remove();
                    sdk.removeItem("giftCode");
                } else {
                    if (sdk.ic.iconBtn.find("i").length) {
                        sdk.ic.iconBtn.find("i").remove();
                    }
                    if (!$("#ftCntJs").length) {
                        sdk.ic.loadMain();
                    } else {
                        $(".shortcut-box").css({"left": "0rem"});
                        $(".shortcut-box>a").addClass("animate");
                    }
                }
                sdk.ic.iconResize();
            }
        },
        loadMain: function () { //加载窗体
            var $scroll = $('<script />', {
                src: sdk.cdnHost +'/static/js/sdk.scroll.min.js'
            });
            var $float = $('<script />', {
                src: sdk.cdnHost +'/static/js/sdk_float_content.js?v=' + Date.now(),
                id: 'ftCntJs'
            });
            $("body").append($scroll);
            $scroll.on("load", function () {
                $("body").append($float);
            });
        },
        touchStart: function (x, y) { //touch-事件开始
            if (sdk.ic.delay) {
                clearTimeout(sdk.ic.delay);
            }
            sdk.ic.offsetX = x;
            sdk.ic.offsetY = y;
            if (!sdk.ic.iconBtn.find(".open").length) {
                sdk.ic.door.css({"width": "2rem"});
            }
        },
        touchMove: function (x, y, e) { //touch-事件移动
            if (y < 20 || y >= sdk.ic.height - 20 || x < 20 || x >= sdk.ic.width - 20) {
                e.preventDefault();
                return;
            }

            sdk.ic.iconBtn.css({
                "right": (sdk.ic.width - x - 20) / 20 + 'rem',
                "top": (y - 20) / 20 + 'rem'
            });
        },
        touchEnd: function (x, y) { //touch-事件停止
            if (Math.abs(x - sdk.ic.offsetX) < 20 && Math.abs(y - sdk.ic.offsetY) < 20) {//点击事件
                sdk.ic.click();
            } else {
                if (y < 40) {
                    y = 20;
                }
                if (y > sdk.ic.height - 40) {
                    y = sdk.ic.height - 20;
                }
                y = (y - 20) / 20;
                sdk.ic.iconBtn.css({
                    "top": y + 'rem'
                });
                sdk.setItem('iconBtnPos_' + sdk.gameId, y); //存储icon当前位置
                sdk.ic.iconResize();
            }
        },
        pcMoveBind: function () { //pc端监控mouse事件
            sdk.ic.move.bind('mousedown', function (e) {
                sdk.ic.touchStart(e.pageX, e.pageY);
                $(sdk.ic.doc).bind("mousemove", function (event) {
                    sdk.ic.touchMove(event.pageX, event.pageY, event);
                    return false;
                });
            });
            sdk.ic.move.bind("mouseup", function (e) {
                $(sdk.ic.doc).unbind("mousemove");
                sdk.ic.touchEnd(e.pageX, e.pageY);
            });
        },
        mbTouchBind: function () { //移动端监控touch事件
            function _touchEvent(event) {
                var event = event || window.event;
                switch (event.type) {
                    case "touchstart":
                        sdk.ic.touchStart(event.touches[0].clientX, event.touches[0].clientY);
                    case "touchmove":
                        sdk.ic.touchMove(event.touches[0].clientX, event.touches[0].clientY, event);
                        break;
                    case "touchend":
                        sdk.ic.touchEnd(event.changedTouches[0].clientX, event.changedTouches[0].clientY);
                        break;
                }
            }

            sdk.ic.move.bind("touchstart", '', _touchEvent);
            sdk.ic.move.bind("touchmove", '', _touchEvent);
            sdk.ic.move.bind("touchend", '', _touchEvent);
        },
        init: function () {
            sdk.ic.width = $(window).width();
            sdk.ic.height = $(window).height();

            //页面添加浮动icon按钮
            if (!$("#shortcut").length) {
                var t = '<div id="shortcut" class="shortcut">';
                t += '<div class="flex">';
                if (sdk.ic.isTrial) { //试玩环境增加事件
                    if (!(sdk.channelInfo && !sdk.channelInfo.downJetApp)) {
                        t += '<div onclick="sdk.downJetApp()" class="flex-list"><i class="icon-down"></i><span>微端</span></div>';
                    }
                    if (sdk.isTrial) { //试玩账号未绑定
                        t += '<div onclick="sdk.loadTrialLogin()" class="flex-list"><i class="icon-user"></i><span>登录</span></div>';

                        //记录开始试玩时间
                        if (!sdk.getItem('trial-time')) {
                            sdk.setItem('trial-time', Date.parse(new Date()));
                        }

                        //试玩账号禁止支付
                        sdk.pay = sdk.jumpPay = function () {
                            sdk.confirmDialog('亲爱的玩家，您当前账号为试玩账号，为保障账户安全，还请尽快绑定！', '现在就去', '知道了', function () {
                                sdk.loadTrialLogin();
                            }, null);
                        };

                        //每分钟检测
                        var trialTime = setInterval(function () {
                            if (Date.parse(new Date()) - sdk.getItem('trial-time') > 3600000) {
                                sdk.confirmDialog('亲爱的玩家，您当前账号为试玩账号，为保障账户安全，还请尽快绑定！', '现在就去', '知道了', function () {
                                    sdk.loadTrialLogin();
                                }, function () {
                                    sdk.setItem('trial-time', Date.parse(new Date()));
                                });
                            }
                        }, 60000);
                    } else {
                        t += '<div onclick="sdk.loadTrialInfo()" class="flex-list"><i class="icon-user"></i><span>账号</span></div>';
                    }
                }
                t += '<div id="ic-menu" ><span></span></div>';
                t += '</div>';
                t += '</div>';
                $("body").append(t);
                sdk.ic.iconBtn = $("#shortcut");
                sdk.ic.door = $("#shortcut>div");
                sdk.ic.move = $("#ic-menu");

                if (sdk.ic.isTrial) {
                    sdk.ic.iconBtn.addClass("trial");
                    sdk.ic.door.addClass("close");
                }

                var iconBtnPos = sdk.getItem("iconBtnPos_" + sdk.gameId);
                if (iconBtnPos) { //检测icon是否有缓存位置
                    sdk.ic.iconBtn.css({"top": iconBtnPos + 'rem'});
                }

                //页面加载后浮动icon复位一次
                sdk.ic.iconResize(function () {
                    //在游戏中心领取了礼包，读取缓存
                    if (sdk.ic.giftCode) {
                        if (!$("#promptGiftCode").length) {//判断是否已经存在，避免重复添加
                            sdk.ic.iconBtn.append('<div id="promptGiftCode" class="r5">请在游戏兑换界面手动输入<br>礼包码：' + sdk.ic.giftCode + '</div>');
                        }
                    } else {
                        if (!sdk.ic.isTrial) { //非试玩环境 检测是否有未读消息／是否有礼包／是否有活动
                            sdk.ic.httpGet(sdk.ic.getUnreadUrl, function (data) {
                                if (data.total) {//有历史消息
                                    sdk.ic.isMsg = true;
                                }
                                sdk.ic.httpGet(sdk.ic.getDotUrl, function (e) {
                                    if (e.hasJh) { //有礼包
                                        sdk.ic.isGift = true;
                                    }
                                    if (data.count) {//有未读消息
                                        sdk.ic.msgCount = data.count;
                                        sdk.ic.iconBtn.append('<i class="r5">消息</i>');
                                    } else if (e.remind[1]) {//有新礼包
                                        sdk.ic.isNewGift = true;
                                        sdk.ic.iconBtn.append('<i class="r5">礼包</i>');
                                    }
                                    sdk.ic.iconBtn.find("i").addClass("active");
                                });
                            });

                            sdk.ic.httpGet(sdk.ic.getPointGiftUrl + 'cmd=hasShop', function (params) {
                                if (params.count) { //有积分礼包
                                    sdk.ic.isPointGift = true;
                                }
                                if (params.new) { //有新积分礼包
                                    sdk.ic.isNewPointGift = true;
                                }
                            })
                        }
                    }
                });

                //根据环境启用事件监控
                if (sdk.isMobile()) {
                    if ("ontouchend" in sdk.ic.doc) {//是否支持touch事件
                        sdk.ic.mbTouchBind();
                    }
                } else {
                    sdk.ic.pcMoveBind();
                }

                window.addEventListener("message", function (event) {
                    if (event.data) {
                        switch (event.data.cmd) {
                            case "joinChat": {
                                sdk.ic.isJoinChat = true;
                                sdk.ic.click();
                                break;
                            }
                        }
                    }
                }, false);
            }
        }
    };
    sdk.ic.init();
})();