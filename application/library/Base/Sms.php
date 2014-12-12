<?php
/**
 * 发送短信客户端
 */
class Base_Sms {

    //单例
    protected static $objSms = null;

    //配置信息
    protected static $arrConf = null;

    //使用哪个短信类
    protected static $strClass = null;

    public static function getInstance(){
        if(is_null(self::$objSms)){
            self::$arrConf  = Base_Config::getConfig('sms', CONF_PATH . '/sms.ini');
            self::$strClass = self::$arrConf['class'];
            switch(strtolower(self::$strClass)){
            case 'weimi':
                self::$objSms = new Base_Sms_Weimi(self::$arrConf['args']);
                break;
            }
        }
        return self::$objSms;
    }
}
