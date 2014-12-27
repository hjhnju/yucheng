<?php
/**
 * 用户注册相关操作
 */
class RegistApiController extends Base_Controller_Api{
    
    public function init(){
       
        $this->setNeedLogin(false);

        parent::init();
        $this->loginLogic = new User_Logic_Login();
    }
    
    /**
     * 检查用户名是否存在
     * 
     */
    public function checkNameAction(){
        $strName = trim($_REQUEST['name']);
        $logic   = new User_Logic_Regist();
        $retCode = $logic->checkName($strName);
        if(User_RetCode::SUCCESS !== $retCode){
            $this->ajaxError($retCode, User_RetCode::getMsg($retCode));
        }else{
            $this->ajax();
        }
    }
    
    /**
     * 检查手机号中否存在，返回值同上
     */
    public function checkPhoneAction(){
        $strPhone = trim($_REQUEST['phone']);

        $logic   = new User_Logic_Regist();
        $retCode = $logic->checkPhone($strPhone);

        if(User_RetCode::SUCCESS !== $retCode){
            $this->ajaxError($retCode, User_RetCode::getMsg($retCode));     
        }else{
            $this->ajax();
        }
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
        $strPhone    = trim($_REQUEST['phone']);
        $strType     = trim($_REQUEST['type']);
        $strVeriCode = trim($_REQUEST['vericode']);
        $ret = User_Api::checkSmsCode($strPhone, $strVeriCode,$strType);
        if($ret){
           return $this->ajax();
       }
       return $this->ajaxError($ret,User_RetCode::getMsg($ret));
    }
    
    /** 
    * 接口4: /user/regist/checkinviter
    * 检查推荐人是否存在
    * @param string $inviter
    * @param string $token
    * @return 标准Json格式
    * status 0:成功，表示推荐人存在
    * status 1031:推荐人不存在
    */
    public function checkInviterAction(){
        $inviter = trim($_REQUEST['inviter']);

        $logic   = new User_Logic_Regist();
        $retCode = $logic->checkInviter($inviter);
        if(User_RetCode::SUCCESS !== $retCode){
            return $this->ajaxError($retCode, User_RetCode::getMsg($retCode)); 
        }
        return $this->ajax();
    }
    
    /** 
    * 接口5: /user/registapi/index
    * 用户注册接口
    * @param string $name
    * @param string $passwd
    * @param string $phone
    * @param string $inviter, option
    * @return 标准Json格式
    * status 0:成功
    * status 1033:注册失败
    * 
    * 
    */
    public function indexAction(){
        $strName    = trim($_REQUEST['name']);
        $strPasswd  = md5(trim($_REQUEST['passwd']));
        $strPhone   = trim($_REQUEST['phone']);
        $strInviter = isset($_REQUEST['inviter']) ? $_REQUEST['inviter'] : '';

        //各字段再验证过一遍
        //TODO::
        
        //注册
        $logic   = new User_Logic_Regist();
        $retCode = $logic->regist($strName, $strPasswd, $strPhone, $strInviter);
        if(User_RetCode::SUCCESS !== $retCode){  
            $this->ajaxError($retCode, User_RetCode::getMsg($retCode));   
        }else{
            $this->ajax();
        }
    }
}
