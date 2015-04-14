<?php
/**
 * 异常码与描述定义类
 * 各模块可以在library中自定义错误码，如Account/RetCode.php
 * 各模块业务状态码自定义，范围(1024, 65535]
 */
class Awards_RetCode extends Base_RetCode {

	
	//定义错误码:
	CONST CANNOT_USE_TICKET            = 1301;
	/* 消息函数
	 * @var array
	*/
	protected static $_arrErrMap = array(
		self::CANNOT_USE_TICKET            => '您无法使用该奖券',
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