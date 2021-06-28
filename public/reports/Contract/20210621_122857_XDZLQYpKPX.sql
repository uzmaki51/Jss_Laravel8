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

 Date: 16/06/2021 15:56:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tbl_voy_settle_fuel
-- ----------------------------
DROP TABLE IF EXISTS `tbl_voy_settle_fuel`;
CREATE TABLE `tbl_voy_settle_fuel`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `shipId` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `voyId` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `rob_fo_1` decimal(10, 2) NULL DEFAULT NULL,
  `rob_do_1` decimal(10, 2) NULL DEFAULT NULL,
  `rob_fo_2` decimal(10, 2) NULL DEFAULT NULL,
  `rob_do_2` decimal(10, 2) NULL DEFAULT NULL,
  `used_fo` decimal(10, 2) NULL DEFAULT NULL,
  `used_do` decimal(10, 2) NULL DEFAULT NULL,
  `rob_fo_price_1` decimal(10, 2) NULL DEFAULT NULL,
  `rob_fo_price_2` decimal(10, 2) NULL DEFAULT NULL,
  `rob_do_price_1` decimal(10, 2) NULL DEFAULT NULL,
  `rob_do_price_2` decimal(10, 2) NULL DEFAULT NULL,
  `total_fo` decimal(20, 2) NULL DEFAULT NULL,
  `total_do` decimal(20, 2) NULL DEFAULT NULL,
  `total_fo_price` decimal(20, 2) NULL DEFAULT NULL,
  `total_do_price` decimal(20, 2) NULL DEFAULT NULL,
  `total_fo_diff` decimal(20, 2) NULL DEFAULT NULL,
  `total_do_diff` decimal(20, 2) NULL DEFAULT NULL,
  `total_fo_price_diff` decimal(20, 2) NULL DEFAULT NULL,
  `total_do_price_diff` decimal(20, 2) NULL DEFAULT NULL,
  `create_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `update_at` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

SET FOREIGN_KEY_CHECKS = 1;
