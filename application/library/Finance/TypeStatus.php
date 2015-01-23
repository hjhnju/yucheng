<?php 
/**
 * 财务类订单类型/状态
 */
class Finance_TypeStatus {

	//订单类型
	CONST NETSAVE          = 0;  //充值
	CONST CASH             = 1;  //提现
	CONST INITIATIVETENDER = 2;  //主动投标
	CONST TENDERCANCEL     = 3;  //投标撤销
	CONST LOANS            = 4;  //满标打款
	CONST REPAYMENT        = 5;  //还款
	CONST TRANSFER         = 6;  //商户用自动扣款转账
	CONST UNKNOWN_TYPE     = 7;  //未知类型
	//订单状态
	CONST ORDER_INITIALIZE = 0;  //订单初始化
	CONST PROCESSING       = 1;  //订单处理中
	CONST ENDWITHFAIL      = 2;  //处理结束，失败
	CONST ENDWITHSUCCESS   = 3;  //处理结束。成功
	CONST FREEZING         = 4;  //冻结中
	CONST PAYING           = 5;  //打款中
	CONST HAVEPAYED        = 6;  //已打款
	CONST PAYFAIDED        = 7;  //打款失败
	CONST CANCELD          = 8;  //投标已撤销
	CONST MERCASH          = 9;  //商户代取现
	CONST UNKNOWN_STATUS   = 10;  //未知状态
	
	public static $typeMapping = array(
		self::NETSAVE          => '充值',
		self::CASH             => '提现',
		self::INITIATIVETENDER => '主动投标',
		self::TENDERCANEL     => '投标撤销',
		self::LOANS            => '满标打款',
		self::REPAYMENT        => '还款',	
		self::TRANSFER         => '商户用自动扣款转账',
		self::MERCASH          => '商户代取现',
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
		self::CANCELD          => '投标已撤销',
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