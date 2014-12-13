<?php
/**
 * 注册Logic层
 */
class User_Logic_Login{
    
    public function __construct(){
        $this->modLogin = new LoginModel();
    }
    
    /**
     * 根据用户$arrUid数组获取用户的一些信息
     */
    public function getUserInfo($arrUid){
        $arrResult = array();
        foreach ($arrUid as $uid){
            $data = $this->modLogin->getUserInfo($uid);
            if(!empty($data)) {
               $arrResult[] = $data;
            }
        }
        return $arrResult;
    }
    
    /**
     * 
     * @param string $strName,用户名
     * @return int,0表示登录失败，1表示登录成功
     */
    public function login($strName,$strPasswd){
        $strPasswd = md5($strPasswd);
        $type = $this->checkType($strName);
        $ip = Base_Util_Ip::getClientIp();
        if('error' != $type){
            $data = $this->modLogin->login($type,$strName,$strPasswd,$ip);
            if($data != User_RetCode::INVALID_USER) {
               return $data;
            }           
            return User_RetCode::INVALID_USER;
        }
        return User_RetCode::INVALID_USER;
    }
    
    /**
     * 第三账号登录，首先需要获取用户的部分资料
     * @param unknown $openid
     * @param unknown $intType
     */
    public function thridLogin($openid,$intType){
        
    }
    
    /**
     * 判断用户的登录状态
     * 若用户处于登录状态，则返回uid，否则返回0
     */
    public function checkLogin(){
        if(Yaf_Session::getInstance()->has("LOGIN")){
            $uid = Yaf_Session::getInstance()->get("LOGIN");
        }
       return $uid;
    }
    
    /**
     * 
     * @param string $val
     * @return string 根据$val的类型返回:name,email,phone,其它情况返回error报错
     */
    protected function checkType($val){
        if(User_Api::checkReg('name',$val)){
            return 'name';
        }elseif(User_Api::checkReg('email',$val)){
            return 'email';
        }elseif(User_Api::checkReg('phone',$val)){
            return 'phone';
        }else{
            return 'error';
        }
    }
}