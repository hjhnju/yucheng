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
	
	//订单状态
	CONST PROCESSING       = 0;  //订单处理中
	CONST ENDWITHFAIL      = 1;  //处理结束，失败
	CONST ENDWITHSUCCESS   = 2;  //处理结束。成功
		  
}