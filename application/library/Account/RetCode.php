<?php
/**
 * 异常码与描述定义类
 * 各模块可以在library中自定义错误码，如Account/RetCode.php
 * 各模块业务状态码自定义，范围(1024, 65535]
 */
class Account_RetCode extends Base_RetCode {

	
	//定义错误码:
	CONST MODIFY_PWD_FAIL            = 1101;//修改密码失败
	CONST VERCODE_ERROR              = 1102;//验证码输入错误
	CONST MODIFY_PHONE_FAIL          = 1103;//修改手机失败
	CONST RECEIVE_AWARDS_FAIL        = 1104;//领取奖励失败
	CONST GET_AWARDSLIST_FAIL        = 1105;//获取奖励列表失败
	CONST GET_MESLIST_FAIL           = 1106;//获取消息列表失败
	CONST GET_WITHDRAW_RECHARGE_FAIL = 1107;//获取充值提现列表失败
	CONST GET_INVEST_LIST_FAIL       = 1108;//获取投资列表失败
	CONST GET_REPAYPLAN_FAIL         = 1109;//获取还款计划失败
	CONST GET_MESCONTENT_FAIL        = 1110;//获取消息内容失败
	CONST GET_PROFIT_CURVE_FAIL      = 1111;//获取收益曲线失败
	CONST GET_VERTICODE_FAIL         = 1112;//获取验证码失败
	CONST PHONE_FORMAT_ERROR         = 1113;//手机号码格式错误
	CONST EMAIL_FOEMAT_ERROR         = 1114;//邮箱格式错误
	CONST TOKEN_VERIFY_ERROR         = 1115;//token验证失败
	CONST MODIFY_EMAIL_FAIL          = 1116;//修改邮箱失败
	CONST OLDPWD_INPUT_ERROR         = 1117;//原密码输入错误
	CONST EMAIL_NOT_CHANGE           = 1118;//邮箱没有发生变化
	/* 消息函数
	 * @var array
	*/
	protected static $_arrErrMap = array(
		self::MODIFY_PWD_FAIL            => '修改密码失败',
		self::VERCODE_ERROR              => '验证码输入错误',
		self::MODIFY_PHONE_FAIL          => '修改手机失败',
		self::RECEIVE_AWARDS_FAIL        => '领取奖励失败',
		self::GET_AWARDSLIST_FAIL        => '获取奖励列表失败',
		self::GET_MESLIST_FAIL           => '获取消息列表失败',		
		self::GET_WITHDRAW_RECHARGE_FAIL => '获取充值提现列表失败',
	    self::GET_INVEST_LIST_FAIL       => '获取投资列表失败',
	    self::GET_REPAYPLAN_FAIL         => '获取还款计划失败',
	    self::GET_MESCONTENT_FAIL        => '获取消息内容失败',
		self::GET_PROFIT_CURVE_FAIL      => '获取收益曲线失败',
	    self::GET_VERTICODE_FAIL         => '获取验证码失败',
	    self::PHONE_FORMAT_ERROR         => '手机号码格式错误',
		self::EMAIL_FOEMAT_ERROR         => '邮箱格式错误',
		self::TOKEN_VERIFY_ERROR         => 'token验证失败',
		self::OLDPWD_INPUT_ERROR         => '原密码输入错误',
		self::EMAIL_NOT_CHANGE           => '邮箱没有发生变化',
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