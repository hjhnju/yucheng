<?php
/**
 * 用户注册相关操作
 */
class RegistController extends Base_Controller_Page{

    CONST LAST_TIME = 5;     //验证码过期时间,5分钟
    
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
       if(empty($strName)||(!User_RegCheck::checkReg('name', $strName))){
           return $this->ajaxError(User_RetCode::PARAM_ERROR,User_RetCode::getMsg(User_RetCode::PARAM_ERROR));
       }
       $data = $this->registLogic->checkName($strName);
       if(User_RetCode::SUCCESS == $data){
           return $this->ajax(User_RetCode::getMsg($data));
       }
       return $this->ajaxError($data,User_RetCode::getMsg($data));
    }
    
    /**
     * 检查手机号中否存在，返回值同上
     */
    public function checkPhoneAction(){
        $strPhone = trim($_REQUEST['phone']);
        if(empty($strPhone)||(!User_RegCheck::checkReg('phone', $strPhone))){
            return $this->ajaxError(User_RetCode::PARAM_ERROR,User_RetCode::getMsg(User_RetCode::PARAM_ERROR));
        }
        $data = $this->registLogic->checkPhone($strPhone);
        if(User_RetCode::SUCCESS == $data){
           return $this->ajax(User_RetCode::getMsg($data));
       }
       return $this->ajaxError($data,User_RetCode::getMsg($data));     
    }
    
    /**
     * 获取验证码信息
     */
    public function getVerificodeAction(){
       $strPhone   = trim($_REQUEST['phone']);
       $srandNum = srand((double)microtime()*1000000);   
       $arrArgs = array($srandNum, self::LAST_TIME);
       $tplid   = Base_Config::getConfig('sms.tplid.vcode', CONF_PATH . '/sms.ini');
       $bResult = Base_Sms::getInstance()->send($strPhone,$tplid, $arrArgs);
       $now = time();
       Yaf_Session::getInstance()->set("vericode",$srandNum.",".$now);
       if(!empty($bResult)){
           return $this->ajax(User_RetCode::getMsg($bResult));
       }
       return $this->ajaxError($bResult,User_RetCode::getMsg($bResult));
    }
    
    /**
     * 验证用户输入的验证码是否正确
     */
    public function checkVerificodeAction(){
        $strVeriCode   = trim($_REQUEST['vericode']);
        $strStoredCode = Yaf_Session::getInstance()->get("vericode");
        $arrData = explode(",",$strStoredCode);
        $time = time() - $arrData[1];
        if(($strVeriCode == $strStoredCode)&&($time <= 60*self::LAST_TIME)){
            return $this->ajax(User_RetCode::getMsg(User_RetCode::SUCCESS));
        }
        return $this->ajax(User_RetCode::getMsg(User_RetCode::UNKNOWN_ERROR));
    }
    
    /**
     * 检验推荐人是否存在
     * 
     */
    public function checkRefereeAction(){
        $referee = rim($_REQUEST['referee']);
        if(empty($referee)||(!User_RegCheck::checkReg('phone', $referee))){
            return $this->ajaxError(User_RetCode::PARAM_ERROR,User_RetCode::getMsg(User_RetCode::PARAM_ERROR));
        }
        $ref = new Referee();
        $bResult = $ref->checkReferee();
        if(User_RetCode::SUCCESS == $data){
           return $this->ajax(User_RetCode::getMsg($data));
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
        $data = $this->registLogic->regist($arrParam);
        if(User_RetCode::SUCCESS == $data){  //注册成功时判用户是否已经QQ等第三方登录
            $openid = Yaf_Session::getInstance()->get("openid");
            if(!empty($openid)){
               $type = Yaf_Session::getInstance()->get("idtype");
               $ret = $this->loginLogic->thirdLogin($openid,$type);
               if(ret){
                   return $this->ajaxError(ret,User_RetCode::getMsg(ret));
               }
            }
            return $this->ajax(User_RetCode::getMsg($data));
        }
        return $this->ajaxError($data,User_RetCode::getMsg($data));   
    }
    
    public function testAction(){
        User_VeriCode::getAuthImage();
    }
}