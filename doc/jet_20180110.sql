-- MySQL dump 10.13  Distrib 5.5.54, for Linux (x86_64)
--
-- Host: localhost    Database: jet
-- ------------------------------------------------------
-- Server version	5.5.54-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cps_config`
--

DROP TABLE IF EXISTS `cps_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `replaceQrcodeUrl` varchar(200) NOT NULL DEFAULT '' COMMENT '公众号二维码地址',
  `downJetApp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否有微端，1有0无',
  `hideStruct` tinyint(4) NOT NULL DEFAULT '0' COMMENT '？？',
  PRIMARY KEY (`cps_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cps_config`
--

LOCK TABLES `cps_config` WRITE;
/*!40000 ALTER TABLE `cps_config` DISABLE KEYS */;
INSERT INTO `cps_config` VALUES (1,'baidu',0,0,0,0,0,1,1,'',0,0);
/*!40000 ALTER TABLE `cps_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game`
--

DROP TABLE IF EXISTS `game`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '游戏id',
  `appid` int(11) NOT NULL COMMENT '游戏的app_id',
  `game_name` varchar(255) NOT NULL DEFAULT '' COMMENT '游戏名称',
  `game_url` varchar(150) NOT NULL DEFAULT '' COMMENT 'h5游戏地址',
  `url` varchar(150) NOT NULL DEFAULT '' COMMENT '游戏地址,暂时没发现用到',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `dc_appid` varchar(50) NOT NULL DEFAULT '',
  `td_appid` varchar(50) NOT NULL DEFAULT '' COMMENT 'talkdata appid',
  `desktop_icon` varchar(150) NOT NULL DEFAULT '' COMMENT '桌面icon',
  `token_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '传递给游戏所需的token类型，1传递user_token(开天token) 0传递access_token',
  `orientation` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否为横屏游戏,1:是，0否',
  `wx_option` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否显示微信右上角菜单  0:隐藏，1不隐藏',
  `entry_url` varchar(150) NOT NULL DEFAULT '' COMMENT '分享url地址',
  `content_url` varchar(150) NOT NULL DEFAULT '' COMMENT '游戏内嵌页地址',
  `use_vuconpon` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否可使用优惠券？ 0 否 1是',
  `package_id` varchar(100) NOT NULL DEFAULT '' COMMENT '例:包名',
  `icon` varchar(150) NOT NULL DEFAULT '' COMMENT '游戏icon',
  `brief_intro` varchar(100) NOT NULL DEFAULT '' COMMENT '游戏简介，显示在用户中心更多游戏',
  `is_exclusive` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否独家,0否，1是',
  `is_gift` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否有礼包，1是，0否',
  `is_new` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否新游尝鲜.1是，0否',
  `is_hot` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否必玩爆款,1是，0否',
  `weight` int(11) NOT NULL DEFAULT '0' COMMENT '权重，越大越靠前',
  PRIMARY KEY (`id`),
  UNIQUE KEY `appid` (`appid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game`
--

LOCK TABLES `game` WRITE;
/*!40000 ALTER TABLE `game` DISABLE KEYS */;
INSERT INTO `game` VALUES (2,1011,'三国志大战1','http://jet.netkingol.com/game?gameid=1011','http://www.qq.com',0,'11111','2123123','',1,1,1,'http://www.baidu.com','http://jet.netkingol.com/h5.html',0,'com.sina','Resources/game/2/picture/icon_date_2017-11-30135504.png','1111111',0,0,0,0,0),(10,321365,'沙巴克传奇H5','http://jet.netkingol.com/game?gameid=321365','',0,'','1','Resources/game/10/picture/desktopicon_date_2017-12-18134139.png',1,0,0,'','http://h5download.ktsdk.com/test_20180109/sbkh5/index.html',0,'','Resources/game/10/picture/icon_date_2017-12-18134139.png','沙巴克传奇H5',0,0,0,0,11);
/*!40000 ALTER TABLE `game` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_item`
--

DROP TABLE IF EXISTS `game_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `reference_name` varchar(255) NOT NULL COMMENT '建议游戏名+游戏币单位+数量',
  `type` enum('Consumable','Non-renewing','Non-renewing-w') NOT NULL COMMENT '如果是元宝包就写Consumable，如果是月卡类就写Non-renewing',
  `tire` varchar(50) NOT NULL COMMENT '价格，选一个Tire填上去',
  `dolarprice` decimal(10,2) NOT NULL,
  `rmbprice` decimal(10,2) NOT NULL,
  `coin` text NOT NULL COMMENT '游戏元宝数',
  `coin_unit` varchar(100) NOT NULL COMMENT '可选，元宝单位(默认金币)',
  `value` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `item_id` varchar(255) NOT NULL COMMENT '商品id{bundleid}.t{tire}g{gold}  {packagename}.g{gold}}',
  `channel_id` varchar(100) NOT NULL COMMENT '充值渠道ID',
  `month` tinyint(4) NOT NULL DEFAULT '0' COMMENT '月数(当定价表为月卡时生效)',
  `week` int(4) NOT NULL DEFAULT '0',
  `display_name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `game_id_item_id` (`game_id`,`item_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='价格表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_item`
--

LOCK TABLES `game_item` WRITE;
/*!40000 ALTER TABLE `game_item` DISABLE KEYS */;
INSERT INTO `game_item` VALUES (24,2,'60个元宝','Consumable','1',11.00,0.01,'60','金币',0.01,'com.sina.t1g60','1,2',0,0,'60个元宝','60个元宝60个元宝60个元宝'),(25,2,'100个元宝','Consumable','7',122.00,10.00,'100','金币',10.00,'com.sina.t7g100','1,2,4',0,0,'100个元宝100个元宝','100个元宝100个元宝'),(26,2,'月卡','Non-renewing-w','3',123.00,11.00,'300','金币',11.00,'com.sina.m1t3g300','1,2,4',1,22,'月卡','月卡月卡月卡'),(28,3,'月卡','Non-renewing','2',5.00,30.00,'300','金币',30.00,'com.jet.m1t2g300','2,4',1,0,'一个月月卡','一个月月卡一个月月卡');
/*!40000 ALTER TABLE `game_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_item_bak`
--

DROP TABLE IF EXISTS `game_item_bak`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_item_bak` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `game_id` int(11) unsigned NOT NULL COMMENT '产品ID',
  `channel_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '充值渠道id',
  `item` varchar(50) NOT NULL COMMENT '商品id',
  `value` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `coin` text NOT NULL COMMENT '元宝数',
  `item_name` varchar(50) NOT NULL COMMENT '商品名称，商品列表显示',
  PRIMARY KEY (`id`),
  KEY `product_id` (`game_id`),
  KEY `pc` (`game_id`,`channel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='游戏商品信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_item_bak`
--

LOCK TABLES `game_item_bak` WRITE;
/*!40000 ALTER TABLE `game_item_bak` DISABLE KEYS */;
INSERT INTO `game_item_bak` VALUES (1,1,1,'10',10.00,'100','元宝100个'),(2,1,2,'10',10.00,'100','元宝100个'),(3,1,3,'10',10.00,'100','元宝100个'),(4,1,4,'10',10.00,'100','元宝100个');
/*!40000 ALTER TABLE `game_item_bak` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_news`
--

DROP TABLE IF EXISTS `game_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '咨询标题',
  `add_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `content` text NOT NULL COMMENT '资讯内容',
  `read_count` int(11) NOT NULL DEFAULT '0' COMMENT '阅读量',
  `thumb_count` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态，1显示，0隐藏',
  `weight` int(11) NOT NULL DEFAULT '0' COMMENT '权重',
  PRIMARY KEY (`id`),
  KEY `game_id_status_weight` (`game_id`,`status`,`weight`) USING BTREE,
  KEY `game_id_status_addtime` (`game_id`,`status`,`add_time`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_news`
--

LOCK TABLES `game_news` WRITE;
/*!40000 ALTER TABLE `game_news` DISABLE KEYS */;
INSERT INTO `game_news` VALUES (2,2,'维护通知2','2017-11-13 03:14:28','<p>今天维护20个小时</p>\r\n',0,0,1,2222),(3,2,'活动公告','2017-11-13 03:14:20','<p>充100送100充100送100充100送100</p>\r\n\r\n<p>充100送100充100送100</p>\r\n\r\n<p>22222222222</p>\r\n',0,0,0,100),(4,2,'活动公告','2017-11-13 09:21:25','<p>啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊</p>\r\n',0,0,1,0);
/*!40000 ALTER TABLE `game_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gift`
--

DROP TABLE IF EXISTS `gift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gift` (
  `gift_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '礼包id',
  `game_id` int(11) NOT NULL COMMENT '游戏id',
  `gift_title` varchar(100) NOT NULL DEFAULT '' COMMENT '礼包title',
  `get_type` enum('normal','qq_group_num','point','vip','union_code') NOT NULL DEFAULT 'normal' COMMENT '礼包领取方式,normal:正常，qq_group_num:QQ群，union_code:统一码，point:积分礼包，vip：vip礼包',
  `union_code` varchar(50) NOT NULL DEFAULT '' COMMENT '礼包统一码，get_type为union_code时必填',
  `qq_group_num` varchar(20) NOT NULL DEFAULT '' COMMENT 'QQ群号,get_type为qq_group_num时必填',
  `qq_group_link` varchar(100) NOT NULL DEFAULT '' COMMENT 'QQ群链接，get_type为qq_group_num时必填',
  `brief_intro` text NOT NULL COMMENT '礼包简介',
  `point_gift_auto_send` tinyint(4) NOT NULL DEFAULT '0' COMMENT '积分礼包是否自动发送到游戏角色,0否，1是。注意，若是1，则total字段可编辑，无须礼包码',
  `point` int(11) NOT NULL DEFAULT '0' COMMENT '领取时需要多少积分，仅当为积分礼包时有效',
  `total` int(11) NOT NULL DEFAULT '0' COMMENT '礼包码总量，get_type为normal和point时有用',
  `start_time` timestamp NULL DEFAULT NULL COMMENT '开始时间',
  `end_time` timestamp NULL DEFAULT NULL COMMENT '结束时间',
  `gift_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '礼包状态，1启用，0停用',
  `vip_gift_content` text NOT NULL COMMENT 'vip礼包信息，json集合，包含图片路径等。',
  `gift_weight` int(11) NOT NULL DEFAULT '0' COMMENT '礼包权重，大的靠前',
  `vip_get_condition` enum('today_recharge','other','vip_level') NOT NULL DEFAULT 'today_recharge' COMMENT 'vip礼包获取条件,today_recharge:今日充值，vip_level:vip等级，other:其他',
  `vip_get_condition_val` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'vip礼包获取条件的值',
  `vip_get_condition_desc` varchar(100) NOT NULL DEFAULT '' COMMENT 'vip礼包获取条件描述',
  `vip_is_get_code` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'vip礼包是否有礼包信息,1是，0否',
  PRIMARY KEY (`gift_id`),
  KEY `game_id` (`game_id`,`get_type`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gift`
--

LOCK TABLES `gift` WRITE;
/*!40000 ALTER TABLE `gift` DISABLE KEYS */;
INSERT INTO `gift` VALUES (4,2,'礼包二','vip','','','','啊啊啊',0,0,0,'2017-10-05 04:09:29','2017-12-08 04:09:39',1,'[{\"title\":\"\\u5ba0\\u7269\\u793c\\u76d2\",\"num\":3,\"icon\":\"Resources\\/vipGift\\/1011\\/icon_81.png\"},{\"title\":\"\\u8fdb\\u9636\\u77f3\",\"num\":100,\"icon\":\"Resources\\/vipGift\\/1011\\/icon_82.png\"},{\"title\":\"\\u89c9\\u9192\\u77f3\",\"num\":500,\"icon\":\"Resources\\/vipGift\\/1011\\/icon_83.png\"}]',10000,'today_recharge',100,'当日充值金额达到100元',1),(5,2,'礼包一','vip','','','','aaaaaa',0,0,0,'2017-09-01 05:48:36','2017-12-01 05:48:41',1,'[{\"title\":\"\\u7fbd\\u6bdb\\u9a91\\u672f\\u793c\\u76d2\",\"num\":5,\"icon\":\"Resources\\/vipGift\\/1011\\/icon_101.png\"},{\"title\":\"\\u57f9\\u517b\\u77f3\",\"num\":100,\"icon\":\"Resources\\/vipGift\\/1011\\/icon_102.png\"},{\"title\":\"\\u7fbd\\u6bdb\\u9a91\\u672f\\u793c\\u76d2\",\"num\":5,\"icon\":\"Resources\\/vipGift\\/1011\\/icon_103.png\"}]',1000,'today_recharge',1000,'当日充值金额达到1000元',1),(25,2,'新手礼包34','normal','','','','新手礼包32',0,0,24,'2017-11-13 09:35:00','2018-03-08 02:35:00',1,'',112,'today_recharge',0,'',0),(26,2,'新手礼包3','normal','','','','111',0,0,0,'2017-11-07 12:14:00','2017-11-07 12:14:00',0,'',11,'today_recharge',0,'',0),(27,2,'新手礼包3','normal','','','','111',0,0,8,'2017-11-07 12:14:00','2017-11-07 12:14:00',0,'',11,'today_recharge',0,'',0),(28,2,'新手礼包6','normal','','','','1111111',0,0,8,'2017-11-07 12:36:00','2017-11-07 12:36:00',0,'',111,'today_recharge',0,'',0),(29,2,'新手礼包6','normal','','','','1111111',0,0,8,'2017-11-07 12:36:00','2017-11-07 12:36:00',0,'',111,'today_recharge',0,'',0),(30,2,'统一码礼包','union_code','asdasdasd1','','','统一码礼包啊',0,0,0,'2017-11-08 02:46:00','2017-12-01 02:50:00',1,'',11,'today_recharge',0,'',0),(31,2,'统一码礼包2','union_code','asdasdasd','','','统一码的礼包',0,0,0,'2017-11-08 02:53:00','2017-11-08 02:53:00',0,'',10,'today_recharge',0,'',0),(33,2,'QQ群礼包2','qq_group_num','','3816119881','http://www.qq.com','aaaaaaaaaaaaaaaa谢谢',0,0,0,'2017-11-08 02:59:00','2017-11-08 02:59:00',0,'',111,'today_recharge',0,'',0),(34,2,'新手礼包5','normal','','','','新手礼包5新手礼包5',0,0,8,'2017-11-08 04:44:00','2018-03-09 03:55:00',1,'',111,'today_recharge',0,'',0),(35,2,'积分礼包','point','','','','积分礼包',1,22,111112,'2017-11-13 09:55:00','2017-11-29 22:30:00',1,'',11,'today_recharge',0,'',0),(36,2,'积分礼包23','point','','','','积分礼包2积分礼包2积分礼包234512',0,0,16,'2017-11-08 07:55:00','2017-11-08 07:55:00',0,'',1001,'today_recharge',0,'',0),(57,2,'礼包1','vip','','','','觉醒石*100 进阶石*100 能量石*50',0,0,0,'2017-10-05 02:50:00','2018-05-03 23:15:00',1,'[{\"title\":\"\\u89c9\\u9192\\u77f3\",\"num\":100,\"icon\":\"Resources\\/vipGift\\/1011\\/icon_1141.png\"},{\"title\":\"\\u5927\\u7406\\u77f3\",\"num\":100,\"icon\":\"Resources\\/vipGift\\/1011\\/icon_1142.png\"},{\"title\":\"\\u94bb\\u77f3\",\"num\":5000,\"icon\":\"Resources\\/vipGift\\/1011\\/icon_1143.png\"}]',10,'today_recharge',10000,'今日充值10000元',1),(68,2,'VIP礼包','vip','','','','今日充值20000元今日充值20000元',0,0,0,'2017-11-02 07:15:00','2018-02-10 03:15:00',0,'[{\"title\":\"\\u89c9\\u9192\\u77f3\",\"num\":1000,\"icon\":\"Resources\\/vipGift\\/2\\/icon_1361.png\"},{\"title\":\"\\u7ea2\\u8272\\u6b66\\u5668\",\"num\":1101,\"icon\":\"Resources\\/vipGift\\/2\\/icon_1362.png\"},{\"title\":\"\\u7d2b\\u8272\\u6b66\\u5668\",\"num\":2,\"icon\":\"Resources\\/vipGift\\/2\\/icon_1363.png\"}]',1001,'today_recharge',20000,'今日充值20000元2',1),(69,2,'vip礼包200','vip','','','','今日充值10000元今日充值10000元',0,0,0,'2017-11-09 07:46:00','2017-11-09 07:46:00',0,'[{\"title\":\"\\u89c9\\u9192\\u77f3\",\"num\":3000,\"icon\":\"Resources\\/vipGift\\/2\\/icon_1381.png\"},{\"title\":\"\\u554a\\u554a\\u554a\\u554a\",\"num\":10,\"icon\":\"Resources\\/vipGift\\/2\\/icon_1382.png\"}]',100,'today_recharge',10000,'今日充值10000元',1),(71,2,'积分礼包2(礼包码)','point','','','','积分礼包2(礼包码)',0,10,8,'2017-11-14 01:50:00','2018-02-15 02:45:00',1,'',100,'today_recharge',0,'',0);
/*!40000 ALTER TABLE `gift` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gift_card`
--

DROP TABLE IF EXISTS `gift_card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gift_card` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `card_no` varchar(200) NOT NULL COMMENT '卡号',
  `gift_id` int(11) NOT NULL DEFAULT '1' COMMENT '礼包id',
  `card_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '卡是否有效',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `user_id` char(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `game_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品ID',
  `server_id` int(11) NOT NULL DEFAULT '0' COMMENT '分服ID',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0',
  `server_name` varchar(50) DEFAULT '' COMMENT '服务器名',
  `role_name` varchar(50) DEFAULT '' COMMENT '角色名称',
  `is_used` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否领取',
  `used_time` int(11) NOT NULL DEFAULT '0' COMMENT '领取时间',
  `card_remark` char(50) NOT NULL DEFAULT '' COMMENT '备注',
  `user_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户IP',
  `phone` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '手机或QQ号',
  `vip_gift_send` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'vip礼包是否已经发放，0否，1是',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_used` (`is_used`),
  KEY `card_no` (`card_no`),
  KEY `card_status` (`card_status`),
  KEY `gift_id` (`gift_id`,`is_used`,`user_id`,`card_status`)
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8 COMMENT='礼包码表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gift_card`
--

LOCK TABLES `gift_card` WRITE;
/*!40000 ALTER TABLE `gift_card` DISABLE KEYS */;
INSERT INTO `gift_card` VALUES (84,'aaaaaa',25,1,1510051134,'0',2,0,0,'','',0,0,'',0,0,0),(86,'cccccccc',25,1,1510051134,'0',2,0,0,'','',0,0,'',0,0,0),(88,'eeeeeeeeee',25,1,1510051134,'0',2,0,0,'','',0,0,'',0,0,0),(89,'fffffffffff',25,1,1510051134,'0',2,0,0,'','',0,0,'',0,0,0),(91,'hhhhhhhh',25,1,1510051134,'0',2,0,0,'','',0,0,'',0,0,0),(92,'aaaaaa',26,1,1510053313,'0',2,0,0,'','',0,0,'',0,0,0),(93,'bbbbbbbb',26,1,1510053313,'0',2,0,0,'','',0,0,'',0,0,0),(94,'cccccccc',26,1,1510053313,'0',2,0,0,'','',0,0,'',0,0,0),(95,'dddddddddd',26,1,1510053313,'0',2,0,0,'','',0,0,'',0,0,0),(96,'eeeeeeeeee',26,1,1510053313,'0',2,0,0,'','',0,0,'',0,0,0),(97,'fffffffffff',26,1,1510053313,'0',2,0,0,'','',0,0,'',0,0,0),(98,'ggggggggg',26,1,1510053313,'0',2,0,0,'','',0,0,'',0,0,0),(99,'hhhhhhhh',26,1,1510053313,'0',2,0,0,'','',0,0,'',0,0,0),(100,'aaaaaa',27,1,1510054579,'0',2,0,0,'','',0,0,'',0,0,0),(101,'bbbbbbbb',27,1,1510054579,'0',2,0,0,'','',0,0,'',0,0,0),(102,'cccccccc',27,1,1510054579,'0',2,0,0,'','',0,0,'',0,0,0),(103,'dddddddddd',27,1,1510054579,'0',2,0,0,'','',0,0,'',0,0,0),(104,'eeeeeeeeee',27,1,1510054579,'0',2,0,0,'','',0,0,'',0,0,0),(105,'fffffffffff',27,1,1510054579,'0',2,0,0,'','',0,0,'',0,0,0),(106,'ggggggggg',27,1,1510054579,'0',2,0,0,'','',0,0,'',0,0,0),(107,'hhhhhhhh',27,1,1510054579,'0',2,0,0,'','',0,0,'',0,0,0),(108,'aaaaaa',28,1,1510054635,'0',2,0,0,'','',0,0,'',0,0,0),(109,'bbbbbbbb',28,1,1510054635,'0',2,0,0,'','',0,0,'',0,0,0),(110,'cccccccc',28,1,1510054635,'0',2,0,0,'','',0,0,'',0,0,0),(111,'dddddddddd',28,1,1510054635,'0',2,0,0,'','',0,0,'',0,0,0),(112,'eeeeeeeeee',28,1,1510054635,'0',2,0,0,'','',0,0,'',0,0,0),(113,'fffffffffff',28,1,1510054635,'0',2,0,0,'','',0,0,'',0,0,0),(114,'ggggggggg',28,1,1510054635,'0',2,0,0,'','',0,0,'',0,0,0),(115,'hhhhhhhh',28,1,1510054635,'0',2,0,0,'','',0,0,'',0,0,0),(116,'aaaaaa',29,1,1510054661,'0',2,0,0,'','',0,0,'',0,0,0),(117,'bbbbbbbb',29,1,1510054661,'0',2,0,0,'','',0,0,'',0,0,0),(118,'cccccccc',29,1,1510054661,'0',2,0,0,'','',0,0,'',0,0,0),(119,'dddddddddd',29,1,1510054661,'0',2,0,0,'','',0,0,'',0,0,0),(120,'eeeeeeeeee',29,1,1510054661,'0',2,0,0,'','',0,0,'',0,0,0),(121,'fffffffffff',29,1,1510054661,'0',2,0,0,'','',0,0,'',0,0,0),(122,'ggggggggg',29,1,1510054661,'0',2,0,0,'','',0,0,'',0,0,0),(123,'hhhhhhhh',29,1,1510054661,'0',2,0,0,'','',0,0,'',0,0,0),(127,'dddddddddd',25,1,1510112304,'0',2,0,0,'','',0,0,'',0,0,0),(128,'eeeeeeeeee',25,1,1510112304,'0',2,0,0,'','',0,0,'',0,0,0),(129,'fffffffffff',25,1,1510112304,'0',2,0,0,'','',0,0,'',0,0,0),(130,'ggggggggg',25,1,1510112304,'0',2,0,0,'','',0,0,'',0,0,0),(131,'hhhhhhhh',25,1,1510112304,'0',2,0,0,'','',0,0,'',0,0,0),(132,'aaaaaa',25,1,1510112325,'0',2,0,0,'','',0,0,'',0,0,0),(133,'bbbbbbbb',25,1,1510112325,'0',2,0,0,'','',0,0,'',0,0,0),(134,'cccccccc',25,1,1510112325,'0',2,0,0,'','',0,0,'',0,0,0),(135,'dddddddddd',25,1,1510112325,'0',2,0,0,'','',0,0,'',0,0,0),(136,'eeeeeeeeee',25,1,1510112325,'0',2,0,0,'','',0,0,'',0,0,0),(137,'fffffffffff',25,1,1510112325,'0',2,0,0,'','',0,0,'',0,0,0),(138,'ggggggggg',25,1,1510112325,'0',2,0,0,'','',0,0,'',0,0,0),(139,'hhhhhhhh',25,1,1510112325,'0',2,0,0,'','',0,0,'',0,0,0),(141,'bbbbbbbb',34,1,1510112745,'0',2,0,0,'','',0,0,'',0,0,0),(142,'cccccccc',34,1,1510112745,'0',2,0,0,'','',0,0,'',0,0,0),(143,'dddddddddd',34,1,1510112745,'0',2,0,0,'','',0,0,'',0,0,0),(144,'eeeeeeeeee',34,1,1510112745,'0',2,0,0,'','',0,0,'',0,0,0),(147,'hhhhhhhh',34,1,1510112745,'0',2,0,0,'','',0,0,'',0,0,0),(148,'aaaaaa',36,1,1510124171,'0',2,0,0,'','',0,0,'',0,0,0),(149,'bbbbbbbb',36,1,1510124171,'0',2,0,0,'','',0,0,'',0,0,0),(150,'cccccccc',36,1,1510124171,'0',2,0,0,'','',0,0,'',0,0,0),(151,'dddddddddd',36,1,1510124171,'0',2,0,0,'','',0,0,'',0,0,0),(152,'eeeeeeeeee',36,1,1510124171,'0',2,0,0,'','',0,0,'',0,0,0),(153,'fffffffffff',36,1,1510124171,'0',2,0,0,'','',0,0,'',0,0,0),(154,'ggggggggg',36,1,1510124171,'0',2,0,0,'','',0,0,'',0,0,0),(155,'hhhhhhhh',36,1,1510124171,'0',2,0,0,'','',0,0,'',0,0,0),(156,'aaaaaa',36,1,1510126639,'0',2,0,0,'','',0,0,'',0,0,0),(157,'bbbbbbbb',36,1,1510126639,'0',2,0,0,'','',0,0,'',0,0,0),(158,'cccccccc',36,1,1510126639,'0',2,0,0,'','',0,0,'',0,0,0),(159,'dddddddddd',36,1,1510126639,'0',2,0,0,'','',0,0,'',0,0,0),(160,'eeeeeeeeee',36,1,1510126639,'0',2,0,0,'','',0,0,'',0,0,0),(161,'fffffffffff',36,1,1510126639,'0',2,0,0,'','',0,0,'',0,0,0),(162,'ggggggggg',36,1,1510126639,'0',2,0,0,'','',0,0,'',0,0,0),(163,'hhhhhhhh',36,1,1510126639,'0',2,0,0,'','',0,0,'',0,0,0),(164,'ggggggggg',34,1,1510112745,'116006418940',2,0,0,'','',1,1510563876,'',2130706433,0,0),(165,'bbbbbbbb',25,1,1510112304,'116006418940',2,0,0,'','',1,1510566380,'',2130706433,0,0),(166,'asdasdasd1',30,1,1510566677,'116006418940',2,0,0,'','',1,1510566677,'统一码',2130706433,0,0),(167,'5a0a4a0e44c0a',35,1,1510623758,'116017686241',2,1,1,'1区','角色1',1,1510623758,'积分礼包发送到游戏',2130706433,0,0),(168,'aaaaaa',70,1,1510623862,'0',2,0,0,'','',0,0,'',0,0,0),(169,'bbbbbbbb',70,1,1510623862,'0',2,0,0,'','',0,0,'',0,0,0),(170,'cccccccc',70,1,1510623862,'0',2,0,0,'','',0,0,'',0,0,0),(171,'dddddddddd',70,1,1510623862,'0',2,0,0,'','',0,0,'',0,0,0),(172,'eeeeeeeeee',70,1,1510623862,'0',2,0,0,'','',0,0,'',0,0,0),(173,'fffffffffff',70,1,1510623862,'0',2,0,0,'','',0,0,'',0,0,0),(174,'ggggggggg',70,1,1510623862,'0',2,0,0,'','',0,0,'',0,0,0),(175,'hhhhhhhh',70,1,1510623862,'0',2,0,0,'','',0,0,'',0,0,0),(176,'aaaaaa',71,1,1510624115,'0',2,0,0,'','',0,0,'',0,0,0),(177,'bbbbbbbb',71,1,1510624115,'0',2,0,0,'','',0,0,'',0,0,0),(178,'cccccccc',71,1,1510624115,'0',2,0,0,'','',0,0,'',0,0,0),(179,'dddddddddd',71,1,1510624115,'0',2,0,0,'','',0,0,'',0,0,0),(180,'eeeeeeeeee',71,1,1510624115,'0',2,0,0,'','',0,0,'',0,0,0),(181,'fffffffffff',71,1,1510624115,'0',2,0,0,'','',0,0,'',0,0,0),(182,'ggggggggg',71,1,1510624115,'0',2,0,0,'','',0,0,'',0,0,0),(184,'hhhhhhhh',71,1,1510624115,'116017686241',2,0,0,'','',1,1510624940,'',2130706433,0,0),(185,'5a0a5ced048b2',4,1,1510628589,'116017686241',2,0,0,'服务器1','昵称',1,1510628589,'vip礼包',2130706433,9730827,1),(186,'ggggggggg',25,1,1510051134,'116017686241',2,0,0,'','',1,1510630229,'',2130706433,0,0),(187,'aaaaaa',34,1,1510112745,'116017686241',2,0,0,'哈哈','屠龙刀',1,1510630233,'',2130706433,0,0),(188,'asdasdasd1',30,1,1510630236,'116017686241',2,0,0,'','',1,1510630236,'统一码',2130706433,0,0),(189,'asdasdasd1',30,1,1511150157,'125057940931',2,0,0,'','',1,1511150157,'统一码',2130706433,0,0),(190,'aaaaaa',25,1,1510112304,'125061986269',2,0,0,'','',1,1511236110,'',2130706433,0,0),(191,'asdasdasd1',30,1,1511425018,'125067944037',2,0,0,'','',1,1511425018,'统一码',1875661242,0,0),(192,'asdasdasd1',30,1,1512022807,'125082927699',2,0,0,'','',1,1512022807,'统一码',1875661236,0,0),(193,'dddddddddd',25,1,1510051134,'125095813220',2,0,0,'','',1,1512637911,'',1875661236,0,0),(194,'fffffffffff',34,1,1510112745,'125095813220',2,0,0,'','',1,1512637914,'',1875661236,0,0),(195,'bbbbbbbb',25,1,1510051134,'125139987895',2,0,0,'','',1,1513509359,'',1875661242,0,0),(196,'cccccccc',25,1,1510112304,'125140983489',2,0,0,'','',1,1515035659,'',1875661234,0,0);
/*!40000 ALTER TABLE `gift_card` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_access_tokens` (
  `access_token` varchar(40) CHARACTER SET utf8mb4 NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`access_token`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
INSERT INTO `oauth_access_tokens` VALUES ('59ccc07990f30','111','2017-09-28 09:44:01'),('59ccc08291ae8','111','2017-09-28 09:44:10'),('59ccc094a47b0','111','2017-09-28 09:44:28'),('59ccc09f50bd8','111','2017-09-28 09:44:39'),('59ccc0a8a5f20','111','2017-09-28 09:44:48'),('59ccc0a90f4f8','111','2017-09-28 09:44:49'),('59ccc0a96ec50','111','2017-09-28 09:44:49'),('59ccc1cf8e820','111','2017-09-28 09:49:43'),('6ae018a134282b8e64eb4ea85840ad0d','111','2017-09-30 06:45:09');
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_authorization_codes`
--

DROP TABLE IF EXISTS `oauth_authorization_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_authorization_codes` (
  `authorization_code` varchar(40) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`authorization_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_authorization_codes`
--

LOCK TABLES `oauth_authorization_codes` WRITE;
/*!40000 ALTER TABLE `oauth_authorization_codes` DISABLE KEYS */;
INSERT INTO `oauth_authorization_codes` VALUES ('59cca019d3998','111','2017-09-28 07:19:13'),('59cca03571b30','111','2017-09-28 07:19:41'),('59cca0431d3a0','111','2017-09-28 07:19:55'),('59cca087584f0','111','2017-09-28 07:21:03'),('59cca089d9b40','111','2017-09-28 07:21:05'),('59cca08a3a478','111','2017-09-28 07:21:06'),('59cca113d7048','111','2017-09-28 07:23:23'),('59cca115bc680','111','2017-09-28 07:23:25'),('59cca1b5a95d0','111','2017-09-28 07:26:05'),('59ccb0c866b68','111','2017-09-28 08:30:24'),('59ccb0c906c40','111','2017-09-28 08:30:25'),('59ccc9e9e1840','111','2017-09-28 10:17:37');
/*!40000 ALTER TABLE `oauth_authorization_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_channel`
--

DROP TABLE IF EXISTS `pay_channel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_channel` (
  `channel_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '渠道ID',
  `channel_name` varchar(50) NOT NULL COMMENT '渠道名称',
  `channel_intro` varchar(250) NOT NULL COMMENT '渠道详细介绍',
  `channel_seque` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '渠道排序，倒序',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否启用,1是，0否',
  `channel_tips` text COMMENT '充值渠道说明',
  PRIMARY KEY (`channel_id`),
  KEY `is_enable` (`is_enable`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='充值渠道信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_channel`
--

LOCK TABLES `pay_channel` WRITE;
/*!40000 ALTER TABLE `pay_channel` DISABLE KEYS */;
INSERT INTO `pay_channel` VALUES (1,'支付宝','支付宝h5',1,1,'支付宝充值'),(2,'微信','微信支付SDK',0,1,'微信支付SDK'),(3,'支付宝','支付宝SDK',0,0,'支付宝SDK'),(4,'银联支付','银联支付',0,1,'银联支付'),(5,'汇付宝','汇付宝充值',1,0,'汇付宝充值汇付宝充值');
/*!40000 ALTER TABLE `pay_channel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_orders`
--

DROP TABLE IF EXISTS `pay_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  KEY `ktuid` (`ktuid`),
  KEY `ktuid_2` (`ktuid`,`appid`,`payOrderTime`)
) ENGINE=InnoDB AUTO_INCREMENT=229 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_orders`
--

LOCK TABLES `pay_orders` WRITE;
/*!40000 ALTER TABLE `pay_orders` DISABLE KEYS */;
INSERT INTO `pay_orders` VALUES (223,'8171207113119555252108726994',125095701782,1011,'official','4200000001201712079619592417','RMB',0.01,0.01,3,'101','半岛铁盒',10,'901','10','9011','1区','wxpay','2017-12-07 11:31:24','2017-12-07 11:31:26','com.sina.t1g60','60个元宝','60个元宝','','哈哈哈','111.204.81.180','2017-12-07 03:31:19'),(224,'8171207113200485057988122870',125095701782,1011,'official','','RMB',0.01,0.01,1,'101','半岛铁盒',10,'901','10','9011','1区','wxpay',NULL,NULL,'com.sina.t1g60','60个元宝','60个元宝','','哈哈哈','111.204.81.180','2017-12-07 03:32:00'),(225,'8171207113207554952491777672',125095813220,1011,'official','4200000010201712079619633753','RMB',0.01,0.01,3,'101','半岛铁盒',10,'901','10','9011','1区','wxpay','2017-12-07 11:32:13','2017-12-07 11:32:14','com.sina.t1g60','60个元宝','60个元宝','','哈哈哈','111.204.81.180','2017-12-07 03:32:07'),(226,'8171207113231102101106094981',125095813220,1011,'official','2017120721001004500200272276','RMB',0.01,0.01,3,'101','半岛铁盒',10,'901','10','9011','1区','alipay','2017-12-07 11:34:40','2017-12-07 11:34:40','com.sina.t1g60','60个元宝','60个元宝','','哈哈哈','111.204.81.180','2017-12-07 03:32:32'),(227,'8171207171010504897987343894',125095701782,1011,'official','','RMB',0.01,0.01,1,'101','半岛铁盒',10,'901','10','9011','1区','wxpay',NULL,NULL,'com.sina.t1g60','60个元宝','60个元宝','','哈哈哈','111.204.81.180','2017-12-07 09:10:10'),(228,'8171207171512485249512592148',125095813220,1011,'official','','RMB',0.01,0.01,1,'101','半岛铁盒',10,'901','10','9011','1区','wxpay',NULL,NULL,'com.sina.t1g60','60个元宝','60个元宝','','哈哈哈','111.204.81.180','2017-12-07 09:15:12');
/*!40000 ALTER TABLE `pay_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `share_log`
--

DROP TABLE IF EXISTS `share_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `share_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL COMMENT '游戏id',
  `share_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '分享时间',
  `ip` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `share_log`
--

LOCK TABLES `share_log` WRITE;
/*!40000 ALTER TABLE `share_log` DISABLE KEYS */;
INSERT INTO `share_log` VALUES (1,123,'2017-10-18 02:49:37','127.0.0.1'),(2,123,'2017-10-18 02:49:43','127.0.0.1'),(3,123,'2017-10-18 02:49:43','127.0.0.1'),(4,123,'2017-10-18 02:49:44','127.0.0.1'),(5,123,'2017-10-18 02:49:44','127.0.0.1'),(6,123,'2017-10-18 03:12:58','127.0.0.1'),(7,123,'2017-10-18 03:12:59','127.0.0.1'),(8,123,'2017-10-18 03:12:59','127.0.0.1'),(9,123,'2017-10-18 03:12:59','127.0.0.1'),(10,123,'2017-10-18 03:13:00','127.0.0.1'),(11,123,'2017-10-18 03:13:00','127.0.0.1'),(12,123,'2017-10-18 03:13:00','127.0.0.1'),(13,123,'2017-10-18 03:13:01','127.0.0.1'),(14,123,'2017-10-18 03:13:01','127.0.0.1'),(15,123,'2017-10-18 03:13:02','127.0.0.1'),(16,1011,'2017-12-06 04:09:01','111.204.81.180'),(17,1011,'2017-12-06 06:12:36','111.204.81.180'),(18,1011,'2017-12-06 06:27:34','111.204.81.180'),(19,1011,'2017-12-06 06:30:41','111.204.81.180'),(20,1011,'2017-12-06 06:34:38','111.204.81.180'),(21,1011,'2017-12-06 06:35:27','111.204.81.180'),(22,1011,'2017-12-06 06:36:32','111.204.81.180'),(23,1011,'2017-12-06 06:37:16','111.204.81.180'),(24,1011,'2017-12-06 06:43:43','111.204.81.180'),(25,1011,'2017-12-06 06:44:40','111.204.81.180'),(26,1011,'2017-12-06 06:45:36','111.204.81.180'),(27,1011,'2017-12-06 12:43:57','111.204.81.180');
/*!40000 ALTER TABLE `share_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_action_log`
--

DROP TABLE IF EXISTS `sys_action_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='操作日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_action_log`
--

LOCK TABLES `sys_action_log` WRITE;
/*!40000 ALTER TABLE `sys_action_log` DISABLE KEYS */;
INSERT INTO `sys_action_log` VALUES (1,10,'default','GameItem','修改游戏商品,游戏名:三国志大战1,游戏ID1011','{\"0\":\"\\u4fee\\u6539PackageId:com.sina,\\u539f\\u4e3a:com.sina\",\"newcamera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,0.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"2\",\"itemId\":\"com.sina.t2g100\",\"tire\\u4ef7\\u683c\":\"2\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}],\"camera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,0.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"2\",\"itemId\":\"com.sina.t2g100\",\"tire\\u4ef7\\u683c\":\"2\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}]}','admin','2017-10-20 15:53:03'),(2,10,'default','GameItem','修改游戏商品,游戏名:三国志大战1,游戏ID1011','{\"0\":\"\\u4fee\\u6539PackageId:com.sina,\\u539f\\u4e3a:com.sina\",\"newcamera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,0.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"2\",\"itemId\":\"com.sina.t2g100\",\"tire\\u4ef7\\u683c\":\"2\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}],\"camera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,0.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"2\",\"itemId\":\"com.sina.t2g100\",\"tire\\u4ef7\\u683c\":\"2\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}]}','admin','2017-10-20 16:18:25'),(3,10,'default','GameItem','修改游戏商品,游戏名:三国志大战1,游戏ID1011','{\"0\":\"\\u4fee\\u6539PackageId:com.sina,\\u539f\\u4e3a:com.sina\",\"newcamera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,0.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"2\",\"itemId\":\"com.sina.t2g100\",\"tire\\u4ef7\\u683c\":\"2\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}],\"camera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,0.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"2\",\"itemId\":\"com.sina.t2g100\",\"tire\\u4ef7\\u683c\":\"2\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}]}','admin','2017-10-20 16:18:33'),(4,10,'default','GameItem','修改游戏商品,游戏名:三国志大战1,游戏ID1011','{\"0\":\"\\u4fee\\u6539PackageId:com.sina,\\u539f\\u4e3a:com.sina\",\"newcamera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,0.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.t2g100\",\"tire\\u4ef7\\u683c\":\"2\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}],\"camera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,0.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"2\",\"itemId\":\"com.sina.t2g100\",\"tire\\u4ef7\\u683c\":\"2\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}]}','admin','2017-10-20 16:25:43'),(5,10,'default','GameItem','修改游戏商品,游戏名:三国志大战1,游戏ID1011','{\"0\":\"\\u4fee\\u6539PackageId:com.sina,\\u539f\\u4e3a:com.sina\",\"newcamera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,0.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.t1g601\",\"tire\\u4ef7\\u683c\":\"2\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}],\"camera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,0.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.t2g100\",\"tire\\u4ef7\\u683c\":\"2\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}]}','admin','2017-10-20 16:27:10'),(6,10,'default','GameItem','修改游戏商品,游戏名:三国志大战1,游戏ID1011','{\"0\":\"\\u4fee\\u6539PackageId:com.sina,\\u539f\\u4e3a:com.sina\",\"newcamera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,0.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.t7g100\",\"tire\\u4ef7\\u683c\":\"7\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}],\"camera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,0.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.t1g601\",\"tire\\u4ef7\\u683c\":\"2\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}]}','admin','2017-10-20 16:27:27'),(7,10,'default','GameItem','修改游戏商品,游戏名:三国志大战1,游戏ID1011','{\"0\":\"\\u4fee\\u6539PackageId:com.sina,\\u539f\\u4e3a:com.sina\",\"newcamera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,11.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.t7g100\",\"tire\\u4ef7\\u683c\":\"7\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}],\"camera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,0.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.t7g100\",\"tire\\u4ef7\\u683c\":\"7\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}]}','admin','2017-10-20 16:27:35'),(8,10,'default','GameItem','修改游戏商品,游戏名:三国志大战1,游戏ID1011','{\"0\":\"\\u4fee\\u6539PackageId:com.sina,\\u539f\\u4e3a:com.sina\",\"newcamera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,11.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.t7g100\",\"tire\\u4ef7\\u683c\":\"7\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,122.00\",\"\\u4ef7\\u683c\":\"10.00\"}],\"camera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,11.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.t7g100\",\"tire\\u4ef7\\u683c\":\"7\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,0.00\",\"\\u4ef7\\u683c\":\"10.00\"}]}','admin','2017-10-20 16:27:43'),(9,10,'default','GameItem','修改游戏商品,游戏名:三国志大战1,游戏ID1011','{\"0\":\"\\u4fee\\u6539PackageId:com.sina,\\u539f\\u4e3a:com.sina\",\"newcamera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,11.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.t7g100\",\"tire\\u4ef7\\u683c\":\"7\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,122.00\",\"\\u4ef7\\u683c\":\"10.00\"},{\"refName\":\"\\u6708\\u5361\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.m1t3g300\",\"tire\\u4ef7\\u683c\":\"3\",\"\\u7c7b\\u578b\":\"Non-renewing\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"300,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"11.00,123.00\",\"\\u4ef7\\u683c\":\"11.00\"}],\"camera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,11.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.t7g100\",\"tire\\u4ef7\\u683c\":\"7\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,122.00\",\"\\u4ef7\\u683c\":\"10.00\"}]}','admin','2017-10-20 16:28:48'),(10,10,'default','GameItem','修改游戏商品,游戏名:三国志大战1,游戏ID1011','{\"0\":\"\\u4fee\\u6539PackageId:com.sina,\\u539f\\u4e3a:com.sina\",\"newcamera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,11.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.t7g100\",\"tire\\u4ef7\\u683c\":\"7\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,122.00\",\"\\u4ef7\\u683c\":\"10.00\"},{\"refName\":\"\\u6708\\u5361\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.m1t3g300\",\"tire\\u4ef7\\u683c\":\"3\",\"\\u7c7b\\u578b\":\"Non-renewing-w\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"300,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"11.00,123.00\",\"\\u4ef7\\u683c\":\"11.00\"}],\"camera\":[{\"refName\":\"60\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2\",\"itemId\":\"com.sina.t1g60\",\"tire\\u4ef7\\u683c\":\"1\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"60,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"6.00,11.00\",\"\\u4ef7\\u683c\":\"6.00\"},{\"refName\":\"100\\u4e2a\\u5143\\u5b9d\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.t7g100\",\"tire\\u4ef7\\u683c\":\"7\",\"\\u7c7b\\u578b\":\"Consumable\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"100,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"10.00,122.00\",\"\\u4ef7\\u683c\":\"10.00\"},{\"refName\":\"\\u6708\\u5361\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.sina.m1t3g300\",\"tire\\u4ef7\\u683c\":\"3\",\"\\u7c7b\\u578b\":\"Non-renewing\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"300,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"11.00,123.00\",\"\\u4ef7\\u683c\":\"11.00\"}]}','admin','2017-10-20 16:31:19'),(11,10,'default','GameItem','修改游戏商品,游戏名:三国志大战2,游戏ID10023','{\"0\":\"\\u4fee\\u6539PackageId:com.jet,\\u539f\\u4e3a:\",\"newcamera\":[{\"refName\":\"\\u6708\\u5361\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.jet.m1t2g300\",\"tire\\u4ef7\\u683c\":\"2\",\"\\u7c7b\\u578b\":\"Non-renewing\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"300,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"30.00,5.00\",\"\\u4ef7\\u683c\":\"30.00\"}],\"camera\":[]}','admin','2017-10-23 11:19:33'),(12,10,'default','GameItem','修改游戏商品,游戏名:三国志大战2,游戏ID10023','{\"0\":\"\\u4fee\\u6539PackageId:com.jet,\\u539f\\u4e3a:com.jet\",\"newcamera\":[{\"refName\":\"\\u6708\\u5361\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"2,4\",\"itemId\":\"com.jet.m1t2g300\",\"tire\\u4ef7\\u683c\":\"2\",\"\\u7c7b\\u578b\":\"Non-renewing\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"300,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"30.00,5.00\",\"\\u4ef7\\u683c\":\"30.00\"}],\"camera\":[{\"refName\":\"\\u6708\\u5361\",\"\\u5145\\u503c\\u6e20\\u9053ID\":\"1,2,4\",\"itemId\":\"com.jet.m1t2g300\",\"tire\\u4ef7\\u683c\":\"2\",\"\\u7c7b\\u578b\":\"Non-renewing\",\"\\u6e38\\u620f\\u8d27\\u5e01,\\u5355\\u4f4d\":\"300,\\u91d1\\u5e01\",\"\\u5217\\u8868\\u663e\\u793a\":\"\\u662f\",\"\\u4eba\\u6c11\\u5e01\\u548c\\u7f8e\\u5143\\u4ef7\\u683c\":\"30.00,5.00\",\"\\u4ef7\\u683c\":\"30.00\"}]}','admin','2017-10-23 11:21:16');
/*!40000 ALTER TABLE `sys_action_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_config`
--

DROP TABLE IF EXISTS `sys_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `segment` varchar(30) NOT NULL COMMENT '分类',
  `name` varchar(255) NOT NULL,
  `type` enum('json','string','float','int','object') NOT NULL,
  `value` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统配置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_config`
--

LOCK TABLES `sys_config` WRITE;
/*!40000 ALTER TABLE `sys_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_privilege`
--

DROP TABLE IF EXISTS `sys_privilege`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_privilege`
--

LOCK TABLES `sys_privilege` WRITE;
/*!40000 ALTER TABLE `sys_privilege` DISABLE KEYS */;
INSERT INTO `sys_privilege` VALUES (1,'index','m','首页','','','index','','',''),(10,'privilege','m','用户、权限管理','','','user','','','del'),(12,'system','m','系统设置','','','admin','','','add,update'),(116,'sysuser','m','后台用户管理','','','sysuser','','',''),(127,'game','m','游戏管理','','','game_manage','','','read,add,del,update'),(128,'pay','m','充值管理','','','pay_manage','','','read,add,update');
/*!40000 ALTER TABLE `sys_privilege` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_role`
--

DROP TABLE IF EXISTS `sys_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL COMMENT '角色名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='角色表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_role`
--

LOCK TABLES `sys_role` WRITE;
/*!40000 ALTER TABLE `sys_role` DISABLE KEYS */;
INSERT INTO `sys_role` VALUES (1,'超级管理员'),(29,'网站编辑'),(30,'草稿录入'),(31,'CPS代理'),(32,'CPS管理');
/*!40000 ALTER TABLE `sys_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_role_privilege`
--

DROP TABLE IF EXISTS `sys_role_privilege`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_role_privilege` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL COMMENT '和lmb_role表id字段对应',
  `privilege_id` int(10) unsigned NOT NULL COMMENT '和lmb_privilege表id字段对应',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`,`privilege_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1427 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='角色和权限对应关系表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_role_privilege`
--

LOCK TABLES `sys_role_privilege` WRITE;
/*!40000 ALTER TABLE `sys_role_privilege` DISABLE KEYS */;
INSERT INTO `sys_role_privilege` VALUES (1422,1,1),(1424,1,10),(1425,1,12),(1426,1,116),(1419,1,121),(1420,1,125),(1421,1,127),(1423,1,128),(1352,29,115),(1375,30,1),(1376,30,119),(1377,30,120),(1409,31,1),(1405,31,122),(1406,31,123),(1407,31,124),(1408,31,126),(1411,32,1),(1410,32,121);
/*!40000 ALTER TABLE `sys_role_privilege` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_user`
--

DROP TABLE IF EXISTS `sys_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_user`
--

LOCK TABLES `sys_user` WRITE;
/*!40000 ALTER TABLE `sys_user` DISABLE KEYS */;
INSERT INTO `sys_user` VALUES (1,'bossmanager','60860627d939017b5d02866ccab695da','qiaochenglei@163.com','13401146796','male',1,'管理员','',0.00,''),(5,'draft_test','d9b1d7db4cd6e70935368a1efb10e377','130702198401291248','12','male',1,'tes','\r\n{\r\n  \"shortcuts\":[\r\n   { \"url\":\"?m=index&c=home&a=modify\", \"icon\":\"signal\",\"btnClass\":\"btn-success\"},\r\n   { \"url\":\"?m=virtue&c=index\",  \"icon\":\"pencil\", \"btnClass\":\"btn-info\"},\r\n   { \"url\":\"?m=user&c=index\",  \"icon\":\"users\", \"btnClass\":\"btn-warning\"}\r\n  ]\r\n}',18.90,'123'),(6,'mayao','901d26c637722905bd8e62249739cd2c','','','female',1,'马瑶',NULL,0.00,''),(8,'lining','14e1b600b1fd579f47433b88e8d85291','','','female',1,'李宁',NULL,0.00,''),(10,'admin','14e1b600b1fd579f47433b88e8d85291','qclei1@qq.com','13401146796','male',1,'管理员','',0.00,''),(12,'test1','f4cc399f0effd13c888e310ea2cf5399','13@qq.com','13810358824','female',6,'测试代理商','',0.00,''),(16,'dutianxiao01','14e1b600b1fd579f47433b88e8d85291','pla0459@foxmail.com','15164541818','male',6,'杜天笑','',571.40,'232331198507221219'),(20,'dongyuanxi01','14e1b600b1fd579f47433b88e8d85291','123456@qq.com','','female',6,'董原希',NULL,613.00,'220203198312245128'),(22,'fenbao','14e1b600b1fd579f47433b88e8d85291','123456@qq.com','','female',10,'fenbao',NULL,176.40,''),(26,'t2','60860627d939017b5d02866ccab695da','21','23','female',1,'52',NULL,0.00,'bossmanager');
/*!40000 ALTER TABLE `sys_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_user_role`
--

DROP TABLE IF EXISTS `sys_user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '对应lmb_user表id字段',
  `role_id` int(10) unsigned NOT NULL COMMENT '对应lmb_role表id字段',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='用户和角色对应关系表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_user_role`
--

LOCK TABLES `sys_user_role` WRITE;
/*!40000 ALTER TABLE `sys_user_role` DISABLE KEYS */;
INSERT INTO `sys_user_role` VALUES (76,0,31),(84,0,31),(88,0,31),(92,0,31),(100,0,31),(102,0,31),(48,1,1),(27,3,29),(10,4,29),(104,5,31),(65,6,31),(66,6,32),(68,8,31),(70,8,32),(74,10,1),(105,12,31),(86,16,31),(90,20,31),(96,22,31);
/*!40000 ALTER TABLE `sys_user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` bigint(11) NOT NULL,
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `device` varchar(100) NOT NULL DEFAULT '' COMMENT '设备号',
  `lv` int(11) NOT NULL DEFAULT '0' COMMENT '等级？积分？',
  `vip` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `headimgurl` varchar(100) NOT NULL DEFAULT '' COMMENT '头像地址',
  `open_id` varchar(65) NOT NULL DEFAULT '' COMMENT '微信openid,只有微信用户会有',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (8534098,'','','18731366664','',0,0,'',''),(113038482020,'e10adc3949ba59abbe56e057f20f883e','','18733631730','',0,0,'',''),(116025893129,'','','13269275270','',0,0,'',''),(125067827087,'','2559479833@sina','','',0,0,'',''),(125067883433,'','oOQ5Hwm6M81y9iHmYNVBn129gtnw@b','','',0,0,'','ozzPxvjtr5zttbEblmIBBk39MxQc'),(125067944037,'','C9B4F6F5BBF93109A64410C79F2447','','',0,0,'http://q.qlogo.cn/qqapp/101432559/C9B4F6F5BBF93109A64410C79F244784/100',''),(125080075136,'','2559479833@sina','','',0,0,'',''),(125082927699,'','2559479833@sina','','',0,0,'',''),(125088628319,'','','18600557843','',0,0,'',''),(125094440516,'','2559479833@sina','','',0,0,'',''),(125095392456,'','C4B7A287C8F3982088E4100B5B9986','','',0,0,'http://q.qlogo.cn/qqapp/101432559/C4B7A287C8F3982088E4100B5B9986A8/100',''),(125095701782,'','oOQ5Hwm6M81y9iHmYNVBn129gtnw@b','','',0,0,'','ozzPxvjtr5zttbEblmIBBk39MxQc'),(125095813220,'','C9B4F6F5BBF93109A64410C79F2447','','',0,0,'http://q.qlogo.cn/qqapp/101432559/C9B4F6F5BBF93109A64410C79F244784/100',''),(125096433442,'','6C1964627BB6C919C5A3D91A0F0991','','',0,0,'http://q.qlogo.cn/qqapp/101432559/6C1964627BB6C919C5A3D91A0F0991B8/100',''),(125096891731,'','oOQ5HwqNfDEuGhbPjP4lk6z6cSmA@baidu','','',0,0,'',''),(125110711054,'e99a18c428cb38d5f260853678922e03','yk125110711054','13811055278','',0,0,'',''),(125112040261,'','','18611802317','',0,0,'',''),(125112966141,'8ee494a1acad1d0204237a44fe097880','','13701196763','',0,0,'',''),(125114166042,'','','13611320730','',0,0,'',''),(125123513322,'','oOQ5HwumZ2Odd__Ghhx3vFUCNNkA@baidu','','',0,0,'',''),(125139199197,'200820e3227815ed1756a6b531e7e0d2','','13426277816','',0,0,'',''),(125139570782,'','2559479833@sina','','',0,0,'',''),(125139779349,'','2406754303@sina','','',0,0,'',''),(125139923753,'','C9B4F6F5BBF93109A64410C79F244784@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/C9B4F6F5BBF93109A64410C79F244784/100',''),(125139987895,'','2112F8E808FADA4977DCF9143E8EF3C0@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/942FEA70050EEAFBD4DCE2C1FC775E/100',''),(125140137216,'','3451C84258A0E95697A42C61D8D58DC3@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/3451C84258A0E95697A42C61D8D58DC3/100',''),(125140200337,'','C4B7A287C8F3982088E4100B5B9986A8@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/C4B7A287C8F3982088E4100B5B9986A8/100',''),(125140282002,'','','18911555199','',0,0,'',''),(125140342328,'','oOQ5HwpMBOVhu1dJU7NowwURXlRg@baidu','','',0,0,'','ozzPxvq6FiQaP9hhEPBAr2WPRmcs'),(125140479598,'','oOQ5HwqNfDEuGhbPjP4lk6z6cSmA@baidu','','',0,0,'','ozzPxvm69q_jHbTiEW0no8tBApJA'),(125140611600,'','6C1964627BB6C919C5A3D91A0F0991B8@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/6C1964627BB6C919C5A3D91A0F0991B8/100',''),(125140734748,'','B649B6F691E9424ADC5D95049E5E0E6F@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/B649B6F691E9424ADC5D95049E5E0E6F/100',''),(125140822605,'','oOQ5HwoiSh0rsXy6Gqx00eI2TLBM@baidu','','',0,0,'','ozzPxvhDs_ixa8Z0t80Rsb31bPfk'),(125140894626,'','','15846539141','',0,0,'',''),(125140983489,'','D812EA8C208288FB0816A051863CB22E@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/D812EA8C208288FB0816A051863CB22E/100',''),(125141063368,'','oOQ5Hwl9Y_SEXf8dHnP8GOKpGoJc@baidu','','',0,0,'','ozzPxvivphtcYVPvBVMKPgnXc7js'),(125141296791,'','8138510ED970B042C1739336E95C5072@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/8138510ED970B042C1739336E95C5072/100',''),(125141400525,'','oOQ5HwsDUnJbUBWTeR6AiVX3ysvk@baidu','','',0,0,'','ozzPxvjFbWXeQvgb8o6LniO0_ods'),(125141477653,'','11A10AFBC0EAA463F4A6F52EE7484752@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/11A10AFBC0EAA463F4A6F52EE7484752/100',''),(125141580845,'','oOQ5HwtNlc6KSptElMDYQ4yirv2c@baidu','','',0,0,'','ozzPxvoBYLQeWtpgMaA18V0XzJgo'),(125141705596,'','CFB898972587A4F6867F7680D7C084A7@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/CFB898972587A4F6867F7680D7C084A7/100',''),(125141766620,'','oOQ5HwmwyG8Dr4xNS1UV-YXUlHbE@baidu','','',0,0,'','ozzPxvk9wHNvliUMxipd6vHOzoMM'),(125141911015,'','oOQ5HwullhBzwwqt36VFAVt8dQpQ@baidu','','',0,0,'','ozzPxvgZx7V5ae7CMXM3R9IfpAIY'),(125141983264,'','5E8B5C52A067B995AD40F44540065821@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/5E8B5C52A067B995AD40F44540065821/100',''),(125142068127,'','oOQ5HwrA4Nddo1oLLnb92zS2PUFg@baidu','','',0,0,'','ozzPxvgbWNuMDsAV5S-QBwCI5qcg'),(125142225954,'','oOQ5Hwq11HIVpRAWepHluQZ4Cccs@baidu','','',0,0,'','ozzPxvi0qhHvpcE_ZnKbQPC07VLU'),(125142379200,'','oOQ5HwlcBOp_vlTP6Zb8HFmhj2W8@baidu','','',0,0,'','ozzPxvtC9UrltElspAE1xYyjAMPE'),(125142484359,'','','15034540633','',0,0,'',''),(125142601087,'','oOQ5HwpfD3cWhz28_E05NO2HOSdI@baidu','','',0,0,'','ozzPxvtzgrLjLeoyhq9gUTe_74iA'),(125142749300,'','DBD17199D4608B2DB4D2CB7DD2310046@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/942FEA70050EEAFBD4DCE2C1FC775E/100',''),(125142858727,'','oOQ5HwgE8rwSrSHDMeG52dK7qoIY@baidu','','',0,0,'','ozzPxvv3KKVEPAaeL3hwnbca9jR4'),(125142951623,'','oOQ5HwkHgdmBNXwEOF-bPK18qbEo@baidu','','',0,0,'','ozzPxvjmzxHwXluyniEe7aswKcVs'),(125143053546,'','F6EED7137E0041212C46FF208E64C03C@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/F6EED7137E0041212C46FF208E64C03C/100',''),(125143199453,'','oOQ5Hwrcj7N7IxeV6fMP7NL4rXNM@baidu','','',0,0,'','ozzPxvqBqUVNilRiJwDzEkwJHg6E'),(125143272163,'','oOQ5HwlFYzgjduZIhaJb_uNeNe6Y@baidu','','',0,0,'','ozzPxvgVkEpFgDOCGSQtTwXCEfAs'),(125143429829,'','oOQ5HwivYEw4t9n6LxXnR0s6MfHk@baidu','','',0,0,'','ozzPxvtv7zHsDwRAJXXqXsCssYiQ'),(125143534100,'','oOQ5HwoqZ32c5f1iSGU_dh2Vbmew@baidu','','',0,0,'','ozzPxvhgHarj2iR1ZJyOiAYqelFg'),(125143604156,'','D3BF6F8A26638397D43D815F02109372@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/D3BF6F8A26638397D43D815F02109372/100',''),(125143713951,'','oOQ5HwlyoIoJpi96sMdtDTWnQURc@baidu','','',0,0,'','ozzPxvubkgmOiu8zMtxgiVfgtVSs'),(125143780530,'','oOQ5HwnZ_TCGzoPLXJUO46CGffZY@baidu','','',0,0,'','ozzPxvtowTN-FWC6ZLky6ajrqUTo'),(125143863428,'','oOQ5HwtHkgjMXOXzeziA3BFBJDOg@baidu','','',0,0,'','ozzPxvhKB8SQHX-hVXmP-WZj7JRU'),(125143962230,'','oOQ5HwtKs8xjvMuibbxOeYkZI914@baidu','','',0,0,'','ozzPxvv94ikilKjRvumRuw9svQ70'),(125144078584,'','oOQ5HwjHIF2m88Hyxo8NaN8sw30M@baidu','','',0,0,'','ozzPxvq-KpBDGXfQedVdxqbHWOsQ'),(125144175056,'','oOQ5HwgTA55OABHLi8tn-xywIXRM@baidu','','',0,0,'','ozzPxvnfAwdDXdAfX9Dsg47JjJE0'),(125144267969,'','oOQ5Hwq3igTzGBqLGzQ_8XTPenwE@baidu','','',0,0,'','ozzPxvk_KsJafTjLl2xxakbD-q4M'),(125144388336,'','oOQ5Hwri34_Y5FSGQeh87betVviA@baidu','','',0,0,'','ozzPxvuWme8PVr6wT3G9Yd55z9ug'),(125144470037,'','oOQ5Hwn-X26xeRVkNB8Jch4grhnw@baidu','','',0,0,'','ozzPxvgYgGg5_XpQCcth058M5yP4'),(125144604383,'','oOQ5HwmCftllT4zT5nlghUfOZoQo@baidu','','',0,0,'','ozzPxvkjBCHcakHVT8d_UBI8McWM'),(125144737289,'','oOQ5Hwtodi5fePlvNzvqmcEJgavE@baidu','','',0,0,'','ozzPxvufC-RpI7Dx1OAB1WnTy53k'),(125144822208,'','oOQ5Hwg80F0v1y8nOSNO9oyPkdWE@baidu','','',0,0,'','ozzPxvolphLyS-DaynB2VexOV_do'),(125144941753,'','6399248615@sina','','',0,0,'',''),(125145128282,'','oOQ5HwlwikdcYGz8oM3SFEFS-hUY@baidu','','',0,0,'','ozzPxvmkOqXqEgAlOpWyiww3L5DQ'),(125145228515,'','oOQ5Hwlv5tXC8O3a1luVu_5drquA@baidu','','',0,0,'','ozzPxvlH0kAMiRsPa98HbTB-WYto'),(125145351278,'','oOQ5HwufjzkFPJyY6qyus9Dzedns@baidu','','',0,0,'','ozzPxvpEzr_wXVvO2TZGsr4WQjU4'),(125145600260,'','oOQ5Hwg-sbINu39yzkK-4WL3WGp0@baidu','','',0,0,'','ozzPxvvO-eg2_mixFgQxBez9lHEU'),(125145740290,'','oOQ5HwvM9YrPp_RecHWop7JLmOHw@baidu','','',0,0,'','ozzPxvngJKFB_Q4lu5izAZlg3a4g'),(125145885097,'','oOQ5Hwml3aLt-sYr2oKWdCc4ypDE@baidu','','',0,0,'','ozzPxvoAFhihqlOTw0nfHo8VgvAU'),(125145962751,'','oOQ5HwqOylIlc0DvrMxmbdmWgqUI@baidu','','',0,0,'','ozzPxvosz60qXDBxLX9gDl7Y6BIc'),(125146032707,'','6A2087A0A3294888B79EF31F0CD74935@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/6A2087A0A3294888B79EF31F0CD74935/100',''),(125146190277,'','oOQ5HwvNCeI2oZbDIxQ0K7Pw-vnQ@baidu','','',0,0,'','ozzPxvszUV7dkNm2lEYxB4KnbeXg'),(125146253504,'','oOQ5HwleWVJZ3DJI39_pHZhhYbXQ@baidu','','',0,0,'','ozzPxvm0TilNO0RzVKl27g8H2Cw8'),(125146375211,'','oOQ5HwsuRyDKLGaWGx-6L6AfwSX4@baidu','','',0,0,'','ozzPxvl6i4RKwqnGNzrFieZm2kFg'),(125146492354,'','1316416780@sina','','',0,0,'',''),(125146650331,'','oOQ5HwtvV1PFCh1O-wUpGqTuTPY8@baidu','','',0,0,'','ozzPxvk7-L2eEZgCj4qf1nEHo-h0'),(125146774236,'','oOQ5HwiNG9nMuULw4rtL_g9iud0c@baidu','','',0,0,'','ozzPxvqy9uF9Pvc6_zKHJZM2zvDE'),(125146871121,'','5CE2979B34AC9F203E8DE2A971C0A81E@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/5CE2979B34AC9F203E8DE2A971C0A81E/100',''),(125146934322,'','F174F48FF50429570184EC957D4A191D@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/F174F48FF50429570184EC957D4A191D/100',''),(125147081996,'','oOQ5HwtNPi01PbeZPNpFvnykcRRk@baidu','','',0,0,'','ozzPxvr71EjOOQ9vWMDbLoB-NFvs'),(125147223808,'','oOQ5HwkMz3IhZgj9SzqGcDI203UQ@baidu','','',0,0,'','ozzPxvtm6_daeHb9_W3uKW-R1sWg'),(125147308259,'','oOQ5HwsnrJ_QFG3ksHd9DmwV1gDI@baidu','','',0,0,'','ozzPxvkAXS3h2zTJao47EORMuraE'),(125147447843,'','oOQ5HwlAIVruAeuGEIc_nUFRMKIQ@baidu','','',0,0,'','ozzPxvlNoGRTiBmCZfNVk7dG2HRM'),(125147559672,'','D9277CE972D51675979ADDA15F682033@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/D9277CE972D51675979ADDA15F682033/100',''),(125147637033,'','2B9BCC13455DBB42DCD0282A2A0A9CE9@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/2B9BCC13455DBB42DCD0282A2A0A9CE9/100',''),(125147736056,'','','18910396672','',0,0,'',''),(125147815682,'','A674E1A7857C3FC73403903883572B2B@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/A674E1A7857C3FC73403903883572B2B/100',''),(125147893038,'','oOQ5Hwgi1y2WyLcKFZmxkK8GxSa8@baidu','','',0,0,'','ozzPxvvNqY5pJ-df4q0Hh9nxiSl4'),(125148038589,'','oOQ5HwoRlpoq7MQb3Lt2oYyMt65M@baidu','','',0,0,'','ozzPxvnv8dRRGbOef-wUZQLFdDc0'),(125148624610,'','EF161B5CECCB3AB39127B5945C10DE33@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/EF161B5CECCB3AB39127B5945C10DE33/100',''),(125148761095,'','oOQ5HwuBZrXs9JMakg9NXt4LrBAQ@baidu','','',0,0,'','ozzPxvrSoUGGnpbhH8Nc3S-zTLaI'),(125148868085,'','oOQ5Hwm34IRtzmGTJqC8nbovlcco@baidu','','',0,0,'','ozzPxvg5NOx7YgFDd1ZEm4wtlPuk'),(125148942767,'','oOQ5HwsHNr--KXoZoJX1kaALulBo@baidu','','',0,0,'','ozzPxvqfb9joQbv_Sr69Sg3SkFps'),(125149076646,'','oOQ5HwhitSkiaAU_EzhBaQdsDxt4@baidu','','',0,0,'','ozzPxvq6VOom8_uhMFM6k5-MGjTc'),(125149217902,'','oOQ5Hwt7SA7VsCo7pIYdFYudn-Zo@baidu','','',0,0,'','ozzPxvkbYgZqBPmdOElqdDoSlslQ'),(125149352095,'','oOQ5Hwih5usTFMxxR71XoNEI5su0@baidu','','',0,0,'','ozzPxvkn3taCer795nqp_feBVYXE'),(125149438956,'','ECEBC87D34260FC49574759337D0BDE6@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/ECEBC87D34260FC49574759337D0BDE6/100',''),(125149547276,'','AD99DDC3FE3D8083E5DEB8546521E335@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/AD99DDC3FE3D8083E5DEB8546521E335/100',''),(125149676670,'','AD63D7EFB930146F5A3E4452B5A9809C@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/AD63D7EFB930146F5A3E4452B5A9809C/100',''),(125149805856,'','oOQ5HwqbCm_7ZbUMX1tNNTc3bx-w@baidu','','',0,0,'','ozzPxvv0jqsKxzcbVjCCrlvUNP68'),(125150035671,'','oOQ5Hwop9YGEX_EWJadHYEABpJs4@baidu','','',0,0,'','ozzPxvp7qkj37qE_suSeW6662Sg4'),(125150108971,'','oOQ5HwqCG8rU1ODwfV_sCiVuZDlc@baidu','','',0,0,'','ozzPxvtTcP0lotuHmVXyOowbs12Y'),(125150210851,'','oOQ5HwuSz02vpi8hqEz93C6T7W0Y@baidu','','',0,0,'',''),(125150346669,'','','18701329321','',0,0,'',''),(125150474897,'','oOQ5Hwqr88EkjZbpkwQpTP-fBr9s@baidu','','',0,0,'','ozzPxvgIhn-NiLgGM82GGLE5Jan0'),(125150571285,'','oOQ5HwuYUQ45jbmUldlthxvyGPek@baidu','','',0,0,'','ozzPxvpeokEI6ZbwewAbCVOAWzgo'),(125150691540,'','oOQ5Hwp_xK0OT0GPP4hi9lV2drtU@baidu','','',0,0,'','ozzPxvg8qrcP7N9j9TC9g2S7ZNIw'),(125150752816,'','A4E0BF7BAA602FEEE63E6277D5166A63@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/942FEA70050EEAFBD4DCE2C1FC775E/100',''),(125150840212,'','AC57C88EDF2E68DF26495BD3F18FB7A1@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/AC57C88EDF2E68DF26495BD3F18FB7A1/100',''),(125150977572,'','','13601054113','',0,0,'',''),(125151038432,'','oOQ5Hwnj21JnJvpyM4qV-nQBVoSQ@baidu','','',0,0,'','ozzPxvpACiJRxwgKmjWh7EfIEXjU'),(125151153245,'','A3861649CD4B57E6B0DCA00CEA5CB459@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/A3861649CD4B57E6B0DCA00CEA5CB459/100',''),(125151240351,'','46CF2F000DB3A364FB3C4D5CE72E2D1A@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/46CF2F000DB3A364FB3C4D5CE72E2D1A/100',''),(125151361729,'','C6F0F77A4EADA6438913122E6FCF2DD3@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/C6F0F77A4EADA6438913122E6FCF2DD3/100',''),(125151516904,'','3E0BAEFFE6882E8290325FA675AC951F@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/3E0BAEFFE6882E8290325FA675AC951F/100',''),(125151646516,'','oOQ5Hwh2mRgBEkJ1dfaFiSkVuZm0@baidu','','',0,0,'','ozzPxvgPDrysuE3SOqeu__LZFOq0'),(125151715283,'','oOQ5HwnTQuC7sn3lRygTYIaWjXlI@baidu','','',0,0,'','ozzPxvsLxNeICyYMsXWSeOGrZLFI'),(125151825148,'','oOQ5HwiJ1XAZ3E74rhMhAJbAtldg@baidu','','',0,0,'','ozzPxvoXJc7tK6lLGR9Tg7Bxu3HY'),(125151924307,'','oOQ5Hwglpyqc5Ty37AuJNuesXRk0@baidu','','',0,0,'','ozzPxvvi2aR3NwyqLL7mFzF8wDIQ'),(125151983839,'','5A1D8F2437402402B3BEFC96F25C4DBA@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/5A1D8F2437402402B3BEFC96F25C4DBA/100',''),(125152114418,'','oOQ5HwsEWPvGjKnfZzg8dPafIBXY@baidu','','',0,0,'','ozzPxvv6PrHwlr-NXJt24kE6KYwA'),(125152225836,'','14B0223925430522B0D1049528938DB0@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/14B0223925430522B0D1049528938DB0/100',''),(125152316594,'','oOQ5Hwuvy2A6fLwmgqzooy6z-6xM@baidu','','',0,0,'','ozzPxvlEGGhK0iXvSP5ozxadczRs'),(125152442757,'','oOQ5HwjXf09iC0_L9KCPALYWjDdE@baidu','','',0,0,'','ozzPxvvorj5uKzvvLvf67Wmdu7GI'),(125152561655,'','oOQ5HwkxIDImJHVuqES_UZbtMqNE@baidu','','',0,0,'','ozzPxvm8n8p_eJgcXsjyTYOJO29k'),(125152695955,'','oOQ5Hwj31nrvchCxFvJPYY_tvDXU@baidu','','',0,0,'','ozzPxvnoUAkD1tcAuYPOF8QbGuo4'),(125152839867,'','1611F5804C1C93A364C6FCF6F5BA4E81@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/1611F5804C1C93A364C6FCF6F5BA4E81/100',''),(125152935971,'','oOQ5HwsNZR0W1CEYGWF1DLC-OOuk@baidu','','',0,0,'','ozzPxvixj2fGbFYmhTs_zobbSa84'),(125153088797,'','oOQ5HwpWl4JxYTQsDFp5rg27hgn8@baidu','','',0,0,'','ozzPxvnFZv0ZqPju2Eov_djtqChA'),(125153177874,'','9D5F08BA106F8A8C0AE50FE1774A0AAE@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/9D5F08BA106F8A8C0AE50FE1774A0AAE/100',''),(125153267119,'','976A349067A7FB84783D3BE5652BFFE8@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/976A349067A7FB84783D3BE5652BFFE8/100',''),(125153338565,'','oOQ5HwhVA34s-CQiidUABq4AYbUI@baidu','','',0,0,'','ozzPxvqQW52QVriZt2_9NCogorGY'),(125153490127,'','oOQ5Hwnm0g2GLrjippMu4byrMiAs@baidu','','',0,0,'','ozzPxvrQaUrcyrt-oMaxSDVw8ft4'),(125153557161,'','oOQ5Hwuk-0spxE7gen1dNqc3JPa8@baidu','','',0,0,'','ozzPxvhLkv_Z8o2_tdamTd-TfFX4'),(125153656074,'','oOQ5HwqQ-iRbwtKzSs25ZJiu4CMA@baidu','','',0,0,'','ozzPxvmXIcuUxmVV7kOc_uJt8zao'),(125153773366,'','D7E149AA9DCBA6DF52880AF6D8BF6B65@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/D7E149AA9DCBA6DF52880AF6D8BF6B65/100',''),(125153880805,'','oOQ5Hwv4R0DioWnQgYDTjL2qyfJE@baidu','','',0,0,'','ozzPxvuN4cXvgq4EPdS8J_tXWhbM'),(125153985480,'','58A773E074ED9A12903D46DF7EBDAED8@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/58A773E074ED9A12903D46DF7EBDAED8/100',''),(125154101148,'','oOQ5HwmgTQe-dR2ldnHeBlH_j2KU@baidu','','',0,0,'','ozzPxvpcMfAHiGDVbJA2niyv0FIs'),(125154235740,'','oOQ5HwuuELGM2oR33n8xUmQC-IDA@baidu','','',0,0,'','ozzPxvnDLjGmTfqN_AUIQ6yIOfwg'),(125154310182,'','oOQ5HwgdzA3Op8l7DqDgAb2aCZYI@baidu','','',0,0,'','ozzPxvuRsB1JFW45fNCwR18OzNPY'),(125154441805,'','622A356C3B799B3C5C4F157DFF494467@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/622A356C3B799B3C5C4F157DFF494467/100',''),(125154581646,'','CB7C9B3ED81265C7E6B6B7784F326447@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/CB7C9B3ED81265C7E6B6B7784F326447/100',''),(125154711968,'','6BE8F637D5F9AF55EC0F84297BF21773@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/6BE8F637D5F9AF55EC0F84297BF21773/100',''),(125154798986,'','oOQ5HwoIUNJZc5A63PSmKHMLtGOE@baidu','','',0,0,'','ozzPxvvbuoZ-SV1vrw-xufI0FH4Q'),(125154882511,'','6221979764@sina','','',0,0,'',''),(125155015178,'','oOQ5HwmupIn9WLsvD9Y6t8fxjNDQ@baidu','','',0,0,'','ozzPxvuTTv6jPRYuLLzQPk0ZGH7s'),(125155131624,'','oOQ5HwtOxcKK3gKp0fdbkOOfYoNc@baidu','','',0,0,'','ozzPxvuKGvCFHmHGnC8v_P9NZ0mc'),(125155262865,'','oOQ5HwqRoU8JgVc-Lk5MsEmkyF6A@baidu','','',0,0,'','ozzPxviDE-eiVJiwKKRR2mskUHjM'),(125155405574,'','4749E6CE7E0151390B82E2E9D0296C01@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/4749E6CE7E0151390B82E2E9D0296C01/100',''),(125155524601,'','oOQ5Hwt0wgxMTcVxUAlvYHbTt0s8@baidu','','',0,0,'','ozzPxvky0iZudDc0gtwxkgySS7dg'),(125155657907,'','oOQ5HwnKpz1VhSLiXVeiTXIpJ1eI@baidu','','',0,0,'','ozzPxvsxYaZSBd061Zhhc014hN3c'),(125155775459,'','oOQ5HwnDeR5xuO14Gl18OcoZ4-80@baidu','','',0,0,'','ozzPxvuSE6rwQXp-VcHCDODwv3qE'),(125155905896,'','oOQ5HwgHENRM3ThO_DVO3jovuGDk@baidu','','',0,0,'','ozzPxvkDiffAeRkjHoTd5IyKnKy8'),(125156013775,'','61219FF998600D31492D9ACFE7072636@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/61219FF998600D31492D9ACFE7072636/100',''),(125156136542,'','oOQ5HwlhXaKjZUbh2V9wIellt5pc@baidu','','',0,0,'','ozzPxvvMXcCYJsb_RleeNsK7pMDk'),(125156226861,'','oOQ5HwjXsyyT_jKHwyCH6wYCrk5s@baidu','','',0,0,'','ozzPxvrGP_wrNF3K0b_NGCF27uH8'),(125156378528,'','5BD9BDAADC9C45E10ACD96A1B492F8FD@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/5BD9BDAADC9C45E10ACD96A1B492F8FD/100',''),(125156440975,'','C54D7877A35EF2C077BF97E404FA1681@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/C54D7877A35EF2C077BF97E404FA1681/100',''),(125156594405,'','oOQ5Hwk3HO3S0_-4m45Mf5qCLBPA@baidu','','',0,0,'','ozzPxvr1O1yhLB0P14r1SJIijhaQ'),(125156655481,'','oOQ5HwoVASY364c3MEgJgxBDIGK0@baidu','','',0,0,'','ozzPxvt8e0A5WX_I5N5Tj32yE8bI'),(125156787508,'','D08DF14A15D8D2FF13419FD30D8AA2B6@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/D08DF14A15D8D2FF13419FD30D8AA2B6/100',''),(125156928855,'','oOQ5HwpdW4vTIaqD0Zpbsh_g_w84@baidu','','',0,0,'','ozzPxvt-b3Q6oaEeyJMZxDBScdmU'),(125157064913,'','oOQ5Hwlrd_nkS_ESC-TLm2GX4kf0@baidu','','',0,0,'','ozzPxvnkPpqjg_bOGS5t91N2CV_M'),(125157144938,'','oOQ5Hwk8f6lgZGOKEdfzIfhj20CQ@baidu','','',0,0,'','ozzPxvg4F__kUe9yUARZMlXpB2oI'),(125157300816,'','79708FE80FC0331FE1E40AEB3762A1A8@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/79708FE80FC0331FE1E40AEB3762A1A8/100',''),(125157444552,'','5064D0DEFAD3459A4893E89C39A19C20@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/5064D0DEFAD3459A4893E89C39A19C20/100',''),(125157564072,'','','19919828029','',0,0,'',''),(125157652213,'','F8BB07048F63B84CEC3B9F5C0402B43A@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/F8BB07048F63B84CEC3B9F5C0402B43A/100',''),(125157739568,'','9787438A87669A134C8B08B1333B849A@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/9787438A87669A134C8B08B1333B849A/100',''),(125157858572,'','ADC034C7D67C22F3B8DFD691F228B61F@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/ADC034C7D67C22F3B8DFD691F228B61F/100',''),(125157962110,'','oOQ5HwlcggAEYD4bZanZ77Q9kZOg@baidu','','',0,0,'','ozzPxvhsB8HXU-_jFurVlqXwkSiY'),(125158103184,'','oOQ5HwvfA0ENj53sbpsUDu6ydJYE@baidu','','',0,0,'','ozzPxvj1ZW17f5DWT885nC1jrQ_w'),(125158247342,'','oOQ5HwgqE8wXUFOos-yCCIvEcguY@baidu','','',0,0,'','ozzPxvsqrZcc_2LMDlqg-mKSO6RA'),(125158352639,'','CBC7DF45D64A5D88C90BE3DDE613448B@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/CBC7DF45D64A5D88C90BE3DDE613448B/100',''),(125158508631,'','oOQ5HwiuVdBIAzvRaBhEISKvwCHA@baidu','','',0,0,'','ozzPxvmDOe2aeHzf_Q1xI-C6jwgE'),(125158660899,'','oOQ5HwsbVMFYeYDZuEq2YlloBWas@baidu','','',0,0,'','ozzPxvhycl6B8iDfMXTDzb1A9dDg'),(125158741841,'','204A2B39C03A1D4208E4F77B083E9438@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/942FEA70050EEAFBD4DCE2C1FC775E/100',''),(125159107155,'','AD7F5A512D3C81F8E7E7229EAD0BAFA3@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/AD7F5A512D3C81F8E7E7229EAD0BAFA3/100',''),(125159721026,'','oOQ5HwtYxg1xw6arIH2JxhmfXlb4@baidu','','',0,0,'','ozzPxvlqp_MD88xlYtdx4NaMkDmk'),(125159823858,'','oOQ5HwtQGonCz6xBWJTr581zaosE@baidu','','',0,0,'','ozzPxvtzqXguVgdW46bsXffc-nwA'),(125160709220,'','oOQ5Hwk5H96jsDVQHd5OhOrNpp5o@baidu','','',0,0,'','ozzPxvo_btekFjGDOd0nJ4rXRGgo'),(125160797891,'','oOQ5HwobbVu_m-Bp8iJYC5JJu-yg@baidu','','',0,0,'','ozzPxvubF-gtZJ8GkCOKaLEvRiBs'),(125160916134,'','oOQ5HwnV0kXRh56MebGQXVMvVhus@baidu','','',0,0,'','ozzPxvodHOOKAmkH4XD0wnR3ufs4'),(125161072164,'','oOQ5HwhEUK8OwSvuF3mq5I9Xtjzw@baidu','','',0,0,'','ozzPxvtqM5Eeqbe1iwlcAQe5ONFA'),(125161814065,'','D87ECF9EA5B4D8BF4F65B2AA58521548@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/D87ECF9EA5B4D8BF4F65B2AA58521548/100',''),(125162586689,'','BF5F6FCF7FD590FC7F2ADD204F8EE7F8@baidu','','',0,0,'',''),(125167192604,'','oOQ5HwsuRyDKLGaWGx-6L6AfwSX42@baidu','','',0,0,'',''),(125168317498,'','oOQ5HwsuRyDKLGaWGx-6L6AfwSX422@baidu','','',0,0,'',''),(125168516005,'','162D9D2D9EF1454904ADF52E372877E3@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/162D9D2D9EF1454904ADF52E372877E3/100',''),(125168626991,'','oOQ5HwjYQYOn13Jj9tUK3Tx37VFk@baidu','','',0,0,'','ozzPxvlogzjyqw6c2ZJfdHV6LaIo'),(125168862373,'','oOQ5Hwj7dXh79jqeHmDVE8xHlnu8@baidu','','',0,0,'','ozzPxviUfCnNcSdtIxxnRoVGDnd4'),(125168997787,'','oOQ5HwuIYWg5DrnL2dX2Mvt5Ax40@baidu','','',0,0,'','ozzPxvlSjBEAkfjX-5C0clruAHp4'),(125169086058,'','oOQ5HwhrzKUbxnuqPZOjMqrvdi-I@baidu','','',0,0,'','ozzPxvnAohbQJ8CXu2yT8BSfhDSk'),(125169228392,'','oOQ5Hwsr-XtH9N6L1WrGg55HWASE@baidu','','',0,0,'','ozzPxvr3OHGCG1vPAok2SuSlVj3M'),(125169287997,'','oOQ5HwnDa5rAuNHJlXr1ba7jcLr0@baidu','','',0,0,'','ozzPxvrzphdq74xsLhXHj2GZCMTA'),(125169573246,'','C5E71B8D81A94001E802D7F3A732C74D@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/C5E71B8D81A94001E802D7F3A732C74D/100',''),(125169765439,'','2058E8B55CB98DD6A395E5D1C1645C7A@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/2058E8B55CB98DD6A395E5D1C1645C7A/100',''),(125169897077,'','oOQ5HwoR4H_kToE3zD5QqR5A-ib4@baidu','','',0,0,'','ozzPxvgeuHHLLw3aPUM0ckdhE8HI'),(125169995335,'','oOQ5HwroOZyFpVHHJHAh6PPG8rOM@baidu','','',0,0,'','ozzPxvg5BnsMXUtby9kw3bE1PpqM'),(125170090116,'','oOQ5HwuuEHE4dD7Re0bxRYor1ivg@baidu','','',0,0,'','ozzPxvt0XiawdVvgqQRjXcwv4xRs'),(125170212980,'','oOQ5Hwib59eUli1X1WVBgy_XkXk8@baidu','','',0,0,'','ozzPxvrkGtpV7qhGp5r93tfmdzN4'),(125170303417,'','oOQ5HwhEzSfTs4_znDQrNGXc52rk@baidu','','',0,0,'','ozzPxvs0Hj6fnaKsCDyUdkLjW9yc'),(125170460932,'','F43A5694DAE0A671E8F157D0CA23123B@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/F43A5694DAE0A671E8F157D0CA23123B/100',''),(125170558958,'','oOQ5HwqUPEm9W6FMtBb2m-SwGnvA@baidu','','',0,0,'','ozzPxvugKyoQOzNdsQvXVIUcgk84'),(125170698799,'','oOQ5HwqzMXdyEqqv8SatGTHJQD48@baidu','','',0,0,'','ozzPxvsZr3IgJmOev6zURQyPN1J0'),(125170842843,'','233064C5690B6605B60A2C45845B9114@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/233064C5690B6605B60A2C45845B9114/100',''),(125170929994,'','oOQ5HwkFkxIiVPQJrzbESgt0pD0g@baidu','','',0,0,'','ozzPxvmP_t0uZBLC4bzmkYUQplW8'),(125171081772,'','oOQ5HwmgcjAqFToc5XMg9IuZGMy8@baidu','','',0,0,'','ozzPxvhU5F9AmicsWlEH95_pVlm8'),(125171180271,'','4DF12400F1153D747275F27657DA5B56@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/4DF12400F1153D747275F27657DA5B56/100',''),(125171325578,'','oOQ5HwhpJAJ5MaJdA5LY1x47WITk@baidu','','',0,0,'','ozzPxvno0eyzBXeh3BGKKKocO8SM'),(125171418444,'','oOQ5HwtS7Ehk3fvjlCUWNcfQ0I6A@baidu','','',0,0,'','ozzPxviRNNQ_egIRuGGMuTVEA_Uc'),(125171547559,'','oOQ5HwuH17oc87MgdRcIsZiGGV0k@baidu','','',0,0,'','ozzPxvkvQLkiupdLwdr_CuyUxzKc'),(125171636943,'','oOQ5HwnwWQq4mrJXUAN3YnE1LJzA@baidu','','',0,0,'','ozzPxvql_ZUaiWPhjRANq0s695ds'),(125171781355,'','oOQ5Hwm6M81y9iHmYNVBn129gtnw@baidu','','',0,0,'','ozzPxvjtr5zttbEblmIBBk39MxQc'),(125171864870,'','oOQ5HwoV_thhET0XLh43iQxAqV8k@baidu','','',0,0,'','ozzPxvsEdv8GhygfV8bQFm3Gg2iQ'),(125171953669,'','oOQ5Hwlt9t4p_INdtDGQ7sgoKFs8@baidu','','',0,0,'','ozzPxvgtrKriFc_lPi2kLDeH31Cc'),(125172022972,'','F9ABA57C9240202C3ECA8552EE79025D@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/F9ABA57C9240202C3ECA8552EE79025D/100',''),(125172164449,'','oOQ5HwrOLQUBaXjb68_JWIJrIx5c@baidu','','',0,0,'','ozzPxvv76HA5snUeDmBpIrOHrG9k'),(125172305556,'','oOQ5Hwlru10XH4pVOcbWA4oKwqpw@baidu','','',0,0,'','ozzPxvmYWXbH7WhVSXBhk5gtcTVE'),(125172449089,'','oOQ5Hwj2hd-GUF7tXAHgh8gfgcbg@baidu','','',0,0,'','ozzPxviz5IpyC1r82y2J_JEHVQxU'),(125172559766,'','oOQ5HwgE_lDllHnhAntBedhMoMmY@baidu','','',0,0,'','ozzPxvpocFI361I9B6SbFQJrPVNw'),(125172702223,'','7EF7D4DA4A0AE0285C562E8907367326@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/7EF7D4DA4A0AE0285C562E8907367326/100',''),(125172781127,'','43776FEF01246DAAA6FD3713B98F10E1@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/43776FEF01246DAAA6FD3713B98F10E1/100',''),(125172849407,'','oOQ5HwqgmOk47b6NBf6HcjPr8-54@baidu','','',0,0,'','ozzPxvrJjKcn9NRDQit7JHvWXpus'),(125173001922,'','75F5F471087D76C61135F5C4348F60A8@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/75F5F471087D76C61135F5C4348F60A8/100',''),(125173151632,'','oOQ5HwtHxfjeTIUvHsVn-_TKiITI@baidu','','',0,0,'','ozzPxvlsn9hIeW1oRmgs7ZyrDuFg'),(125173294848,'','E9835F8B826FEB6A3625A07CEBFD38E8@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/E9835F8B826FEB6A3625A07CEBFD38E8/100',''),(125173430783,'','2D3B3D0EDFFA4EE7596ADF42ADD9BFF9@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/2D3B3D0EDFFA4EE7596ADF42ADD9BFF9/100',''),(125173548127,'','5FF9CA0E0EB48790FFE0425BE998D85B@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/5FF9CA0E0EB48790FFE0425BE998D85B/100',''),(125173655119,'','oOQ5Hwu4ebGeYTPwMV2gUcvWphYI@baidu','','',0,0,'','ozzPxvlxyPxb3DBeHtL1laYUmEEk'),(125173800781,'','oOQ5Hwu2MyBAS0IxhzL2cQj_6dxg@baidu','','',0,0,'','ozzPxvqwJ-TwCHrUwE3cuLRiQagU'),(125173914519,'','oOQ5Hwo50FPP6fe-L6C-Jt6Qe2DA@baidu','','',0,0,'','ozzPxvjK4zeyFFx0siWe302zfvNQ'),(125173995835,'','oOQ5HwvtPvULl6RXE3vmxVyouFfI@baidu','','',0,0,'','ozzPxvgW33KtlYSBvzK50nKp2Ykg'),(125174139471,'','BBB8ECF35F3359C4DF2FD2302F778BB4@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/BBB8ECF35F3359C4DF2FD2302F778BB4/100',''),(125174218253,'','F6535E5626F77FC5126C6A0E6C156D53@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/F6535E5626F77FC5126C6A0E6C156D53/100',''),(125174356057,'','oOQ5HwvgDcgk9nr7fnGxReC66dEw@baidu','','',0,0,'','ozzPxvhSCUOjAiLQiRdnddRfpkGA'),(125174427724,'','oOQ5Hwtq3VDsiMrtFgZFXeY3lIbo@baidu','','',0,0,'','ozzPxvk_Fd6V-1nkTIbwSmzg7Evo'),(125174561822,'','oOQ5HwlgMX1jdPuuh2bhX7bNLS9E@baidu','','',0,0,'','ozzPxvim2F281hybR03e6gMcBKWI'),(125174633664,'','A6C85845B82F3AC0EA45E10CBEEF4604@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/A6C85845B82F3AC0EA45E10CBEEF4604/100',''),(125174714990,'','oOQ5Hwo8tq1pZT2YVZoRjqN_Zf28@baidu','','',0,0,'','ozzPxvlDo5alPUu_SOqh3EK4vn58'),(125174784439,'','oOQ5Hwlb-BTjLhb1Y4b1oGczRDZc@baidu','','',0,0,'','ozzPxvltO0xbtV7nfIfEoMzi4X-4'),(125174850707,'','oOQ5HwsWEwo1nappeTHHWg4iAPS8@baidu','','',0,0,'','ozzPxvjS5qILGaUYnRM9Pduh2F3M'),(125174994569,'','oOQ5Hwqf09jtjsWvONuVOhJqC0NQ@baidu','','',0,0,'','ozzPxvlOo9eMH-pQbyGPysTYVxTg'),(125175067131,'','0BB7A577195F074C0532CFF50969F8D2@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/0BB7A577195F074C0532CFF50969F8D2/100',''),(125175168575,'','oOQ5Hwu-jFijqgg4DlumSFgXM0sY@baidu','','',0,0,'','ozzPxvrj-uDGuKT_f9b_xQ9q_4SM'),(125175304662,'','oOQ5Hwix6svLWPc3ymLWj5MDdbI4@baidu','','',0,0,'','ozzPxvoQjC3XtPHuEvEWT9H9EdNI'),(125175443572,'','F21925BCE57BD666207307375CA04D5F@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/F21925BCE57BD666207307375CA04D5F/100',''),(125175599626,'','oOQ5HwoWxjsRyPOCs7AH-n_dD06A@baidu','','',0,0,'','ozzPxvl7GuOwJ_injowCrnhlQUUI'),(125175733948,'','oOQ5HwndRLK1kAhGTL3Sfsi7HGtI@baidu','','',0,0,'','ozzPxviiiGjBt6-vn7NCj5e-mNPM'),(125175829943,'','CFA019C48D01E351C9B4E80915DDC11A@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/CFA019C48D01E351C9B4E80915DDC11A/100',''),(125175973365,'','oOQ5HwtpZbtOtiMYuyxgOhFIPBEE@baidu','','',0,0,'','ozzPxvpwRTeMVIYUiihV1ncMxYl4'),(125176046276,'','F6CA100A688BE3C1C01200FD0D06F002@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/F6CA100A688BE3C1C01200FD0D06F002/100',''),(125176161735,'','oOQ5Hwi0vE80s9ZA5dUvq6UO592M@baidu','','',0,0,'','ozzPxvq3qM4JxjNe_DKHURSLjxIM'),(125176264234,'','oOQ5Hwi0-E-wsL77BAiUdzV_nWcY@baidu','','',0,0,'',''),(125176375939,'','oOQ5HwlsqMqYe0hwcIEEZOvvrKP8@baidu','','',0,0,'','ozzPxvkuMzbCWLpE70b7sH4mwGTY'),(125176465043,'','oOQ5HwlZICyaG-x9zFi9Meb-PMX8@baidu','','',0,0,'','ozzPxvpJOzB3VkwJgBcvR343MRDc'),(125176545128,'','oOQ5HwrrqXohHQFs3dhfkykgP_G4@baidu','','',0,0,'','ozzPxvilba5RgzUkzXvwd5cDSM4g'),(125176620423,'','oOQ5HwuZMi5FkrlplPnkMIjz3tAc@baidu','','',0,0,'',''),(125176774983,'','762ABDB114BBFDA0F2C2D7768D2A8126@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/762ABDB114BBFDA0F2C2D7768D2A8126/100',''),(125176906634,'','oOQ5HwjkDIPnR9U1sX8mo5Y9mCN4@baidu','','',0,0,'','ozzPxvi1l-pkZQeuWdyXFv2HebMU'),(125176987861,'','016362F9F85DB78CE1EF8BE22540B932@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/016362F9F85DB78CE1EF8BE22540B932/100',''),(125177103003,'','oOQ5HwrQK83fDi6QATBhZ_EJW1u0@baidu','','',0,0,'','ozzPxvjiD6ZIRnPLAbMgV6oRg9Pw'),(125177247060,'','oOQ5HwgJ5I6MmLMBpZ_7iP-nsjGg@baidu','','',0,0,'','ozzPxvqZzHOVkFPFTDmhBjlI4y7M'),(125177335689,'','4567C234230DF0076D942166F671C06A@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/4567C234230DF0076D942166F671C06A/100',''),(125177433492,'','BF091BE75519CBF10450134F9F089724@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/BF091BE75519CBF10450134F9F089724/100',''),(125177504403,'','78DDE0AC3D1EDA2674BD9FB127EBD6C8@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/78DDE0AC3D1EDA2674BD9FB127EBD6C8/100',''),(125177648500,'','9BEA22C85D1B6C21A247BFFC0ADE720A@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/9BEA22C85D1B6C21A247BFFC0ADE720A/100',''),(125177722641,'','oOQ5HwgHJzZ8t37uyWehN3Ex7ThA@baidu','','',0,0,'','ozzPxvvMIUA5wlVkKWldUw42ZW3I'),(125177849523,'','oOQ5HwndSyVPs7ADpC6spMn0lckM@baidu','','',0,0,'','ozzPxvh8Vbxmu8RrAM0Kt5yL3TCM'),(125177928975,'','6967B0F7530E1E124DA91E2C36911296@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/6967B0F7530E1E124DA91E2C36911296/100',''),(125178062089,'','D27049A11CEF493F023B4F0D28D5BA61@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/D27049A11CEF493F023B4F0D28D5BA61/100',''),(125178147096,'','566B3C8C505A8E9915D3E0D8FDC2B90A@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/566B3C8C505A8E9915D3E0D8FDC2B90A/100',''),(125178224044,'','2A2CF6EE935B99B79AB5502F5DDD50D9@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/2A2CF6EE935B99B79AB5502F5DDD50D9/100',''),(125178296463,'','oOQ5HwlQfGJKEU4-S6gwXw0CYi78@baidu','','',0,0,'','ozzPxvp1wFMOrBTa0Pwk-5W5-DtQ'),(125178420616,'','064DC14FB50FE0FABD50304711E92BE6@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/064DC14FB50FE0FABD50304711E92BE6/100',''),(125178486136,'','4EA28BEE2E8B519B2CD023B586987392@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/4EA28BEE2E8B519B2CD023B586987392/100',''),(125178605039,'','47FA3ED61282B558FA387ACBD14DFE95@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/47FA3ED61282B558FA387ACBD14DFE95/100',''),(125178751268,'','oOQ5HwgxTUst2WHqmLFy-K2FNU5g@baidu','','',0,0,'','ozzPxvuo8ALswseeiPVECXkupOq4'),(125178815579,'','A6A60F88B008C2B930B71AD5EC11A2A8@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/A6A60F88B008C2B930B71AD5EC11A2A8/100',''),(125178965839,'','oOQ5HwkCTtHqr-jNxEWuSD5g2B-w@baidu','','',0,0,'','ozzPxvhpA5fLaj_q0pT8m6R4tvCw'),(125179069180,'','oOQ5HwgCyR4H6spakcSZluDVkR_c@baidu','','',0,0,'','ozzPxvjONCD3g-QQ8CvfSsbKBbKk'),(125179220986,'','oOQ5HwugLKa4EJIiVoKOE-OkYrxc@baidu','','',0,0,'','ozzPxvnX2yvDfJFCKMAhhdZX1cU0'),(125179308282,'','A1E3CE4F26B2AF1248CA03F5F066D231@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/A1E3CE4F26B2AF1248CA03F5F066D231/100',''),(125179368498,'','08F1FCF669CA56349A8F184FA8E72558@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/08F1FCF669CA56349A8F184FA8E72558/100',''),(125179475267,'','oOQ5HwvPms_wBh3Wil3OrABmae6Q@baidu','','',0,0,'','ozzPxvrR5SCtvLdxjWuPAFi4vQig'),(125179616882,'','oOQ5HwjavJYsiokmB2h3qA3_oZ_4@baidu','','',0,0,'','ozzPxvh6IxXGJ1DolbS6IoecF9p8'),(125179756742,'','2CDA762F3D836ED7E088E698C48A2C40@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/2CDA762F3D836ED7E088E698C48A2C40/100',''),(125179825492,'','BB5CF20493CFB287CBB9B42E07AA6613@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/BB5CF20493CFB287CBB9B42E07AA6613/100',''),(125179892813,'','C14379923D7C264F51AA57A381DA8B0A@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/C14379923D7C264F51AA57A381DA8B0A/100',''),(125180037094,'','B9E8FE5582C0B793223D02076F96FB3E@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/B9E8FE5582C0B793223D02076F96FB3E/100',''),(125180162057,'','oOQ5Hwj8BWS206EarAWCB6lEuJ1Y@baidu','','',0,0,'','ozzPxvvEQsNPt35FR8zqOTMUuaRw'),(125180243379,'','EF44772DD4CFDF3C7F74ACCE9FEA96FD@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/EF44772DD4CFDF3C7F74ACCE9FEA96FD/100',''),(125180325252,'','FE044C2FCF7DA9D9D8624812FB318721@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/FE044C2FCF7DA9D9D8624812FB318721/100',''),(125180411406,'','oOQ5HwkGlXOCobONoooblbdF5e44@baidu','','',0,0,'',''),(125180483648,'','oOQ5HwgEyRIQlOeTV2HnJSobQQuc@baidu','','',0,0,'','ozzPxvhgWlt3-LJ-MDqAkdmG18xw'),(125180631506,'','4E9E793FF0F10580E806939776D1FF0D@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/4E9E793FF0F10580E806939776D1FF0D/100',''),(125180695425,'','1E75BABA05CE495E231258388AEB1341@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/1E75BABA05CE495E231258388AEB1341/100',''),(125180806604,'','oOQ5Hwva8CxnbqHtzVZHencTpjIY@baidu','','',0,0,'','ozzPxvk6jXyxHNYzaQXUj-jM3B1c'),(125180931981,'','oOQ5Hwixut-Wk2-UB-ruBROL7M1A@baidu','','',0,0,'','ozzPxvmaGOH4cKN3JOwfEIsQeQu0'),(125181033370,'','oOQ5HwiJ2io_YPdxToBDMGE2gZnE@baidu','','',0,0,'','ozzPxvnvuDzSXya29x3_ssD-Bblc'),(125181179632,'','oOQ5HwtJ_WByx6sv3QtKQ7f1aDhs@baidu','','',0,0,'',''),(125181245632,'','oOQ5HwqP4hag3r-LQ8cKfukHKLJE@baidu','','',0,0,'','ozzPxvlmTJJqadTAe1zipTSI9-nw'),(125181313630,'','oOQ5Hwnhnfsc6xmmRGvg-xx2S2pA@baidu','','',0,0,'','ozzPxvmThumKWvaK0jtNcN2PNfyo'),(125181412652,'','oOQ5Hwmb4m0hHUQrMtCW9RKt5hpU@baidu','','',0,0,'','ozzPxvsD0nNAZjHYihEKEnAbV7uM'),(125181568713,'','oOQ5HwmAguIPR8XU9g8bZVaC9XwE@baidu','','',0,0,'','ozzPxvk5pbnYUVPO9ppzeMalGr3g'),(125181664810,'','803F174CF0312FCEECEE7E64513C059C@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/803F174CF0312FCEECEE7E64513C059C/100',''),(125181759429,'','FA3AF341F9D49F5567B62B7CBD590934@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/FA3AF341F9D49F5567B62B7CBD590934/100',''),(125181898707,'','oOQ5HwsKoYzp-ZPOsxdiShJ8ptJg@baidu','','',0,0,'','ozzPxvr5XPQmCP2I8icqW5ZY8wqU'),(125181995953,'','oOQ5HwqKFX_ECb21G57Cvd9oiPcs@baidu','','',0,0,'',''),(125182129009,'','oOQ5HwuZxMqv-sMKsA1hY-5MDFC4@baidu','','',0,0,'','ozzPxvrxmz5n-wJlBuOs7_3mqDCg'),(125182250604,'','F5D17155445C27D75F3C92AA66F1AA54@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/F5D17155445C27D75F3C92AA66F1AA54/100',''),(125182325782,'','EE78D9E37DBD05924D839CAC520544E9@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/EE78D9E37DBD05924D839CAC520544E9/100',''),(125182476236,'','oOQ5Hwj_g14BZVxA9L5qCw_Lp53E@baidu','','',0,0,'','ozzPxvn9LQavmcKivyyH3pfVbkeM'),(125182627235,'','oOQ5HwiU_ZT5ET_zeCExsysB0wIo@baidu','','',0,0,'','ozzPxvuTasPA5nVsh6dvK4a85BwY'),(125182763239,'','oOQ5HwgqVPPtI9z9USDCRfAwvqY4@baidu','','',0,0,'','ozzPxvhANfol8fE1HFIMvQx6PwbE'),(125182885808,'','42158B5DD25589FE3B368D21C338D35C@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/42158B5DD25589FE3B368D21C338D35C/100',''),(125182977554,'','453DAACBA214E5C543E04BCE44C01807@baidu','','',0,0,'http://q.qlogo.cn/qqapp/101432559/453DAACBA214E5C543E04BCE44C01807/100',''),(125183103352,'','oOQ5Hwvpbo4e3O86KfoeB3urZ7Mo@baidu','','',0,0,'','ozzPxvm5QHTgGksDKynjRmALYhlE'),(125183207608,'','oOQ5HwoHDbqY9LOEf_nMXq2iusBo@baidu','','',0,0,'','ozzPxvseDxSYpf5uUcs_lvlUePHw'),(125183334873,'','oOQ5Hwt2tYzG45BUrp2GSBGRYiAU@baidu','','',0,0,'','ozzPxvi5p3zgmOmmbgcB9DeYJN1c'),(125183428798,'','oOQ5HwoGJOSCsOaE9cwgy2VGHN6w@baidu','','',0,0,'','ozzPxvqfuUsCCX23UnGwbwW2Q3fA'),(125183522708,'','oOQ5HwiZ_8wuTfAzXeD4X8YNngx8@baidu','','',0,0,'','ozzPxvjlsJtubKkg67c3p1Fj89pI'),(125183654084,'','oOQ5Hwqw6p9UakuP4QLL5pxJeZt8@baidu','','',0,0,'','');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_play`
--

DROP TABLE IF EXISTS `user_play`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_play` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(11) NOT NULL COMMENT '用户id',
  `game_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL DEFAULT '',
  `cps_id` varchar(20) NOT NULL DEFAULT '' COMMENT '渠道id',
  `sub_cps_id` varchar(20) NOT NULL DEFAULT '' COMMENT '子渠道id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`game_id`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_play`
--

LOCK TABLES `user_play` WRITE;
/*!40000 ALTER TABLE `user_play` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_play` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_read_msg`
--

DROP TABLE IF EXISTS `user_read_msg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_read_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `msg_type` enum('news','gift') NOT NULL DEFAULT 'news' COMMENT 'news:资讯，gift:礼包',
  `last_read_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后读取时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`game_id`,`msg_type`,`last_read_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_read_msg`
--

LOCK TABLES `user_read_msg` WRITE;
/*!40000 ALTER TABLE `user_read_msg` DISABLE KEYS */;
INSERT INTO `user_read_msg` VALUES (29,125067827087,2,'news','2017-11-23 07:12:46'),(30,125067827087,2,'gift','2017-11-23 07:12:47'),(31,125067944037,2,'news','2017-11-23 07:26:37'),(32,125067944037,2,'gift','2017-11-23 08:01:34'),(33,125080075136,2,'news','2017-11-28 08:06:37'),(34,125080075136,2,'gift','2017-11-28 08:07:01'),(35,125082927699,2,'news','2017-11-30 06:19:58'),(36,125082927699,2,'gift','2017-11-30 05:56:52'),(37,125095392456,2,'news','2017-12-05 08:03:03'),(42,125095392456,2,'gift','2017-12-07 06:03:17'),(38,125095701782,2,'news','2017-12-06 02:35:28'),(40,125095701782,2,'gift','2017-12-06 12:43:46'),(39,125095813220,2,'news','2017-12-07 09:15:26'),(45,125095813220,2,'gift','2017-12-07 09:10:18'),(41,125096433442,2,'news','2017-12-07 01:52:53'),(43,125096891731,2,'news','2017-12-07 06:17:52'),(44,125096891731,2,'gift','2017-12-07 06:17:54'),(48,125139570782,2,'news','2017-12-18 04:45:07'),(49,125139570782,2,'gift','2017-12-18 04:33:11'),(46,125139987895,2,'news','2017-12-17 11:15:46'),(47,125139987895,2,'gift','2017-12-17 11:15:57'),(50,125140137216,2,'news','2017-12-18 04:38:16'),(51,125140137216,2,'gift','2017-12-18 04:38:28'),(54,125140983489,2,'news','2018-01-04 03:14:36'),(55,125140983489,2,'gift','2017-12-28 10:05:24'),(52,125143053546,2,'news','2017-12-28 08:59:55'),(53,125143053546,2,'gift','2017-12-28 08:59:58'),(58,125146375211,2,'news','2018-01-08 02:20:25'),(59,125146375211,2,'gift','2018-01-08 02:20:33'),(56,125167192604,2,'news','2018-01-05 06:44:25'),(57,125167192604,2,'gift','2018-01-05 06:44:27');
/*!40000 ALTER TABLE `user_read_msg` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-01-10  9:32:27
