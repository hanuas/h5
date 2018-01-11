(function() {
    sdk.history = {
        chid: sdk.getURLVar("chid"),
        subchid: sdk.getURLVar("subchid"),
        trial: sdk.getURLVar("trial"),
        back: sdk.getURLVar("backGC"),
        qrcode: null,
        recGameList: [],
        recList: [],
        specialGameList: [147],
        specialImgList: [{157: "157.gif"}],
        code: null,
        buildURL: function (url) {
            if (sdk.history.chid) {
                url = sdk.buildURL(url, {chid: sdk.history.chid});
            }
            if (sdk.history.subchid) {
                url = sdk.buildURL(url, {subchid: sdk.history.subchid});
            }
            if (sdk.history.trial) {
                url = sdk.buildURL(url, {trial: sdk.history.trial});
            }
            if (sdk.history.code) {
                url = sdk.buildURL(url, {code: sdk.history.code});
            }
            return url;
        },
        fill: function (data, list) {
            var _ht = parseInt((sdk.ic.width * 0.8 / (360 / 160)).toFixed(0));
            if (_ht % 2) {
                _ht = _ht - 1;
            }
            if (_ht > 176) {
                _ht = 176;
            }
            function _fill() {
                var t = '<div class="history mask" id="history-wrap">';
                t += '<div>';
                if (data) {
                    t += '<div class="history-banner" style="height:' + (_ht / 20) + 'rem;"><a href="' + sdk.history.buildURL(data.url) + '"><img src="' + sdk.cdnHost + '/static/image/goback/' + data.random + '" ></a><i id="history-cancel" class="icon-cancel"></i></div>';
                } else {
                    t += '<div class="history-head">';
                    if (sdk.channelInfo) {
                        t += '热门游戏推荐';
                    } else {
                        t += '更多好游戏尽在多纷';
                    }
                    t += '<i id="history-cancel" class="icon-cancel"></i></div>';
                }
                if (list) {
                    t += '<ol class="flex">';
                    for (var i in list) {
                        t += '<li class="flex-list"><a class="flex flex-v" href="' + sdk.history.buildURL(list[i].game_url) + '"><img class="r5" src="' + list[i].icon + '"><span>' + list[i].title + '</span></a></li>';
                    }
                    t += '</ol>';
                }
                if (!sdk.isQQ()) {
                    function _focusFill() {
                        t += '<div class="history-focus"><fieldset>';
                        t += '<legend>关注公众号精彩无限</legend>';
                        t += '<img src=" ' + (sdk.history.qrcode ? sdk.history.qrcode :sdk.cdnHost +  "/static/image/qrcode_for_jet.png" ) + '"><p>';
                        if (sdk.isWeixin()) {
                            t += '长按识别二维码';
                        } else {
                            t += '微信扫扫二维码';
                        }
                        t += '</p>';
                        t += '</fieldset></div>';
                    }

                    if (sdk.channelInfo) { //CPS
                        if (parseInt(sdk.channelInfo.qrcode)) {
                            sdk.history.qrcode = sdk.channelInfo.replaceQrcodeUrl;
                            _focusFill();
                        }
                    } else {
                        if (!sdk.isFocus) {
                            _focusFill();
                        }
                    }
                }
                t += '<div class="history-btn"><a href="javascript:;" id="history-exit" class="btn">仍要离开</a></div>';
                t += '<div class="history-check"><input id="history-checkbox" type="checkbox"><label for="history-checkbox">今日不再提示</label></div>';
                t += '</div>';
                t += '</div>';
                $("body").append(t);

                $("#history-checkbox").off().click(function () {
                    if ($(this).prop("checked")) {
                        sdk.setItem("history_date", new Date().getDate());
                    } else {
                        sdk.setItem("history_date", -1);
                    }
                });

                $("#history-exit").off().click(function () {
                    if (sdk.isWeixin()) {
                        if (sdk.history.back) {
                            location.href = sdk.history.buildURL( sdk.gameHost );
                        } else {
                            wx.closeWindow();
                        }
                    } else if (sdk.isQQ()) {
                        mqq.closeWindow();
                    } else {
                        window.history.back();
                    }
                });

                $("#history-cancel").off().click(function () {
                    $("#history-wrap").remove();
                    sdk.history.recList = [];
                    window.history.pushState({
                        title: document.title,
                        url: location.href
                    }, document.title, location.href);
                });
            }

            if (!sdk.history.code) {
                sdk.createCode(sdk.token, function (data) {
                    sdk.history.code = data.code;
                    _fill();
                });
            } else {
                _fill();
            }
        },
        init: function () {
            if ("pushState" in window.history) {
                window.history.pushState({
                    title: document.title,
                    url: location.href
                }, document.title, location.href);
                setTimeout(function () {
                    window.addEventListener("popstate", function (e) {
                        if (!e.state) {
                            sdk.history.recGameList = [];
                            if (sdk.channelInfo && sdk.channelInfo.replaceQrcodeUrl) {
                                sdk.history.qrcode = sdk.channelInfo.replaceQrcodeUrl;
                            }
                            if (sdk.history.specialGameList.indexOf(parseInt(sdk.gameId)) >= 0) { //特殊情况
                                var object = sdk.history.specialImgList[sdk.history.specialGameList.indexOf(parseInt(sdk.gameId))];
                                var _key = [];
                                var _val = [];
                                $.each(object, function (key, val) {
                                    _key.push(key);
                                    _val.push(val);
                                });

                                //取随机数
                                var random = parseInt(Math.random() * _key.length);
                                _key = _key[random];
                                _val = _val[random];

                                if (_val.indexOf("|") >= 0) {
                                    _val = _val.split("|");
                                }
                                if (typeof (_val) == "object") {
                                    _val = _val[parseInt(Math.random() * _val.length)];
                                }
                                sdk.history.fill({
                                    url: sdk.playHost + "/ic/game/?gameid=" + _key,
                                    random: _val
                                });
                            } else {
                                function _buildArray() {
                                    for (var i = 0; i < 3; i++) {
                                        var random = parseInt(Math.random() * sdk.history.recGameList.length);
                                        sdk.history.recList.push(sdk.history.recGameList[random]);
                                        sdk.history.recGameList.splice(random, 1);
                                    }
                                    sdk.history.recList.push({
                                        game_url: sdk.gameHost,
                                        icon: sdk.cdnHost+'/static/image/jeticon.png',
                                        id: 68,
                                        title: '更多游戏'
                                    });
                                    sdk.history.fill(false, sdk.history.recList);
                                }

                                $.get( sdk.webHost + "/ic/api?cmd=getCommonRecoGames&token=" + sdk.token, function (data) {
                                    data = data.recommends;
                                    for (var i in data) {
                                        if (data[i].id != sdk.gameId) {
                                            sdk.history.recGameList.push(data[i]);
                                        }
                                    }
                                    _buildArray();
                                }, 'json');
                            }
                        }
                    })
                }, 1000);
            }
        }
    };
    sdk.history.init();
})();