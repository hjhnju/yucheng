<?php
/**
 * 用户注册相关操作
 */
class RegistApiController extends Base_Controller_Api{
    
    public function init(){
       
        $this->setNeedLogin(false);

        parent::init();
        $this->registLogic = new User_Logic_Regist();
        $this->loginLogic = new User_Logic_Login();
    }
    
    /**
     * 检查用户名是否存在
     * 
     */
    public function checkNameAction(){
       $strName = trim($_REQUEST['name']);
       $data = $this->registLogic->checkName($strName);
       if(User_RetCode::SUCCESS == $data){
           return $this->ajax();
       }
       return $this->ajaxError($data,User_RetCode::getMsg($data));
    }
    
    /**
     * 检查手机号中否存在，返回值同上
     */
    public function checkPhoneAction(){
        $strPhone = trim($_REQUEST['phone']);
        $data = $this->registLogic->checkPhone($strPhone);
        if(User_RetCode::SUCCESS == $data){
           return $this->ajax();
       }
       return $this->ajaxError($data,User_RetCode::getMsg($data));     
    }
    
    /**
     * 获取验证码信息
     */
    public function sendSmsCodeAction(){
       $strPhone   = trim($_REQUEST['phone']);
       $strType   = trim($_REQUEST['type']);
       $ret = User_Api::sendSmsCode($strPhone,$strType);
       if($ret){
           return $this->ajax();
       }
       return $this->ajaxError($ret);
    }
    
    /**
     * 验证用户输入的验证码是否正确
     */
    public function checkSmsCodeAction(){
        $strPhone   = trim($_REQUEST['phone']);
        $strType   = trim($_REQUEST['type']);
        $strVeriCode   = trim($_REQUEST['vericode']);
        $ret = User_Api::checkSmsCode($strPhone, $strVeriCode,$strType);
        if($ret){
           return $this->ajax();
       }
       return $this->ajaxError($ret,User_RetCode::getMsg($ret));
    }
    
    /**
     * 检验推荐人是否存在
     * 
     */
    public function checkInviterAction(){
        $inviter = trim($_REQUEST['inviter']);
        if(empty($inviter)||(User_Api::checkReg('phone', $inviter))){
            return $this->ajaxError(User_RetCode::REFEREE_SNYTEX_ERROR,User_RetCode::getMsg(User_RetCode::REFEREE_SNYTEX_ERROR));
        }
        $ret = $this->registLogic->checkInviter($inviter);
        if(User_RetCode::INVALID_USER== $ret){
           return $this->ajaxError(User_RetCode::INVITER_NOT_EXIST,User_RetCode::getMsg(User_RetCode::INVITER_NOT_EXIST)); 
       }
       return $this->ajax();
    }
    
    /**
     * 用户注册类
     */
    public function IndexAction(){
        $strName    = trim($_REQUEST['name']);
        $strPasswd = trim($_REQUEST['passwd']);
        if(User_RetCode::USER_PASSWD_FORMAT_ERROR == User_Api::checkReg('passwd',$strPasswd)){
            return $this->ajaxError(User_RetCode::USER_PASSWD_FORMAT_ERROR,User_RetCode::getMsg(User_Api::USER_PASSWD_FORMAT_ERROR));
        }
        $strPasswd  = md5($strPasswd);
        $strPhone   = trim($_REQUEST['phone']);
        $strReferee = "";
        if(isset($_REQUEST['referee'])){
           $intReferee = trim($_REQUEST['referee']);
           $inviterid = $this->registLogic->checkInviter($intReferee);
           if(User_RetCode::INVALID_USER == $inviterid) {
               return $this->ajaxError($inviterid,User_RetCode::getMsg($inviterid));
           }
        }
        $arrParam = array(
            'name'   => $strName,
            'passwd' => $strPasswd,
            'phone'  => $strPhone,
            'refere' => $strReferee,
        );
        $uid = $this->registLogic->regist($arrParam);
        if(User_RetCode::INVALID_USER != $uid){  //注册成功时判用户是否已经QQ等第三方登录
            //Awards_Api::registNotify($uid, $inviterid);
            $openid = Yaf_Session::getInstance()->get("openid");
            if(!empty($openid)){
               $type = Yaf_Session::getInstance()->get("idtype");
               $ret = $this->loginLogic->thirdLogin($uid,$openid,$type);
               if($ret){
                   return $this->ajaxError($ret,User_RetCode::getMsg($ret));
               }
            }
            return $this->ajax();
        }
        return $this->ajaxError(User_RetCode::UNKNOWN_ERROR,User_RetCode::getMsg(User_RetCode::UNKNOWN_ERROR));   
    }
}
