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
        self::REG_NAME           => 'xx',
        self::REG_EMAIL          => 'xx',
        self::REG_PHONE          => 'xx',
        self::REG_REALNAME       => 'xx',
    );
    
    /**
     * 正则匹配验证：返回1为成功，其它为失败
     */
    public static function checkReg($type, $value){
        return preg_match(self::$_arrErrMap[$type],$value);
    }
}