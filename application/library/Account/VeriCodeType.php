<?php 
class Account_VeriCodeType {
	
	/**
	 * 账户中心获取验证码的类型
	 */	
	CONST MODIFY_PHONE = 3;
	CONST MODIFY_EMAIL = 4;
	
	/**
	 * code映射
	 */
	protected static $_arrCodeMap = array(
			self::MODIFY_PHONE   => '修改手机',
			self::MODIFY_EMAIL   => '修改邮箱',	
	);
}
