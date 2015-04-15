<?php
/**
 * 登录Logic层
 */
class User_Logic_Login {

    //登录成功
    const STATUS_LOGIN_SUCC = 0;  
    //登录失败
    const STATUS_LOGIN_FAIL = 1;  
    
    const DEFAULT_LOGIN_REDIRECT = '/account/overview';
    
    //需要使用默认登录后路转地址的URL
    protected static $arrUrl = array(
        '/user/regist',
        '/user/login',
        '/user/modifypwd',
        '/m/regist',
        '/m/login',
    );

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
    public function login($strType,$strName, $strPasswd){
        $strPasswd = Base_Util_Secure::encrypt($strPasswd);

        $objLogin = new User_Object_Login();
        $objLogin->fetch(array($strType=>$strName));

        $objRecord     = new User_Object_Record();
        $lastip        = Base_Util_Ip::getClientIp();
        $objRecord->ip = $lastip;
        
        //用户名不存在 
        if(empty($objLogin->userid)){
            $objRecord->status = self::STATUS_LOGIN_FAIL;
            $objRecord->save();
            if('name' === $strType){
                return User_RetCode::USER_NAME_NOTEXIT;
            }elseif('phone' === $strType){
                return User_RetCode::USER_PHONE_NOTEXIT;
            }elseif('email' === $strType){
                return User_RetCode::USER_EMAIL_NOTEXIT;
            }
        }

        //用户名或密码错误
        if($objLogin->passwd !== $strPasswd){
            $objRecord->status = self::STATUS_LOGIN_FAIL;
            $objRecord->save();
            if('name' === $strType){
                return  User_RetCode::USER_NAME_OR_PASSWD_ERROR;;
            }elseif('phone' === $strType){
                return User_RetCode::PHONE_OR_PASSWD_ERROR;
            }elseif('email' === $strType){
                return User_RetCode::EMAIL_OR_PASSWD_ERROR;
            }
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
        $bUrlIn = false;
        foreach (self::$arrUrl as $val){
            if(false !== strstr($strRefer,$val)){
                $bUrlIn = true;
                break;
            }
        }
        if(empty($strRefer)||(false === strstr($strRefer,'www.xingjiaodai.com'))||$bUrlIn){
            return self::DEFAULT_LOGIN_REDIRECT;
        }
        return $strRefer;  
    }
}
