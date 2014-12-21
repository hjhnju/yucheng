<?php
/**
 * 注册Logic层
 * @author hejunhua
 */
class User_Logic_Login{

    const SESSION_LOGIN_USER = "login_user";

    public function __construct(){
    }
    
    /**
     * 判断用户的登录状态
     * @return object || null
     */
    public function checkLogin(){
        $objUser = Yaf_Session::getInstance()->get(self::SESSION_LOGIN_USER);
        if($objUser){
            return $objUser;
        }
       return null;
    }

    /**
     * 设置用户的登陆状态
     * @return boolean
     */ 
    protected function setLogin($objUser){
        if($objUser){
            Yaf_Session::getInstance()->set(self::SESSION_LOGIN_USER, $objUser);
            return true;
        }
        return false;
    }

    /**
     * 获取用户对象
     * @return User_Object
     */
    public function getUserObject($userid) {
        $objUser = new User_Object($userid);
        return $objUser;
    }
    
    /**
     * @param type $type, 用户类型？ //TODO:
     * @param string $strName,用户名
     * @param string $strPasswd,用户密码
     * @return boolean
     */
    public function login($type, $strName, $strPasswd){
        $objLogin         = new User_Object_Login();
        $objLogin->name   = $strName;
        $objLogin->passwd = md5($strPasswd); 
        $objLogin->lastip = Base_Util_Ip::getClientIp();
        $ret              = $objLogin->save();
        //todo:纪录登陆纪录
        return $ret;
    }
  
}