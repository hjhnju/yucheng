CREATE DATABASE IF NOT EXISTS `xjd` DEFAULT CHARSET=utf8;

use xjd;
set names utf8;
    
DROP TABLE IF EXISTS  `awards_ticket`;
CREATE TABLE `awards_ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '奖券id',
  `type` tinyint(3) NOT NULL COMMENT '1:现金券，2:利息券，3:代金券',
  `value` decimal(10,2) NOT NULL COMMENT '价值',
  `valid_time` int(11) NOT NULL COMMENT '有效截止时间',
  `claim` varchar(32) DEFAULT NULL COMMENT '要求达成的条件',
  `userid` int(11) DEFAULT NULL COMMENT '拥有用户',
  `pay_time` int(11) NOT NULL COMMENT '兑换时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `memo` varchar(255) NOT NULL COMMENT '说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='奖券表';

CREATE TABLE `awards_entity` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `type` tinyint(3) NOT NULL COMMENT '',
  `value` decimal(10,2) NOT NULL COMMENT '价值',
  `valid_time` int(11) NOT NULL COMMENT '有效截止时间',
  `claim` varchar(32) DEFAULT NULL COMMENT '要求达成的条件',
  `userid` int(11) DEFAULT NULL COMMENT '拥有用户',
  `pay_time` int(11) NOT NULL COMMENT '兑换时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `memo` varchar(255) NOT NULL COMMENT '说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='实物奖励表';


DROP TABLE IF EXISTS  `awards_invite`;
CREATE TABLE `awards_invite` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `userid` int(11) unsigned NOT NULL COMMENT '用户id',
  `inviterid` int(11) unsigned NOT NULL COMMENT '邀请人id',
  `status` int(11) unsigned NOT NULL COMMENT '领取状态:1-未达到, 2-已达到未领取，3-已领取',
  `amount` decimal(10,2) unsigned NOT NULL COMMENT '奖励金额',
  `memo` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`),
  KEY `idx_inviterid` (`inviterid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='邀请奖励表';

DROP TABLE IF EXISTS  `awards_regist`;
CREATE TABLE `awards_regist` (
  `userid` int(11) unsigned NOT NULL COMMENT '用户id',
  `status` int(11) unsigned NOT NULL COMMENT '领取状态:1-未达到, 2-已达到未领取，3-已领取',
  `amount` decimal(10,2) unsigned NOT NULL COMMENT '奖励金额',
  `memo` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='注册奖励表';