/*
Navicat MySQL Data Transfer

Source Server         : MySQL1_copy
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : suesyiban

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-02-01 00:00:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for articles
-- ----------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `Art_Id` int(4) NOT NULL AUTO_INCREMENT,
  `Art_Name` varchar(8) NOT NULL,
  `Art_Num` int(4) NOT NULL,
  `Art_Time` varchar(8) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`Art_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of articles
-- ----------------------------
INSERT INTO `articles` VALUES ('1', '易班熊', '6', '16-01-16', '1');
INSERT INTO `articles` VALUES ('2', '便签', '518', '16-01-16', '1');
INSERT INTO `articles` VALUES ('8', '哈哈', '21', '16-01-31', '1');
INSERT INTO `articles` VALUES ('9', '电脑', '31', '16-01-31', '1');
INSERT INTO `articles` VALUES ('10', '手机', '123', '16-01-31', '1');
INSERT INTO `articles` VALUES ('12', '呵呵呵', '12', '16-01-31', '1');
INSERT INTO `articles` VALUES ('13', '哈哈哈', '12', '16-01-31', '1');
INSERT INTO `articles` VALUES ('14', '嗄哈哈哈哈哈', '13333333', '16-01-31', '1');
INSERT INTO `articles` VALUES ('15', '平板', '12345678', '16-01-31', '1');
INSERT INTO `articles` VALUES ('16', '电脑2', '1234445', '16-01-31', '1');
INSERT INTO `articles` VALUES ('17', '太阳', '999999999', '16-01-31', '1');
INSERT INTO `articles` VALUES ('20', '月亮', '999999999', '16-01-31', '1');
INSERT INTO `articles` VALUES ('21', '星星', '2147483647', '16-01-31', '1');
INSERT INTO `articles` VALUES ('22', '月亮2', '2147483647', '16-01-31', '3');
INSERT INTO `articles` VALUES ('23', '海王星小', '0', '16-01-31', '2');
INSERT INTO `articles` VALUES ('24', '第六宇宙', '1233131313', '16-01-31', '3');
INSERT INTO `articles` VALUES ('25', '彗星', '2147483647', '16-01-31', '3');

-- ----------------------------
-- Table structure for files
-- ----------------------------
DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `id` bigint(128) NOT NULL,
  `XH_ID` varchar(10) NOT NULL,
  `name` varchar(32) NOT NULL,
  `location` varchar(64) NOT NULL,
  `Time` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of files
-- ----------------------------

-- ----------------------------
-- Table structure for items
-- ----------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `Item_Id` bigint(16) NOT NULL AUTO_INCREMENT,
  `XH_ID` varchar(10) NOT NULL,
  `Item_Name` varchar(16) NOT NULL,
  `Item_Intro` varchar(64) DEFAULT NULL,
  `Status` int(1) NOT NULL,
  `ShowPublic` int(1) NOT NULL,
  PRIMARY KEY (`Item_Id`),
  KEY `XH_ID` (`XH_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of items
-- ----------------------------
INSERT INTO `items` VALUES ('1', '031513217', '示例项目', '这是向你展示的项目', '1', '1');

-- ----------------------------
-- Table structure for moments
-- ----------------------------
DROP TABLE IF EXISTS `moments`;
CREATE TABLE `moments` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `XH_ID` varchar(10) NOT NULL,
  `Mdate` varchar(8) NOT NULL,
  `Time` varchar(20) NOT NULL,
  `Content` varchar(32) NOT NULL,
  `like_Num` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `XH_ID` (`XH_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of moments
-- ----------------------------
INSERT INTO `moments` VALUES ('1', '031513218', '16-01-14', '10:46:23', '测试1', '3');
INSERT INTO `moments` VALUES ('2', '031513217', '16-01-16', '12:25:03', '测试2', '0');
INSERT INTO `moments` VALUES ('6', '031513217', '16-01-16', '19:41:47', '测试5', '0');
INSERT INTO `moments` VALUES ('7', '031513217', '16-01-23', '11:17:03', '测试6', '0');
INSERT INTO `moments` VALUES ('13', '031513218', '16-01-23', '11:17:37', '测试12', '0');
INSERT INTO `moments` VALUES ('24', '031513217', '16-01-31', '23:23:10', '测试1313131', '0');
INSERT INTO `moments` VALUES ('25', '031513217', '16-01-31', '23:45:11', '测试10000', '0');
INSERT INTO `moments` VALUES ('27', '031513217', '16-01-31', '23:45:50', '测试321', '0');

-- ----------------------------
-- Table structure for moment_top
-- ----------------------------
DROP TABLE IF EXISTS `moment_top`;
CREATE TABLE `moment_top` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `moment_id` bigint(255) NOT NULL,
  `status` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of moment_top
-- ----------------------------
INSERT INTO `moment_top` VALUES ('34', '8', '1');

-- ----------------------------
-- Table structure for owntodos
-- ----------------------------
DROP TABLE IF EXISTS `owntodos`;
CREATE TABLE `owntodos` (
  `XH_ID` varchar(10) NOT NULL,
  `CreateDate` varchar(10) NOT NULL,
  `Num` int(2) NOT NULL,
  `content` varchar(16) NOT NULL,
  `urgentLev` int(1) NOT NULL,
  PRIMARY KEY (`XH_ID`,`CreateDate`,`Num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of owntodos
-- ----------------------------
INSERT INTO `owntodos` VALUES ('031513217', '2016-01-26', '1', '完成todo表的建立', '1');
INSERT INTO `owntodos` VALUES ('031513217', '2016-01-26', '2', '烧饭', '1');
INSERT INTO `owntodos` VALUES ('031513217', '2016-01-26', '3', '搬水', '1');
INSERT INTO `owntodos` VALUES ('031513217', '2016-01-26', '4', '做菜', '1');
INSERT INTO `owntodos` VALUES ('031513217', '2016-01-26', '5', '测试', '1');

-- ----------------------------
-- Table structure for test_tb
-- ----------------------------
DROP TABLE IF EXISTS `test_tb`;
CREATE TABLE `test_tb` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `content` varchar(8) NOT NULL,
  `status` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of test_tb
-- ----------------------------
INSERT INTO `test_tb` VALUES ('1', '数据库内容1', '1');
INSERT INTO `test_tb` VALUES ('2', '数据库内容2', '1');
INSERT INTO `test_tb` VALUES ('3', '数据库内容3', '1');
INSERT INTO `test_tb` VALUES ('4', '数据库内容4', '2');
INSERT INTO `test_tb` VALUES ('5', '数据库内容5', '2');
INSERT INTO `test_tb` VALUES ('6', '数据库内容6', '2');

-- ----------------------------
-- Table structure for user_tb
-- ----------------------------
DROP TABLE IF EXISTS `user_tb`;
CREATE TABLE `user_tb` (
  `XH_ID` varchar(10) NOT NULL,
  `XH_PW` varchar(128) NOT NULL,
  `Name` varchar(8) NOT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`XH_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_tb
-- ----------------------------
INSERT INTO `user_tb` VALUES ('031513217', 'e10adc3949ba59abbe56e057f20f883e', '缪钧轴', '13012882930', '1');
INSERT INTO `user_tb` VALUES ('031513218', 'e10adc3949ba59abbe56e057f20f883e', '金永辉', '123', '2');
INSERT INTO `user_tb` VALUES ('1', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '2');
INSERT INTO `user_tb` VALUES ('3', 'e10adc3949ba59abbe56e057f20f883e', '2', '4', '2');
INSERT INTO `user_tb` VALUES ('555', 'e10adc3949ba59abbe56e057f20f883e', '555', '555', '2');
