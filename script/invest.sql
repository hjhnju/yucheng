CREATE TABLE `invest` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '投标ID',
  `loan_id` int(11) NOT NULL COMMENT '借款ID',
  `user_id` int(11) NOT NULL COMMENT '投资人ID',
  `order_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `name` varchar(32) NOT NULL COMMENT '姓名',
  `duration` smallint(5) unsigned NOT NULL COMMENT '投资周期',
  `interest` decimal(10,2) NOT NULL COMMENT '投资利率',
  `amount` decimal(10,2) NOT NULL COMMENT '投资金额',
  `capital_refund` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已还本金',
  `capital_rest` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '待还本金',
  `amount_refund` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已回收收益',
  `amount_rest` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '待回收收益',
  `income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '预期收益',
  `fail_info` varchar(255) DEFAULT NULL COMMENT '失败原因',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态:1-审核中，5-回款中',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8 COMMENT='借款的投标记录';

CREATE TABLE `invest_fresh` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `loan_id` int(11) NOT NULL COMMENT '借款ID',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) COMMENT 'ID',
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8 COMMENT='新手标投资记录';

CREATE TABLE `invest_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '计划ID',
  `invest_id` int(11) NOT NULL COMMENT '投标ID',
  `loan_id` int(11) NOT NULL COMMENT '借款ID',
  `user_id` int(11) NOT NULL COMMENT '投资人ID',
  `period` tinyint(4) NOT NULL COMMENT '期数',
  `capital` decimal(10,2) NOT NULL COMMENT '本金',
  `capital_refund` decimal(10,2) NOT NULL COMMENT '已回收本金',
  `capital_rest` decimal(10,2) NOT NULL COMMENT '剩余本金',
  `interest` decimal(10,2) NOT NULL COMMENT '利息',
  `amount` decimal(10,2) NOT NULL COMMENT '应还本息',
  `late_charge` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '逾期罚息',
  `promise_time` int(10) unsigned NOT NULL COMMENT '应还日期',
  `refund_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回款时间',
  `transfer` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否打款 0未打款 1已打款',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 1正常 2已还 3逾期',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `invest_id` (`invest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8 COMMENT='借款的还款计划';