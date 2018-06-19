/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50629
Source Host           : localhost:3306
Source Database       : jeemoo_template

Target Server Type    : MYSQL
Target Server Version : 50629
File Encoding         : 65001

Date: 2017-06-07 10:25:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for member
-- ----------------------------
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `open_id` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `username` varchar(50) DEFAULT NULL COMMENT '姓名',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机',
  `auth_key` varchar(32) NOT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of member
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` smallint(6) NOT NULL DEFAULT '10' COMMENT '状态',
  `username` varchar(255) NOT NULL COMMENT '姓名',
  `mobile` varchar(11) NOT NULL COMMENT '手机',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `auth_key` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `is_super` bit(1) NOT NULL DEFAULT b'0' COMMENT '是否超级管理员',
  `last_login_at` int(11) DEFAULT NULL COMMENT '最后登录',
  `last_login_ip` varchar(50) DEFAULT NULL COMMENT '最后登录IP',
  `create_user` int(11) DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('3', '10', 'admin', 'admin', '$2y$13$8/BNUj4wBkGVu2Ah42ZAZuFidPM1vcW/3a.S1vBzzs2hpJdcwryWC', '111', '', '', '1496719097', '127.0.0.1', null, '1', '3', '1496719097');

-- ----------------------------
-- Table structure for user_login_log
-- ----------------------------
DROP TABLE IF EXISTS `user_login_log`;
CREATE TABLE `user_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_ip` varchar(50) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`user_id`),
  CONSTRAINT `user_login_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_login_log
-- ----------------------------
