/*
 Navicat Premium Data Transfer

 Source Server         : PHP_5.6
 Source Server Type    : MySQL
 Source Server Version : 100138
 Source Host           : localhost:3306
 Source Schema         : jss_ship_db

 Target Server Type    : MySQL
 Target Server Version : 100138
 File Encoding         : 65001

 Date: 16/06/2021 15:56:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tbl_voy_settle_main
-- ----------------------------
DROP TABLE IF EXISTS `tbl_voy_settle_main`;
CREATE TABLE `tbl_voy_settle_main`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `shipId` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `voyId` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `load_date` datetime(0) NULL DEFAULT NULL,
  `dis_date` datetime(0) NULL DEFAULT NULL,
  `total_sail_time` decimal(10, 2) NULL DEFAULT NULL,
  `sail_time` decimal(10, 2) NULL DEFAULT NULL,
  `load_time` decimal(10, 2) NULL DEFAULT NULL,
  `cargo_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `voy_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cgo_qty` decimal(20, 2) NULL DEFAULT NULL,
  `freight_price` decimal(20, 2) NULL DEFAULT NULL,
  `freight` decimal(20, 2) NULL DEFAULT NULL,
  `total_distance` decimal(20, 0) NULL DEFAULT NULL,
  `lport` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `dport` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `avg_speed` decimal(10, 2) NULL DEFAULT NULL,
  `com_fee` int(10) NULL DEFAULT NULL,
  `create_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `update_at` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

SET FOREIGN_KEY_CHECKS = 1;
