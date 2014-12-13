REATE DATABASE IF NOT EXISTS `xjd` DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS user_login (
`uid` int(11) unsigned NOT NULL auto_increment COMMENT '用户id',
`status` tinyint(3) unsigned NOT NULL COMMENT '是否允许登录',
`name` varchar(100) default '' COMMENT '用户名',
`passwd` varchar(33) default '' COMMENT '用户密码md5值',
`phone` varchar(12) default '' COMMENT '用户手机号',
`email` varchar(255) default '' COMMENT '用户邮箱',
`ip` varchar(16) default '' COMMENT 'IP',
`login_time` datetime NOT NULL COMMENT '最近一次登录时间',
`create_time` datetime NOT NULL COMMENT '注册时间',
PRIMARY KEY  (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS user_third_login (
`id` int(11) unsigned NOT NULL auto_increment COMMENT '自增id',
`uid` int(11) unsigned NOT NULL COMMENT '用户id',
`type` tinyint(3) unsigned NOT NULL COMMENT '第三方登录类型',
`nickname` varchar(100) default '' COMMENT '用户第三方站点用户名',
`openid` varchar(50) NOT NULL COMMENT '第三方认证ID',
`create_time` datetime NOT NULL COMMENT '绑定时间',
PRIMARY KEY  (`id`),
INDEX uid_type ( `uid`, `type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS user_info (
`uid` int(11) unsigned NOT NULL COMMENT '用户id',
`type` tinyint(3) unsigned NOT NULL COMMENT '用户类型',
`real_name` varchar(100) default '' COMMENT '用户真实姓名',
`certificate_type` tinyint(11) unsigned NOT NULL COMMENT '证件类型',
`certificate_content` varchar(50) default '' COMMENT '证件内容',
`headurl` varchar(200) default '' COMMENT '头像URL',
`huifu_uid` varchar(50) default '' COMMENT '汇付用户ID',
`create_time` datetime NOT NULL COMMENT '注册时间',
`update_time` datetime NOT NULL COMMENT '修改资料时间',
PRIMARY KEY  (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS login_record (
`id` int(11) unsigned NOT NULL auto_increment COMMENT '自增id',
`uid` int(11) unsigned NOT NULL COMMENT '用户id',
`status` tinyint(3) unsigned NOT NULL COMMENT '登录状态',
`ip` varchar(16) NOT NULL COMMENT 'IP',
`create_time` datetime NOT NULL COMMENT '登录时间',
PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
