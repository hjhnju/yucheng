<?php
class EditController extends Base_Controller_Response {

    public function init(){
    	$this->setNeedLogin(false);
        parent::init();
        $this->ajax = true;
    }

    /**
     * 接口/account/edit/chphone
     * 修改手机号
     * @return 标准json
     * status 0:成功
     * 
     */
    public function chphoneAction() {
    	//渲染验证原手机号页面
    	$this->outputView = 'edit/chphone.phtml';
     	$this->output(); 
    }
    /**
     * 接口: /account/edit/checkphone
     * 用户验证原手机号
     * @param $phone
     * @param $vericode
     * @param $token, csrf token
     * @return 标准json格式
     * status 0: 成功
     * status 1102: 验证码输入错误
     * status 1113: 手机号码格式错误
     * status 1114: token验证失败
     */
    public function checkphoneAction(){
    	/* $pattern = '/^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/';
    	$phone = $_REQUEST['phone'];//前端会判空
        if(!preg_match($pattern,$phone)) {
           
            $errCode = Account_RetCode::PHONE_FORMAT_ERROR;//手机号码格式错误
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    		
    	} else {
    		$veriCode = isset($_REQUEST['vericode']) ? $_REQUEST['vericode'] : '';
    		$chkret = User_Api::checkSmscode($phone,$veriCode,Account_VeriCodeType::MODIFY_PHONE_SESSIONCODE);
    		if($chkret == User_RetCode::VERICODE_WRONG) {
    			$errCode = Account_RetCode::VERCODE_ERROR; //验证码输入错误
    			$errMsg = Account_RetCode::getMsg($errCode);
    			$this->outputError($errCode,$errMsg);
    		} else {
    			$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
    			$sessToken = $sessPhoneCode = Yaf_Session::getInstance()->get(Base_Keys::getCsrfTokenKey());
    			if($token != $sessToken) {
    				$errCode = Account_RetCode::TOKEN_VERIFY_ERROR; //验证码输入错误
    				$errMsg = Account_RetCode::getMsg($errCode);
    				$this->outputError($errCode,$errMsg);
    			} else {
    				//所有验证通过，返回status=0
    				$this->output();
    			}
    		}
    		   		
    	} */
    	$this->output();
    }
    
    /**
     * 接口3: /account/edit/bindNewPhone
     * 用户绑定新手机号
     * @param $phone
     * @param $vericode
     * @param $token, csrf token
     * @return 标准json格式
     * status 0: 成功
     * status 1102: 验证码输入错误
     * status 1113: 手机号码格式错误
     * status 1114: token验证失败
     * status 1103: 修改手机失败
     * 若前端检测返回值为0,即可以跳入至修改成功页面
     */
    public function bindNewPhoneAction(){
    	$pattern = '/^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/';
    	$phone = $_REQUEST['phone'];//前端会判空
    	if(!preg_match($pattern,$phone)) {
    		$errCode = Account_RetCode::PHONE_FORMAT_ERROR;//手机号码格式错误
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    	}
    	$veriCode = isset($_REQUEST['vericode']) ? $_REQUEST['vericode'] : '';
    	$chkret = User_Api::checkSmscode($phone,$veriCode,Account_VeriCodeType::MODIFY_PHONE_SESSIONCODE);
    	if($chkret == User_RetCode::VERICODE_WRONG) {
    		$errCode = Account_RetCode::VERCODE_ERROR; //验证码输入错误
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    	}
    	$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
    	$sessToken = $sessPhoneCode = Yaf_Session::getInstance()->get(Base_Keys::getCsrfTokenKey());
    	if($token != $sessToken) {
    		$errCode = Account_RetCode::TOKEN_VERIFY_ERROR; //验证码输入错误
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    	}
    	 
    	$userId = $this->getUserId();
    	$ret = User_Api::setPhone($userId,$phone);///新的手机号码入库
    	if($ret==false) {
    		$errCode = Account_RetCode::MODIFY_PHONE_FAIL; //修改手机号码失败
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    	}
    	//所有验证通过，返回status=0
    	$this->output();
    }
    
    /** 
     * 接口: /account/edit/chpwd
     * 用户密码修改接口
     * @param $oldpwd
     * @param $newpwd
     * @param $token, csrf token
     * @return 标准json格式
     * status 0: 成功
     * status 1101: 修改密码失败
     */
    public function chpwdAction(){
    	
    }
    
    /**
     * 接口 /account/edit/newemail
     * 提供新邮箱接口
     * @param $email
     * @param $vericode
     * @param $$token, csrf token
     * @return 标准json
     * status 0:成功
     * status 1102: 验证码输入错误
     * status 1114: 输入邮箱格式错误
     */
    public function newemailAction() {
    	
    }
    
    /**
     * 接口: /account/edit/chemail
     * 用户修改邮箱
     * 
     */
    public function chemailAction() {
    	//渲染提供新邮箱接口
    }
    
    /** 
     * 接口: /account/edit/getsmscode
     * 获取修改手机的短信验证码
     * @param $phone
     * @return 标准json格式
     * status 0: 成功
     * status 1112: 获取验证码失败
     * status 1113: 手机号格式错误
     * 
     */
    public function getSmsCodeAction(){
    	$pattern = '/^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/';
        $phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
        if(!preg_match($pattern,$phone)) {
        	$errCode = Account_RetCode::PHONE_FORMAT_ERROR;
        	$errMsg = Account_RetCode::getMsg($errCode);
        	$this->outputError($errCode,$errMsg);
        }
        //修改手机号的验证码获取使用type=2
        $type = Account_VeriCodeType::MODIFY_PHONE;
        $retCode = User_Api::sendSmsCode($phone,$type);
        if($retCode == User_RetCode::SUCCESS) {
        	$this->output();
        } else {
        	$errCode = Account_RetCode::GET_VERTICODE_FAIL;
        	$errMsg = Account_RetCode::getMsg($errCode);
        	$this->outputError($errCode,$errMsg);        	
        }
    }

}
