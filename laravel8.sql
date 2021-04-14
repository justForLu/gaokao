/*
 Navicat Premium Data Transfer

 Source Server         : 本地数据库
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : laravel8

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 26/12/2020 09:24:38
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for zh_banner
-- ----------------------------
DROP TABLE IF EXISTS `zh_banner`;
CREATE TABLE `zh_banner`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'banner图标题',
  `image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'banner图地址',
  `url` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '跳转的链接',
  `position` tinyint(1) NOT NULL DEFAULT 0 COMMENT '位置。1首页，2设备区',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序（越大越靠前）',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态（1启用，2禁用）',
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '轮播图' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_banner
-- ----------------------------

-- ----------------------------
-- Table structure for zh_car
-- ----------------------------
DROP TABLE IF EXISTS `zh_car`;
CREATE TABLE `zh_car`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户ID',
  `name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '车主姓名',
  `car_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '车牌号',
  `car_brand` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '汽车品牌',
  `car_model` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '汽车型号',
  `car_frame` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '车架号',
  `car_engine` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '发动机型号',
  `car_delivery` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '出厂日期',
  `car_image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '汽车照片',
  `car_category` tinyint(1) NOT NULL DEFAULT 0 COMMENT '汽车分类（1家用车，2运输车，3工程车）',
  `car_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '汽车类型（1轿车）',
  `car_output` decimal(10, 2) NULL DEFAULT NULL COMMENT '汽车排量',
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '汽车表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_car
-- ----------------------------

-- ----------------------------
-- Table structure for zh_cash
-- ----------------------------
DROP TABLE IF EXISTS `zh_cash`;
CREATE TABLE `zh_cash`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `money` decimal(10, 2) NULL DEFAULT NULL COMMENT '提现金额',
  `bank_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '银行名称',
  `bank_branch` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '银行支行名称',
  `account_name` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '持卡人',
  `account_no` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '银行卡号',
  `alipay` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付宝账号',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '支付类型（1银行卡转账，2支付宝转账）',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态（0待审核，1审核中，2已提现，3已拒绝）',
  `examine_time` int(11) NOT NULL DEFAULT 0 COMMENT '审查时间',
  `admin_id` int(11) NOT NULL DEFAULT 0 COMMENT '操作管理员',
  `refuse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '拒绝理由',
  `remark` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '提现记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_cash
-- ----------------------------

-- ----------------------------
-- Table structure for zh_city
-- ----------------------------
DROP TABLE IF EXISTS `zh_city`;
CREATE TABLE `zh_city`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of zh_city
-- ----------------------------

-- ----------------------------
-- Table structure for zh_config
-- ----------------------------
DROP TABLE IF EXISTS `zh_config`;
CREATE TABLE `zh_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '值',
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '配置名称',
  `describe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `only_tag` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '唯一标签',
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `only_tag`(`only_tag`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_config
-- ----------------------------

-- ----------------------------
-- Table structure for zh_device
-- ----------------------------
DROP TABLE IF EXISTS `zh_device`;
CREATE TABLE `zh_device`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '设备表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_device
-- ----------------------------

-- ----------------------------
-- Table structure for zh_device_car
-- ----------------------------
DROP TABLE IF EXISTS `zh_device_car`;
CREATE TABLE `zh_device_car`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '设备汽车关联表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of zh_device_car
-- ----------------------------

-- ----------------------------
-- Table structure for zh_device_flow
-- ----------------------------
DROP TABLE IF EXISTS `zh_device_flow`;
CREATE TABLE `zh_device_flow`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '设备流量统计表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_device_flow
-- ----------------------------

-- ----------------------------
-- Table structure for zh_device_log
-- ----------------------------
DROP TABLE IF EXISTS `zh_device_log`;
CREATE TABLE `zh_device_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '设备日志' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_device_log
-- ----------------------------

-- ----------------------------
-- Table structure for zh_device_user
-- ----------------------------
DROP TABLE IF EXISTS `zh_device_user`;
CREATE TABLE `zh_device_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户ID',
  `device_id` int(11) NOT NULL DEFAULT 0 COMMENT '设备ID（一个用户可以有多个设备，一个设备只能有一个用户）',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态（0未绑定，1已绑定）',
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '设备用户关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_device_user
-- ----------------------------

-- ----------------------------
-- Table structure for zh_device_user_log
-- ----------------------------
DROP TABLE IF EXISTS `zh_device_user_log`;
CREATE TABLE `zh_device_user_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户ID',
  `device_id` int(11) NOT NULL DEFAULT 0 COMMENT '设备ID',
  `create_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '设备用户绑定日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_device_user_log
-- ----------------------------

-- ----------------------------
-- Table structure for zh_feedback
-- ----------------------------
DROP TABLE IF EXISTS `zh_feedback`;
CREATE TABLE `zh_feedback`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '联系人',
  `mobile` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `content` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '内容',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '处理状态（0待处理，1处理中，2处理完成）',
  `admin_id` int(11) NOT NULL DEFAULT 0 COMMENT '处理人',
  `handle_time` int(11) NOT NULL DEFAULT 0 COMMENT '处理时间',
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '投诉建议表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for zh_file
-- ----------------------------
DROP TABLE IF EXISTS `zh_file`;
CREATE TABLE `zh_file`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件地址',
  `original_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '原始文件名',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态',
  `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '类型',
  `gmt_create` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `gmt_update` datetime(0) NULL DEFAULT NULL,
  `note` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文件表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_file
-- ----------------------------

-- ----------------------------
-- Table structure for zh_log
-- ----------------------------
DROP TABLE IF EXISTS `zh_log`;
CREATE TABLE `zh_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '管理员id',
  `operate_module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求控制器',
  `operate_action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `operate_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '日志内容',
  `ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'IP',
  `module` tinyint(1) NOT NULL DEFAULT 0 COMMENT '前、后台',
  `create_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 86 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '后台日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_log
-- ----------------------------
INSERT INTO `zh_log` VALUES (4, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608608098);
INSERT INTO `zh_log` VALUES (5, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608618591);
INSERT INTO `zh_log` VALUES (6, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608618908);
INSERT INTO `zh_log` VALUES (7, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608618983);
INSERT INTO `zh_log` VALUES (8, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608619343);
INSERT INTO `zh_log` VALUES (9, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608619395);
INSERT INTO `zh_log` VALUES (10, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608620873);
INSERT INTO `zh_log` VALUES (11, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608624541);
INSERT INTO `zh_log` VALUES (12, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608624561);
INSERT INTO `zh_log` VALUES (13, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608624608);
INSERT INTO `zh_log` VALUES (14, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608624870);
INSERT INTO `zh_log` VALUES (15, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608624910);
INSERT INTO `zh_log` VALUES (16, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608625001);
INSERT INTO `zh_log` VALUES (17, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608625040);
INSERT INTO `zh_log` VALUES (18, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608625120);
INSERT INTO `zh_log` VALUES (19, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608625189);
INSERT INTO `zh_log` VALUES (20, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608625256);
INSERT INTO `zh_log` VALUES (21, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608625256);
INSERT INTO `zh_log` VALUES (22, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608625436);
INSERT INTO `zh_log` VALUES (23, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608625490);
INSERT INTO `zh_log` VALUES (24, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608625563);
INSERT INTO `zh_log` VALUES (25, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608626352);
INSERT INTO `zh_log` VALUES (26, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608626431);
INSERT INTO `zh_log` VALUES (27, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608626472);
INSERT INTO `zh_log` VALUES (28, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608626508);
INSERT INTO `zh_log` VALUES (29, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608631654);
INSERT INTO `zh_log` VALUES (30, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608685096);
INSERT INTO `zh_log` VALUES (31, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608688045);
INSERT INTO `zh_log` VALUES (32, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608690803);
INSERT INTO `zh_log` VALUES (33, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608691239);
INSERT INTO `zh_log` VALUES (34, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608691338);
INSERT INTO `zh_log` VALUES (35, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608696470);
INSERT INTO `zh_log` VALUES (36, 1, 'manager', 'store', '/admin/manager/store', '添加管理员', '127.0.0.1', 1, 1608712051);
INSERT INTO `zh_log` VALUES (37, 1, 'manager', 'store', '/admin/manager/store', '添加管理员', '127.0.0.1', 1, 1608712230);
INSERT INTO `zh_log` VALUES (38, 1, 'manager', 'store', '/admin/manager/store', '添加管理员', '127.0.0.1', 1, 1608712255);
INSERT INTO `zh_log` VALUES (39, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608771696);
INSERT INTO `zh_log` VALUES (40, 1, 'manager', 'store', '/admin/manager', '添加管理员', '127.0.0.1', 1, 1608771945);
INSERT INTO `zh_log` VALUES (41, 1, 'manager', 'store', '/admin/manager', '添加管理员', '127.0.0.1', 1, 1608774842);
INSERT INTO `zh_log` VALUES (42, 1, 'manager', 'update', '/admin/manager/6', '更新管理员信息', '127.0.0.1', 1, 1608775343);
INSERT INTO `zh_log` VALUES (43, 1, 'manager', 'update', '/admin/manager/3', '更新管理员信息', '127.0.0.1', 1, 1608775364);
INSERT INTO `zh_log` VALUES (44, 1, 'manager', 'store', '/admin/manager', '添加管理员', '127.0.0.1', 1, 1608777143);
INSERT INTO `zh_log` VALUES (45, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608781087);
INSERT INTO `zh_log` VALUES (46, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608788507);
INSERT INTO `zh_log` VALUES (47, 1, 'role', 'store', '/admin/role', '添加角色', '127.0.0.1', 1, 1608792897);
INSERT INTO `zh_log` VALUES (48, 1, 'role', 'store', '/admin/role', '添加角色', '127.0.0.1', 1, 1608792972);
INSERT INTO `zh_log` VALUES (49, 1, 'role', 'store', '/admin/role', '添加角色', '127.0.0.1', 1, 1608793028);
INSERT INTO `zh_log` VALUES (50, 1, 'role', 'store', '/admin/role', '添加角色', '127.0.0.1', 1, 1608793306);
INSERT INTO `zh_log` VALUES (51, 1, 'role', 'update', '/admin/role/4', '编辑角色', '127.0.0.1', 1, 1608793736);
INSERT INTO `zh_log` VALUES (52, 1, 'menu', 'store', '/admin/menu', '添加菜单', '127.0.0.1', 1, 1608795484);
INSERT INTO `zh_log` VALUES (53, 1, 'menu', 'update', '/admin/menu/21', '更新菜单', '127.0.0.1', 1, 1608796248);
INSERT INTO `zh_log` VALUES (54, 1, 'menu', 'update', '/admin/menu/21', '更新菜单', '127.0.0.1', 1, 1608796254);
INSERT INTO `zh_log` VALUES (55, 1, 'menu', 'update', '/admin/menu/3', '更新菜单', '127.0.0.1', 1, 1608796395);
INSERT INTO `zh_log` VALUES (56, 1, 'menu', 'update', '/admin/menu/3', '更新菜单', '127.0.0.1', 1, 1608796401);
INSERT INTO `zh_log` VALUES (57, 1, 'menu', 'update', '/admin/menu/21', '更新菜单', '127.0.0.1', 1, 1608796422);
INSERT INTO `zh_log` VALUES (58, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608858611);
INSERT INTO `zh_log` VALUES (59, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608858836);
INSERT INTO `zh_log` VALUES (60, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608859098);
INSERT INTO `zh_log` VALUES (61, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608859288);
INSERT INTO `zh_log` VALUES (62, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608859336);
INSERT INTO `zh_log` VALUES (63, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608859400);
INSERT INTO `zh_log` VALUES (64, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608859555);
INSERT INTO `zh_log` VALUES (65, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608860202);
INSERT INTO `zh_log` VALUES (66, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608860248);
INSERT INTO `zh_log` VALUES (67, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608860555);
INSERT INTO `zh_log` VALUES (68, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608860598);
INSERT INTO `zh_log` VALUES (69, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608860757);
INSERT INTO `zh_log` VALUES (70, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608860852);
INSERT INTO `zh_log` VALUES (71, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608860936);
INSERT INTO `zh_log` VALUES (72, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608860999);
INSERT INTO `zh_log` VALUES (73, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608861157);
INSERT INTO `zh_log` VALUES (74, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608862392);
INSERT INTO `zh_log` VALUES (75, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608865083);
INSERT INTO `zh_log` VALUES (76, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608865791);
INSERT INTO `zh_log` VALUES (77, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608866474);
INSERT INTO `zh_log` VALUES (78, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608866519);
INSERT INTO `zh_log` VALUES (79, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608866845);
INSERT INTO `zh_log` VALUES (80, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608868031);
INSERT INTO `zh_log` VALUES (81, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608868296);
INSERT INTO `zh_log` VALUES (82, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608874500);
INSERT INTO `zh_log` VALUES (83, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608876497);
INSERT INTO `zh_log` VALUES (84, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608876556);
INSERT INTO `zh_log` VALUES (85, 1, 'login', 'login', '/admin/login', '登录后台', '127.0.0.1', 1, 1608877578);

-- ----------------------------
-- Table structure for zh_manager
-- ----------------------------
DROP TABLE IF EXISTS `zh_manager`;
CREATE TABLE `zh_manager`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'session令牌',
  `gmt_last_login` datetime(0) NULL DEFAULT NULL COMMENT '最后登录时间',
  `last_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `wechat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '微信号',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态',
  `is_system` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否系统用户',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  `deleted_at` datetime(0) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '后台管理员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_manager
-- ----------------------------
INSERT INTO `zh_manager` VALUES (1, 'admin', '$2y$10$njkBfDd/cwLmmY25tq96neZj0wbxCye0x/vtCii8YnL6k5FfNyckK', 'VWrrJZZxgnG2IBW0U8t1mzp6pDjPQwXfwcBBbFJRSs8rrNGGAY8AD8nI0bck', '2020-12-23 10:44:38', '127.0.0.1', '15924789588', '123456', 1, 1, '2020-12-01 09:40:30', '2020-12-25 11:10:05', NULL);
INSERT INTO `zh_manager` VALUES (2, 'test', '$2y$10$K9ZqSDPveI6zuQjOgJj3OeWibeAevedhv5E6vOSLCo2qizF1GAUw.', 'LcIu9FjtB9uapTJXS5HTuMIAn0SgokQKWfjdIv0xBzJudwGvNciF7a0UgQ48', '2020-12-23 12:27:38', '127.0.0.1', '15633339999', '', 2, 0, '2020-12-23 09:28:10', '2020-12-23 10:27:38', NULL);
INSERT INTO `zh_manager` VALUES (3, '111', '$2y$10$/PDEu9LeNq1w8GJECdBRLOAYnJNyXoTMNZR7XhkM2dzPH2JsAhKii', '', NULL, '', '15987456987', '', 1, 0, '2020-12-23 16:27:31', '2020-12-24 10:02:44', NULL);
INSERT INTO `zh_manager` VALUES (4, '888', '$2y$10$QwVBSryEqycCfI.ZK/ysmOwtnlDJquZxCHzqjLIZCyVZ/hWO4sA6C', '', NULL, '', '15968745896', '', 1, 0, '2020-12-24 10:32:23', '2020-12-24 10:32:23', NULL);
INSERT INTO `zh_manager` VALUES (8, '888', '$2y$10$QwVBSryEqycCfI.ZK/ysmOwtnlDJquZxCHzqjLIZCyVZ/hWO4sA6C', '', NULL, '', '15968745896', '', 1, 0, '2020-12-24 10:32:23', '2020-12-24 10:32:23', NULL);

-- ----------------------------
-- Table structure for zh_menu
-- ----------------------------
DROP TABLE IF EXISTS `zh_menu`;
CREATE TABLE `zh_menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '编码',
  `parent` int(11) NOT NULL DEFAULT 0 COMMENT '父级id',
  `path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单地址',
  `grade` tinyint(4) NOT NULL DEFAULT 0 COMMENT '菜单等级',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '菜单排序',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '菜单状态',
  `icon` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `module` tinyint(4) NOT NULL DEFAULT 1 COMMENT '所属模块',
  `is_system` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否系统菜单',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '菜单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_menu
-- ----------------------------
INSERT INTO `zh_menu` VALUES (1, '系统配置', 'system', 0, '0,1,1', '/system', 1, 1, 1, 'layui-icon-set', 1, 0);
INSERT INTO `zh_menu` VALUES (2, '管理员管理', 'manager', 1, '0,1,2', '/manager', 2, 2, 1, '', 1, 0);
INSERT INTO `zh_menu` VALUES (3, '角色管理', 'role', 1, '0,1,1,3', '/role', 2, 3, 1, '', 1, 0);
INSERT INTO `zh_menu` VALUES (4, '权限管理', 'permission', 1, '0,1,4', '/permission', 2, 4, 1, '', 1, 1);
INSERT INTO `zh_menu` VALUES (5, '菜单管理', 'menu', 1, '0,1,5', '/menu', 2, 5, 1, '', 1, 1);
INSERT INTO `zh_menu` VALUES (6, '日志列表', 'log', 1, '0,1,6', '/log', 2, 6, 1, '', 1, 1);

-- ----------------------------
-- Table structure for zh_money_log
-- ----------------------------
DROP TABLE IF EXISTS `zh_money_log`;
CREATE TABLE `zh_money_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户ID',
  `money` decimal(10, 2) NOT NULL COMMENT '金额变化（增加为正，减少为负）',
  `after_money` decimal(10, 2) NOT NULL COMMENT '变化后金额',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '类型（）',
  `admin_id` int(11) NOT NULL DEFAULT 0 COMMENT '后台操作管理员',
  `describe` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '金额变化描述',
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户金额变更日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_money_log
-- ----------------------------

-- ----------------------------
-- Table structure for zh_order
-- ----------------------------
DROP TABLE IF EXISTS `zh_order`;
CREATE TABLE `zh_order`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '设备充值订单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_order
-- ----------------------------

-- ----------------------------
-- Table structure for zh_payment
-- ----------------------------
DROP TABLE IF EXISTS `zh_payment`;
CREATE TABLE `zh_payment`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付名称',
  `only_tag` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '唯一标识',
  `icon` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付图标',
  `describe` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态（1启用，2禁用）',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序（越大越靠前）',
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '支付方式表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_payment
-- ----------------------------

-- ----------------------------
-- Table structure for zh_permission
-- ----------------------------
DROP TABLE IF EXISTS `zh_permission`;
CREATE TABLE `zh_permission`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `menu_id` int(11) NOT NULL DEFAULT 1 COMMENT '所属菜单id',
  `module` tinyint(4) NOT NULL DEFAULT 0 COMMENT '所属模块',
  `is_system` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否系统权限',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `wx_permissions_code_unique`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 51 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_permission
-- ----------------------------
INSERT INTO `zh_permission` VALUES (1, '菜单列表', 'menu.index', '', 5, 1, 1);
INSERT INTO `zh_permission` VALUES (2, '菜单添加', 'menu.create,menu.store', '', 5, 1, 1);
INSERT INTO `zh_permission` VALUES (3, '菜单修改', 'menu.edit,menu.update', '', 5, 1, 1);
INSERT INTO `zh_permission` VALUES (4, '菜单查看', 'menu.show', '', 5, 1, 1);
INSERT INTO `zh_permission` VALUES (5, '菜单删除', 'menu.destroy', '', 5, 1, 1);
INSERT INTO `zh_permission` VALUES (6, '管理员列表', 'manager.index', '', 2, 1, 0);
INSERT INTO `zh_permission` VALUES (7, '管理员添加', 'manager.create,manager.store', '', 2, 1, 0);
INSERT INTO `zh_permission` VALUES (8, '管理员修改', 'manager.edit,manager.update', '', 2, 1, 0);
INSERT INTO `zh_permission` VALUES (9, '管理员查看', 'manager.show', '', 2, 1, 0);
INSERT INTO `zh_permission` VALUES (10, '管理员删除', 'manager.destroy', '', 2, 1, 0);
INSERT INTO `zh_permission` VALUES (11, '权限列表', 'permission.index', '', 4, 1, 1);
INSERT INTO `zh_permission` VALUES (12, '权限添加', 'permission.create,permission.store', '', 4, 1, 1);
INSERT INTO `zh_permission` VALUES (13, '权限修改', 'permission.edit,permission.update', '', 4, 1, 1);
INSERT INTO `zh_permission` VALUES (14, '权限查看', 'permission.show', '', 4, 1, 1);
INSERT INTO `zh_permission` VALUES (15, '权限删除', 'permission.destroy', '', 4, 1, 1);
INSERT INTO `zh_permission` VALUES (16, '角色列表', 'role.index', '', 3, 1, 0);
INSERT INTO `zh_permission` VALUES (17, '角色添加', 'role.create,role.store', '', 3, 1, 0);
INSERT INTO `zh_permission` VALUES (18, '角色修改', 'role.edit,role.update', '', 3, 1, 0);
INSERT INTO `zh_permission` VALUES (19, '角色查看', 'role.show', '', 3, 1, 0);
INSERT INTO `zh_permission` VALUES (20, '角色删除', 'role.destroy', '', 3, 1, 0);
INSERT INTO `zh_permission` VALUES (21, '角色授权', 'role.authority', '', 3, 1, 0);
INSERT INTO `zh_permission` VALUES (22, '新闻列表', 'news.index', '', 10, 1, 0);
INSERT INTO `zh_permission` VALUES (23, '新闻添加', 'news.create,news.store', '', 10, 1, 0);
INSERT INTO `zh_permission` VALUES (24, '新闻编辑', 'news.edit,news.update', '', 10, 1, 0);
INSERT INTO `zh_permission` VALUES (25, '新闻删除', 'news.destroy', '', 10, 1, 0);
INSERT INTO `zh_permission` VALUES (26, '产品列表', 'product.index', '', 11, 1, 0);
INSERT INTO `zh_permission` VALUES (27, '产品添加', 'product.create,product.store', '', 11, 1, 0);
INSERT INTO `zh_permission` VALUES (28, '产品编辑', 'product.edit,product.update', '', 11, 1, 0);
INSERT INTO `zh_permission` VALUES (29, '产品删除', 'product.destroy', '', 11, 1, 0);
INSERT INTO `zh_permission` VALUES (30, '案例列表', 'reveal.index', '', 14, 1, 0);
INSERT INTO `zh_permission` VALUES (31, '案例添加', 'reveal.create,reveal.store', '', 14, 1, 0);
INSERT INTO `zh_permission` VALUES (32, '案例编辑', 'reveal.edit,reveal.update', '', 14, 1, 0);
INSERT INTO `zh_permission` VALUES (33, '案例删除', 'reveal.destroy', '', 14, 1, 0);
INSERT INTO `zh_permission` VALUES (34, '网站配置', 'config.index', '', 15, 1, 0);
INSERT INTO `zh_permission` VALUES (35, '网站配置编辑', 'config.edit,config.update', '', 15, 1, 0);
INSERT INTO `zh_permission` VALUES (36, '关于我们', 'about.index', '', 16, 1, 0);
INSERT INTO `zh_permission` VALUES (37, '关于我们编辑', 'about.edit,about.update', '', 16, 1, 0);
INSERT INTO `zh_permission` VALUES (38, '联系我们', 'contact.index', '', 17, 1, 0);
INSERT INTO `zh_permission` VALUES (39, '联系我们编辑', 'contact.edit,contact.update', '', 17, 1, 0);
INSERT INTO `zh_permission` VALUES (40, '背景图列表', 'banner.index', '', 18, 1, 0);
INSERT INTO `zh_permission` VALUES (41, '背景图添加', 'banner.create,banner.store', '', 18, 1, 0);
INSERT INTO `zh_permission` VALUES (42, '背景图编辑', 'banner.edit,banner.update', '', 18, 1, 0);
INSERT INTO `zh_permission` VALUES (43, '背景图删除', 'banner.destroy', '', 18, 1, 0);
INSERT INTO `zh_permission` VALUES (44, '分类列表', 'category.index', '', 19, 1, 0);
INSERT INTO `zh_permission` VALUES (45, '分类添加', 'category.create,category.store', '', 19, 1, 0);
INSERT INTO `zh_permission` VALUES (46, '分类编辑', 'category.edit,category.update', '', 19, 1, 0);
INSERT INTO `zh_permission` VALUES (47, '分类删除', 'category.destroy', '', 19, 1, 0);
INSERT INTO `zh_permission` VALUES (48, '反馈管理', 'feedback.index', '', 20, 1, 0);
INSERT INTO `zh_permission` VALUES (49, '反馈编辑', 'feedback.edit,feedback.update', '', 20, 1, 0);
INSERT INTO `zh_permission` VALUES (50, '反馈删除', 'feedback.destroy', '', 20, 1, 0);

-- ----------------------------
-- Table structure for zh_permission_role
-- ----------------------------
DROP TABLE IF EXISTS `zh_permission_role`;
CREATE TABLE `zh_permission_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `role_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `module` tinyint(4) NULL DEFAULT NULL COMMENT '所属模块',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色权限表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_permission_role
-- ----------------------------
INSERT INTO `zh_permission_role` VALUES (1, 22, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (2, 23, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (3, 24, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (4, 25, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (5, 26, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (6, 27, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (7, 28, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (8, 29, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (9, 30, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (10, 31, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (11, 32, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (12, 33, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (13, 34, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (14, 35, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (15, 36, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (16, 37, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (17, 39, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (18, 40, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (19, 38, 2, NULL);
INSERT INTO `zh_permission_role` VALUES (20, 16, 4, NULL);
INSERT INTO `zh_permission_role` VALUES (21, 17, 4, NULL);
INSERT INTO `zh_permission_role` VALUES (23, 19, 4, NULL);
INSERT INTO `zh_permission_role` VALUES (24, 20, 4, NULL);
INSERT INTO `zh_permission_role` VALUES (25, 21, 4, NULL);
INSERT INTO `zh_permission_role` VALUES (26, 7, 4, 1);
INSERT INTO `zh_permission_role` VALUES (27, 18, 4, 1);

-- ----------------------------
-- Table structure for zh_role
-- ----------------------------
DROP TABLE IF EXISTS `zh_role`;
CREATE TABLE `zh_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `module` tinyint(4) NOT NULL DEFAULT 1 COMMENT '所属模块',
  `is_system` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_role
-- ----------------------------
INSERT INTO `zh_role` VALUES (1, '超级管理员', '超级管理员', 1, 1, 1, 0, 0);
INSERT INTO `zh_role` VALUES (4, '测试管理员', '这是测试管理员', 1, 0, 0, 1608792897, 1608793736);

-- ----------------------------
-- Table structure for zh_role_user
-- ----------------------------
DROP TABLE IF EXISTS `zh_role_user`;
CREATE TABLE `zh_role_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `role_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `module` tinyint(4) NULL DEFAULT NULL COMMENT '所属模块',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户角色关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_role_user
-- ----------------------------
INSERT INTO `zh_role_user` VALUES (1, 1, 1, 1);
INSERT INTO `zh_role_user` VALUES (2, 2, 2, 1);
INSERT INTO `zh_role_user` VALUES (3, 3, 1, 1);
INSERT INTO `zh_role_user` VALUES (4, 4, 1, 1);
INSERT INTO `zh_role_user` VALUES (8, 8, 1, 1);

-- ----------------------------
-- Table structure for zh_team
-- ----------------------------
DROP TABLE IF EXISTS `zh_team`;
CREATE TABLE `zh_team`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '团队表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_team
-- ----------------------------

-- ----------------------------
-- Table structure for zh_team_report
-- ----------------------------
DROP TABLE IF EXISTS `zh_team_report`;
CREATE TABLE `zh_team_report`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '团队统计表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of zh_team_report
-- ----------------------------

-- ----------------------------
-- Table structure for zh_user
-- ----------------------------
DROP TABLE IF EXISTS `zh_user`;
CREATE TABLE `zh_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '加密盐',
  `head_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `real_name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `nickname` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `province` int(11) NOT NULL DEFAULT 0 COMMENT '省份',
  `city` int(11) NOT NULL DEFAULT 0 COMMENT '城市',
  `area` int(11) NOT NULL DEFAULT 0 COMMENT '区/县',
  `address` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '详细地址',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `money` decimal(10, 2) NULL DEFAULT NULL COMMENT '余额',
  `invite_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '邀请码',
  `role` tinyint(1) NOT NULL DEFAULT 0 COMMENT '角色名称',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态（1启用，2禁用）',
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  `delete_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of zh_user
-- ----------------------------

-- ----------------------------
-- Table structure for zh_user_address
-- ----------------------------
DROP TABLE IF EXISTS `zh_user_address`;
CREATE TABLE `zh_user_address`  (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户地址表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of zh_user_address
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
