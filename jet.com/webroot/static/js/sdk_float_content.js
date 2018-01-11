(function () {
    var sdk = window['JET_SDK'] || {};
    sdk.icc = {
        baseUrl: sdk.webHost +'/ic/api?',
        msgUrl: sdk.apiHost + '/ic/mmsg?cmd=',
        vipGiftUrl: sdk.apiHost + '/ic/activity?',
        msgPage: 1,
        msgSize: 15,
        msgId: null,
        msgItem: 0,
        msgCurrent: 0,
        pointGiftAuto: [206, 97, 170, 157,1011],
        chid: sdk.getURLVar('chid'),
        subchid: sdk.getURLVar('subchid'),
        isTrial: sdk.getURLVar('trial'),
        chatScroll: null,
        msgScroll: null,
        msgScrollPos: 0, //记忆资讯列表滚动的高度
        autoScroll: true,
        giftScroll: null,
        chatItem: 0,
        config: {},
        addNotice: function (txt) {
            $("#chatList").append('<li class="info flex flex-x-center"><em class="r3">' + txt + '</em></li>');
            if (sdk.icc.chatScroll) {
                sdk.icc.chatScroll.refresh();
                sdk.icc.chatScroll.scrollToElement(document.querySelector('#chatList li:last-child'), 200, null, null, IScroll.utils.ease.quadratic);
            }
        },
        jetIMConnect: function () {
            sdk.chat.connect(sdk.token, 'club_' + sdk.gameId);
            //sdk.chat.getStatus();

            sdk.chat.onConnect = function () {
                sdk.icc.addNotice('聊天室连接成功');
                sdk.icc.joinChatRoom();
            };

            sdk.chat.onMessage = function (data) {
                sdk.icc.addMessage(data);
            };

            sdk.chat.onDisconnect = function () {
                sdk.icc.addNotice('与聊天室断开连接');
                sdk.icc.addNotice('正在尝试重新连接');
                setTimeout(function () {
                    sdk.icc.jetIMConnect();
                }, 3000)
            };

            sdk.chat.onError = function (err) {
                //$("#chatList").append('<li class="info flex flex-x-center"><em class="r3">' + err + '</em></li>');
            };
        },
        joinChatRoom: function () {
            sdk.chat.joinChatRoom(1, function (err, ret) {
                if (!err) {
                    sdk.icc.addNotice('加入聊天室成功');
                } else {
                    sdk.icc.addNotice('加入聊天室失败');
                }
            });
        },
        fillHeadimg: function (data) {
            return data.headimgurl ? sdk.loadHeadImg(data.headimgurl, 96) : sdk.cdnHost + '/static/image/jeticon.png';
        },
        fillNickname: function (data, e) {
            if (data.nickname) {
                try {
                    data.nickname = decodeURIComponent(data.nickname);
                } catch (e) {
                    data.nickname = '多纷玩家';
                }
                if (e) {
                    if (e.source_id.split('|')[0] != sdk.uid) { //玩家昵称处理
                        data.nickname = data.nickname.replace(/官方|多纷|客服|多纷|返利|返点|充值|法老喵|快鹿|派派|疯狂游乐|1758|9G|7724/g, '');
                    }
                }
            } else {
                data.nickname = '多纷玩家';
            }
            return data.nickname;
        },
        report: function (uid, msg, msgId) {
            sdk.confirmDialog('您确认要举报该玩家吗?', '举报', '算了', function () {
                $.post(sdk.apiHost + '/ic/chat_new?cmd=blackChatUid', {
                    'chatUid': sdk.uid,
                    'blackChatUid': uid,
                    'content': msg,
                    'messageId': msgId,
                    'roomId': 'club_' + sdk.gameId
                }, function (data) {
                    if (!data.error) {
                        sdk.confirmDialog('举报成功！');
                    }
                }, 'json');
            }, null);
        },
        chatResize: function () {
            sdk.icc.autoScroll = true;
            sdk.icc.chatItem = 0;
            $("#chatView").css("right", "-6rem");
            setTimeout(function () {
                $("#chatView").remove();
            }, 400);
        },
        changeVip: function (number) { //VIP转换
        	return number;
//             switch (parseInt(number)) {
//                 case 1:
//                 case 2:
//                 case 3:
//                     return 1;
//                 case 4:
//                 case 5:
//                 case 6:
//                     return 2;
//                 case 7:
//                 case 8:
//                 case 9:
//                     return 3;
//                 case 10:
//                 case 11:
//                 case 12:
//                     return 4;
//                 default:
//                     return 5;
//             }
        },
        addMessage: function (data) {
            var _content = data.msg_data;
            if (_content) {
                var o = '';
                var source_id = data.source_id.split('|')[0];
                if (source_id == sdk.uid) { //自己
                    o += '<li class="flex flex-end self">';
                    o += '\t<div class="headimg">';
                    o += '\t\t<img class="r50" src="' + sdk.icc.fillHeadimg(sdk.icc.config) + '">';
                    o += '\t</div>';
                    o += '\t<div class="flex-list">';
                    o += '\t\t<div class="r5">' + _content + '</div>';
                    o += '\t</div>';
                    o += '</li>';
                } else { //其他玩家
                    o += '<li class="flex">';
                    o += '\t<div class="headimg">';
                    o += '\t\t<img onclick="sdk.copy(' + source_id + ',\'已复制玩家UID：' + source_id + '\')" class="r50" src="' + sdk.icc.fillHeadimg(data.ext) + '">';
                    if (data.ext.vip) {
                        o += '<div class="flex v' + sdk.icc.changeVip(data.ext.vip) + '"><i class="icon-vip-text"></i><em>' + data.ext.vip + '</em></div>';
                    }
                    o += '\t</div>';
                    o += '\t<div class="flex-list">';
                    o += '\t\t<p class="flex">';
                    o += '\t\t\t<span>' + sdk.icc.fillNickname(data.ext, data) + '</span>';
                    if (data.ext.sex == 2) { //女
                        o += '<i class="icon-female"></i>';
                    } else {
                        o += '<i class="icon-male"></i>';
                    }
                    o += '<span class="time">';

                    var result = (Date.now() - data.time) / 1000;
                    var hh = Math.floor(result / 3600 % 24);
                    var mm = Math.floor(result / 60 % 60);
                    if (hh && hh > 0) {
                        if (hh > 24) {
                            hh = Math.floor(hh / 24);
                            o += hh + '天前';
                        } else {
                            o += hh + '小时前';
                        }
                    } else if (mm > 0) {
                        o += mm + '分钟前';
                    } else {
                        o += '刚刚';
                    }
                    o += '</span>';

                    if (data.ext.pos) {
                        o += '<span class="pos">(<i class="icon-pos"></i>' + data.ext.pos + ')</span>';
                    }
                    o += '\t\t\t<a class="report" href="javascript:;" onclick="sdk.icc.report(' + source_id + ',\'' + _content.replace(/'/g, '') + '\',' + data.time + ')" >举报</a>';
                    o += '\t\t</p>';
                    o += '\t\t<div class="r5">' + _content + '</div>';
                    o += '\t</div>';
                    o += '</li>';
                }
                $("#chatList").append(o);
            }

            if (!sdk.icc.chatScroll) {
                sdk.icc.chatScroll = new IScroll('.chat-box', {
                    mouseWheel: true,
                    preventDefault: false
                });
            } else {
                sdk.icc.chatScroll.refresh();
            }

            if (sdk.icc.autoScroll) {
                sdk.icc.chatScroll.scrollToElement(document.querySelector('#chatList li:last-child'), 200, null, null, IScroll.utils.ease.quadratic);
            } else {
                if ($("#chatList").height() > $(".chat-box").height()) {
                    sdk.icc.chatItem++;
                    if (!$("#chatView").length) {
                        $(".chat-box").append('<em id="chatView"></em>');

                        $("#chatView").off().click(function () {
                            sdk.icc.chatScroll.scrollToElement(document.querySelector('#chatList li:last-child'), 100, null, null, IScroll.utils.ease.quadratic);
                            sdk.icc.chatResize();
                        });
                    }
                    $("#chatView").html('<i class="icon-jt"></i>' + sdk.icc.chatItem + '条新消息');
                }
            }

            sdk.icc.chatScroll.on('scrollEnd', function () {
                if (Math.abs(this.y) >= Math.abs(this.maxScrollY) - 10) {
                    sdk.icc.chatResize();
                } else {
                    sdk.icc.autoScroll = false;
                }
            });
        },
        sendBind: function () {
            $("#chat-send").click(function () {
                sdk.icc.sendMessage($.trim($("#chat-text").val()));
            });
            $("#chat-text").keyup(function (e) {
                if (e.keyCode == 13) {
                    sdk.icc.sendMessage($.trim($("#chat-text").val()));
                }
            })
        },
        getLocation: function () {
            sdk.loadSingleScript('https://3gimg.qq.com/lightmap/components/geolocation/geolocation.min.js', function () {
                var geolocation = new qq.maps.Geolocation("PBJBZ-P5IWS-QYPO5-6VUZK-5JUYJ-7HB7V", "myapp");
                var positionNum = 0;
                var options = {timeout: 9000};

                function showPosition(position) {
                    positionNum++;
                    sdk.setItem('jet_my_local', position.city.replace(/市/g, ''));
                    sdk.icc.config.pos = sdk.getItem('jet_my_local');
                }

                function showErr() {
                    positionNum++;
                    console.log("定位失败！");
                }

                sdk.setItem('my_local_time', new Date().getDate());
                geolocation.getLocation(showPosition, showErr, options);
            });
        },
        sendMessage: function (content) {
            if (!sdk.getItem('jet_my_local') && (new Date().getDate() != sdk.getItem("my_local_time"))) {
                sdk.icc.getLocation();
            }
            if (content) {
                var now = Date.now();
                if (sdk.icc.leastSendTime && now - sdk.icc.leastSendTime < 10000) {
                    sdk.confirmDialog("请不要频繁发送消息! ");
                } else {
                    if (sdk.icc.leastMsg && sdk.icc.leastMsg == content && now - sdk.icc.leastSendTime < 180000) {
                        sdk.confirmDialog("请不要频繁发送同一条消息!");
                    } else {
                        if (content.length > 40) {
                            sdk.confirmDialog("聊天内容不能超过40个字！");
                        } else {
                            sdk.icc.leastSendTime = Date.now();
                            sdk.icc.leastMsg = content;
                            var ext = {
                                "nickname": sdk.icc.config.nickname,
                                "headimgurl": sdk.icc.config.headimgurl,
                                "vip": sdk.icc.config.vip,
                                "sex": sdk.icc.config.sex,
                                "pos": sdk.icc.config.pos
                            };

                            if (!sdk.icc.config.headimgurl || !sdk.icc.config.nickname || sdk.icc.config.headimgurl == "/0") {
                                sdk.icc.addMessage({
                                    ext: ext,
                                    msg_content_type: 1,
                                    msg_data: content,
                                    msg_type: 2,
                                    source_id: sdk.uid + '|',
                                    time: now,
                                    to_id: 1
                                });
                            } else {
                                sdk.chat.sendRoomMessage(content, ext, function (err) {
                                    if (err) {
                                        if (err == 40015) {
                                            sdk.icc.addMessage({
                                                ext: ext,
                                                msg_content_type: 1,
                                                msg_data: content,
                                                msg_type: 2,
                                                source_id: sdk.uid + '|',
                                                time: now,
                                                to_id: 1
                                            });
                                        } else {
                                            switch (parseInt(err)) {
                                                case 40017:
                                                case 60001:
                                                    sdk.confirmDialog('您已被禁止发言');
                                                    break;
                                            }
                                        }
                                    }
                                });
                            }
                            $("#chat-text").val("");
                        }
                    }
                }
            } else {
                sdk.confirmDialog('发送的消息不能为空')
            }
        },
        getGiftCode: function (type) { //领取礼包
            sdk.ic.httpGet(sdk.icc.baseUrl + 'cmd=drawJh' + '&type=' + type, function (data) {
                var t = '<div class="flex gift-dialog"><label>兑换码：</label><div class="r3">' + $.trim(data.activation_code) + '</div></div><p class="gift-dialog-info">复制兑换码，去游戏中使用</p>';
                sdk.confirmDialog(t, null, '复制', null, function () {
                    setTimeout(function () {
                        sdk.copy($.trim(data.activation_code));
                    }, 50)
                }, '领取提示');
            })
        },
        thumb: function (e) { //点赞
            function _thumb(type) {
                sdk.ic.httpGet(sdk.icc.msgUrl + 'thumb&type=' + type + '&id=' + sdk.icc.msgId, function () {
                })
            }

            var _type = parseInt($(e).attr("data-thumb"));
            var _obj = $(e).find("em");
            var _value = parseInt(_obj.html());
            if (!_type) {
                $(e).attr("data-thumb", 1);
                $(e).attr("class", "active");
                _obj.html(_value + 1);
            } else {
                $(e).attr("data-thumb", 0);
                $(e).attr("class", "");
                _obj.html(_value - 1)
            }
            _thumb(_type);
        },
        imConnect: function () { //聊天
            if (sdk.getItem('jet_my_local')) {
                sdk.icc.config.pos = sdk.getItem('jet_my_local');
            }
            sdk.loadSingleScript(sdk.cdnHost+"/static/js/sdk_webchat.js", function () {
                sdk.icc.sendBind();
                sdk.icc.jetIMConnect();
            });
        },
        getPointGift: function (id, number,is_auto_send) { //领取积分礼包
            function _goodsChange(serverid, servername, roleid, rolename) {
                var _url = '';
                if (serverid) {
                    _url = '&serverid=' + serverid + '&servername=' + servername + '&roleid=' + roleid + '&rolename=' + rolename;
                }
                sdk.ic.httpGet(sdk.ic.getPointGiftUrl + 'cmd=buyGoods&goodsId=' + id + _url, function (data) {
                    $("#mypoint").html(data.leftScore);
                    if (data.cardSn) { //有码
                        var t = '<div class="flex gift-dialog"><label>兑换码：</label><div class="r3">' + $.trim(data.cardSn) + '</div></div><p class="gift-dialog-info">复制兑换码，去游戏中使用</p>';
                        sdk.confirmDialog(t, null, '复制', null, function () {
                            setTimeout(function () {
                                sdk.copy($.trim(data.cardSn));
                            }, 50)
                        }, '兑换提示');
                    } else {
                        sdk.confirmDialog('恭喜您兑换成功<br>礼包已经通过邮件发放', null, '好的', null, null, '兑换提示')
                    }
                })
            }

            var point = $("#mypoint").html();
            if (parseInt(number) > parseInt(point)) {
                sdk.confirmDialog('您的积分不足');
            } else {
                sdk.confirmDialog('您确定要兑换吗？', '立即兑换', '再想想', function () {
                    //if (sdk.icc.pointGiftAuto.indexOf(parseInt(sdk.gameId)) >= 0) {
                    if(is_auto_send){
                        var _userName = '';
                        var _userId = '';
                        var _serverName = '';
                        var _serverId = '';
                        sdk.ic.httpGet(sdk.ic.getPointGiftUrl + 'cmd=getRoleList&goodsId=' + id, function (data) {
                            var data = data.roleList;
                            var t = '<div class="serviceArea"><div class="flex">';
                            t += '<label>区服：</label>';
                            t += '<select id="serviceChange" class="flex-list">';
                            t += '<option value="0">请选择区服</option>';
                            for (var i in data) {
                                t += '<option data-userid="' + data[i].roleid + '" data-user="' + data[i].rolename + '" value="' + data[i].serverid + '">' + data[i].servername + '</option>';
                            }
                            t += '</select>';
                            t += '</div>';
                            t += '<div class="flex">';
                            t += '<label>角色：</label>';
                            t += '<input type="text" class="flex-list" id="userNameChange"  disabled readonly>';
                            t += '</div>';
                            t += '</div>';
                            sdk.confirmDialog(t, '确认', null, function () {
                                if (!parseInt(_serverId)) {
                                    sdk.confirmDialog('缺少必填参数');
                                } else {
                                    _goodsChange(_serverId, _serverName, _userId, _userName);
                                }
                            }, null, '请先选择区服角色', function () {
                                $("#serviceChange").change(function () {
                                    var _this = $(this);
                                    var _index = _this.prop("selectedIndex");
                                    var _obj = _this.find("option").eq(_index);
                                    _userName = _obj.attr("data-user");
                                    _userId = _obj.attr("data-userid");
                                    _serverName = _obj.html();
                                    _serverId = _this.val();
                                    $("#userNameChange").val(decodeURIComponent(_userName));
                                });
                            });
                        });
                    } else {
                        _goodsChange();
                    }
                }, null, '兑换提示')
            }
        },
        getPoint: function () {
            var url = sdk.gameHost ;
            sdk.createCode(sdk.token, function (data) {
                if (data.code) {
                    url = sdk.buildURL(url, {"code": data.code});
                }
                if (sdk.icc.chid) {
                    url = sdk.buildURL(url, {"chid": sdk.icc.chid});
                }
                if (sdk.icc.subchid) {
                    url = sdk.buildURL(url, {"subchid": sdk.icc.subchid});
                }
                var t = '<p class="pt-info flex"><i>1、</i><em>游戏中心 <a href="' + url + '#market">每日签到</a> 即可获得大量积分；</em></p>';
                t += '<p class="pt-info flex"><i>2、</i><em>在平台任意游戏内每充值1元即可获得10积分；</em></p>';
                t += '<p class="pt-info flex"><i>3、</i><em>每隔一段时间都会开放积分获取的限时活动，小伙伴们别错过哦~</em></p>';
                sdk.confirmDialog(t, null, '好的');
            });
        },
        loadPointGiftList: function () { //积分礼包
            sdk.ic.httpGet(sdk.ic.getPointGiftUrl + 'cmd=getGameGoods', function (params) {
                var params = params.goodsList;
                if (params.length) {
                    var t = '<dl class="rules pt-gift">';
                    t += '<dt class="flex"><span>积分礼包</span><a href="javascript:;" onclick="sdk.icc.getPoint()">如何获取积分?</a></dt>';
                    t += '<dd>';
                    t += '<ol class="gift-box">';
                    for (var i in params) {
                        if (!params[i].hide) {
                            t += '<li class="flex">';
                            t += '<div class="flex-list">';
                            t += '<p>';
                            t += '<em class="tags-point">积分</em> ';
                            t += '<span>' + params[i].goods_name + '</span> </p>';
                            t += '<p class="intro">' + params[i].goods_brief + '</p>';
                            t += '</div>';
                            if (!params[i].goods_number) {
                                t += '<a href="javascript:;" class="btn r3 disabled">缺货</a>';
                            } else {
                                t += '<div class="flex flex-v">';
                                t += '<a href="javascript:;" onclick="sdk.icc.getPointGift(' + params[i].goods_id + ',' + params[i].point + ',' + params[i].is_auto_send + ')" class="btn r3">领取</a>';
                                t += '<p>需' + params[i].point + '积分</p>';
                                t += '</div>';
                            }
                            t += '</li>';
                        }
                    }
                    t += '</ol>';
                    $("#box-gift>div").append(t);

                    if (!sdk.icc.giftScroll) {
                        sdk.icc.giftScroll = new IScroll('#box-gift', {
                            mouseWheel: true,
                            click: true
                        });
                    } else {
                        sdk.icc.giftScroll.refresh();
                    }
                }
            });
        },
        loadGiftList: function (callback) { //礼包
            sdk.ic.httpGet(sdk.icc.baseUrl + 'cmd=getGameInfo&id=' + sdk.gameId, function (data) {
                var gift = data.gift;
                var t = '';
                if (sdk.ic.isPointGift) { //有积分礼包添加标题
                    t += '<dl class="rules">';
                    t += '<dt class="flex"><span>普通礼包</span></dt>';
                    t += '<dd>';
                }
                t += '<ol class="gift-box">';
                if (gift.length) {
                    for (var i in gift) {
                        console.log(gift);
                        t += '<li class="flex">';
                        t += '<div class="flex-list">';
                        t += '<p>';
                        if (gift[i].union_code) {
                            t += '<em class="tags-coupon">统一码</em> ';
                        } else if (parseInt(gift[i].qq_group_num)) {
                            t += '<em class="tags-qq">入群</em> ';
                        } else if (gift[i].sum) {
                            t += '<em class="tags-normal">普通</em> ';
                        }
                        t += '<span>' + gift[i].title + '</span>';
                        t += '</p>';
                        if (gift[i].brief_intro.length > 50) {
                            t += '<p class="intro">' + gift[i].brief_intro.replace(/\r\n/g, "<br>").substring(0, 50) + '...<span onclick="sdk.confirmDialog(\'' + gift[i].brief_intro.replace(/\r\n/g, "<br>") + '\',null,null,null,null,\'礼包详细\')">详细</span></p>';
                        } else {
                            t += '<p class="intro">' + gift[i].brief_intro + '</p>';
                        }
                        t += '</div>';
                        if (gift[i].qq_group_num) {
                            t += '<a onclick="sdk.icc.joinGroup(\'' + gift[i].qq_group_link + '\')" href="javascript:;" class="btn r3">加群</a>';
                        } else if ((parseInt(gift[i].sum) == parseInt(gift[i].getcount)) && !gift[i].union_code) {
                            t += '<a href="javascript:;" class="btn r3 disabled">缺货</a>';
                        } else {
                            t += '<a href="javascript:;" onclick="sdk.icc.getGiftCode(' + gift[i].type + ')" class="btn r3">';
                            if (gift[i].getCode) {
                                t += '查看';
                            } else {
                                t += '领取';
                            }
                            t += '</a>';
                        }
                        t += '</li>';
                    }
                    t += '</ol>';
                    if (sdk.ic.isPointGift) { //有积分礼包添加标题
                        t += '</dd>';
                        t += '</dl>';
                    }
                    $("#box-gift>div").append(t);
                    if (callback) {
                        callback();
                    }
                    if (!sdk.icc.giftScroll) {
                        sdk.icc.giftScroll = new IScroll('#box-gift', {
                            mouseWheel: true,
                            click: true
                        });
                    } else {
                        sdk.icc.giftScroll.refresh();
                    }
                }
            });
        },
        joinGroup: function (href) {
            window.open(href);
        },
        pageTurn: function (number) { //资讯翻页
            $("#info-list li").eq(number).trigger("click");
        },
        pageBack: function () { //资讯返回
            $("#tar-msg").trigger("click");
            $("#info-content").empty();
        },
        msgAddScroll: function (len) { //资讯列表添加滑动事件
            setTimeout(function () {
                if (!sdk.icc.msgScroll) {
                    sdk.icc.msgScroll = new IScroll('#box-msg', {
                        mouseWheel: true,
                        tap: true
                    });
                } else {
                    sdk.icc.msgScroll.refresh();
                }
                sdk.icc.msgScroll.on('scrollEnd', function () {
                    if (Math.abs(this.maxScrollY) == Math.abs(this.y)) {
                        if (len >= 15) {
                            sdk.icc.loadMsgList();
                        }
                    }
                });

                if (sdk.isMobile()) {
                    $("#info-list>li").off().on("tap", function () {
                        $(this).trigger("click");
                    });
                }
            }, 100);
        },
        loadMsgContent: function (id, e) { //加载资讯详情
            if ($(e).attr("class").indexOf("unread") >= 0) {
                $(e).removeClass("unread");
            }
            sdk.ic.httpGet(sdk.icc.msgUrl + 'readMsg&id=' + id, function (data) {
                sdk.icc.msgId = id;
                sdk.icc.msgCurrent = $(e).index() + 1;
                var T = new Date(data.time * 1000);
                var t = '';
                t += '<div class="flex-list">';
                t += '\t<h1 class="title">' + data.title + '</h1>';
                t += '\t<span class="time">' + T.format('yyyy-MM-dd hh:mm:ss') + '</span>';
                t += '\t<div class="txt" id="loadTxt"></div>';
                t += '\t<p class="flex flex-x-end comment">';
                //t += '\t\t<span><i class="icon-ready"></i>' + (data.read < 100000 ? data.read : 100000 + '+') + '</span>';
                //t += '\t\t<span class="' + (data.isThumb ? 'active' : '') + '" data-thumb="' + data.isThumb + '" onclick="sdk.icc.thumb(this)"><i class="icon-good"></i><em>' + (data.thumb < 100000 ? data.thumb : 100000 + '+') + '</em></span>';
                t += '\t</p>';
                t += '</div>';
                t += '<div class="flex flex-x-center">';
                t += '\t<span class="r3" onclick="sdk.icc.pageBack()">返回</span>';
                if (sdk.icc.msgCurrent < sdk.icc.msgItem) {
                    t += '<span class="r3" onclick="sdk.icc.pageTurn(' + sdk.icc.msgCurrent + ')">上一封</span>';
                } else {
                    t += '<span class="r3 active">上一封</span>';
                }
                t += '\t<span class="page">' + sdk.icc.msgCurrent + ' / ' + sdk.icc.msgItem + '</span>';
                if (sdk.icc.msgCurrent == 1) {
                    t += '<span class="r3 active">下一封</span>';
                } else {
                    t += '<span class="r3" onclick="sdk.icc.pageTurn(' + (sdk.icc.msgCurrent - 2) + ')">下一封</span>';
                }

                t += '</div>';
                $("#info-content").empty().append(t);
                $("#box-msg-content").removeClass("hidden").siblings().addClass("hidden");
                //$("#loadTxt").load(data.contentUrl, function () {
                //})
                $("#loadTxt").html(data.content);
            });
        },
        loadMsgList: function () { //资讯列表
            if (sdk.icc.msgSize >= 15) {
                sdk.ic.httpGet(sdk.icc.msgUrl + 'getMsgList&pageNo=' + sdk.icc.msgPage + '&pageSize=15', function (data) {
                    var data = data.msgList;
                    var t = '';
                    if (data.length) {
                        for (var i in data) {
                            var T = new Date(data[i].time * 1000);
                            t += '<li class="flex ' + (!data[i].isRead ? 'unread' : '') + '" data-id="' + data[i].id + '" onclick="sdk.icc.loadMsgContent(' + data[i].id + ',this)">';
                            t += '<i class="icon-read"></i>';
                            t += '<div class="flex-list"><a href="javascript:;">';
                            if (data[i].title.indexOf('】') >= 0) {
                                data[i].title = data[i].title.split('】')[1];
                            }
                            if (data[i].title.indexOf('》') >= 0) {
                                data[i].title = data[i].title.split('》')[1];
                            }
                            t += data[i].title;
                            t += '</a></div>';
                            t += '<span>' + T.format('yyyy-MM-dd') + '</span>';
                            t += '</li>';
                        }
                        $("#info-list").append(t);
                        sdk.icc.msgPage++;
                        sdk.icc.msgSize = data.length;
                        sdk.icc.msgItem = sdk.icc.msgItem + data.length;
                        sdk.icc.msgAddScroll(sdk.icc.msgSize);
                    }
                })
            }
        },
        vipGiftError: function (number) { //vip礼包错误码
            switch (parseInt(number)) {
                case 1:
                    return "出了点小状况！";
                case 2:
                    return "缺少参数！";
                case 3:
                    return "登录超时，请重新登录！";
                case 4:
                    return "参数无效！";
                case 5:
                    return "礼包不存在！";
                case 6:
                    return "您的领取次数不足！";
                case 7:
                    return "权限不足！";
                case 8:
                    return "您的VIP等级不足！";
                case 9:
                    return "礼包码不足！";
                case 10:
                    return "已经领取过礼包！";
                case 11:
                    return "未满足领奖条件！";
                default:
                    return "未知错误！";
            }
        },
        crazyChange: function (id, list) { //疯狂打怪兽物品排序
            var t = [], t1 = null, t2 = null, t3 = null, t4 = null, t5 = null;
            switch (parseInt(id)) {
                case 71:
                    t1 = "水晶*2000";
                    t2 = "碎片*500";
                    t3 = "月光宝盒*2";
                    t4 = "武器*25";
                    t5 = "钻石*2000";
                    break;
                case 72:
                    t1 = "水晶*3500";
                    t2 = "碎片*1000";
                    t3 = "月光宝盒*3";
                    t4 = "武器*33";
                    t5 = "钻石*2500";
                    break;
                case 73:
                    t1 = "水晶*6000";
                    t2 = "碎片*2000";
                    t3 = "精华升阶石*200";
                    t4 = "武器*1套";
                    t5 = "钻石*6000";
                    break;
                case 74:
                    t1 = "水晶*10000";
                    t2 = "碎片*2500";
                    t3 = "精华升阶石*500";
                    t4 = "武器*3套";
                    t5 = "钻石*10000";
                    break;
                case 75:
                    t1 = "水晶*10000";
                    t2 = "碎片*2500";
                    t3 = "精华升阶石*500";
                    t4 = "武器*3套";
                    t5 = "钻石*10000";
                    break;
                case 76:
                    t1 = "水晶*10000";
                    t2 = "碎片*2500";
                    t3 = "精华升阶石*500";
                    t4 = "武器*3套";
                    t5 = "钻石*10000";
                    break;
                case 77:
                    t1 = "水晶*10000";
                    t2 = "碎片*2500";
                    t3 = "精华升阶石*500";
                    t4 = "武器*3套";
                    t5 = "钻石*10000";
                    break;
                case 78:
                    t1 = "金龙头饰*1";
                    break;
                case 79:
                    t1 = "金龙之吼*1";
                    break;
            }
            if (typeof (list) == 'string') {
                list = list.split(',');
            }
            for (var i in list) {
                list[i].indexOf("1") >= 0 ? t.push(t1) : '';
                list[i].indexOf("2") >= 0 ? t.push(t2) : '';
                list[i].indexOf("3") >= 0 ? t.push(t3) : '';
                list[i].indexOf("4") >= 0 ? t.push(t4) : '';
                list[i].indexOf("5") >= 0 ? t.push(t5) : '';
            }
            if (list.length > 3) {
                t.push(list[3]);
            }
            return t;
        },
        viewPrizeLog: function () { //查看领取记录
            sdk.ic.httpGet(sdk.icc.vipGiftUrl + 'cmd=log', function (data) {
                var data = data.logList;
                if (data.length) {
                    var t = '<table class="prize-log"><thead><tr><th>领取时间</th><th>礼包详情</th><th>发放状态</th></tr></thead><tbody>';
                    for (var i in data) {
                        var T = new Date(data[i].fetchTime * 1000);
                        data[i].status = data[i].status == 1 ? '<td class="end">已发放</td>' : '<td class="move">发放中</td>';
                        t += '<tr><td>' + T.format('yyyy-MM-dd hh:mm:ss') + '</td><td>';
                        if (sdk.gameId == 95) {
                            t += sdk.icc.crazyChange(data[i].prizeId, data[i].choose);
                        } else {
                            t += data[i].desc;
                        }
                        t += '</td>' + data[i].status + '</tr>';
                    }
                    t += '</tbody></table>';
                    sdk.confirmDialog(t, null, null, null, null, '领取信息');
                } else {
                    sdk.confirmDialog('您还没有领取记录', null, '知道了');
                }
            });
        },
        getVipGift: function (id, gid, txt, e) { //vip礼包领取 礼包ID，位置gid
            var leftChance = parseInt($("#b-" + gid).html()); //获取领取次数
            if (!leftChance) {
                sdk.confirmDialog('您的领取次数不足！', null, '知道了');
            } else {
                var role = sdk.getItem("role_" + sdk.gameId);
                var zone = sdk.getItem("zone_" + sdk.gameId);
                var phone = sdk.getItem("phone_" + sdk.gameId);
                var choose = null;
                var t = '';
                if (sdk.gameId == 95) { //疯狂打怪兽
                    choose = [];
                    if (id > 70 && id < 78) {
                        var _len = $(e).prev().find("li.active");
                        if (!_len.length || _len.length < 3) {
                            sdk.confirmDialog('请先选择要领取的物品！');
                            return;
                        } else {
                            for (var i = 0; i < _len.length; i++) {
                                choose.push(_len.eq([i]).attr("data-id"));
                            }
                        }
                    } else if (id > 77 && id < 80) {
                        choose.push("1");
                    }
                    t += '<div class="gift-info-fill flex"><label>角色 ID<span>*</span>：</label><input class="flex-list" id="role" value="' + (role ? role : '') + '" placeholder="请填写游戏角色ID" type="text" ></div>';
                    if (id == 72) {
                        t += '<div class="gift-info-fill flex"><label>蓝绿装备<span>*</span>：</label><input class="flex-list" id="zone" value="' + (zone ? zone : '') + '" placeholder="请填写任意蓝绿装备名称" type="text" ></div>';
                    } else if (id >= 73 && id <= 77) {
                        t += '<div class="gift-info-fill flex"><label>紫色装备<span>*</span>：</label><input class="flex-list" id="zone" value="' + (zone ? zone : '') + '" placeholder="请填写任意紫色装备名称" type="text" ></div>';
                    }
                } else {
                    if (sdk.gameId == 226) {
                        t += '<div class="gift-info-fill flex"><label>角色编号<span>*</span>：</label><input class="flex-list" id="role" value="' + (role ? role : '') + '" placeholder="游戏内点头像可查看" type="number" ></div>';
                    } else {
                        t += '<div class="gift-info-fill flex"><label>角色昵称<span>*</span>：</label><input class="flex-list" id="role" value="' + (role ? role : '') + '" placeholder="请填写游戏角色昵称" type="text" ></div>';
                    }
                    t += '<div class="gift-info-fill flex"><label>区服信息<span>*</span>：</label><input class="flex-list" id="zone" value="' + (zone ? zone : '') + '" placeholder="请填写角色所在区服" type="text" ></div>';
                }
                t += '<div class="gift-info-fill flex"><label>联系方式：</label><input class="flex-list" id="phone" value="' + (phone ? phone : '') + '" placeholder="手机号码／QQ号" type="number" ></div>';
                t += '<div class="gift-info-warning">为保证礼包正常领取，请填写真实有效的信息！</div>';
                sdk.confirmDialog(t, '确认信息', '关闭', function () {
                    role = $.trim($("#role").val());
                    zone = $.trim($("#zone").val());
                    phone = $.trim($("#phone").val());
                    if (sdk.gameId == 95 && !(id > 71 && id < 78)) {
                        zone = '空';
                    }
                    if (sdk.gameId == 95) {
                        txt = sdk.icc.crazyChange(id, choose);
                        if (id > 71 && id < 78) {
                            txt += ',' + zone;
                        }
                    }
                    if (role && zone) {
                        sdk.confirmDialog('您确认要领取吗？<br>' + txt, '立即领取', '我再想想', function () {
                            sdk.showLoading();
                            sdk.ic.httpGet(sdk.icc.vipGiftUrl + 'cmd=prize&id=' + id + '&role=' + role + '&zone=' + zone + '&phone=' + phone + (choose ? '&choose=' + choose : ''), function (data) {
                                sdk.hideLoading();
                                if (data.error) {
                                    sdk.confirmDialog(sdk.icc.vipGiftError(data.error));
                                } else {
                                    sdk.setItem("role_" + sdk.gameId, role);
                                    sdk.setItem("zone_" + sdk.gameId, zone);
                                    if (phone) {
                                        sdk.setItem("phone_" + sdk.gameId, phone);
                                    }
                                    leftChance--;
                                    $("#b-" + gid).html(leftChance);
                                    sdk.confirmDialog('领取成功<br>信息核实后1-2个工作日会发送到您的邮箱，请注意查收！');
                                }
                            });
                        })
                    } else {
                        sdk.confirmDialog('必填信息不能为空', '继续填写', null, function () {
                            sdk.icc.getVipGift(id, gid, txt);
                        }, null);
                    }
                }, null, '填写信息');
            }
        },
        koudaiCurrentChange: function (number) { //口袋妖怪大师版礼包序号重排序
            switch (parseInt(number)) {
                case 0:
                    return 1;
                case 1:
                    return 17;
                case 2:
                    return 0;
                case 3:
                    return 2;
                case 4:
                    return 3;
                case 5:
                    return 18;
                case 6:
                    return 4;
                case 7:
                    return 5;
                case 8:
                    return 14;
                case 9:
                    return 6;
                case 10:
                    return 7;
                case 11:
                    return 19;
                case 12:
                    return 8;
                case 13:
                    return 9;
                case 14:
                    return 15;
                case 15:
                    return 10;
                case 16:
                    return 20;
                case 17:
                    return 11;
                case 18:
                    return 12;
                case 19:
                    return 13;
                case 20:
                    return 16;
            }
        },
        checkPrize: function (e) { //疯狂打怪兽礼包内容选择
            if ($(e).attr("class").indexOf("active") >= 0) {
                $(e).removeClass("active");
            } else {
                if ($(e).parent().find("li.active").length == 3) { //已选择三个
                    sdk.confirmDialog('最多只能选择3个物品！')
                } else {
                    $(e).addClass("active");
                }
            }
        },
        loadVipGift: function () { //加载VIP礼包
            //level 每日充值满500元|每日充值满1000元|每日充值满2000元|每日充值满3000元|每日充值满5000元|每日充值满8000元|每日充值满10000元
            /*
            gift:
             90,礼包一,羽毛骑术礼盒*5*1,培养石*100*2,超·进化石*50*3,守护兽升级石*150*4,守护兽进阶石*50*5&96,礼包二,羽毛骑术礼盒*5*1,培养石*100*2,超·进化石*50*3,守护兽升级石*150*4,守护兽进阶石*50*5
             91,礼包一,羽毛骑术礼盒*10*1,培养石*200*2,超·进化石*100*3,守护兽升级石*300*4,守护兽进阶石*100*5
             92,礼包一,羽毛骑术礼盒*15*1,培养石*400*2,超·进化石*200*3,守护兽升级石*600*4,守护兽进阶石*200*5
             93,礼包一,羽毛骑术礼盒*20*1,培养石*800*2,超·进化石*300*3,守护兽升级石*900*4,守护兽进阶石*300*5
             94,礼包一,羽毛骑术礼盒*25*1,培养石*1600*2,超·进化石*500*3,守护兽升级石*1500*4,守护兽进阶石*500*5
             95,礼包一,羽毛骑术礼盒*30*1,培养石*3200*2,超·进化石*800*3,守护兽升级石*2400*4,守护兽进阶石*800*5
             */
            function _fillGiftList(data, level, gift, imgUrl, imgFormat) {
                var count = 0;      //计数器 取礼包描述
                var t = '<dt class="flex"><span>充值活动</span>';
                if (data) {
                    t += '<a href="javascript:;" onclick="sdk.icc.viewPrizeLog()">领取记录</a><em>今日已充值' + data.rmb + '元</em>';
                }
                t += '</dt>';
                t += '\t<dd>';
                t += '\t\t<dl class="wrapper-list">';
                for (var i in level) {
                    t += '<dt class="flex">';
                    t += '\t<span>' + level[i] + '</span>';
                    if (data) {
                        t += '\t<em>可领取<i id="b-' + i + '">' + data.groupList[Object.keys(data.groupList)[i]].leftChance + '</i>次</em>';
                    }
                    t += '</dt>';
                    t += '<dd>';
                    t += '\t<ol class="gift-item">';
                    //giftItem:90,礼包一,羽毛骑术礼盒*5*1,培养石*100*2,超·进化石*50*3,守护兽升级石*150*4,守护兽进阶石*50*5
                    //羽毛骑术礼盒*5*1  5是物品数量，1是图片地址 如：http://cdn.11h5.com/static/image/vipGift/yaoguaibaokemeng/icon_1.png
                    var giftItem = gift[i];
                    if (giftItem.indexOf('&') >= 0) {
                        giftItem = giftItem.split('&'); //同档有多个礼包拆分成数组
                    } else {
                        giftItem = [giftItem]; //单礼包形成数组
                    }
                    for (var j in giftItem) {
                        var items = giftItem[j].split(',');
                        var id = items[0];    //获取ID            90
                        var title = items[1]; //获取礼包名称      礼包一
                        items.splice(0, 1);  //移除ID
                        items.splice(0, 1);  //移除礼包名称
                        t += '<li class="flex">';
                        if (data) {
                            t += '\t<label>' + title + '</label>';
                        }
                        t += '\t<div class="flex-list wrapper">';
                        t += '\t\t<ol class="flex" style="width:' + items.length * 4 + 'rem;">';
                        for (var k in items) {
                            var _items = items[k].split('*');
                            if (sdk.gameId == 95) { //疯狂打怪兽添加事件
                                t += '<li onclick="sdk.icc.checkPrize(this)" data-id="' + (parseInt(k) + 1) + '" class="flex-list unselect">';
                            } else {
                                t += '<li class="flex-list unselect">';
                            }
                            t += '<div><img class="r50" src="' + imgUrl + _items[2] + imgFormat + '">X' + _items[1] + '</div><span class="' + (id > 70 && id < 78 ? 'checkbox' : '') + '">' + _items[0] + '</span>';
                            t += '</li>';
                        }
                        t += '\t\t</ol>';
                        t += '\t</div>';
                        if (data) {
                            var des = data.prizeList[Object.keys(data.prizeList)[count]].des;
                            if (sdk.gameId == 97) { //口袋妖怪大师版 count重新排序
                                des = data.prizeList[Object.keys(data.prizeList)[sdk.icc.koudaiCurrentChange(count)]].des;
                            }
                            t += '<a href="javascript:;" class="btn r3" onclick="sdk.icc.getVipGift(' + id + ',' + i + ',\'' + des + '\',this)">领取</a>';
                        }
                        t += '</li>';
                        count++;
                    }
                    t += '\t</ol>';
                    t += '</dd>';
                }
                t += '\t\t</dl>';
                t += '\t</dd>';

                $("#vipGiftItems").append(t);

                for (var i = 0; i < $(".gift-item>li").length; i++) {
                    var scroll = new IScroll($(".gift-item>li").eq(i).find(".wrapper")[0], {
                        scrollX: true,
                        scrollY: false,
                        mouseWheel: true,
                        preventDefault: false
                    });
                }

                if (!sdk.icc.vipScroll) {
                    sdk.icc.vipScroll = new IScroll('#box-vip', {
                        mouseWheel: true,
                        click: true
                    });
                } else {
                    sdk.icc.vipScroll.refresh();
                }
            }

            sdk.ic.httpGet(sdk.icc.baseUrl + 'cmd=getGameVipGift', function (data) {
                /*
                 每日充值满500元|每日充值满1000元|每日充值满2000元|每日充值满3000元|每日充值满5000元|每日充值满8000元|每日充值满10000元
                 90,礼包一,羽毛骑术礼盒*5*1,培养石*100*2,超·进化石*50*3,守护兽升级石*150*4,守护兽进阶石*50*5
                 91,礼包一,羽毛骑术礼盒*10*1,培养石*200*2,超·进化石*100*3,守护兽升级石*300*4,守护兽进阶石*100*5
                 92,礼包一,羽毛骑术礼盒*15*1,培养石*400*2,超·进化石*200*3,守护兽升级石*600*4,守护兽进阶石*200*5
                 93,礼包一,羽毛骑术礼盒*20*1,培养石*800*2,超·进化石*300*3,守护兽升级石*900*4,守护兽进阶石*300*5
                 94,礼包一,羽毛骑术礼盒*25*1,培养石*1600*2,超·进化石*500*3,守护兽升级石*1500*4,守护兽进阶石*500*5
                 95,礼包一,羽毛骑术礼盒*30*1,培养石*3200*2,超·进化石*800*3,守护兽升级石*2400*4,守护兽进阶石*800*5
                 */
                var gift = data.gift_content;
                if (gift) {
                    //data.title: 170妖怪宝可萌|yaoguaibaokemeng|png|0
                    var imgInfo = data.title.split('|');
                    //imgInfo[1] yaoguaibaokemeng
                    var imgUrl = sdk.cdnHost + '/static/image/Resources/vipGift/' + imgInfo[1] + '/icon_';
                    //imgInfo[2] png
                    var imgFormat = '.' + imgInfo[2];
                    gift = gift.split('\r\n');
                    //level 每日充值满500元|每日充值满1000元|每日充值满2000元|每日充值满3000元|每日充值满5000元|每日充值满8000元|每日充值满10000元
                    var level = gift[0];        //获取充值档次
                    level = level.split('|');   //拆分成数组
                    gift.splice(level, 1);      //充值档次移除

                    if (!parseInt(imgInfo[3])) {
                        _fillGiftList(null, level, gift, imgUrl, imgFormat);
                    } else {
                        sdk.ic.httpGet(sdk.icc.vipGiftUrl + 'cmd=info', function (params) {
                            _fillGiftList(params, level, gift, imgUrl, imgFormat);
                        });
                    }
                }
            });
        },
        rebuildGameURL: function (url) { //游戏跳转
            function _buildURL() {
                if (sdk.icc.chid) {
                    url = sdk.buildURL(url, {"chid": sdk.icc.chid});
                    if (sdk.icc.isTrial) { //建立试玩链接
                        url = sdk.buildURL(url, {"trial": 1});
                    }
                }
                if (sdk.icc.subchid) {
                    url = sdk.buildURL(url, {"subchid": sdk.icc.subchid});
                }
                if (sdk.getURLVar("tokenkey")) {
                    url = sdk.buildURL(url, {"tokenkey": sdk.getURLVar("tokenkey")});
                }
                if (sdk.isJetAPP()) {
                    if (sdk.isiOS()) {
                        sdk.initWebViewJavascriptBridge(function () {
                            if (window.WebViewJavascriptBridge) {
                                window.WebViewJavascriptBridge.callHandler("setRefresh", true);
                                window.WebViewJavascriptBridge.callHandler("setBack", false);
                                window.WebViewJavascriptBridge.callHandler("setGoTo", true);
                                window.WebViewJavascriptBridge.callHandler("setShare", {
                                    wxsession: true,
                                    wxtimeline: false,
                                    qq: false,
                                    qzone: false
                                });
                                window.WebViewJavascriptBridge.callHandler("setGoToURL", location.href);
                            }
                        });
                    } else if (sdk.isAndroid()) {
                        window.android.setRefresh(true);
                        window.android.setBack(false);
                        window.android.setGoTo(true);
                        window.android.setShare('{"wxsession": true, "wxtimeline": false, "qq": true, "qzone": true}');
                        window.android.setGoToURL(location.href);
                    }
                }
                location.href = url;
            }

            if (sdk.token) {
                sdk.createCode(sdk.token, function (data) {
                    if (data.code) {
                        url = sdk.buildURL(url, {"code": data.code});
                    }
                    _buildURL();
                });
            } else {
                _buildURL();
            }
        },
        addGameLabels: function (data) { //添加游戏标签
            var t = '';
            if (data.indexOf(5) >= 0) {
                t += ' <span class="tags-new">独家</span>';
            }
            if (data.indexOf(4) >= 0) {
                t += ' <span class="tags-coupon">礼包</span>';
            }
            return t;
        },
        loadGameList: function () { //加载更多游戏
            sdk.ic.httpGet(sdk.icc.baseUrl + 'cmd=getSomRecoAndNewGames', function (data) {
                var newGame = data.new;
                var recGame = data.recos;

                for (var i in recGame) {
                    var t = '<li class="flex"><img class="r5" src="' + recGame[i].icon + '"><div class="flex flex-list flex-v"><p class="title"><span>' + recGame[i].title + '</span>' + sdk.icc.addGameLabels(recGame[i].labels) + '</p><p>' + recGame[i].brief_intro + '</p></div><a href="javascript:;" onclick="sdk.icc.rebuildGameURL(\'' + recGame[i].game_url + '\')" class="btn r3">开始</a></li>';
                    $("#hotGames").append(t);
                }

                for (var i in newGame) {
                    var t = '<li class="flex"><img class="r5" src="' + newGame[i].icon + '"><div class="flex flex-list flex-v"><p class="title"><span>' + newGame[i].title + '</span>' + sdk.icc.addGameLabels(newGame[i].labels) + '</p><p>' + newGame[i].brief_intro + '</p></div><a href="javascript:;" onclick="sdk.icc.rebuildGameURL(\'' + newGame[i].game_url + '\')" class="btn r3">开始</a></li>';
                    $("#newGames").append(t);
                }

                setTimeout(function () {
                    var scroll = new IScroll('#box-game', {
                        mouseWheel: true,
                        click: true
                    });
                }, 100);
            });
        },
        loadUserInfo: function () { //加载个人信息
            sdk.ic.httpGet(sdk.icc.baseUrl + 'cmd=getUserInfo', function (data) {
                //console.log(data);
                if (data.uid) {
                    sdk.icc.config = data;
                    sdk.uid = data.uid;
                    // if (data.nickname) {
                    //     try {
                    //         data.nickname = decodeURIComponent(data.nickname);
                    //     } catch (e) {
                    //         data.nickname = '多纷玩家';
                    //     }
                    // } else {
                    //     data.nickname = '多纷玩家';
                    // }

                    if (!data.headimgurl) {
                        data.headimgurl = sdk.cdnHost + '/static/image/jeticon.png';
                    } else {
                        $(".shortcut-head .vip-info em").addClass("active").empty();
                    }

                    $(".shortcut-head .headimg").attr("src", sdk.loadHeadImg(data.headimgurl, 96));
                    $("#nickname").text(data.uid);
                    $("#mypoint").text(data.lv);
                    if (data.vip) {
                        $(".shortcut-head .vip-info").append('<div><i class="icon-vip-head"></i><em>' + data.vip + '</em></div>');
                    }
                }
            });
        },
        myIdVerify: function () {
            $.get(sdk.apiHost + '/ic/conf?token=' + sdk.token + '&cmd=isRealVerify', function (data) {
                if (!data.isVerify) {
                    $("#focusSrv").before('<a href="javascript:;" onclick="sdk.icc.showBind()" class="btn">实名认证</a>');
                }
            }, 'json');
        },
        showBind: function () {
            $("#box-bind").removeClass("hidden").siblings().addClass('hidden');
            $(".shortcut-nav li").removeClass("active");
        },
        bindMyId: function () {
            var myName = $("#myName").val();
            var myId = $("#myId").val();

            if (!myName) {
                sdk.confirmDialog("姓名不能为空");
                return;
            }
            if (!myId) {
                sdk.confirmDialog("身份证号码不能为空");
                return;
            }
            $.get(sdk.apiHost + '/ic/conf?token=' + sdk.token + '&cmd=addRealVerify' + '&realname=' + myName + '&idcard=' + myId.toUpperCase(), function (data) {
                if (data.error) {
                    switch (data.error) {
                        case 60004:
                            sdk.confirmDialog("该身份证号码已经实名注册过", null, "好的", null, null);
                            break;
                        case 60005:
                            sdk.confirmDialog("该身份证号码有误", null, "好的", null, null);
                            break
                    }
                } else {
                    $("#myName,#myId").attr('readonly', true).attr('disabled', true);
                    $("#myName").val(myName.substring(0, 1) + '**');
                    $("#myId").val(myId.substring(0, 3) + "***********" + myId.substring(14, 18));
                    $("#bindInfo").html('您已经完成了身份验证');
                    $("#bindBtn").remove();
                    sdk.confirmDialog("恭喜您认证成功", null, "好的");
                }
            }, 'json');
        },
        init: function () {
            //时间初始化
            Date.prototype.format = function (time) {
                var o = {
                    "M+": this.getMonth() + 1,
                    "d+": this.getDate(),
                    "h+": this.getHours(),
                    "m+": this.getMinutes(),
                    "s+": this.getSeconds()
                };
                if (/(y+)/.test(time)) time = time.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
                for (var k in o)
                    if (new RegExp("(" + k + ")").test(time)) time = time.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
                return time;
            };

            //窗体主结构
            var t = '';
            t += '<div class="mask shortcut-box">';
            t += '\t<div>';

            //侧边栏头部
            t += '<div class="flex flex-x-between shortcut-head">';
            t += '\t<div class="flex flex-list">';
            t += '\t\t<div class="vip-info"><img class="r50 headimg" src="" ><em class="r50" onclick="sdk.logout()"><i class="icon-fresh"></i></em></div>';
            t += '\t\t<div class="flex flex-v info">';
            t += '\t\t\t<span id="nickname"></span>';
            t += '\t\t\t<span><i class="icon-point"></i><em id="mypoint"></em></span>';
            t += '\t\t</div>';
            t += '\t</div>';
            t += '<a class="btn" id="focusSrv" href="javascript:;">';
            if (sdk.desktopIcon && !navigator.standalone) {
                t += '收藏/';
            }
            if (sdk.channelInfo && sdk.channelInfo.replaceQrcodeUrl) {
                t += '关注';
            } else {
                t += '客服';
            }
            t += '</a>';
            if ( !sdk.isWeixin() && ((sdk.channelInfo && !parseInt(sdk.channelInfo.hideStruct)) || !sdk.isDesktopApp()) ) {
                t += '<a class="btn" onclick="sdk.logout()" href="javascript:;">切换账号</a>';
            }
            t += '</div>';

            //侧边栏导航菜单
            t += '<ol class="flex shortcut-nav">';
            //t += '<li data-target="box-chat" id="tar-chat" class="flex flex-v flex-list"><i class="icon-chat"></i><span>聊天</span></li>';
            if (sdk.ic.isGift || sdk.ic.isPointGift) {
                t += '<li data-target="box-gift" id="tar-gift" class="flex flex-v flex-list"><i class="icon-gift"></i><span>礼包</span></li>';
            }
            if (sdk.ic.isMsg) {
                t += '<li data-target="box-msg" id="tar-msg" class="flex flex-v flex-list"><i class="icon-info"></i><span>资讯</span></li>';
            }
            t += '<li data-target="box-vip" id="tar-vip" class="flex flex-v flex-list"><i class="icon-vip-gift"></i><span>VIP特权</span></li>';
            t += '<li data-target="box-game" id="tar-game" class="flex flex-v flex-list"><i class="icon-game"></i><span>更多游戏</span></li>';
            t += '</ol>';

            //侧边栏主体容器
            t += '\t\t<ol class="shortcut-main">';

            //聊天界面
            t += '<li id="box-chat">';
            t += '\t<div>';
            t += '\t\t<div class="marquee">';
            t += '<p>官方提醒：不要轻易相信聊天频道的交易信息，谨防被骗，欢迎举报虚假信息！</p>';
            t += '\t\t</div>';
            // t += '\t\t<marquee class="marquee" behavior="scroll">官方提醒：不要轻易相信聊天频道的交易信息，谨防被骗，欢迎举报虚假信息！</marquee>';
            t += '\t\t<div class="chat-box">';
            t += '\t\t\t<ol id="chatList"></ol>';
            t += '\t\t</div>';
            t += '\t\t<div class="flex chat-input">';
            t += '\t\t\t<input class="flex-list r5" id="chat-text" type="text" placeholder="聊天内容不能超过40个字" >';
            t += '\t\t\t<a href="javascript:;" id="chat-send" class="btn">发送</a>';
            t += '\t\t</div>';
            t += '\t</div>';
            t += '</li>';

            //礼包界面
            if (sdk.ic.isGift || sdk.ic.isPointGift) {
                t += '<li id="box-gift">';
                t += '\t<div></div>';
                t += '</li>';
            }

            //资讯界面
            if (sdk.ic.isMsg) {
                t += '<li id="box-msg">';
                t += '\t<div>';
                t += '\t\t<ol class="info-box" id="info-list"></ol>';
                t += '\t</div>';
                t += '</li>';

                //资讯详细
                t += '<li id="box-msg-content">';
                t += '\t\t<div class="info-content flex flex-v" id="info-content"></div>';
                t += '</li>';
            }

            //VIP特权界面
            t += '<li id="box-vip">';
            t += '\t<div>';
            t += '\t\t<dl class="rules">';
            t += '\t\t\t<dt class="flex"><span>会员条件</span></dt>';
            t += '\t\t\t<dd>';
            t += '\t\t\t\t<ol>';
            t += '\t\t\t\t\t<li><span>条件1：</span>平台VIP等级达到V8以上。</li>';
            t += '\t\t\t\t\t<li><span>条件2：</span>单个游戏单日充值满1000RMB。</li>';
            t += '\t\t\t\t</ol>';
            t += '\t\t\t\t<p>满足以上任何一项即可成为SVIP超级会员，您的多纷天使会主动联系您。</p>';
            t += '\t\t\t</dd>';
            t += '\t\t</dl>';
            t += '\t\t<dl class="rules svip">';
            t += '\t\t\t<dt class="flex"><span>SVIP特权</span></dt>';
            t += '\t\t\t<dd>';
            t += '\t\t\t\t<ol>';
            t += '\t\t\t\t\t<li class="flex"><i class="icon-vip-head"></i><span><em>专属通道：</em>多纷天使一对一贴心服务，优先解答您的问题及需求；</span></li>';
            t += '\t\t\t\t\t<li class="flex"><i class="icon-vip-head"></i><span><em>优先权：</em>游戏第一手资料优先知晓；</span></li>';
            t += '\t\t\t\t\t<li class="flex"><i class="icon-vip-head"></i><span><em>专属福利：</em>节日专属礼包。</span></li>';
            t += '\t\t\t\t</ol>';
            t += '\t\t\t</dd>';
            t += '\t\t</dl>';
            t += '\t\t<dl class="rules svip-kf">';
            t += '\t\t\t<dt class="flex"><span>多纷天使</span></dt>';
            t += '\t\t\t<dd>';
            t += '\t\t\t\t<ol class="flex">';
            t += '\t\t\t\t\t<li class="r50 kf_1"></li>';
            t += '\t\t\t\t\t<li class="r50 kf_2"></li>';
            t += '\t\t\t\t\t<li class="r50 kf_3"></li>';
            t += '\t\t\t\t\t<li class="r50 kf_4"></li>';
            t += '\t\t\t\t\t<li class="r50 kf_5"></li>';
            t += '\t\t\t\t</ol>';
            t += '\t\t\t\t<p>天使工作时间：<span>周一至周五9:30-18:30，法定节假日正常休息。</span></p>';
            t += '\t\t\t\t<p>在非工作时间内，您也可以向天使提交问题，天使看到后会第一时间回复您。</p>';
            t += '\t\t\t</dd>';
            t += '\t\t</dl>';
            t += '\t\t<dl class="items" id="vipGiftItems"></dl>';
            t += '\t</div>';
            t += '</li>';

            //更多游戏界面
            t += '<li id="box-game">';
            t += '\t<div class="gamelist">';
            t += '\t\t<dl>';
            t += '\t\t\t<dt>必玩爆款</dt>';
            t += '\t\t\t<dd>';
            t += '\t\t\t\t<ol id="hotGames"></ol>';
            t += '\t\t\t</dd>';
            t += '\t\t\t<dt class="active">新游尝鲜</dt>';
            t += '\t\t\t<dd>';
            t += '\t\t\t\t<ol id="newGames"></ol>';
            t += '\t\t\t</dd>';
            t += '\t\t</dl>';
            t += '\t</div>';
            t += '</li>';

            //收藏客服界面
            t += '<li id="box-srv">';
            t += '\t<div class="service flex flex-v">';
            t += '<ol>';
            if (sdk.channelInfo && sdk.channelInfo.replaceQrcodeUrl) {
                t += '\t\t<li><span>1</span><em>长按识别下方二维码</em></li>';
                t += '\t\t<li><span>2</span><em>关注公众号</em></li>';
                t += '\t\t<li><span>3</span><em>更多精彩游戏等你来体验</em></li>';
            } else {
                t += '\t\t<li><span>1</span><em>关注【多纷Plus】公众号</em></li>';
                t += '\t\t<li><span>2</span><em>直接回复游戏问题</em></li>';
                t += '\t\t<li><span>3</span><em>客服会及时帮您解决</em></li>';
            }
            t += '\t\t</ol>';
            t += '\t\t<fieldset><legend>长按识别二维码关注</legend></fieldset>';
            if (sdk.channelInfo && sdk.channelInfo.replaceQrcodeUrl) {
                t += '<img src="' + sdk.channelInfo.replaceQrcodeUrl + '" >';
            } else {
                t += '<img src="'+ sdk.cdnHost + '/static/image/qrcode_for_jet.png" >';
            }
            if (sdk.desktopIcon && !sdk.getURLVar("tokenkey") && !sdk.isJetAPP() && sdk.isiOS() && sdk.isMobile()) {
                t += '<a onclick="sdk.saveGameDesktop()" class="btn">保存游戏至主屏幕</a>';
            }
            t += '\t</div>';
            t += '</li>';

            //实名认证
            t += '<li id="box-bind" class="bind">';
            t += '<p id="bindInfo">根据最新监管要求，进行游戏需要身份验证</p>';
            t += '<div class="flex">';
            t += '<i class="icon-name"></i>';
            t += '<input class="flex-list" type="text" id="myName" placeholder="请输入您的真实姓名">';
            t += '</div>';
            t += '<div class="flex">';
            t += '<i class="icon-id"></i>';
            t += '<input class="flex-list" type="text" id="myId" placeholder="请输入您的身份证号">';
            t += '</div>';
            t += '<a href="javascript:;" id="bindBtn" onclick="sdk.icc.bindMyId()" class="btn">提交认证</a>';
            t += '</li>';

            t += '\t\t</ol>';

            //收起控件
            // t += '\t\t<i class="icon-left" id="packUp"></i>';
            t += '\t</div>';
            t += '<a href="javascript:;" class="flex flex-v control close-slide animate"><i class="icon-cancel close-slide"></i><span class="close-slide">关闭</span></a>';
            t += '<a href="javascript:;" onclick="sdk.refresh()" class="flex flex-v control animate"><i class="icon-refresh"></i><span>刷新游戏</span></a>';
            t += '</div>';

            $("body").append(t);

            //加载个人信息
            sdk.icc.loadUserInfo();
            sdk.icc.myIdVerify();

            if (sdk.isiOS()) {
                $("#chat-text").on('focus', '', function () {
                    setTimeout(function () {
                        $("body").scrollTop(1000);
                    }, 200);
                });
            }

            //导航
            $(".shortcut-nav").off().on("click", "li", function () {
                var _this = $(this);
                var _tar = _this.attr("data-target");
                var _id = _this.attr("id");
                if (_this.attr("class").indexOf("tip") >= 0) {
                    _this.removeClass("tip");
                    if (_id == 'tar-gift') {
                        sdk.ic.httpGet(sdk.ic.delDotUrl, function () {
                        })
                    }
                }
                if (!$('#' + _tar).attr("data-ready")) {
                    switch (_id) {
                        case 'tar-chat': //聊天
                            sdk.icc.imConnect();
                            break;
                        case 'tar-gift': //礼包
                            if (sdk.ic.isPointGift) { //是否有积分礼包
                                if (sdk.ic.isGift) { //是否有普通礼包
                                    sdk.icc.loadGiftList(function () {
                                        sdk.icc.loadPointGiftList();
                                    })
                                } else {
                                    sdk.icc.loadPointGiftList();
                                }
                            } else {
                                sdk.icc.loadGiftList();
                            }
                            break;
                        case 'tar-msg': //资讯
                            sdk.icc.loadMsgList();
                            break;
                        case 'tar-vip': //VIP礼包
                            sdk.icc.loadVipGift();
                            if (!sdk.icc.vipScroll) {
                                sdk.icc.vipScroll = new IScroll('#box-vip', {
                                    mouseWheel: true,
                                    click: true
                                });
                            } else {
                                sdk.icc.vipScroll.refresh();
                            }
                            break;
                        case 'tar-game': //游戏
                            sdk.icc.loadGameList();
                            break;
                    }
                    $('#' + _tar).attr("data-ready", "1");
                }

                _this.addClass('active').siblings().removeClass('active');
                $('#' + _tar).removeClass("hidden").siblings().addClass('hidden');
            });

            //关注客服
            $("#focusSrv").off().click(function () {
                $("#box-srv").removeClass("hidden").siblings().addClass('hidden');
                $(".shortcut-nav li").removeClass("active");
            });

            $(".shortcut-main").height(sdk.ic.height - $(".shortcut-head").height() - $(".shortcut-nav").height());
            $(".chat-box").height($(".shortcut-main").height() - $(".chat-input").height());
            if (sdk.ic.isNewGift || sdk.ic.isNewPointGift) { //是否有新礼包
                $("#tar-gift").addClass("tip");
            }

            //focusSrv
            if (sdk.channelInfo) {
                if (parseInt(sdk.channelInfo.hideStruct)) {
                    $("#focusSrv").trigger("click");
                    $(".shortcut-box .shortcut-nav,.shortcut-head .info>span:last-child").remove();
                }
            }

            if (sdk.ic.isJoinChat) {
                $("#tar-chat").trigger("click");
            } else {
                if (sdk.ic.msgCount) { //检测是否有未读消息
                    $("#tar-msg").addClass("tip").trigger("click");
                } else if ((sdk.ic.isGift || sdk.ic.isPointGift) && sdk.gameId != 147) { //检测是否有礼包
                    $("#tar-gift").trigger("click");
                } else {
                    $("#tar-chat").trigger("click");
                }
            }

            $(".shortcut-box").click(function (e) {
                var target = $(e.target).attr("class");
                if (target && (target.indexOf("shortcut-box") >= 0 || target.indexOf("close-slide") >= 0)) {
                    $(".shortcut-box>a").removeClass("animate");
                    $(".shortcut-box").css({"left": "-100%"});
                }
            });
        }
    };
    sdk.icc.init();
})();