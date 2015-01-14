<?php 
/**
 * 财务类订单类型/状态
 */
class Finance_TypeStatus {

	//订单类型
	CONST NETSAVE          = 0;  //充值
	CONST CASH             = 1;  //提现
	CONST INITIATIVETENDER = 2;  //主动投标
	CONST TENDERCANCLE     = 3;  //投标撤销
	CONST LOANS            = 4;  //满标打款
	CONST REPAYMENT        = 5;  //还款
	CONST UNKNOWN_TYPE     = 6;  //未知类型
	//订单状态
	CONST ORDER_INITIALIZE = 0;  //订单初始化
	CONST PROCESSING       = 1;  //订单处理中
	CONST ENDWITHFAIL      = 2;  //处理结束，失败
	CONST ENDWITHSUCCESS   = 3;  //处理结束。成功
	CONST FREEZING         = 4;  //冻结中
	CONST PAYING           = 5;  //打款中
	CONST HAVEPAYED        = 6;  //已打款
	CONST PAYFAIDED        = 7;  //打款失败
	CONST UNKNOWN_STATUS   = 8;  //未知状态
	
	public static $typeMapping = array(
		self::NETSAVE          => '充值',
		self::CASH             => '提现',
		self::INITIATIVETENDER => '主动投标',
		self::TENDERCANCLE     => '投标撤销',
		self::LOANS            => '满标打款',
		self::REPAYMENT        => '还款',	
		self::UNKNOWN_TYPE     => '未知类型',	
	);
	
	public static $statusMapping = array(
		self::ORDER_INITIALIZE => '订单初始化',
		self::PROCESSING       => '订单处理中',
		self::ENDWITHFAIL      => '处理以失败结束',
		self::ENDWITHSUCCESS   => '处理以成功结束',      
		self::FREEZING         => '冻结中',
		self::PAYING           => '打款中',
		self::HAVEPAYED        => '已打款',
		self::PAYFAIDED        => '打款失败',	
		self::UNKNOWN_STATUS   => '未知状态',		
	);  
	public static function getStatusDesc($statusCode) {
		if (isset(self::$statusMapping[$statusCode])) {
			return self::$statusMapping[$statusCode];
		} else {
			return self::$statusMapping[self::UNKNOWN_STATUS];
		}
	}
	public static function getType($typeCode) {
		if (isset(self::$typeMapping[$typeCode])) {
			return self::$typeMapping[$typeCode];
		} else {
			return self::$typeMapping[self::UNKNOWN_TYPE];
		}
	}
}