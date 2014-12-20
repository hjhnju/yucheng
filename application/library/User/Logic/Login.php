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
    /*public function getUserInfo($arrUid){
        $arrResult = array();
        foreach ($arrUid as $uid){
            $data = $this->modLogin->getUserInfo($uid);
            if(!empty($data)) {
               $arrResult[] = $data;
            }
        }
        return $arrResult;
    }*/
    
    /**
     * 
     * @param string $strName,用户名
     * @return int,0表示登录失败，1表示登录成功
     */
    public function login($type,$strName,$strPasswd){
        $strPasswd = md5($strPasswd);
        $ip = Base_Util_Ip::getClientIp();
        $data = $this->modLogin->login($type,$strName,$strPasswd,$ip);
        if($data != User_RetCode::INVALID_USER) {
            return $data;
        }
        return User_RetCode::INVALID_USER;
    }
    
    /**
     * 根据$intType类型获取auth code
     * 拼接URL的操作，发给前端放在点击授权处
     * @param int $intType,1表示qq,2表示微博
     */
    public function getAuthCode($intType){
        $this->type = $intType;
        $arrData =  Base_Config::getConfig('login');
        $redirect_url = $arrData['auth_code_url'];
        $arrData = $arrData[$intType];
        $host = $arrData['host'];
        $randnum = md5(uniqid(rand(), TRUE));
        Yaf_Session::getInstance()->set("state",$randnum);
        $url = $arrData['authcode_url'].$arrData['appid']."&redirect_uri=".$redirect_url."&scope=get_user_info&state=".$randnum;
        if(empty($host)||empty($url))  {
            return User_RetCode::INVALID_URL;
        }
        return $host.$url;
    }
    
    /**
     * 第三账号登录，首先需要获取用户的部分资料
     * @param unknown $openid
     * @param unknown $intType
     */
    public function checkBind($openid,$intType){
        $ret = $this->modLogin->checkBing($openid,$intType);
        return $ret;
    }
       
    /**
     * 第三账号登录，首先需要获取用户的部分资料
     * @param unknown $openid
     * @param unknown $intType
     */
    public function setBind($openid, $intType,$strName,$strPasswd){
        $ret = $this->modLogin->setBing($openid,$intType,$status['bind']);
        return $ret;
    }
    /**
     * 第三账号登录，首先需要获取用户的部分资料
     * @param unknown $openid
     * @param unknown $intType
     */
    public function thridLogin($uid,$openid,$intType){
        $ip = Base_Util_Ip::getClientIp();
        if(User_RetCode::INVALID_USER != $uid){
           $this->modLogin->addRecord($uid,User_RetCode::SUCCESS,$ip);
           return User_RetCode::SUCCESS;
        }
        return User_RetCode::UNKNOWN_ERROR;
    }
    
    /**
     * 
     * @param string $val
     * @return string 根据$val的类型返回:name,email,phone,其它情况返回error报错
     */
    public function checkType($val){
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