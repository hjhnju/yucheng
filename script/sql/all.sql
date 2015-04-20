use xjd;

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

DROP TABLE IF EXISTS  `invest_fresh`;
CREATE TABLE `invest_fresh` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `loan_id` int(11) NOT NULL COMMENT '借款ID',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) COMMENT 'ID',
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新手标投资记录';

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

DROP TABLE IF EXISTS  `loan_log`;
CREATE TABLE `loan_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL COMMENT '借款ID',
  `user_id` int(11) NOT NULL COMMENT '操作人',
  `content` varchar(512) NOT NULL COMMENT '操作内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `ip` varchar(32) NOT NULL COMMENT '操作IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='借款操作记录';

DROP TABLE IF EXISTS  `loan_company`;
CREATE TABLE `loan_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL COMMENT '借款ID',
  `user_id` int(11) NOT NULL COMMENT '创建人',
  `school` varchar(255) NOT NULL COMMENT '学校',
  `area` varchar(64) NOT NULL COMMENT '区域',
  `assets` varchar(32) NOT NULL COMMENT '总资产',
  `employers` int(11) NOT NULL COMMENT '员工数',
  `years` int(11) NOT NULL COMMENT '注册年限',
  `funds` varchar(32) NOT NULL COMMENT '注册资金',
  `students` int(11) NOT NULL COMMENT '学生数',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='借款企业信息';

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

DROP TABLE IF EXISTS  `infos`;
CREATE TABLE `infos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `status` tinyint(3) unsigned NOT NULL COMMENT '发布状态:1-未发布，2-已发布',
  `type` tinyint(3) unsigned NOT NULL COMMENT '资讯类型:1-官方公告，2-媒体报道',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '序列化内容',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `publish_time` int(11) DEFAULT NULL COMMENT '发布时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资讯表';


DROP TABLE IF EXISTS  `loan_request`;
CREATE TABLE `loan_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '借款标题',
  `school_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '学校类型',
  `use_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '借款用途',
  `amount` decimal(10,2) NOT NULL COMMENT '借款金额',
  `interest` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '利率',
  `duration` tinyint(4) NOT NULL COMMENT '借款期限',
  `name` varchar(32) NOT NULL COMMENT '借款人',
  `prov_id` int(11) NOT NULL COMMENT '所在省',
  `city_id` int(11) NOT NULL COMMENT '所在地区',
  `contact` varchar(20) NOT NULL COMMENT '联系方式',
  `refund_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '还款方式',
  `content` varchar(1024) NOT NULL COMMENT '借款详情',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `update_uid` int(11) NOT NULL DEFAULT '0' COMMENT '跟进人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='借款申请表';








