<?php
/**
 * 异常码与描述定义类
 * 各模块可以在library中自定义错误码，如Account/RetCode.php
 * 各模块业务状态码自定义，范围(1024, 65535]
 */
class Finance_RetCode extends Base_RetCode {

	
	//定义错误码:
	CONST REQUEST_API_ERROR          = 1101;//请求汇付API出错
	CONST NOTUSERCARD                = 1102;//该用户无此卡
	CONST NOTBINDANYCARD             = 1103;//用户没用绑定任何一张卡
	
	CONST CANNOT_DEL_DEFALTCARD      = 1104;//默认取现卡不可以删除

	
	
	
 
	/* 消息函数
	 * @var array
	*/
	protected static $_arrErrMap = array(
		self::REQUEST_API_ERROR  => '请求API出错',
		self::NOTUSERCARD        => '该用户无此卡',
		self::NOTBINDANYCARD     => '用户没用绑定任何一张卡',
		self::CANNOT_DEL_DEFALTCARD => '默认取现卡不可以删除',
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