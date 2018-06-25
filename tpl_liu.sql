/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50717
Source Host           : localhost:3306
Source Database       : tpl_liu

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2018-06-25 17:25:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for member
-- ----------------------------
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `open_id` varchar(255) DEFAULT '' COMMENT 'openid',
  `union_id` varchar(255) DEFAULT '' COMMENT 'unionid',
  `nickname` varchar(255) CHARACTER SET utf8mb4 DEFAULT '' COMMENT '昵称',
  `gender` tinyint(4) DEFAULT '0' COMMENT '性别',
  `city` varchar(255) DEFAULT '' COMMENT '城市',
  `province` varchar(255) DEFAULT '' COMMENT '省份',
  `country` varchar(255) DEFAULT '' COMMENT '国家',
  `head_img` varchar(1024) DEFAULT '' COMMENT '头像',
  `avatar` varchar(1024) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '头像地址',
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
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL COMMENT '父菜单',
  `icon` varchar(50) DEFAULT NULL COMMENT '图标',
  `title` varchar(50) NOT NULL COMMENT '名称',
  `route` varchar(255) DEFAULT NULL COMMENT '路由',
  `child_count` int(11) NOT NULL DEFAULT '0' COMMENT '子菜单数量',
  `depth` int(11) DEFAULT NULL,
  `sort` int(11) NOT NULL COMMENT '排序',
  `create_user` int(11) DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='菜单';

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '0', 'fa fa-align-justify', '系统管理', '', '4', '1', '1', null, null, '2', '1491797555');
INSERT INTO `menu` VALUES ('2', '1', '', '用户管理', 'user/index', '0', '2', '1', null, null, '12', '1496732493');
INSERT INTO `menu` VALUES ('3', '1', '', '角色管理', 'role/index', '0', '2', '2', null, null, '12', '1496732493');
INSERT INTO `menu` VALUES ('4', '1', '', '权限管理', 'permission/index', '0', '2', '3', null, null, '3', '1496729426');
INSERT INTO `menu` VALUES ('5', '1', '', '菜单管理', 'menu/index', '0', '2', '4', null, null, '3', '1496729426');

-- ----------------------------
-- Table structure for permission
-- ----------------------------
DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL COMMENT '父节点',
  `title` varchar(50) NOT NULL COMMENT '名称',
  `route` varchar(255) DEFAULT NULL COMMENT '路由',
  `rule` varchar(255) DEFAULT NULL COMMENT '规则',
  `child_count` int(11) NOT NULL DEFAULT '0' COMMENT '子节点数量',
  `sort` int(11) NOT NULL COMMENT '排序',
  `depth` int(11) DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='权限';

