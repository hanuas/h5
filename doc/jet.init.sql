/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : jet

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2017-10-18 10:28:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `cps_config`
-- ----------------------------
DROP TABLE IF EXISTS `cps_config`;
CREATE TABLE `cps_config` (
  `cps_id` int(11) NOT NULL AUTO_INCREMENT,
  `cps_name` varchar(100) NOT NULL DEFAULT '' COMMENT '渠道名',
  `wxqr_login` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否支付微信登陆，1是，0否',
  `qq_login` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否支付QQ登陆，1是，0否',
  `sina_login` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否支付微博登陆，1是，0否',
  `jet_login` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否支付jet登陆，1是，0否',
  `wxh5pay` tinyint(4) NOT NULL COMMENT '是否支付wxh5pay，1是，0否',
  `wxpay` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否支持微信支付',
  `alih5pay` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否支付支付宝h5支付',
  PRIMARY KEY (`cps_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of cps_config
-- ----------------------------
INSERT INTO `cps_config` VALUES ('1', 'baidu', '0', '0', '0', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for `game`
-- ----------------------------
DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '游戏id',
  `appid` int(11) NOT NULL COMMENT '游戏的app_id',
  `game_name` varchar(255) NOT NULL DEFAULT '' COMMENT '游戏名称',
  `url` varchar(150) NOT NULL DEFAULT '' COMMENT '游戏地址？',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `dc_appid` varchar(50) NOT NULL DEFAULT '',
  `td_appid` varchar(50) NOT NULL DEFAULT '' COMMENT '统计？',
  `desktop_icon` varchar(150) NOT NULL DEFAULT '' COMMENT '桌面icon',
  `token_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '需要的token传递类型，1:需要的是userToken, 0:需要的是token',
  `orientation` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否为横屏游戏,1:是，0否',
  `wx_option` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否支持微信?  0:不支持，1支持',
  `entry_url` varchar(150) NOT NULL DEFAULT '' COMMENT '进入游戏url',
  `content_url` varchar(150) NOT NULL DEFAULT '' COMMENT '游戏内嵌页地址',
  `use_vuconpon` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否可使用优惠券？ 0 否 1是',
  PRIMARY KEY (`id`),
  UNIQUE KEY `appid` (`appid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of game
-- ----------------------------
INSERT INTO `game` VALUES ('2', '1011', '三国志大战1', 'http://www.qq.com', '0', '11111', '2123123', '', '1', '1', '0', 'http://www.baidu.com', 'http://www.neiqianye.com', '0');
INSERT INTO `game` VALUES ('3', '10023', '三国志大战2', 'http://www.qq.com', '0', '11111', '2123123', '', '1', '1', '0', 'http://www.baidu.com', 'http://www.neiqianye.com', '0');
INSERT INTO `game` VALUES ('4', '10045', '三国志大战3', 'http://www.qq.com', '0', '11111', '2123123', '', '1', '1', '0', 'http://www.baidu.com', 'http://www.neiqianye.com', '0');
INSERT INTO `game` VALUES ('5', '10067', '三国志大战4', 'http://www.qq.com', '0', '11111', '2123123', '', '1', '1', '0', 'http://www.baidu.com', 'http://www.neiqianye.com', '0');
INSERT INTO `game` VALUES ('6', '10012', '三国志大战5', 'http://www.qq.com2', '2', '222222', '33333333', 'Resources/game/6/picture/desktopicon_date_2017-09-26154400.jpg', '0', '0', '0', 'http://www.baidu.com2', 'http://www.neiqianye.com3', '0');
INSERT INTO `game` VALUES ('7', '1002', '三国志大战6', 'http://www.qq.com', '1', '11111', '2123123', 'Resources/game/7/picture/desktopicon_date_2017-09-26150919.jpg', '0', '0', '0', 'http://www.baidu.com', 'http://www.neiqianye.com', '1');
INSERT INTO `game` VALUES ('8', '10000', '屠龙战记', 'http://www.qq.com', '2', '10000', '100001', 'Resources/game/8/picture/desktopicon_date_2017-09-26155954.jpg', '0', '1', '1', 'http://www.qq.com2', 'http://www.qq.com3', '1');
INSERT INTO `game` VALUES ('9', '10000111', '屠龙战记2', 'http://www.qq.com', '1', '10000', '2123123', 'Resources/game/9/picture/desktopicon_date_2017-09-27103602.jpg', '1', '0', '0', 'http://www.baidu.com', 'http://www.neiqianye.com', '0');


-- ----------------------------
-- Table structure for `pay_orders`
-- ----------------------------
DROP TABLE IF EXISTS `pay_orders`;
CREATE TABLE `pay_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` char(30) NOT NULL DEFAULT '' COMMENT '订单id',
  `ktuid` bigint(16) NOT NULL DEFAULT '0' COMMENT '开天用户id',
  `appid` int(11) NOT NULL DEFAULT '0' COMMENT 'APPID',
  `channel` varchar(50) NOT NULL DEFAULT 'ktcs' COMMENT '渠道标识，默认ktcs',
  `thirdOrderID` varchar(255) DEFAULT '' COMMENT '第三方订单id',
  `currency` varchar(20) DEFAULT 'RMB' COMMENT '货币类型',
  `amount` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `realamount` decimal(8,2) DEFAULT '0.00' COMMENT '真实价格',
  `payState` tinyint(3) NOT NULL DEFAULT '1' COMMENT '支付状态(1.支付中，2.支付成功，3.加元宝成功)',
  `roleID` varchar(20) DEFAULT '' COMMENT '角色ID',
  `roleName` varchar(100) DEFAULT '' COMMENT '角色名称',
  `roleLevel` mediumint(8) DEFAULT '0' COMMENT '角色等级',
  `areaID` varchar(50) DEFAULT '' COMMENT '大区ID',
  `accountID` varchar(50) DEFAULT '' COMMENT '游戏账号ID',
  `serverID` varchar(50) DEFAULT '' COMMENT '服ID',
  `serverName` varchar(30) DEFAULT '' COMMENT '服名称',
  `gateway` varchar(30) DEFAULT '' COMMENT '支付类型',
  `payOrderTime` datetime DEFAULT NULL,
  `completeTime` datetime DEFAULT NULL COMMENT '完成时间',
  `productID` varchar(255) DEFAULT '' COMMENT '产品ID',
  `productName` varchar(255) DEFAULT '' COMMENT '产品名称',
  `productDesc` varchar(255) DEFAULT '' COMMENT '产品描述',
  `kt_ext` varchar(255) DEFAULT '' COMMENT '开天扩展参数',
  `extendbox` varchar(255) DEFAULT '' COMMENT '扩展参数',
  `userip` varchar(20) NOT NULL DEFAULT '' COMMENT 'ip地址',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `appid` (`appid`),
  KEY `ktuid` (`ktuid`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pay_orders
-- ----------------------------
INSERT INTO `pay_orders` VALUES ('21', '851125089986783683', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 11:09:47');
INSERT INTO `pay_orders` VALUES ('22', '851125090454260366', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 11:10:34');
INSERT INTO `pay_orders` VALUES ('23', '851125090748497230', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 11:11:03');
INSERT INTO `pay_orders` VALUES ('24', '851125094185323751', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 11:16:47');
INSERT INTO `pay_orders` VALUES ('25', '851125209802276755', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 14:29:29');
INSERT INTO `pay_orders` VALUES ('26', '851125209818937647', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 14:29:30');
INSERT INTO `pay_orders` VALUES ('27', '851125209827618167', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 14:29:31');
INSERT INTO `pay_orders` VALUES ('28', '851125210162877338', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 14:30:04');
INSERT INTO `pay_orders` VALUES ('29', '851125249555230434', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 15:35:44');
INSERT INTO `pay_orders` VALUES ('30', '851125249706359023', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 15:35:59');
INSERT INTO `pay_orders` VALUES ('31', '851125250213828188', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 15:36:49');
INSERT INTO `pay_orders` VALUES ('32', '851125252591284192', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '3', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, '2017-09-25 16:42:07', '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 15:40:47');
INSERT INTO `pay_orders` VALUES ('33', '851125289381878458', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '3', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, '2017-09-28 13:37:29', '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 16:42:06');
INSERT INTO `pay_orders` VALUES ('34', '851125289609801427', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 16:42:29');
INSERT INTO `pay_orders` VALUES ('35', '851125292233871590', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 16:46:51');
INSERT INTO `pay_orders` VALUES ('36', '851125292775582588', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-25 16:47:46');
INSERT INTO `pay_orders` VALUES ('37', '851128770553909863', '6121312032', '1011', 'baidu', '', 'RMB', '11.00', '11.00', '1', '100', '屠龙宝刀', '10', '901', '10', '9011', '开天9区', 'wxpay', null, null, '10111', '倚天剑', '倚天剑', '', '123456', '127.0.0.1', '2017-09-28 13:37:28');

-- ----------------------------
-- Table structure for `share_log`
-- ----------------------------
DROP TABLE IF EXISTS `share_log`;
CREATE TABLE `share_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL COMMENT '游戏id',
  `share_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '分享时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of share_log
-- ----------------------------

-- ----------------------------
-- Table structure for `sys_action_log`
-- ----------------------------
DROP TABLE IF EXISTS `sys_action_log`;
CREATE TABLE `sys_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'default',
  `type` varchar(50) NOT NULL COMMENT '操作类型',
  `title` varchar(100) NOT NULL COMMENT '操作概要',
  `action_detail` text NOT NULL COMMENT '操作详情',
  `user_name` varchar(100) NOT NULL,
  `time` datetime NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `cat_type` (`category`,`type`),
  KEY `time` (`time`)
) ENGINE=InnoDB AUTO_INCREMENT=2259 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='操作日志';

-- ----------------------------
-- Records of sys_action_log
-- ----------------------------

-- ----------------------------
-- Table structure for `sys_config`
-- ----------------------------
DROP TABLE IF EXISTS `sys_config`;
CREATE TABLE `sys_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `segment` varchar(30) NOT NULL COMMENT '分类',
  `name` varchar(255) NOT NULL,
  `type` enum('json','string','float','int','object') NOT NULL,
  `value` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统配置';

-- ----------------------------
-- Records of sys_config
-- ----------------------------

-- ----------------------------
-- Table structure for `sys_privilege`
-- ----------------------------
DROP TABLE IF EXISTS `sys_privilege`;
CREATE TABLE `sys_privilege` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `the_group` varchar(50) NOT NULL DEFAULT '0' COMMENT '父ID，主要是管理仅限分组。当authtype为group时表示这是个权限组',
  `authtype` enum('group','name','url','mca','mc','m','mcab') NOT NULL COMMENT '权限方式,如果为subprevilege则代表为角色',
  `privilege_name` varchar(120) NOT NULL COMMENT '权限名',
  `url` varchar(255) NOT NULL,
  `name` varchar(120) NOT NULL,
  `m` varchar(120) NOT NULL,
  `c` varchar(120) NOT NULL,
  `a` varchar(120) NOT NULL,
  `branch` varchar(30) NOT NULL COMMENT '分支，控制ueditor的增删改',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限表';

-- ----------------------------
-- Records of sys_privilege
-- ----------------------------
INSERT INTO `sys_privilege` VALUES ('1', 'index', 'm', '首页', '', '', 'index', '', '', '');
INSERT INTO `sys_privilege` VALUES ('10', 'privilege', 'm', '用户、权限管理', '', '', 'user', '', '', 'del');
INSERT INTO `sys_privilege` VALUES ('12', 'system', 'm', '系统设置', '', '', 'admin', '', '', 'add,update');
INSERT INTO `sys_privilege` VALUES ('116', 'sysuser', 'm', '后台用户管理', '', '', 'sysuser', '', '', '');
INSERT INTO `sys_privilege` VALUES ('127', 'game', 'm', '游戏管理', '', '', 'game_manage', '', '', 'read,add,del,update');
INSERT INTO `sys_privilege` VALUES ('128', 'pay', 'm', '充值管理', '', '', 'pay_manage', '', '', 'read,add,del,update');

-- ----------------------------
-- Table structure for `sys_role`
-- ----------------------------
DROP TABLE IF EXISTS `sys_role`;
CREATE TABLE `sys_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL COMMENT '角色名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='角色表';

-- ----------------------------
-- Records of sys_role
-- ----------------------------
INSERT INTO `sys_role` VALUES ('1', '超级管理员');
INSERT INTO `sys_role` VALUES ('29', '网站编辑');
INSERT INTO `sys_role` VALUES ('30', '草稿录入');
INSERT INTO `sys_role` VALUES ('31', 'CPS代理');
INSERT INTO `sys_role` VALUES ('32', 'CPS管理');

-- ----------------------------
-- Table structure for `sys_role_privilege`
-- ----------------------------
DROP TABLE IF EXISTS `sys_role_privilege`;
CREATE TABLE `sys_role_privilege` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL COMMENT '和lmb_role表id字段对应',
  `privilege_id` int(10) unsigned NOT NULL COMMENT '和lmb_privilege表id字段对应',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`,`privilege_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1427 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='角色和权限对应关系表';

-- ----------------------------
-- Records of sys_role_privilege
-- ----------------------------
INSERT INTO `sys_role_privilege` VALUES ('1422', '1', '1');
INSERT INTO `sys_role_privilege` VALUES ('1424', '1', '10');
INSERT INTO `sys_role_privilege` VALUES ('1425', '1', '12');
INSERT INTO `sys_role_privilege` VALUES ('1426', '1', '116');
INSERT INTO `sys_role_privilege` VALUES ('1419', '1', '121');
INSERT INTO `sys_role_privilege` VALUES ('1420', '1', '125');
INSERT INTO `sys_role_privilege` VALUES ('1421', '1', '127');
INSERT INTO `sys_role_privilege` VALUES ('1423', '1', '128');
INSERT INTO `sys_role_privilege` VALUES ('1352', '29', '115');
INSERT INTO `sys_role_privilege` VALUES ('1375', '30', '1');
INSERT INTO `sys_role_privilege` VALUES ('1376', '30', '119');
INSERT INTO `sys_role_privilege` VALUES ('1377', '30', '120');
INSERT INTO `sys_role_privilege` VALUES ('1409', '31', '1');
INSERT INTO `sys_role_privilege` VALUES ('1405', '31', '122');
INSERT INTO `sys_role_privilege` VALUES ('1406', '31', '123');
INSERT INTO `sys_role_privilege` VALUES ('1407', '31', '124');
INSERT INTO `sys_role_privilege` VALUES ('1408', '31', '126');
INSERT INTO `sys_role_privilege` VALUES ('1411', '32', '1');
INSERT INTO `sys_role_privilege` VALUES ('1410', '32', '121');

-- ----------------------------
-- Table structure for `sys_user`
-- ----------------------------
DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE `sys_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(120) NOT NULL COMMENT '用户名',
  `user_pwd` char(32) NOT NULL COMMENT '密码',
  `email` varchar(120) NOT NULL COMMENT '邮箱地址',
  `phone` varchar(120) NOT NULL COMMENT '电话号码',
  `gender` enum('male','female') NOT NULL COMMENT '性别',
  `leader` int(10) unsigned NOT NULL COMMENT '相关负责人',
  `nick_name` varchar(255) NOT NULL COMMENT '昵称',
  `extend` text,
  `user_coins` decimal(10,2) NOT NULL DEFAULT '0.00',
  `id_no` varchar(50) NOT NULL DEFAULT '' COMMENT '身份证',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户表';

-- ----------------------------
-- Records of sys_user
-- ----------------------------
INSERT INTO `sys_user` VALUES ('1', 'bossmanager', '60860627d939017b5d02866ccab695da', 'qiaochenglei@163.com', '13401146796', 'male', '1', '管理员', '', '0.00', '');
INSERT INTO `sys_user` VALUES ('5', 'draft_test', 'd9b1d7db4cd6e70935368a1efb10e377', '130702198401291248', '12', 'male', '1', 'tes', '\r\n{\r\n  \"shortcuts\":[\r\n   { \"url\":\"?m=index&c=home&a=modify\", \"icon\":\"signal\",\"btnClass\":\"btn-success\"},\r\n   { \"url\":\"?m=virtue&c=index\",  \"icon\":\"pencil\", \"btnClass\":\"btn-info\"},\r\n   { \"url\":\"?m=user&c=index\",  \"icon\":\"users\", \"btnClass\":\"btn-warning\"}\r\n  ]\r\n}', '18.90', '123');
INSERT INTO `sys_user` VALUES ('6', 'mayao', '901d26c637722905bd8e62249739cd2c', '', '', 'female', '1', '马瑶', null, '0.00', '');
INSERT INTO `sys_user` VALUES ('8', 'lining', '14e1b600b1fd579f47433b88e8d85291', '', '', 'female', '1', '李宁', null, '0.00', '');
INSERT INTO `sys_user` VALUES ('10', 'admin', '224cf2b695a5e8ecaecfb9015161fa4b', 'qclei1@qq.com', '13401146796', 'male', '1', '管理员', '', '0.00', '');
INSERT INTO `sys_user` VALUES ('12', 'test1', 'f4cc399f0effd13c888e310ea2cf5399', '13@qq.com', '13810358824', 'female', '6', '测试代理商', '', '0.00', '');
INSERT INTO `sys_user` VALUES ('16', 'dutianxiao01', '14e1b600b1fd579f47433b88e8d85291', 'pla0459@foxmail.com', '15164541818', 'male', '6', '杜天笑', '', '571.40', '232331198507221219');
INSERT INTO `sys_user` VALUES ('20', 'dongyuanxi01', '14e1b600b1fd579f47433b88e8d85291', '123456@qq.com', '', 'female', '6', '董原希', null, '613.00', '220203198312245128');
INSERT INTO `sys_user` VALUES ('22', 'fenbao', '14e1b600b1fd579f47433b88e8d85291', '123456@qq.com', '', 'female', '10', 'fenbao', null, '176.40', '');
INSERT INTO `sys_user` VALUES ('26', 't2', '60860627d939017b5d02866ccab695da', '21', '23', 'female', '1', '52', null, '0.00', 'bossmanager');

-- ----------------------------
-- Table structure for `sys_user_role`
-- ----------------------------
DROP TABLE IF EXISTS `sys_user_role`;
CREATE TABLE `sys_user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '对应lmb_user表id字段',
  `role_id` int(10) unsigned NOT NULL COMMENT '对应lmb_role表id字段',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='用户和角色对应关系表';

-- ----------------------------
-- Records of sys_user_role
-- ----------------------------
INSERT INTO `sys_user_role` VALUES ('76', '0', '31');
INSERT INTO `sys_user_role` VALUES ('84', '0', '31');
INSERT INTO `sys_user_role` VALUES ('88', '0', '31');
INSERT INTO `sys_user_role` VALUES ('92', '0', '31');
INSERT INTO `sys_user_role` VALUES ('100', '0', '31');
INSERT INTO `sys_user_role` VALUES ('102', '0', '31');
INSERT INTO `sys_user_role` VALUES ('48', '1', '1');
INSERT INTO `sys_user_role` VALUES ('27', '3', '29');
INSERT INTO `sys_user_role` VALUES ('10', '4', '29');
INSERT INTO `sys_user_role` VALUES ('104', '5', '31');
INSERT INTO `sys_user_role` VALUES ('65', '6', '31');
INSERT INTO `sys_user_role` VALUES ('66', '6', '32');
INSERT INTO `sys_user_role` VALUES ('68', '8', '31');
INSERT INTO `sys_user_role` VALUES ('70', '8', '32');
INSERT INTO `sys_user_role` VALUES ('74', '10', '1');
INSERT INTO `sys_user_role` VALUES ('105', '12', '31');
INSERT INTO `sys_user_role` VALUES ('86', '16', '31');
INSERT INTO `sys_user_role` VALUES ('90', '20', '31');
INSERT INTO `sys_user_role` VALUES ('96', '22', '31');
