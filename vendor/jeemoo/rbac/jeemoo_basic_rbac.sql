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
INSERT INTO `menu` VALUES ('2', '1', '', '用户管理', 'admin/user/index', '0', '2', '1', null, null, '12', '1496732493');
INSERT INTO `menu` VALUES ('3', '1', '', '角色管理', 'admin/role/index', '0', '2', '2', null, null, '12', '1496732493');
INSERT INTO `menu` VALUES ('4', '1', '', '权限管理', 'admin/permission/index', '0', '2', '3', null, null, '3', '1496729426');
INSERT INTO `menu` VALUES ('5', '1', '', '菜单管理', 'admin/menu/index', '0', '2', '4', null, null, '3', '1496729426');

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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='权限';

-- ----------------------------
-- Records of permission
-- ----------------------------
INSERT INTO `permission` VALUES ('1', '0', '用户管理', '', '', '6', '1', '1', '3', '1496387795', '12', '1496732473');
INSERT INTO `permission` VALUES ('2', '1', '查看', 'admin/user/index', '', '0', '1', '2', '3', '1496393960', '3', '1496393960');
INSERT INTO `permission` VALUES ('3', '1', '添加', 'admin/user/create', '', '0', '2', '2', '3', '1496393965', '3', '1496393965');
INSERT INTO `permission` VALUES ('4', '1', '编辑', 'admin/user/update', '', '0', '3', '2', '3', '1496393971', '3', '1496393991');
INSERT INTO `permission` VALUES ('5', '1', '角色设置', 'admin/user-role/edit', '', '0', '4', '2', '3', '1496393984', '3', '1496393984');
INSERT INTO `permission` VALUES ('6', '1', '修改密码', 'admin/user/password', '', '0', '5', '2', '3', '1496393991', '3', '1496393991');
INSERT INTO `permission` VALUES ('7', '1', '删除', 'admin/user/delete', null, '0', '6', '2', '3', '1496648217', '3', '1496726385');
INSERT INTO `permission` VALUES ('8', '0', '角色管理', '', '', '4', '2', '1', '3', '1496387795', '12', '1496732473');
INSERT INTO `permission` VALUES ('9', '8', '查看', 'admin/role/index', '', '0', '1', '2', '3', '1496393960', '3', '1496393960');
INSERT INTO `permission` VALUES ('10', '8', '添加', 'admin/role/create', '', '0', '2', '2', '3', '1496393965', '3', '1496393965');
INSERT INTO `permission` VALUES ('11', '8', '编辑', 'admin/role/update', '', '0', '3', '2', '3', '1496393971', '3', '1496393991');
INSERT INTO `permission` VALUES ('12', '8', '删除', 'admin/role/delete', '', '0', '4', '2', '3', '1496648217', '3', '1496726385');
INSERT INTO `permission` VALUES ('13', '0', '权限管理', '', '', '4', '3', '3', '3', '1496387795', '12', '1496732468');
INSERT INTO `permission` VALUES ('14', '13', '查看', '', '', '2', '1', '2', '3', '1496393960', '3', '1496393960');
INSERT INTO `permission` VALUES ('15', '14', '查看', 'admin/permission/index', '', '0', '1', '3', '3', '1496393960', '3', '1496393960');
INSERT INTO `permission` VALUES ('16', '14', '排序', 'admin/permission/order', '', '0', '2', '3', '3', '1496393965', '3', '1496393965');
INSERT INTO `permission` VALUES ('17', '13', '添加', 'admin/permission/create', '', '0', '2', '2', '3', '1496393965', '3', '1496393965');
INSERT INTO `permission` VALUES ('18', '13', '编辑', 'admin/permission/update', '', '0', '3', '2', '3', '1496393971', '3', '1496393991');
INSERT INTO `permission` VALUES ('19', '13', '删除', 'admin/permission/delete', '', '0', '4', '2', '3', '1496648217', '3', '1496726385');
INSERT INTO `permission` VALUES ('20', '0', '菜单管理', '', '', '4', '4', '1', '3', '1496387795', '3', '1496649296');
INSERT INTO `permission` VALUES ('21', '20', '查看', '', '', '2', '1', '2', '3', '1496393960', '3', '1496393960');
INSERT INTO `permission` VALUES ('22', '21', '查看', 'admin/menu/index', '', '0', '1', '3', '3', '1496393960', '3', '1496393960');
INSERT INTO `permission` VALUES ('23', '21', '排序', 'admin/menu/order', '', '0', '2', '3', '3', '1496393965', '3', '1496393965');
INSERT INTO `permission` VALUES ('24', '20', '添加', 'admin/menu/create', '', '0', '2', '2', '3', '1496393965', '3', '1496393965');
INSERT INTO `permission` VALUES ('25', '20', '编辑', 'admin/menu/update', '', '0', '3', '2', '3', '1496393971', '3', '1496393991');
INSERT INTO `permission` VALUES ('26', '20', '删除', 'admin/menu/delete', '', '0', '4', '2', '3', '1496648217', '3', '1496726385');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='角色';

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
) ENGINE=InnoDB AUTO_INCREMENT=323 DEFAULT CHARSET=utf8 COMMENT='角色权限';

-- ----------------------------
-- Records of role_permission
-- ----------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_role
-- ----------------------------
