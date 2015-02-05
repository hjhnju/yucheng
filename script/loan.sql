
DROP TABLE IF EXISTS  `loan`;
CREATE TABLE `loan` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '借款ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `order_id` bigint(20) NOT NULL COMMENT '订单号',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `pic` varchar(255) DEFAULT NULL COMMENT '图片',
  `area` int(11) NOT NULL COMMENT '所在地',
  `content` varchar(1024) NOT NULL COMMENT '借款详情',
  `type_id` tinyint(4) NOT NULL COMMENT '标的类型',
  `cat_id` tinyint(4) NOT NULL COMMENT '借款类型',
  `fresh` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否新手标',
  `duration` smallint(5) unsigned NOT NULL COMMENT '借款期限',
  `level` tinyint(4) NOT NULL COMMENT '信用级别',
  `amount` decimal(10,2) NOT NULL COMMENT '借款金额',
  `interest` decimal(10,2) NOT NULL DEFAULT '12.00' COMMENT '利率',
  `invest_cnt` int(11) NOT NULL DEFAULT '0' COMMENT '投标总数',
  `invest_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已投资金额',
  `safe_id` varchar(255) NOT NULL COMMENT '保障方式 逗号分隔',
  `refund_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '还款方式',
  `audit_info` varchar(512) DEFAULT NULL COMMENT '审核信息',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `deadline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '截止时间',
  `risk_rate` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT '风险准备金',
  `serv_rate` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT '融资服务费',
  `mang_rate` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT '账户管理费',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `create_uid` int(11) NOT NULL COMMENT '创建人',
  `full_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '满标时间',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '放款时间',
  `fail_info` varchar(255) DEFAULT NULL COMMENT '失败原因',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100000 DEFAULT CHARSET=utf8 COMMENT='借款信息表';

DROP TABLE IF EXISTS  `loan_attach`;
CREATE TABLE `loan_attach` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL COMMENT '借款ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '类别 1认证 2合同 3实地照片',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `url` varchar(255) NOT NULL COMMENT '地址',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='借款附件';

DROP TABLE IF EXISTS  `loan_audit`;
CREATE TABLE `loan_audit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL COMMENT '借款ID',
  `user_id` int(11) NOT NULL COMMENT '创建人',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '认证类型 1企业 2担保',
  `name` varchar(32) NOT NULL COMMENT '认证项 英文',
  `comment` varchar(255) NOT NULL COMMENT '备注',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 1通过 2未通过',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='借款审核信息';

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

DROP TABLE IF EXISTS  `loan_counter`;
CREATE TABLE `loan_counter` (
  `userid` int(11) NOT NULL COMMENT '用户ID',
  `success` int(11) NOT NULL DEFAULT '0' COMMENT '成功借款次数',
  `limit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '授信额度',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '累计借款',
  `finished` int(11) NOT NULL DEFAULT '0' COMMENT '还清笔数',
  `refund` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已还金额',
  `rest` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '待还本息',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态 0正常',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='借款统计数据';

DROP TABLE IF EXISTS  `loan_guarantee`;
CREATE TABLE `loan_guarantee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL COMMENT '借款ID',
  `user_id` int(11) NOT NULL COMMENT '创建人ID',
  `name` varchar(255) NOT NULL COMMENT '担保人',
  `account` varchar(255) DEFAULT NULL COMMENT '户口',
  `age` tinyint(4) DEFAULT NULL COMMENT '年龄',
  `marriage` tinyint(4) DEFAULT NULL COMMENT '婚姻',
  `company_type` varchar(255) DEFAULT NULL COMMENT '企业类型',
  `job_title` varchar(255) DEFAULT NULL COMMENT '职务',
  `income` varchar(255) DEFAULT NULL COMMENT '收入',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='借款担保信息';

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

DROP TABLE IF EXISTS  `loan_refund`;
CREATE TABLE `loan_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL COMMENT '借款ID',
  `user_id` int(11) NOT NULL COMMENT '借款人ID',
  `period` tinyint(4) NOT NULL COMMENT '期数',
  `capital` decimal(10,2) NOT NULL COMMENT '本金',
  `capital_rest` decimal(10,2) NOT NULL COMMENT '剩余本金',
  `capital_refund` decimal(10,2) NOT NULL COMMENT '已还本金',
  `interest` decimal(10,2) NOT NULL COMMENT '利息',
  `amount` decimal(10,2) NOT NULL COMMENT '应还本息',
  `late_charge` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '逾期罚息',
  `promise_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '应还日期',
  `refund_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '还款时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 1正常 2已还 3逾期',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='借款的还款计划';

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