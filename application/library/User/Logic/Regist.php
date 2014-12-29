<?php
/**
 * 注册Logic层
 */
class User_Logic_Regist{
    
    public function __construct(){
    }
    
    /**
     * 
     * @param string $strName,用户名
     * @return int $retCode
     * SUCCESS 表示用户名可用
     * USERNAME_EXIST 表示用户名已存在
     * USERNAME_SYNTEX_ERROR 表示输入的用户名不合法
     */
    public function checkName($strName){        
        if(empty($strName) || !User_Logic_Validate::checkName($strName)){
            return User_RetCode::USERNAME_SYNTEX_ERROR;
        }

        $objLogin = new User_Object_Login();
        $objLogin->fetch(array('name'=>$strName));

        if(!empty($objLogin->name)) {
            return User_RetCode::USERNAME_EXIST;
        }
        return User_RetCode::SUCCESS;
    }
    
    /**
     *
     * @param string $strPhone,手机号
     * @return 
     * SUCCESS 表示手机可用
     * USERPHONE_EXIST 表示手机不存在
     * USERPHONE_SYNTEX_ERROR 表示手机格式错误
     */
    public function checkPhone($strPhone){
        if(empty($strPhone) || !User_Logic_Validate::checkPhone($strPhone)){
            return User_RetCode::USERPHONE_SYNTEX_ERROR;
        }

        $objLogin = new User_Object_Login();
        $objLogin->fetch(array('phone'=>$strPhone));
        if(!empty($objLogin->phone)) {
            return User_RetCode::USERPHONE_EXIST;
        }
        return User_RetCode::SUCCESS;
    }
    
    /**
     *
     * @param string $strPhone,手机号
     * @return int,0表示手机存在，1表示手机不存在
     */
    public function checkInviter($strPhone){  
        //邀请人可为空      
        if(!empty($strPhone) && !User_Logic_Validate::checkPhone($strPhone)){
            return User_RetCode::USERPHONE_SYNTEX_ERROR;
        }

        $objLogin = new User_Object_Login();
        $objLogin->fetch(array('phone'=>$strPhone));
        if(empty($objLogin->userid)) {
            return User_RetCode::INVITER_NOT_EXIST;
        }
        return User_RetCode::SUCCESS;
    }
    
    /**
     * 注册的同时要添加信息进`user_info`表
     * @return $userid | false
     */
    public function regist($username, $passwd, $phone, $inviter = ''){
        $objLogin         = new User_Object_Login();
        $objLogin->name   = $username;
        $objLogin->passwd = $passwd;
        $objLogin->phone  = $phone;

        $ret = $objLogin->save();
        if(!$ret){
            return false;
        }

        $objInfo         = new User_Object_Info();
        $objInfo->userid = $objLogin->userid;
        //个人用户
        $objInfo->usertype = 1;
        $objInfo->save();

        return $objLogin->userid;
    }
    
}