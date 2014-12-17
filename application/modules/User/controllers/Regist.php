<?php
/**
 * 用户注册相关操作
 */
class RegistController extends Base_Controller_Page{
    
    public function init(){
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
    public function getVerificodeAction(){
       $strPhone   = trim($_REQUEST['phone']);
       $ret = User_Api::getVerificode($strPhone);
       if(User_RetCode::SUCCESS == $ret){
           return $this->ajax();
       }
       return $this->ajaxError($ret);
    }
    
    /**
     * 验证用户输入的验证码是否正确
     */
    public function checkVerificodeAction(){
        $strPhone   = trim($_REQUEST['phone']);
        $strVeriCode   = trim($_REQUEST['vericode']);
        $ret = User_Api::checkVerificode($strPhone, $strVeriCode);
       if(User_RetCode::SUCCESS == $ret){
           return $this->ajax();
       }
       return $this->ajaxError($ret,User_RetCode::getMsg($ret));
    }
    
    /**
     * 检验推荐人是否存在
     * 
     */
    public function checkRefereeAction(){
        $referee = rim($_REQUEST['referee']);
        if(empty($referee)||(User_Api::checkReg('phone', $referee))){
            return $this->ajaxError(User_RetCode::REFEREE_SNYTEX_ERROR,User_RetCode::getMsg(User_RetCode::REFEREE_SNYTEX_ERROR));
        }
        $ref = new Referee();
        $bResult = $ref->checkReferee();
        if(User_RetCode::SUCCESS == $data){
           return $this->ajax();
       }
       return $this->ajaxError($data,User_RetCode::getMsg($data));   
    }
    
    /**
     * 用户注册类
     */
    public function RegistAction(){
        $strName    = trim($_REQUEST['name']);
        $strPasswd  = md5(trim($_REQUEST['passwd']));
        $strPhone   = trim($_REQUEST['phone']);
        $strReferee = "";
        if(isset($_REQUEST['referee'])){
           $strReferee = $_REQUEST['referee'];
        }
        $arrParam = array(
            'name'   => $strName,
            'passwd' => $strPasswd,
            'phone'  => $strPhone,
            'refere' => $strReferee,
        );
        $uid = $this->registLogic->regist($arrParam);
        if(User_RetCode::INVALID_USER!= $uid){  //注册成功时判用户是否已经QQ等第三方登录
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