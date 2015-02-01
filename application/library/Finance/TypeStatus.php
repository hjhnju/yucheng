<?php 
/**
 * 财务类订单类型/状态
 */
class Finance_TypeStatus {

	//订单类型
	CONST NETSAVE          = 2;  //充值
	CONST CASH             = 3;  //提现
	CONST INITIATIVETENDER = 4;  //主动投标
	CONST TENDERCANCEL     = 5;  //投标撤销
	CONST LOANS            = 6;  //满标打款
	CONST REPAYMENT        = 7;  //还款
	CONST TRANSFER         = 8;  //商户用自动扣款转账	
	CONST RECE_AWD         = 9;  //领取奖励
	CONST MONEY_BACK       = 10;  //退款
	CONST MERCASH          = 11;  //商户代取现
	CONST UNKNOWN_TYPE     = 12;  //未知类型
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
	CONST UNKNOWN_STATUS   = 10;  //未知状态
	
	public static $typeMapping = array(
		self::NETSAVE          => '充值',
		self::CASH             => '提现',
		self::INITIATIVETENDER => '主动投标',
		self::TENDERCANCEL     => '投标撤销',
		self::LOANS            => '满标打款',
		self::REPAYMENT        => '还款',	
		self::TRANSFER         => '商户用自动扣款转账',
		self::MERCASH          => '商户代取现',
		self::RECE_AWD         => '领取奖励',
	    self::MONEY_BACK       => '退款',
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