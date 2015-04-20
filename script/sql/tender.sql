CREATE TABLE `finance_tender` (
`orderId`  bigint(20) unsigned NOT NULL DEFAULT '0',
`proId` int(11) NOT NULL  COMMENT '借款ID',
`freezeTrxId` bigint(18) unsigned NOT NULL DEFAULT '0' COMMENT '冻结序列号', 
`amount` decimal(10,2) NOT NULL COMMENT '金额',
`status` tinyint(4) NOT NULL COMMENT '状态',
`create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
`update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
`comment` varchar(255) DEFAULT NULL COMMENT '备注',
 PRIMARY KEY (`orderId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='投标订单'
