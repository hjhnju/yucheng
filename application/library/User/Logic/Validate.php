<?php
/**
 * 字段验证类
 */
class User_Logic_Validate {
    
    const REG_NAME     = 'name';
    const REG_EMAIL    = 'email';
    const REG_PHONE    = 'phone';
    const REG_REALNAME = 'realname';
    const REG_PASSWD   = 'passwd';
    
    public static $_arrRegMap = array(
        self::REG_PASSWD         => '/^[a-zA-Z0-9!@#$%^&\'\(\({}=+\-]{6,20}$/',
        self::REG_NAME           => '/^([a-zA-Z])+[-_.0-9a-zA-Z]{4,19}$/',
        self::REG_EMAIL          => '/^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$/',
        self::REG_PHONE          => '/^(13[0-9]|15[0|3|6|7|8|9]|18[6|0|8|9])\d{8}$/',
        self::REG_REALNAME       => '/^[\x7f-\xff]{2,4}$/',
    );

    /**
     * 验证字段是否正确
     * @param   $strType
     * @param   $value 
     */
    protected static function check($strType, $value){
        $strType  = strtolower($strType);
        if(!isset(self::$_arrRegMap[$strType])){
            return false;
        }
        $pattern  = self::$_arrRegMap[$strType];
        $intNum   = preg_match($pattern, $value);
        Base_Log::debug($intNum);
        return $intNum > 0 ? true : false; 
    }

    public static function checkName($strName){
        return self::check('name', $strName);
    }

    public static function checkPhone($strPhone){
        return self::check('phone', $strPhone);
    }
    

}