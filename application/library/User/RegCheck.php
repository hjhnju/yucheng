<?php 
/**
 * 验证各种输入是否符合规范的类
 */
class User_RegCheck{
    
    const REG_NAME = 'name';
    const REG_EMAIL = 'email';
    const REG_PHONE = 'phone';
    const REG_REALNAME = 'realname';
    
    protected static $_arrRegMap = array(
        self::REG_NAME           => '//',
        self::REG_EMAIL          => '/^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$/',
        self::REG_PHONE          => '/^(13[0-9]|15[0|3|6|7|8|9]|18[0|8|9])\d{8}$/',
        self::REG_REALNAME       => '/^[\x7f-\xff]{2,4}$/',
    );
    
    /**
     * 正则匹配验证：返回1为成功，其它为失败
     */
    public static function checkReg($type, $value){
        return preg_match(self::$_arrErrMap[$type],$value);
    }
}