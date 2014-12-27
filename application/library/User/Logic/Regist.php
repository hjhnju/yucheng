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
        Base_Log::debug('H3LLO'.$strName);
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
        if(!empty($objLogin->phone)) {
            return User_RetCode::USERPHONE_EXIST;
        }
        return User_RetCode::SUCCESS;
    }
    
    /**
     * 注册的同时要添加信息进`user_info`表
     * @param array $arrParam注册所需要的信息
     * @return int $uid,成功注册返回用户id，否则返回0
     */
    public function regist($username, $passwd, $phone, $inviter = ''){
        $objLogin         = new User_Object_Login();
        $objLogin->name   = $username;
        $objLogin->passwd = $passwd;
        $objLogin->phone  = $phone;
        $ret1             = $objLogin->save();

        if(!$ret1){
            return 0;
        }

        $objInfo           = new User_Object_objInfo();
        $objInfo->userid   = $objLogin->userid;
        //个人用户
        $objInfo->usertype = 1;
        $objInfo->save();

        //TODO:绑定第三方账户的注册
        $openid   = Yaf_Session::get(User_Keys::getOpenidKey());
        $authtype = Yaf_Session::get(User_Keys::getAuthTypeKey());

        //邀请通知
        //TODO:获取inviterid
        // Awards_Api::registNotify($objLogin->userid, $inviterid);
        return $objLogin->userid;
    }
    
}