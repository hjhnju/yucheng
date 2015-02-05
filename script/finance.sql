use xjd;
set names utf8;

DROP TABLE IF EXISTS  `finance_order`;
CREATE TABLE `finance_order` (
  `orderId` bigint(20) unsigned NOT NULL DEFAULT '0',
  `orderDate` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '订单日期',
  `userId` int(11) DEFAULT NULL,
  `type` tinyint(4) NOT NULL COMMENT '支付类型',
  `amount` decimal(10,2) NOT NULL COMMENT '金额',
  `avlBal` decimal(10,2) NOT NULL COMMENT '可用余额',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `failCode` varchar(50) DEFAULT NULL COMMENT '失败返回码',
  `failDesc` varchar(50) DEFAULT NULL COMMENT '失败描述',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `comment` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`orderId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付订单';


DROP TABLE IF EXISTS `finance_record`;
CREATE TABLE `finance_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` bigint(20) unsigned DEFAULT NULL,
  `orderDate` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '订单日期',
  `userId` int(11) DEFAULT NULL,
  `type` tinyint(4) NOT NULL COMMENT '类型',
  `amount` decimal(10,2) NOT NULL COMMENT '交易金额',
  `balance` decimal(10,2) NOT NULL COMMENT '用户账户余额',
  `total` decimal(10,2) NOT NULL COMMENT '系统总额',
  `comment` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `ip` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资金记录'