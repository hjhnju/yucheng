<?php
/**
 * 对外的API接口
 */
class User_Api{

    const REG_NAME = 'name';
    const REG_EMAIL = 'email';
    const REG_PHONE = 'phone';
    const REG_REALNAME = 'realname';
    const REG_PASSWD = 'passwd';
    const LAST_TIME = 5;     //验证码过期时间,5分钟
    
    protected static $_arrRegMap = array(
        self::REG_PASSWD         => '/^[a-zA-Z0-9!@#$%^&\'\(\({}=+\-]{6,20}$/',
        self::REG_NAME           => '/^([a-zA-Z])+[-_.0-9a-zA-Z]{4,19}$/',
        self::REG_EMAIL          => '/^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$/',
        self::REG_PHONE          => '/^(13[0-9]|15[0|3|6|7|8|9]|18[6|0|8|9])\d{8}$/',
        self::REG_REALNAME       => '/^[\x7f-\xff]{2,4}$/',
    );
    
    /**
     * 检验$value是否是$type规定的类型
     * 正则匹配验证：返回1为成功，其它为失败
    */
    public static function checkReg($type, $value){
        $ret = preg_match(self::$_arrRegMap[$type],$value);
        if($ret == User_RetCode::REG_FORMAT_WRONG) {
            return 1;
        }
        return 0;
    }
    
    /** 
     * 方法1: User_Api::checkLogin()
     * 判断登陆状态，获取用户Object
     * @return object User_Object | null
     * User/Object.php封装了User_Object_Login, User_Object_Info实例
     */
    public function checkLogin(){
        $logic   = new User_Logic_Login();
        $objUser = $logic->checkLogin();
        Base_Log::notice(array(
            'msg' => 'Api success',
            'user'=> $objUser
        ));
        return $objUser;
    }
    
    /**
     * 获取短信验证码信息
     */
    public static function sendSmsCode($strPhone){
        $srandNum = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $arrArgs = array($srandNum, self::LAST_TIME);
        $tplid   = Base_Config::getConfig('sms.tplid.vcode', CONF_PATH . '/sms.ini');
        $bResult = Base_Sms::getInstance()->send($strPhone,$tplid, $arrArgs);
        $now = time();
        Yaf_Session::getInstance()->set("vericode".$strPhone,$srandNum.",".$now);
        if(!empty($bResult)){
            return User_RetCode::SUCCESS;
        }
        return User_RetCode::GETVERICODE_FAIL;
    }
    
    /**
     * 验证用户输入的短信验证码是否正确
     */
    public static function checkSmscode($strPhone,$strVeriCode){
        $strStoredCode = Yaf_Session::getInstance()->get("vericode".$strPhone);
        $arrData = explode(",",$strStoredCode);
        $time = time() - $arrData[1];
        if(($strVeriCode == $arrData[0])&&($time <= 60*self::LAST_TIME)){
            return User_RetCode::SUCCESS;
        }
        return User_RetCode::VERICODE_WRONG;

    /** 
     * 方法2: User_Api::getUserObject($userid)
     * 获取用户Object
     * @return object User_Object | null
     * User/Object.php封装了User_Object_Login, User_Object_Info, User_Object_Third实例
     */
    public function getUserObject($userid){
        $logic   = new User_Logic_Info();
        $objUser = $logic->getUserObject($userid);
        Base_Log::notice(array(
            'msg'    => 'Api success',
            'userid' => $userid;
        ));
        return $objUser;
    }
    
    public static function checkRealName($uid,$strRealName) {
        return true;
    }
    
    public static function setRealName($uid,$strRealName){
        return true;
    }
    
    public static function setEmail($uid,$strEmail){
        return true;
    }
    
    public static function setPasswd($uid,$strPasswd){
        return true;
    }
    
}
