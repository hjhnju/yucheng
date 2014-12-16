    CREATE DATABASE IF NOT EXISTS `xjd` DEFAULT CHARSET=utf8;
    
    use xjd;
    set names utf8;
    
    DROP TABLE IF EXISTS awards_regist;
    CREATE TABLE IF NOT EXISTS awards_regist (
    `userid` int(11) unsigned NOT NULL COMMENT '用户id',
    `status` int(11) unsigned NOT NULL COMMENT '领取状态:1-未达到, 2-已达到未领取，3-已领取',
    `amount` decimal(10,2) unsigned NOT NULL COMMENT '奖励金额',
    `memo` varchar(255) default NULL COMMENT '备注',
    `create_time` datetime NOT NULL COMMENT '创建时间',
    PRIMARY KEY  (`userid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '注册奖励表';
    
    DROP TABLE IF EXISTS awards_invite;
    CREATE TABLE IF NOT EXISTS awards_invite (
    `id` int(11) unsigned NOT NULL auto_increment COMMENT '自增id',
    `userid` int(11) unsigned NOT NULL COMMENT '用户id',
    `inviterid` int(11) unsigned NOT NULL COMMENT '邀请人id',
    `status` int(11) unsigned NOT NULL COMMENT '领取状态:1-未达到, 2-已达到未领取，3-已领取',
    `amount` decimal(10,2) unsigned NOT NULL COMMENT '奖励金额',
    `memo` varchar(255) default NULL COMMENT '备注',
    `create_time` datetime NOT NULL COMMENT '创建时间',
    PRIMARY KEY(`id`),
    UNIQUE (`userid`),
    index idx_inviterid(inviterid)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '邀请奖励表';
    
