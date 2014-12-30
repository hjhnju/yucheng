<?php
/**
 * 注册Logic层
 */
class User_Logic_Login {

    //登录成功
    const STATUS_LOGIN_SUCC = 0;  
    //登录失败
    const STATUS_LOGIN_FAIL = 1;  

    public function __construct(){
    }
    
    /**
     * 判断用户的登录状态
     * @return object || null
     */
    public function checkLogin(){
        $objUser = Yaf_Session::getInstance()->get(User_Keys::getLoginUserKey());
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
            Yaf_Session::getInstance()->set(User_Keys::getLoginUserKey(), $objUser);
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
     * @param string $strName,用户名
     * @param string $strPasswd,用户密码
     * @return User_RetCode::USER_NAME_NOTEXIT | USER_PASSWD_ERROR | SUCCESS
     */
    public function login($strName, $strPasswd){
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

        //用户名密码
        if($objLogin->passwd !== $strPasswd){
            $objRecord->status = self::STATUS_LOGIN_FAIL;
            $objRecord->save();
            return User_RetCode::USER_PASSWD_ERROR;
        }

        //正确保存
        $objLogin->lastip = $lastip;
        $objLogin->save();
        //保存正确纪录
        $objRecord->userid = $objLogin->userid;
        $objRecord->status = self::STATUS_LOGIN_SUCC;
        $objRecord->save();

        $this->setLogin(new User_Object($objLogin->userid));

        return User_RetCode::SUCCESS;
    }
  
}
