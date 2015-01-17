<?php
/**
 * 注册Logic层
 */
class User_Logic_Login {

    //登录成功
    const STATUS_LOGIN_SUCC = 0;  
    //登录失败
    const STATUS_LOGIN_FAIL = 1;  
    
    const DEFAULT_LOGIN_REDIRECT = '/account/overview';

    public function __construct(){
    }
    
    /**
     * 判断用户的登录状态
     * @return userid || false
     */
    public function checkLogin(){
        $userid = Yaf_Session::getInstance()->get(User_Keys::getLoginUserKey());
        if(!empty($userid)){
            $userid = intval($userid);
            return $userid;
        }
       return false;
    }

    /**
     * 设置用户的登陆状态
     * @return boolean
     */ 
    public function setLogin($objUser){
        if(is_object($objUser)){
            Yaf_Session::getInstance()->set(User_Keys::getLoginUserKey(), $objUser->userid);
            return true;
        }
        return false;
    }
    
    /**
     * 用户退出登陆
     * @return boolean
     */
    public function signOut(){
        $ret = Yaf_Session::getInstance()->del(User_Keys::getLoginUserKey());
        //$obj = new User_Object_Record();  应该要做条记录
        return $ret;
    }

    /**
     * @param string $strName,用户名
     * @param string $strPasswd,用户密码
     * @return User_RetCode::USER_NAME_NOTEXIT | USER_PASSWD_ERROR | SUCCESS
     */
    public function login($strName, $strPasswd){
        $strPasswd = Base_Util_Secure::encrypt($strPasswd);

        $objLogin = new User_Object_Login();
        $objLogin->fetch(array('name'=>$strName));

        $objRecord     = new User_Object_Record();
        $lastip        = Base_Util_Ip::getClientIp();
        $objRecord->ip = $lastip;
        
        //用户名不存在 
        if(empty($objLogin->userid)){
            $objRecord->status = self::STATUS_LOGIN_FAIL;
            $objRecord->save();
            return User_RetCode::USER_NAME_NOTEXIT;
        }

        //用户名或密码错误
        if($objLogin->passwd !== $strPasswd){
            $objRecord->status = self::STATUS_LOGIN_FAIL;
            $objRecord->save();
            return User_RetCode::USER_NAME_OR_PASSWD_ERROR;
        }

        //正确保存
        $objLogin->lastip = $lastip;
        $objLogin->loginTime = time();
        $objLogin->save();
        //保存正确纪录
        $objRecord->userid = $objLogin->userid;
        $objRecord->status = self::STATUS_LOGIN_SUCC;
        $objRecord->save();

        $this->setLogin(new User_Object($objLogin->userid));

        return User_RetCode::SUCCESS;
    }
    
    /**
     * 设置用户登录后的跳转页面
     * @param string $strRefer
     * @return string
     */
    public function loginRedirect($strRefer){
        if(empty($strRefer)||(false === strstr($strRefer,'www.xingjiaodai.com')||
        (false !== strstr($strRefer,'/user/regist'))||(false !== strstr($strRefer,'/user/login')))){
            return self::DEFAULT_LOGIN_REDIRECT;
        }
        return $strRefer;  
    }
}
