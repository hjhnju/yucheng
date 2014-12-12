<?php
/**
 * 用户注册相关操作
 */
class RegistController extends Base_Controller_Page{

    
    CONST LAST_TIME = 60;
    protected $verified;
    
    public function init(){
        parent::init();
        $this->verified = 0;
        $this->registLogic = new User_Logic_Regist();
    }
    
    /**
     * 检查用户名是否存在
     * empty=>0表示存在,empty=>1表示不存在
     */
    public function checkNameAction(){
       $strName = trim($_REQUEST['name']);
       $data = $this->registLogic->checkName($strName);
       if(0 == $data){
           return $this->ajax('success');
       }
       return $this->ajaxError($data,"test");
    }
    
    /**
     * 检查手机号中否存在，返回值同上
     */
    public function checkPhoneAction(){
        $strPhone = trim($_REQUEST['phone']);
        $data = $this->registLogic->checkPhone($strPhone);
        return $this->ajax(array(
            'empty'=>$data,
        ));      
    }
    
    /**
     * 获取验证码信息
     */
    public function getVerificodeAction(){
       $strPhone   = trim($_REQUEST['phone']);
       $srandNum = srand((double)microtime()*1000000);
       $sms = new Base_Util_Sms();      
       $sms ->send($strPhone,'您的验证码是：'.$srandNum);
       $now = time();
       Yaf_Session::getInstance()->set("vericode",$srandNum.",".$now);
       return $this->ajax(array(
           'status'=>$bResult,
       ));
    }
    
    /**
     * 验证用户输入的验证码是否正确
     */
    public function checkVerificodeAction(){
        $strVeriCode   = trim($_REQUEST['vericode']);
        $strStoredCode = Yaf_Session::getInstance()->get("vericode");
        $arrData = explode(",",$strStoredCode);
        $time = time() - $arrData[1];
        if(($strVeriCode == $strStoredCode)&&($time<=100)&&($this->verified <= sef::LAST_TIME)){
            return $this->ajax(array(
            'status'=>0,
        ));
        }
        return $this->ajax(array(
            'status'=>1,
        ));
    }
    
    /**
     * 检验推荐人是否存在
     * empty=>0表示存在,empty=>1表示不存在
     */
    public function checkRefereeAction(){
        $ref = new Referee();
        $bResult = $ref->checkReferee();
        return $this->ajax(array(
            'empty'=>$bResult,
        ));
    }
    
    /**
     * 用户注册类
     */
    public function RegistAction(){
        if($this->verified){
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
        return $this->ajax(array(
            'success'=>$data,
        ));
        }       
        return $this->ajax(array(
            'success'=>$data,
        ));
    }
}