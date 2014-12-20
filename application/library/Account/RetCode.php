<?php
/**
 * 异常码与描述定义类
 * 各模块可以在library中自定义错误码，如Account/RetCode.php
 * 各模块业务状态码自定义，范围(1024, 65535]
 */
class Account_RetCode extends Base_RetCode {
	
	//定义错误码:
	CONST MODIFY_PWD_FAIL            = 1101;//修改密码失败
	CONST VERCODE_ERROR              = 1102;//验证码错误
	CONST MODIFY_PHONE_FAIL          = 1103;//修改手机失败
	CONST RECEIVE_AWARDS_FAIL        = 1104;//领取奖励失败
	CONST GET_AWARDSLIST_FAIL        = 1105;//获取奖励列表失败
	CONST GET_MESLIST_FAIL           = 1106;//获取消息列表失败
	CONST GET_WITHDRAW_RECHARGE_FAIL = 1107;//获取充值提现列表失败
	CONST GET_INVEST_LIST_FAIL = 1108;//获取投资列表
	
	/* 消息函数
	 * @var array
	*/
	protected static $_arrErrMap = array(
		self::MODIFY_PWD_FAIL            => '修改密码失败',
		self::VERCODE_ERROR              => '验证码错误',
		self::MODIFY_PHONE_FAIL          => '修改手机失败',
		self::RECEIVE_AWARDS_FAIL        => '领取奖励失败',
		self::GET_AWARDSLIST_FAIL        => '获取奖励列表失败',
		self::GET_MESLIST_FAIL           => '获取消息列表失败',		
		self::GET_WITHDRAW_RECHARGE_FAIL => '获取充值提现列表失败',
	    self::GET_INVEST_LIST_FAIL       => '获取投资列表',
	);
	
	/**
	 * 获取信息描述
	 * @param  int    $exceptionCode 错误码
	 * @return string            描述
	 */
	public static function getMsg($exceptionCode) {
	
		if (isset(self::$_arrErrMap[$exceptionCode])) {
			return self::$_arrErrMap[$exceptionCode];
		} else {
			return self::$_arrErrMap[self::UNKNOWN_ERROR];
		}
	}
	
}