/*
 Navicat Premium Data Transfer

 Source Server         : Beta
 Source Server Type    : MySQL
 Source Server Version : 50616
 Source Host           : xingjiaodai.mysql.rds.aliyuncs.com
 Source Database       : xjd

 Target Server Type    : MySQL
 Target Server Version : 50616
 File Encoding         : utf-8

 Date: 05/15/2015 16:43:53 PM
*/
use xjd;
set names utf8;
-- ----------------------------
--  Table structure for `apply`
-- ----------------------------
DROP TABLE IF EXISTS `apply`;
CREATE TABLE `apply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '贷款总额',
  `duration` int(11) unsigned DEFAULT '0' COMMENT '贷款期限，如果duration_type为1则以天为单位，如果duration_type为2则以月为单位',
  `duration_type` tinyint(4) DEFAULT '0' COMMENT '申请带块期限类型，1为天，2为月',
  `userid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '申请贷款人的id',
  `service_charge` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '服务费',
  `rate` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '贷款的利率',
  `status` tinyint(4) DEFAULT '0' COMMENT '融资申请状态',
  `start_time` int(11) DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) DEFAULT '0' COMMENT '结束时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建申请贷款的日期',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新贷款的日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `apply_attach`
-- ----------------------------
DROP TABLE IF EXISTS `apply_attach`;
CREATE TABLE `apply_attach` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apply_id` int(11) NOT NULL COMMENT '借款ID',
  `userid` int(11) NOT NULL COMMENT '用户ID',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '类别 1申请人及担保人信息材料 2学校基本信息材料 3学校财务证明材料',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `url` varchar(255) NOT NULL COMMENT '地址',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8 COMMENT='借款附件';

-- ----------------------------
--  Table structure for `apply_personal`
-- ----------------------------
DROP TABLE IF EXISTS `apply_personal`;
CREATE TABLE `apply_personal` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `realname` varchar(50) NOT NULL,
  `certificate` char(18) NOT NULL COMMENT '身份证号',
  `house_type` tinyint(4) NOT NULL COMMENT '住房类型',
  `detail_address` varchar(255) NOT NULL COMMENT '住房详细地址',
  `cellphone` int(11) NOT NULL DEFAULT '0' COMMENT '手机号码',
  `telephone` varchar(20) NOT NULL COMMENT '住宅电话',
  `scope_cash` tinyint(4) DEFAULT NULL COMMENT '现金账户余额',
  `scope_stock` tinyint(4) DEFAULT NULL COMMENT '股票、债券等其他有价证券资产',
  `is_criminal` tinyint(4) DEFAULT NULL COMMENT '是否有犯罪记录',
  `is_lawsuit` tinyint(4) DEFAULT '0' COMMENT '是否有未决诉讼',
  `apply_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `apply_school`
-- ----------------------------
DROP TABLE IF EXISTS `apply_school`;
CREATE TABLE `apply_school` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '学校的名字',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '学校的类型',
  `nature` tinyint(4) NOT NULL DEFAULT '0' COMMENT '学校主体性质',
  `province` int(11) NOT NULL DEFAULT '0' COMMENT '学校所在的省份',
  `city` int(11) NOT NULL DEFAULT '0' COMMENT '学校所在城市',
  `school_source` tinyint(4) NOT NULL COMMENT '该学校是从哪里了解到我们的，也就是学校对我们来将来源是哪',
  `year` int(5) NOT NULL DEFAULT '0' COMMENT '学校创建的时间，具体到年份',
  `is_annual_income` tinyint(4) DEFAULT '0' COMMENT '年收入',
  `is_profit` tinyint(4) DEFAULT '0' COMMENT '最近一年是否盈利',
  `is_other_business` tinyint(4) DEFAULT '0' COMMENT '该学校是否还有其他的业务',
  `address` varchar(255) NOT NULL COMMENT '学校的具体地址',
  `total_student` int(11) NOT NULL COMMENT '学校的学生总数',
  `staff` int(6) NOT NULL DEFAULT '0' COMMENT '学校的教职工数量',
  `purpose` tinyint(4) NOT NULL COMMENT '贷款用途',
  `guarantee_count` tinyint(4) DEFAULT '0' COMMENT '担保人数量',
  `branch_school` tinyint(4) NOT NULL DEFAULT '0' COMMENT '分校的数量',
  `apply_id` int(11) NOT NULL DEFAULT '0' COMMENT '与apply表中对应的申请id',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `apply_stock`
-- ----------------------------
DROP TABLE IF EXISTS `apply_stock`;
CREATE TABLE `apply_stock` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '名字',
  `weight` decimal(4,2) NOT NULL COMMENT '所占股份比例',
  `apply_id` int(11) NOT NULL DEFAULT '0' COMMENT '申请id',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
