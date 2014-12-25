CREATE DATABASE IF NOT EXISTS `xjd` DEFAULT CHARSET=utf8;

use xjd;
set names utf8;

DROP TABLE IF EXISTS infos;
CREATE TABLE IF NOT EXISTS infos (
`id` int(11) unsigned NOT NULL auto_increment COMMENT '自增id',
`status` tinyint(3) unsigned NOT NULL COMMENT '发布状态:1-未发布，2-已发布',
`type` tinyint(3) unsigned NOT NULL COMMENT '资讯类型:1-官方公告，2-媒体报道',
`title` varchar(255) NOT NULL COMMENT '标题',
`content` text NOT NULL COMMENT '序列化内容',
`author` varchar(50) NOT NULL COMMENT '作者',
`publish_time` int default NULL COMMENT '发布时间',
`create_time` int NOT NULL COMMENT '创建时间',
`update_time` int NOT NULL COMMENT '修改时间',
PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '资讯表';
