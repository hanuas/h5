/* 游戏扩展库：聊天 （必须在sdk.min.js之后引用）*/
(function() {
    var sdk = window.JET_SDK || {};

    sdk.chat = {};
    sdk.chat.loginws = "";
    sdk.chat.socket = null;
    sdk.chat.status = 0;
    sdk.chat.token = "";
    sdk.chat.gameId = "";
    sdk.chat.chatId = null;
    sdk.chat.chatRoomId = 0;
    sdk.chat.onConnect = null;
    sdk.chat.onMessage = null;
    sdk.chat.onDisconnect = null;
    sdk.chat.onError = null;
    sdk.chat.callbackList = [];

    sdk.chat.MSGOBJ_PLAYER = 1;
    sdk.chat.MSGOBJ_ROOM = 2;

    sdk.chat.MSGTYPE_TEXT = 1;

    sdk.chat.STATUS_DISCONNECTED = 0;
    sdk.chat.STATUS_CONNECTING = 1;
    sdk.chat.STATUS_CONNECTED = 2;

    /**
     * 初始化服务器地址
     * @param callback
     */
    sdk.chat.init = function (callback) {
        if (sdk.chat.loginws) {
            callback();
        } else {
            sdk.httpGet(sdk.apiHost + "/ic/chat/getChatServer?" + Date.now(), function (data) {
                sdk.chat.loginws = data.loginws;
                if (sdk.chat.loginws) {
                    callback();
                } else {
                    console.log("获取聊天服务器失败！" + data.error);
                }
            }, "json");
        }
    };

    sdk.chat.close = function () {
        if (sdk.chat.status == sdk.chat.STATUS_CONNECTED) {
            if (sdk.chat.socket) {
                sdk.chat.socket.close();
            }
        }
    };

    sdk.chat.connect = function (token, gameId) {
        sdk.chat.init(function() {
            sdk.chat.token = token;
            sdk.chat.gameId = gameId;
            if (window["WebSocket"]) {
                sdk.chat.socket = new WebSocket(sdk.chat.loginws);
            } else if (window["MozWebSocket"]) {
                sdk.chat.socket = new MozWebSocket(sdk.chat.loginws);
            }

            sdk.chat.socket.onopen = function(event) {
                sdk.chat.status = sdk.chat.STATUS_CONNECTED;
                var msg = JSON.stringify({"_C": "player", "cmd": "login", "data": {
                    token: token,
                    gameId: gameId
                }, "_T": 0});
                sdk.chat.socket.send(msg);
            };

            sdk.chat.socket.onclose = function(event) {
                sdk.chat.status = sdk.chat.STATUS_DISCONNECTED;
                sdk.chat.onDisconnect();
            };

            sdk.chat.socket.onmessage = function(event) {
                if (typeof(event.data) == "string") {
                    var msg = JSON.parse(event.data);
                    switch(msg.cmd) {
                        case "login": {
                            if (msg.error) {
                                sdk.chat.onError && sdk.chat.onError(msg.error);
                            } else {
                                sdk.chat.callbackList = [];
                                sdk.chat.callbackList["sendMsg"] = [];
                                sdk.chat.chatId = msg.data.chatId;
                                sdk.chat.onConnect && sdk.chat.onConnect();
                            }
                            break;
                        }
                        case "sendMsg": {
                            if (msg._T == 1) {
                                var cb = sdk.chat.callbackList["sendMsg"].pop();
                                cb && cb(msg.error);
                            } else if (msg._T == 2) {
                                sdk.chat.onMessage && sdk.chat.onMessage(msg.data);
                                if (msg.data.msg_type == sdk.chat.MSGOBJ_ROOM) {
                                    var time = sdk.getItem(sdk.chat.gameId + "_" + sdk.chat.chatRoomId + "_time") || 0;
                                    if (msg.data.time > time) {
                                        sdk.setItem(sdk.chat.gameId + "_" + sdk.chat.chatRoomId + "_time", msg.data.time);
                                    }
                                }
                            }
                            break;
                        }
                        case "joinChatRoom": {
                            sdk.chat.callbackList["joinChatRoom"] && sdk.chat.callbackList["joinChatRoom"](msg.error, msg.data);
                            if (msg.data) {
                                sdk.chat.chatRoomId = msg.data.chatRoomId;
                            }
                            break;
                        }
                        case "getSessionList": {
                            sdk.chat.callbackList["getSessionList"] && sdk.chat.callbackList["getSessionList"](msg.error, msg.data);
                            break;
                        }
                        case "getSessionHistory": {
                            sdk.chat.callbackList["getSessionHistory"] && sdk.chat.callbackList["getSessionHistory"](msg.error, msg.data);
                            break;
                        }
                        case "sendLastMsg": {
                            if (msg.data) {
                                var time = sdk.getItem(sdk.chat.gameId + "_" + sdk.chat.chatRoomId + "_time") || 0;
                                for (var i in msg.data.msgList) {
                                    sdk.chat.onMessage && sdk.chat.onMessage(msg.data.msgList[i]);
                                    if (msg.data.msgList[i].time > time) {
                                        time = msg.data.msgList[i].time;
                                    }
                                }
                                sdk.setItem(sdk.chat.gameId + "_" + sdk.chat.chatRoomId + "_time", time);
                            }
                            break;
                        }
                    }
                }
            };

            sdk.chat.socket.onerror = function (event) {
                sdk.chat.onError && sdk.chat.onError(event);
            };
        });
    };

    sdk.chat.sendMessage = function (id, content, extra, callback) {
        if (content) {
            sdk.chat.callbackList["sendMsg"].push(callback);
            var msg = JSON.stringify({"_C": "player", "cmd": "sendMsg", "data": {
                to_id: id,
                msg_type: sdk.chat.MSGOBJ_PLAYER,
                msg_content_type: sdk.chat.MSGTYPE_TEXT,
                msg_data: content,
                ext: extra
            }, "_T": 0});
            sdk.chat.socket.send(msg);
        }
    };

    sdk.chat.sendRoomMessage = function (content, extra, callback) {
        if (content) {
            sdk.chat.callbackList["sendMsg"].push(callback);
            var msg = JSON.stringify({"_C": "player", "cmd": "sendMsg", "data": {
                msg_type: sdk.chat.MSGOBJ_ROOM,
                msg_content_type: sdk.chat.MSGTYPE_TEXT,
                msg_data: content,
                ext: extra
            }, "_T": 0});
            sdk.chat.socket.send(msg);
        }
    };

    sdk.chat.joinChatRoom = function (chatRoomId, callback) {
        if (!sdk.chat.chatRoomId) {
            sdk.removeItem(sdk.chat.gameId + "_" + chatRoomId + "_time");
        }
        sdk.chat.callbackList["joinChatRoom"] = callback;
        var time = sdk.getItem(sdk.chat.gameId + "_" + chatRoomId + "_time") || 0;
        var msg = JSON.stringify({"_C": "player", "cmd": "joinChatRoom", "data": {
            chatRoomId: chatRoomId,
            time: time
        }, "_T": 0});
        sdk.chat.socket.send(msg);
    };

    sdk.chat.getSessionList = function (callback) {
        sdk.chat.callbackList["getSessionList"] = callback;
        var msg = JSON.stringify({"_C": "player", "cmd": "getSessionList", "data": {}, "_T": 0});
        sdk.chat.socket.send(msg);
    };

    sdk.chat.removeSession = function (id) {
        var msg = JSON.stringify({"_C": "player", "cmd": "removeSession", "data": {
            session: id
        }, "_T": 0});
        sdk.chat.socket.send(msg);
    };

    sdk.chat.getSessionHistory = function (id, callback) {
        sdk.chat.callbackList["getSessionHistory"] = callback;
        var msg = JSON.stringify({"_C": "player", "cmd": "getSessionHistory", "data": {
            session: id
        }, "_T": 0});
        sdk.chat.socket.send(msg);
    };

    sdk.chat.getStatus = function () {
        return sdk.chat.status;
    };
})();