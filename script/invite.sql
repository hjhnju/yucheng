CREATE DATABASE IF NOT EXISTS `xjd` DEFAULT CHARSET=utf8;

use xjd;
set names utf8;

DROP TABLE IF EXISTS invite;
CREATE TABLE IF NOT EXISTS invite (
`id` int(11) unsigned NOT NULL auto_increment COMMENT '自增id',
`userid` int(11) unsigned NOT NULL COMMENT '用户id',
`inviterid` int(11) unsigned NOT NULL COMMENT '邀请人id',
`create_time` datetime NOT NULL COMMENT '创建时间',
PRIMARY KEY  (`id`),
index idx_userid(userid),
index idx_inviterid(inviterid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '邀请表';

DROP TABLE IF EXISTS invite_awards;
CREATE TABLE IF NOT EXISTS invite_awards (
`id` int(11) unsigned NOT NULL auto_increment COMMENT '自增id',
`userid` int(11) unsigned NOT NULL COMMENT '用户id',
`type` tinyint(3) unsigned NOT NULL COMMENT '奖励类型: 1-注册奖励, 2-邀请奖励',
`status` int(11) unsigned NOT NULL COMMENT '领取状态:1-未达到, 2-已达到未领取，3-已领取',
`amount` decimal(10,2) unsigned NOT NULL COMMENT '奖励金额',
`create_time` datetime NOT NULL COMMENT '创建时间',
PRIMARY KEY  (`id`),
index idx_userid(userid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '奖励表';

