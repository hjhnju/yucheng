<?php 
class Account_VeriCodeType {
	
	/**
	 * 账户中心获取验证码的类型
	 */	
	CONST MODIFY_PHONE = 2;
	CONST MODIFY_EMAIL = 3;
	
	CONST MODIFY_PHONE_SESSIONCODE = 'modifyPhoneSessionCode';
	CONST MODIFY_EMAIL_SESSIONCODE = 'modifyEmailSessionCode';
	
	/**
	 * code映射
	 */
	protected static $_arrCodeMap = array(
			self::MODIFY_PHONE   => '修改手机',
			self::MODIFY_EMAIL   => '修改邮箱',	
	);
}
