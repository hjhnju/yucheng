CREATE DATABASE IF NOT EXISTS `xjd` DEFAULT CHARSET=utf8;
set names utf8;
use xjd;

DROP TABLE IF EXISTS  `user_login`;
CREATE TABLE `user_login` (
  `userid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `usertype` tinyint(3) NOT NULL DEFAULT '1' COMMENT '用户类型 1:个人用户 2:企业用户',
  `status` tinyint(3) unsigned NOT NULL COMMENT '是否允许登录',
  `name` varchar(100) DEFAULT NULL COMMENT '用户名',
  `passwd` varchar(33) NOT NULL COMMENT '用户密码md5值',
  `phone` varchar(12) DEFAULT NULL COMMENT '用户手机号',
  `email` varchar(50) DEFAULT NULL COMMENT '用户邮箱',
  `huifuid` varchar(50) DEFAULT NULL COMMENT '汇付用户ID',
  `isborrower` tinyint(3) NOT NULL DEFAULT 0 COMMENT '是否借款人0:否，1:是',
  `lastip` varchar(50) DEFAULT NULL COMMENT '最近登陆ip',
  `login_time` int(11) NOT NULL COMMENT '最近一次登录时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新信息时间',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS  `user_record`;
CREATE TABLE `user_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `userid` int(11) unsigned NOT NULL COMMENT '用户id',
  `status` tinyint(3) unsigned NOT NULL COMMENT '登录状态',
  `ip` varchar(50) NOT NULL COMMENT 'IP',
  `create_time` int(11) NOT NULL COMMENT '登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户登录历史纪录表';

DROP TABLE IF EXISTS  `user_corpinfo`;
CREATE TABLE `user_corpinfo` (
  `userid` int(11) unsigned NOT NULL COMMENT '用户id',
  `corpname` varchar(100) DEFAULT NULL COMMENT '企业名称',
  `busicode` varchar(50) DEFAULT NULL COMMENT '营业执照',
  `instucode` varchar(50) DEFAULT NULL COMMENT '组织机构代码证',
  `taxcode` varchar(255) DEFAULT NULL COMMENT '税务登记号',
  `area` int(11) DEFAULT NULL COMMENT '所在地',
  `years` int(11) DEFAULT NULL COMMENT '注册年限',
  `create_time` int(11) NOT NULL COMMENT '注册时间',
  `update_time` int(11) NOT NULL COMMENT '修改资料时间',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='企业用户信息表';

DROP TABLE IF EXISTS  `user_info`;
CREATE TABLE `user_info` (
  `userid` int(11) unsigned NOT NULL COMMENT '用户id',
  `realname` varchar(100) DEFAULT NULL COMMENT '用户真实姓名',
  `certificate_type` tinyint(3) unsigned NOT NULL COMMENT '证件类型',
  `certificate_content` varchar(50) DEFAULT NULL COMMENT '证件内容',
  `headurl` varchar(255) DEFAULT NULL COMMENT '头像URL',
  `create_time` int(11) NOT NULL COMMENT '注册时间',
  `update_time` int(11) NOT NULL COMMENT '修改资料时间',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS  `user_third`;
CREATE TABLE `user_third` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `userid` int(11) unsigned NOT NULL COMMENT '用户id',
  `authtype` tinyint(3) unsigned NOT NULL COMMENT '第三方登录类型 1:qq, 2:weibo, 3:weixin',
  `nickname` varchar(50) DEFAULT NULL COMMENT '用户第三方站点用户名',
  `openid` varchar(50) NOT NULL COMMENT '第三方认证ID',
  `create_time` int(11) NOT NULL COMMENT '绑定时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户第三方登陆表';

DROP TABLE IF EXISTS  `admin`;
CREATE TABLE `admin` (
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(32) NOT NULL COMMENT '姓名',
  `role` int(11) NOT NULL COMMENT '角色',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员信息表';

ALTER TABLE `user_login` ADD COLUMN `isborrower` TINYINT(3) NOT NULL DEFAULT '0' COMMENT '是否借款人' AFTER `huifuid`;

CREATE TABLE `user_invite` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `userid` int(11) unsigned NOT NULL COMMENT '用户id',
  `invitee` int(11) unsigned NOT NULL COMMENT '被邀请用户',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE(invitee)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8 COMMENT='用户邀请表';

