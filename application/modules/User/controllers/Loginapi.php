<?php
/**
 * 用户登录相关操作
 */
class LoginApiController extends Base_Controller_Api{
    
    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    /**
     * 标准登录过程
     * 状态返回0表示登录成功
     */    
    public function indexAction(){
        $strName   = trim($_REQUEST['name']);
        $strPasswd = trim($_REQUEST['passwd']);
        
        //检查错误次数
        $intFails = Yaf_Session::getInstance()->get(User_Keys::getFailTimesKey());
        if($intFails >= 3) {
            return $this->ajaxError(
                User_RetCode::NEED_PICTURE,
                User_RetCode::getMsg(User_RetCode::NEED_PICTURE),
                array('url' => Base_Config::getConfig('web')->root 
                    . '/user/authimage/getauthimage?type=login')
            );
        }
  
        //检查用户名语法
        if(!User_Logic_Validate::checkName($strName)) {
            $intFails = intval($intFails) + 1;
            Yaf_Session::getInstance()->set(User_Keys::getFailTimesKey(), $intFails);
    
            return $this->ajaxError(User_RetCode::USER_NAME_NOTEXIT,
                User_RetCode::getMsg(User_RetCode::USER_NAME_NOTEXIT));
        }
       
        //登陆
        $logic   = new User_Logic_Login();
        $retCode = $logic->login($$strName,$strPasswd);
        if(User_RetCode::SUCCESS === $retCode) {
            // $this->ajaxJump($redirectUri);
            $this->ajax();
        }else{
            $intFails = intval($intFails) + 1;
            Yaf_Session::getInstance()->set(User_Keys::getFailTimesKey(), $intFails);

            $this->ajaxError($retCode, User_RetCode::getMsg($retCode));
        }
    }
    
}
