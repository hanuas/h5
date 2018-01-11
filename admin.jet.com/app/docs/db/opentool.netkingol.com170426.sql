/*
 Navicat MySQL Data Transfer

 Source Server         : localhost
 Source Server Version : 50616
 Source Host           : localhost
 Source Database       : opentool.netkingol.com

 Target Server Version : 50616
 File Encoding         : utf-8

 Date: 04/26/2017 12:16:59 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `tb_sms`
-- ----------------------------
DROP TABLE IF EXISTS `tb_sms`;
CREATE TABLE `tb_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rrid` varchar(50) NOT NULL COMMENT '发送ID',
  `phone` text NOT NULL,
  `content` varchar(255) NOT NULL COMMENT '内容',
  `create_time` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  `stime` varchar(30) NOT NULL DEFAULT '' COMMENT '发送时间',
  `partner_plat` varchar(50) NOT NULL DEFAULT 'md' COMMENT '合作方',
  `return_status` int(11) NOT NULL DEFAULT '-1' COMMENT '返回码，本地意义，0：成功 1：失败',
  `return_message` varchar(500) NOT NULL COMMENT '返回信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='发送短信表';

-- ----------------------------
--  Records of `tb_sms`
-- ----------------------------
BEGIN;
INSERT INTO `tb_sms` VALUES ('9', 'md_1451964923', '13401146796', 'hi qcl', '2016-01-05 11:35:23', '2016-01-05 11:35:23', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1451964923</string>'), ('10', 'md_1451964962', '13401146796', 'hi qcl', '2016-01-05 11:36:02', '2016-01-05 11:36:02', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1451964962</string>'), ('11', 'md_1451965129', '13401146796', 'hi qcl', '2016-01-05 11:38:49', '2016-01-05 11:38:49', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1451965129</string>'), ('12', 'md_1451965903', '13401146796', 'hi qcl', '2016-01-05 11:51:43', '2016-01-05 11:51:43', '', 'md', '-1', ''), ('13', 'md_1451965926', '13401146796', 'hi qcl', '2016-01-05 11:52:06', '2016-01-05 11:52:06', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1451965926</string>'), ('14', 'md_1454223700', '31234567890', '913202,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:01:40', '2016-01-31 15:01:40', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454223700</string>'), ('15', 'md_1454223900', '31234567890', '954577,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:05:00', '2016-01-31 15:05:00', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454223900</string>'), ('16', 'md_1454223902', '31234567890', '175052,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:05:02', '2016-01-31 15:05:03', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454223902</string>'), ('17', 'md_1454223915', '31234567890', '413626,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:05:15', '2016-01-31 15:05:15', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454223915</string>'), ('18', 'md_1454223967', '31234567890', '101592,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:06:07', '2016-01-31 15:06:07', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454223967</string>'), ('19', 'md_1454223981', '31234567890', '821271,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:06:21', '2016-01-31 15:06:21', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454223981</string>'), ('20', 'md_1454224238', '31234567890', '686524,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:10:38', '2016-01-31 15:10:39', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224238</string>'), ('21', 'md_1454224249', '31234567890', '620935,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:10:49', '2016-01-31 15:10:49', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224249</string>'), ('22', 'md_1454224335', '31234567890', '187346,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:12:15', '2016-01-31 15:12:15', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224335</string>'), ('23', 'md_1454224338', '31234567890', '579683,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:12:18', '2016-01-31 15:12:18', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224338</string>'), ('24', 'md_1454224365', '31234567890', '217368,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:12:45', '2016-01-31 15:12:45', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224365</string>'), ('25', 'md_1454224437', '31234567890', '903558,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:13:57', '2016-01-31 15:13:59', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224437</string>'), ('26', 'md_1454224444', '31234567890', '408642,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:14:04', '2016-01-31 15:14:04', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224444</string>'), ('27', 'md_1454224451', '31234567890', '908335,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:14:11', '2016-01-31 15:14:13', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224451</string>'), ('28', 'md_1454224523', '31234567890', '843333,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:15:23', '2016-01-31 15:15:23', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224523</string>'), ('29', 'md_1454224541', '31234567890', '382017,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:15:41', '2016-01-31 15:15:44', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224541</string>'), ('30', 'md_1454224553', '31234567890', '610921,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:15:53', '2016-01-31 15:15:53', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224553</string>'), ('31', 'md_1454224604', '31234567890', '199265,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:16:44', '2016-01-31 15:16:44', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224604</string>'), ('32', 'md_1454224687', '31234567890', '262488,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:18:07', '2016-01-31 15:18:07', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224687</string>'), ('33', 'md_1454224699', '31234567890', '334608,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:18:19', '2016-01-31 15:18:20', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224699</string>'), ('34', 'md_1454224726', '31234567890', '516960,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:18:46', '2016-01-31 15:18:47', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224726</string>'), ('35', 'md_1454224848', '31234567890', '805237,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:20:48', '2016-01-31 15:20:49', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224848</string>'), ('36', 'md_1454224851', '31234567890', '184404,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:20:51', '2016-01-31 15:20:52', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224851</string>'), ('37', 'md_1454224862', '31234567890', '428278,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:21:02', '2016-01-31 15:21:02', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224862</string>'), ('38', 'md_1454224879', '31234567890', '848510,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:21:19', '2016-01-31 15:21:19', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224879</string>'), ('39', 'md_1454224883', '31234567890', '114638,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:21:23', '2016-01-31 15:21:24', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224883</string>'), ('40', 'md_1454224890', '31234567890', '200927,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:21:30', '2016-01-31 15:21:30', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224890</string>'), ('41', 'md_1454224906', '31234567890', '740429,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:21:46', '2016-01-31 15:21:46', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224906</string>'), ('42', 'md_1454224960', '31234567890', '900892,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:22:40', '2016-01-31 15:22:40', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454224960</string>'), ('43', 'md_1454226151', '31234567890', '923954,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:42:31', '2016-01-31 15:42:31', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454226151</string>'), ('44', 'md_1454226160', '31234567890', '165742,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:42:40', '2016-01-31 15:42:40', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454226160</string>'), ('45', 'md_1454226609', '31234567890', '820576,您的手机注册码，请注意保密【谦谦君子】', '2016-01-31 15:50:09', '2016-01-31 15:50:09', '', 'md', '0', '<string xmlns=\"http://tempuri.org/\">md_1454226609</string>');
COMMIT;

-- ----------------------------
--  Table structure for `tb_sys_action_log`
-- ----------------------------
DROP TABLE IF EXISTS `tb_sys_action_log`;
CREATE TABLE `tb_sys_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `type` varchar(255) NOT NULL COMMENT '操作类型',
  `title` varchar(255) NOT NULL COMMENT '操作概要',
  `action_detail` text NOT NULL COMMENT '操作详情',
  `user_name` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='操作日志';

-- ----------------------------
--  Table structure for `tb_sys_config`
-- ----------------------------
DROP TABLE IF EXISTS `tb_sys_config`;
CREATE TABLE `tb_sys_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `segment` varchar(30) NOT NULL COMMENT '分类',
  `name` varchar(255) NOT NULL,
  `type` enum('json','string','float','int','object') NOT NULL,
  `value` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统配置';

-- ----------------------------
--  Table structure for `tb_sys_privilege`
-- ----------------------------
DROP TABLE IF EXISTS `tb_sys_privilege`;
CREATE TABLE `tb_sys_privilege` (
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
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限表';

-- ----------------------------
--  Records of `tb_sys_privilege`
-- ----------------------------
BEGIN;
INSERT INTO `tb_sys_privilege` VALUES ('1', 'index', 'm', '首页', '', '', 'index', '', '', ''), ('10', 'privilege', 'm', '用户、权限管理', '', '', 'user', '', '', 'del'), ('12', 'system', 'm', '系统设置', '', '', 'admin', '', '', 'add,update'), ('108', 'material', 'm', '资料站', '', '', 'material', '', '', 'read,add'), ('115', 'article', 'm', '资讯/文章管理', '', '', 'article', '', '', ''), ('116', 'sysuser', 'm', '后台用户管理', '', '', 'sysuser', '', '', ''), ('121', 'cps', 'm', 'CPS管理', '', '', 'cps_manage', '', '', ''), ('122', 'cps', 'm', 'CPS代理商', '', '', 'cps_agent', '', '', '');
COMMIT;

-- ----------------------------
--  Table structure for `tb_sys_role`
-- ----------------------------
DROP TABLE IF EXISTS `tb_sys_role`;
CREATE TABLE `tb_sys_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL COMMENT '角色名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='角色表';

-- ----------------------------
--  Records of `tb_sys_role`
-- ----------------------------
BEGIN;
INSERT INTO `tb_sys_role` VALUES ('1', '超级管理员'), ('29', '网站编辑'), ('30', '草稿录入');
COMMIT;

-- ----------------------------
--  Table structure for `tb_sys_role_privilege`
-- ----------------------------
DROP TABLE IF EXISTS `tb_sys_role_privilege`;
CREATE TABLE `tb_sys_role_privilege` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL COMMENT '和lmb_role表id字段对应',
  `privilege_id` int(10) unsigned NOT NULL COMMENT '和lmb_privilege表id字段对应',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`,`privilege_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1386 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='角色和权限对应关系表';

-- ----------------------------
--  Records of `tb_sys_role_privilege`
-- ----------------------------
BEGIN;
INSERT INTO `tb_sys_role_privilege` VALUES ('1381', '1', '1'), ('1383', '1', '10'), ('1384', '1', '12'), ('1382', '1', '108'), ('1378', '1', '115'), ('1385', '1', '116'), ('1379', '1', '121'), ('1380', '1', '122'), ('1352', '29', '115'), ('1375', '30', '1'), ('1376', '30', '119'), ('1377', '30', '120');
COMMIT;

-- ----------------------------
--  Table structure for `tb_sys_user`
-- ----------------------------
DROP TABLE IF EXISTS `tb_sys_user`;
CREATE TABLE `tb_sys_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(120) NOT NULL COMMENT '用户名',
  `user_pwd` char(32) NOT NULL COMMENT '密码',
  `email` varchar(120) NOT NULL COMMENT '邮箱地址',
  `phone` varchar(120) NOT NULL COMMENT '电话号码',
  `gender` enum('male','female') NOT NULL COMMENT '性别',
  `leader` int(10) unsigned NOT NULL COMMENT '相关负责人',
  `nick_name` varchar(255) NOT NULL COMMENT '昵称',
  `extend` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户表';

-- ----------------------------
--  Records of `tb_sys_user`
-- ----------------------------
BEGIN;
INSERT INTO `tb_sys_user` VALUES ('1', 'bossmanager', '60860627d939017b5d02866ccab695da', '397086786@qq.com', '13401146796', 'male', '1', '管理员', '{\r\n \r\n}'), ('5', 'draft_test', 'd9b1d7db4cd6e70935368a1efb10e377', '12', '12', 'female', '1', 'draft_test', '\r\n{\r\n  \"shortcuts\":[\r\n   { \"url\":\"?m=index&c=home&a=modify\", \"icon\":\"signal\",\"btnClass\":\"btn-success\"},\r\n   { \"url\":\"?m=virtue&c=index\",  \"icon\":\"pencil\", \"btnClass\":\"btn-info\"},\r\n   { \"url\":\"?m=user&c=index\",  \"icon\":\"users\", \"btnClass\":\"btn-warning\"}\r\n  ]\r\n}');
COMMIT;

-- ----------------------------
--  Table structure for `tb_sys_user_role`
-- ----------------------------
DROP TABLE IF EXISTS `tb_sys_user_role`;
CREATE TABLE `tb_sys_user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '对应lmb_user表id字段',
  `role_id` int(10) unsigned NOT NULL COMMENT '对应lmb_role表id字段',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='用户和角色对应关系表';

-- ----------------------------
--  Records of `tb_sys_user_role`
-- ----------------------------
BEGIN;
INSERT INTO `tb_sys_user_role` VALUES ('30', '1', '1'), ('31', '1', '29'), ('27', '3', '29'), ('10', '4', '29'), ('32', '5', '30');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
