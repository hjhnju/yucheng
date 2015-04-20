CREATE DATABASE IF NOT EXISTS `xjd` DEFAULT CHARSET=utf8;

use xjd;
set names utf8;

DROP TABLE IF EXISTS  `infos`;
CREATE TABLE `infos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `status` tinyint(3) unsigned NOT NULL COMMENT '发布状态:1-未发布，2-已发布',
  `type` tinyint(3) unsigned NOT NULL COMMENT '资讯类型:1-官方公告，2-媒体报道',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `abstract` varchar(500) DEFAULT NULL,
  `content` text NOT NULL COMMENT '序列化内容',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `publish_time` int(11) DEFAULT NULL COMMENT '发布时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资讯表';


DROP TABLE IF EXISTS  `msg`;
CREATE TABLE `msg` (
  `mid` int(11) NOT NULL AUTO_INCREMENT COMMENT '消息ID',
  `sender` int(11) NOT NULL COMMENT '发送人',
  `receiver` int(11) NOT NULL COMMENT '接收人',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '消息标题',
  `type` varchar(50) DEFAULT NULL COMMENT '消息类型',
  `content` varchar(4096) NOT NULL COMMENT '消息内容',
  `status` tinyint(3) NOT NULL DEFAULT '2' COMMENT '状态 1已读 2未读 -1删除',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发送时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `read_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读时间',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消息';