-- ----------------------------
-- Records of permission
-- ----------------------------
INSERT INTO `permission` VALUES ('1', '0', '用户管理', '', '', '6', '1', '1', '3', '1496387795', '12', '1496732473');
INSERT INTO `permission` VALUES ('2', '1', '查看', 'user/index', '', '0', '1', '2', '3', '1496393960', '3', '1496393960');
INSERT INTO `permission` VALUES ('3', '1', '添加', 'user/create', '', '0', '2', '2', '3', '1496393965', '3', '1496393965');
INSERT INTO `permission` VALUES ('4', '1', '编辑', 'user/update', '', '0', '3', '2', '3', '1496393971', '3', '1496393991');
INSERT INTO `permission` VALUES ('5', '1', '角色设置', 'user-role/edit', '', '0', '4', '2', '3', '1496393984', '3', '1496393984');
INSERT INTO `permission` VALUES ('6', '1', '修改密码', 'user/password', '', '0', '5', '2', '3', '1496393991', '3', '1496393991');
INSERT INTO `permission` VALUES ('7', '1', '删除', 'user/delete', null, '0', '6', '2', '3', '1496648217', '3', '1496726385');
INSERT INTO `permission` VALUES ('8', '0', '角色管理', '', '', '4', '2', '1', '3', '1496387795', '12', '1496732473');
INSERT INTO `permission` VALUES ('9', '8', '查看', 'role/index', '', '0', '1', '2', '3', '1496393960', '3', '1496393960');
INSERT INTO `permission` VALUES ('10', '8', '添加', 'role/create', '', '0', '2', '2', '3', '1496393965', '3', '1496393965');
INSERT INTO `permission` VALUES ('11', '8', '编辑', 'role/update', '', '0', '3', '2', '3', '1496393971', '3', '1496393991');
INSERT INTO `permission` VALUES ('12', '8', '删除', 'role/delete', '', '0', '4', '2', '3', '1496648217', '3', '1496726385');
INSERT INTO `permission` VALUES ('13', '0', '权限管理', '', '', '4', '3', '3', '3', '1496387795', '12', '1496732468');
INSERT INTO `permission` VALUES ('14', '13', '查看', '', '', '2', '1', '2', '3', '1496393960', '3', '1496393960');
INSERT INTO `permission` VALUES ('15', '14', '查看', 'permission/index', '', '0', '1', '3', '3', '1496393960', '3', '1496393960');
INSERT INTO `permission` VALUES ('16', '14', '排序', 'permission/order', '', '0', '2', '3', '3', '1496393965', '3', '1496393965');
INSERT INTO `permission` VALUES ('17', '13', '添加', 'permission/create', '', '0', '2', '2', '3', '1496393965', '3', '1496393965');
INSERT INTO `permission` VALUES ('18', '13', '编辑', 'permission/update', '', '0', '3', '2', '3', '1496393971', '3', '1496393991');
INSERT INTO `permission` VALUES ('19', '13', '删除', 'permission/delete', '', '0', '4', '2', '3', '1496648217', '3', '1496726385');
INSERT INTO `permission` VALUES ('20', '0', '菜单管理', '', '', '4', '4', '1', '3', '1496387795', '3', '1496649296');
INSERT INTO `permission` VALUES ('21', '20', '查看', '', '', '2', '1', '2', '3', '1496393960', '3', '1496393960');
INSERT INTO `permission` VALUES ('22', '21', '查看', 'menu/index', '', '0', '1', '3', '3', '1496393960', '3', '1496393960');
INSERT INTO `permission` VALUES ('23', '21', '排序', 'menu/order', '', '0', '2', '3', '3', '1496393965', '3', '1496393965');
INSERT INTO `permission` VALUES ('24', '20', '添加', 'menu/create', '', '0', '2', '2', '3', '1496393965', '3', '1496393965');
INSERT INTO `permission` VALUES ('25', '20', '编辑', 'menu/update', '', '0', '3', '2', '3', '1496393971', '3', '1496393991');
INSERT INTO `permission` VALUES ('26', '20', '删除', 'menu/delete', '', '0', '4', '2', '3', '1496648217', '3', '1496726385');

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '名称',
  `description` varchar(255) DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='角色';

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', '管理员', '管理员', null, null, '3', '1496653830');

-- ----------------------------
-- Table structure for role_permission
-- ----------------------------
DROP TABLE IF EXISTS `role_permission`;
CREATE TABLE `role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` int(11) NOT NULL COMMENT '权限',
  `role_id` int(11) NOT NULL COMMENT '角色',
  `create_user` int(11) DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permission_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `role_permission_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色权限';

-- ----------------------------
-- Records of role_permission
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('3', '10', 'admin', 'admin', '$2y$13$8/BNUj4wBkGVu2Ah42ZAZuFidPM1vcW/3a.S1vBzzs2hpJdcwryWC', '111', '', '', '1529055850', '127.0.0.1', null, '1', '3', '1529055850');

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_login_log
-- ----------------------------
INSERT INTO `user_login_log` VALUES ('22', '3', '1529055850', '3', '127.0.0.1', '1529055850', '3');

-- ----------------------------
-- Table structure for user_role
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户',
  `role_id` int(11) NOT NULL COMMENT '角色',
  `create_user` int(11) DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_role
-- ----------------------------
