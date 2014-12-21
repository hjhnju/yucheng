<?php
/**
 * 对外的API接口
 */
class User_Api{
    
    const LAST_TIME = 5;     //验证码过期时间,5分钟
 
    /**
     * 检验$value是否是$type规定的类型
     * 正则匹配验证：返回1为成功，其它为失败
    */
    public static function checkReg($type, $value){
        $ret = preg_match(User_Logic_Api::$_arrRegMap[$type],$value);
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
            'userid' => $userid,
        ));
        return $objUser;
    }
    
    /**
     * 设置用户真实姓名
     * @param unknown $uid
     * @param unknown $strRealName
     * @return boolean
     */
    public function setRealName($uid,$strRealName){
        $objInfo = new User_Object_Info();
        $objInfo->fetch(array('userid'=>$uid));
        $objInfo->realname = $strRealName;
        $ret = $objInfo->save();
        return $ret;
    }
    
    /**
     * 设置用户邮箱
     * @param unknown $uid
     * @param unknown $strEmail
     * @return boolean
     */
    public function setEmail($uid,$strEmail){
        $objInfo = new User_Object_Info();
        $objInfo->fetch(array('userid'=>$uid));
        $objInfo->email = $strEmail;
        $ret = $objInfo->save();
        return $ret;
    }
    
    /**
     * 设置用户密码
     * @param unknown $uid
     * @param unknown $strPasswd
     * @return boolean
     */
    public function setPasswd($uid,$strPasswdOld,$strPasswdNew){
        $objInfo = new User_Object_Info();
        $objInfo->fetch(array('userid'=>$uid,'passwd'=>md5($strPasswdOld)));
        if(empty($objInfo->userid)){
            return false;
        }
        $objInfo->passwd = md5($strPasswdNew);
        $ret = $objInfo->save();
        return $ret;
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
    public static function sendSmsCode($strPhone,$type){
        $srandNum = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $arrArgs = array($srandNum, self::LAST_TIME);
        $tplid   = Base_Config::getConfig('sms.tplid.vcode', CONF_PATH . '/sms.ini');
        $bResult = Base_Sms::getInstance()->send($strPhone,$tplid, $arrArgs);
        Base_Redis::getInstance()->setex($strPhone.$type,60*(self::LAST_TIME),$srandNum);
        if(!empty($bResult)){
            return User_RetCode::SUCCESS;
        }
        return User_RetCode::GETVERICODE_FAIL;
    }
    
    /**
     * 验证用户输入的短信验证码是否正确
     */
    public static function checkSmscode($strPhone,$strVeriCode,$strtype){
        $strStoredCode = Base_Redis::getInstance()->get($strPhone.$strtype);
        if($strVeriCode == $strStoredCode){
            return User_RetCode::SUCCESS;
        }
        return User_RetCode::VERICODE_WRONG;
    }
}
