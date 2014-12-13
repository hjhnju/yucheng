<?php
/**
 * 用户资料查看、修改相关操作
 */
class MaterialController extends Base_Controller_Page{
    
    public function init(){
        parent::init();
        $this->materialLogic = new User_Logic_Material();
    }
    
    /**
     * 获取用户信息
     */
    public function getUserInfo(){
        if(Yaf_Session::getInstance()->has("LOGIN")){
            $this->uid = Yaf_Session::getInstance()->get("LOGIN");
            $data = $this->materialLogic->getUserInfo($this->uid);
        }
        if(!empty($data)){
            return $this->ajax(array(
                'data'=> $data,
            ));
        }
        return $this->ajaxError(User_RetCode::DATA_NULL,User_RetCode::getMsg(User_RetCode::DATA_NULL));
    }
    
    /**
     * 设置用户信息
     * @param $arrParams
     */
    public function setUserInfo(){
        $strRealName = trim($_REQUEST['realname']);
        $strPhone = trim($_REQUEST['phone']);
        $huifu_id = trim($_REQUEST['huifuid']);
        $cert_type = trim($_REQUEST['cert_type']);
        $cert_contents = trim($_REQUEST['cert_content']);
        $strEmail = trim($_REQUEST['email']);
        $ret = $this->materialLogic->addUserInfo(array(
        	'realname'            => $strRealName,
            'phone'               => $strPhone,
            'huifu_id'            => $huifu_id,
            'certificate_type'    =>$cert_type,
            'certificate_content' =>$cert_contents,
            'email'               =>$strEmail,
        ));
        if(User_RetCode::SUCCESS == $ret){
            return $this->ajax(User_RetCode::SUCCESS);
        }
        return $this->ajaxError($ret,User_RetCode::getMsg($ret));
    }
    
    /**
     * 设置真实用户姓名
     */
    public function setRealNameAction(){
        $strName = trim($_REQUEST['realname']);
        if(empty($strName)||(!User_RegCheck::checkReg('realname', $strName))){
            return $this->ajaxError(User_RetCode::PARAM_ERROR,User_RetCode::getMsg(User_RetCode::PARAM_ERROR));
        }
        $data = $this->materialLogic->setRealName($strName);
        if(User_RetCode::SUCCESS == $data){
            return $this->ajax(User_RetCode::getMsg($data));
        }
        return $this->ajaxError($data,User_RetCode::getMsg($data));
    }
    
    /**
     * 设置用户手机号
     */
    public function setPhoneAction(){
        $strPhone = trim($_REQUEST['phone']);
        if(empty($strPhone)||(!User_RegCheck::checkReg('phone', $strPhone))){
            return $this->ajaxError(User_RetCode::PARAM_ERROR,User_RetCode::getMsg(User_RetCode::PARAM_ERROR));
        }
        $data = $this->materialLogic->setPhone($strPhone);
        if(User_RetCode::SUCCESS == $data){
           return $this->ajax(User_RetCode::getMsg($data));
       }
       return $this->ajaxError($data,User_RetCode::getMsg($data));  
    }
    
    /**
     * 设置用户邮箱
     */
    public function setEmailAction(){
        $strPhone = trim($_REQUEST['phone']);
        if(empty($strPhone)||(!User_RegCheck::checkReg('email', $strPhone))){
            return $this->ajaxError(User_RetCode::PARAM_ERROR,User_RetCode::getMsg(User_RetCode::PARAM_ERROR));
        }
        $data = $this->materialLogic->setEmail($strPhone);
        if(User_RetCode::SUCCESS == $data){
            return $this->ajax(User_RetCode::getMsg($data));
        }
        return $this->ajaxError($data,User_RetCode::getMsg($data));
    }
    
    /**
     * 设置用户密码
     */
    public function setPasswdAction(){
        $strPhone = trim($_REQUEST['passwd']);
        if(empty($strPhone)||(!User_RegCheck::checkReg('passwd', $strPhone))){
            return $this->ajaxError(User_RetCode::PARAM_ERROR,User_RetCode::getMsg(User_RetCode::PARAM_ERROR));
        }
        $data = $this->materialLogic->setPasswd($strPhone);
        if(User_RetCode::SUCCESS == $data){
            return $this->ajax(User_RetCode::getMsg($data));
        }
        return $this->ajaxError($data,User_RetCode::getMsg($data));
    }
}
