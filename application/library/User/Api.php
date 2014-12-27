<?php
/**
 * 对外的API接口
 */
class User_Api{
    
    const LAST_TIME = 5;     //验证码过期时间,5分钟
 
    /**
     * User_Api::checkReg()
     * 检验$value是否是$type规定的类型,正则在User_Logic_Api类中
     * 正则匹配验证：返回true为成功，其它为失败
     */
    public static function checkReg($strType, $strValue){
        $ret = preg_match(User_Logic_Api::$_arrRegMap[$strType],$strValue);
        if($ret == User_RetCode::REG_FORMAT_WRONG) {
            return true;
        }
        return false;
    }
    
    /** 
     * User_Api::checkLogin()
     * 判断登陆状态，获取用户Object
     * @return object User_Object | null
     * User/Object.php封装了User_Object_Login, User_Object_Info实例
     */
    public static function checkLogin(){
        $logic   = new User_Logic_Login();
        $objUser = $logic->checkLogin();
        $userid  = is_object($objUser) ? $objUser->userid : 0;
        Base_Log::notice(array(
            'userid' => $userid,
        ));
        return $objUser;
    }
    /** 
     *User_Api::getUserObject($userid)
     * 获取用户Object
     * @return object User_Object | null
     * User/Object.php封装了User_Object_Login, User_Object_Info, User_Object_Third实例
     */
    public static function getUserObject($userid){
        $logic   = new User_Logic_Info();
        $objUser = $logic->getUserObject($userid);
        $userid  = is_object($objUser) ? $objUser->userid : 0;
        Base_Log::notice(array(
            'userid' => $userid,
        ));
        return $objUser;
    }
    
    /**
     * 设置用户真实姓名
     * @param int $uid
     * @param string $strRealName
     * @return boolean
     */
    public static function setRealName($uid,$strRealName){
        $objInfo = new User_Object_Info();
        $objInfo->fetch(array('userid'=>$uid));
        $objInfo->realname = $strRealName;
        $ret = $objInfo->save();
        return $ret;
    }
    
    /**
     * 设置用户邮箱
     * @param int $uid
     * @param string $strEmail
     * @return boolean
     */
    public static function setEmail($uid,$strEmail){
        $objInfo = new User_Object_Info();
        $objInfo->fetch(array('userid'=>$uid));
        $objInfo->email = $strEmail;
        $ret = $objInfo->save();
        return $ret;
    }
    
    /**
     * 设置用户邮箱
     * @param int $uid
     * @param string $strPhone
     * @return boolean
     */
    public static function setPhone($uid,$strPhone){
        $objInfo = new User_Object_Info();
        $objInfo->fetch(array('userid'=>$uid));
        $objInfo->phone = $strPhone;
        $ret = $objInfo->save();
        return $ret;
    }
    
    /**
     * 设置用户密码
     * @param int $uid
     * @param string $strPasswd
     * @return int
     */
    public static function setPasswd($uid,$strPasswdOld,$strPasswdNew){
        $objInfo = new User_Object_Info();
        $objInfo->fetch(array('userid'=>$uid,'passwd'=>md5($strPasswdOld)));
        if(empty($objInfo->userid)){
            return User_RetCode::ORIGIN_PASSWD_WRONG;
        }
        $objInfo->passwd = md5($strPasswdNew);
        $ret = $objInfo->save();
        if(!$ret) {
            return User_RetCode::SAVE_PASSWD_WRONG;
        }
        return User_RetCode::SUCCESS;
    }
 
    /**
     * 设置用户的汇付id
     * @param unknown $uid
     * @param unknown $strHuifuid
     * @return boolean
     */
    public static function setHuifuId($uid,$strHuifuid){
        $objInfo = new User_Object_Info();
        $objInfo->fetch(array('userid'=>$uid));
        $objInfo->huifuid = $strHuifuid;
        $ret = $objInfo->save();
        return $ret;
    }
    
    /**
     * 获取短信验证码信息,需要参数手机号及类型
     */
    public static function sendSmsCode($strPhone,$intType){
        $srandNum = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $arrArgs = array($srandNum, self::LAST_TIME);
        $tplid   = Base_Config::getConfig('sms.tplid.vcode', CONF_PATH . '/sms.ini');
        $bResult = Base_Sms::getInstance()->send($strPhone,$tplid, $arrArgs);
        Base_Redis::getInstance()->setex($strPhone.$intType,60*(self::LAST_TIME),$srandNum);
        if(!empty($bResult)){
            return true;
        }
        return false;
    }
    
    /**
     * 验证用户输入的短信验证码是否正确
     */
    public static function checkSmscode($strPhone,$strVeriCode,$intType){
        $strStoredCode = Base_Redis::getInstance()->get($strPhone.$intType);
        if($strVeriCode == $strStoredCode){
            return true;
        }
        return false;
    }
    
    /**
     * 返回获取图片验证码的URL
     * @param $strType:类型
     */
    public static function getAuthImageUrl($strType){
        $strId = session_id().$strType;
        return "http://123.57.46.229:8301/User/loginapi/getAuthImage?&token=$strId";
    }
    
    /**
     * 验证图片验证码
     * @param $strImageCode:图片验证码
     * @param $strType:类型
     */
    public static function checkAuthImage($strImageCode,$strType){
        $strId = session_id().$strType;
        $storedImageCode = Base_Redis::getInstance()->get($strId);
        if(strtolower($storedImageCode) == strtolower($strImageCode)){
            return true;
        }
        return false;
    }
}
