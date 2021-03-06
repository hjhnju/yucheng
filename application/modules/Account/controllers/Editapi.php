<?php
/**
 * 修改api类
 * @author lilu
 */
class EditapiController extends Base_Controller_Api {
	
	const REG_EMAIL = 'email';
	const REG_PHONE = 'phone';
	
	protected static $_arrRegMap = array(
			self::REG_EMAIL => '/^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$/',
			self::REG_PHONE => '/^0*(1[0-9])\d{9}$/',		
	);
	
    public function init(){
        parent::init();
        $this->ajax = true;
    }

    public function checkReg($type, $value){
        if(preg_match(self::$_arrRegMap[$type],$value)) {
        	return true;
        } else {
        	return false;
        }        
    }
    /**
     * 接口: /account/editapi/checkphone
     * 用户验证原手机号
     * @param $phone
     * @param $vericode
     * @param $token, csrf token
     * @return 标准json格式
     * status 0: 成功
     * status 1102: 验证码输入错误
     * status 1113: 手机号码格式错误
     * status 1120: 手机号输入与原手机号不同
     */
    public function checkphoneAction() {
        $objUser      = $this->objUser;
        $userId       = !empty($this->objUser) ? $this->objUser->userid : 0;
        $_originPhone = $objUser->phone;
        $originPhone  = isset($_originPhone) ? $_originPhone : '';
        $phone        = $_REQUEST['oldPhone'];//前端会判空
        $checkRet     = $this->checkReg(self::REG_PHONE,$phone);
    	if(!$checkRet) {
            $errCode = Account_RetCode::PHONE_FORMAT_ERROR;//手机号码格式错误
            $errMsg  = Account_RetCode::getMsg($errCode);
            $this->outputError($errCode,$errMsg);
    		return ;
    	}   	
    	if($phone != $originPhone) {
            $errCode = Account_RetCode::PHONE_INPUT_ERROR;//手机号输入与原手机号不同
            $errMsg  = Account_RetCode::getMsg($errCode);
            $this->outputError($errCode,$errMsg);
    		return ;
    	}   	
        $veriCode = isset($_REQUEST['vericode']) ? $_REQUEST['vericode'] : '';
        $type     = 3;
        $chkret   = User_Api::checkSmscode($phone,$veriCode,$type);
    	if(!$chkret) {
    		$errCode = Account_RetCode::VERCODE_ERROR; //验证码输入错误
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    	} else {
    		$this->output();  			 
    	}  
            
    } 
    
    
    /**
     * 接口3: /account/editapi/bindnewphone
     * 用户绑定新手机号
     * @param $phone
     * @param $vericode
     * @param $token, csrf token
     * @return 标准json格式
     * status 0: 成功
     * status 1102: 验证码输入错误
     * status 1113: 手机号码格式错误
     * status 1119: 手机号未发生改变
     * status 1103: 修改手机失败
     * 若前端检测返回值为0,即可以跳入至修改成功页面
     */
    public function bindnewphoneAction(){
        $objUser   = $this->objUser;
        $userId    = !empty($this->objUser) ? $this->objUser->userid : 0;
        $_oldphone = $objUser->phone;
        $oldphone  = isset($_oldphone) ? $_oldphone : '';     	
        $phone     = $_REQUEST['newPhone'];//前端会判空
        $veriCode  = isset($_REQUEST['vericode']) ? $_REQUEST['vericode'] : '';
        $checkReg  = $this->checkReg(self::REG_PHONE,$phone);
        $chkret    = User_Api::checkSmscode($phone,$veriCode,Account_VeriCodeType::MODIFY_PHONE);
    	if(!$checkReg) {
            $errCode = Account_RetCode::PHONE_FORMAT_ERROR;//手机号码格式错误
            $errMsg  = Account_RetCode::getMsg($errCode);
            $this->outputError($errCode,$errMsg);
    		return ;
    	} 
    	if($phone === $oldphone) {
    		$errCode = Account_RetCode::PHONE_NOT_CHANGE; //手机号没变
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    		return ;
    	}
    	$objLogin = new User_List_Login();
    	$filters = array('phone'=>$phone);
    	$objLogin->setFilter($filters);
    	$list = $objLogin->toArray();
    	$data = $list['list'][0];    	
    	if(!empty($data)) {
    		$errCode = Account_RetCode::PHONE_EXISTS;
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    		return;
    	}
    	if(!$chkret) {
    		$errCode = Account_RetCode::VERCODE_ERROR; //验证码输入错误
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    		return ;
    	} 
    	$ret = User_Api::setPhone($userId,$phone);///新的手机号码入库
    	if(!$ret) {
    		$errCode = Account_RetCode::MODIFY_PHONE_FAIL; //修改手机号码失败
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    		return ;
    	}
    	//所有验证通过，返回status=0
    	$this->output();  	    	   	
    }
        
    /** 
     * 接口: /account/editapi/modifypwd
     * 用户密码修改实现接口
     * @param $oldpwd
     * @param $newpwd
     * @param $token, csrf token
     * @return 标准json格式
     * status 0: 成功
     * status 1101: 修改密码失败
     */
    public function modifypwdAction(){
    	$oldpwd = $_REQUEST['oldpwd'];
    	$newpwd = $_REQUEST['newpwd'];
    	if(empty($oldpwd) || empty($newpwd)) {
    		$errCode = Account_RetCode::INPUT_PWD;
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    		return ;
    	}    	        
    	$userId = $this->userid;   	    				
    	$ret = User_Api::setPasswd($userId,$oldpwd,$newpwd);
        if($ret === User_RetCode::ORIGIN_PASSWD_WRONG) {
    		$errCode = Account_RetCode::OLDPWD_INPUT_ERROR;//原密码输入错误
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    		return ;
    	}
    	if($ret === User_RetCode::SAVE_PASSWD_WRONG){
    	} else if($ret === false){
    		$errCode = Account_RetCode::MODIFY_PWD_FAIL;//密码修改错误
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    		return ;
    	} 
    	$this->output();   	      
    }
    
    /**
     * 接口 /account/editapi/newemail
     * 提供新邮箱接口
     * @param $email
     * @param $vericode
     * @param $type
     * @param $$token, csrf token
     * @return 标准json
     * status 0:成功
     * status 1102: 验证码输入错误
     * status 1114: 输入邮箱格式错误
     * status 1118: 邮箱没有发生变化
     */
    public function newemailAction() {
    	$email = $_REQUEST['email'];
    	$vericode = $_REQUEST['vericode'];////////////////////前端没约定好！    	
    	$type = 'email';     
    	if(empty($email) || empty($vericode)) {
    		
    		$errCode = Account_RetCode::INPUT_CONTENT;
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    		return;
    	}
    	$emailPattern = self::$_arrRegMap[self::REG_EMAIL];
    	if(!preg_match($emailPattern,$email)) {
    		$errCode = Account_RetCode::EMAIL_FOEMAT_ERROR;
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    		return;
    	}
    	$objUser = $this->objUser;
    	$oldEmail = !empty($objUser) ? ($objUser->email) : '';
    	if($oldEmail === $email) {
    		$errCode = Account_RetCode::EMAIL_NOT_CHANGE;
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    		return;
    	}
    	
    	$objLogin = new User_List_Login();
    	$filters = array('email'=>$email);
    	$objLogin->setFilter($filters);
        $list = $objLogin->toArray(); 
        $data = $list['list'][0]; 
        
    	if(!empty($data)) {
    		$errCode = Account_RetCode::EMAIL_EXISTS;
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    		return;
    	}    	
    	$type = strval('email');    	
    	$bolCheckImg = User_Api::checkImageCode($vericode,$type);   
    	if(!$bolCheckImg) {
    		$errCode = Account_RetCode::VERCODE_ERROR;
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    		return;
    	} 
    	
    	////发送一封邮件到email代表的邮箱中
    	$email = strval($email);
    	$userid = $this->userid;
    	
    	$emailSec = Base_Util_Secure::encodeSand(Base_Util_Secure::PASSWD_KEY,$email);
    	$emailKey = $emailSec['key'];
    	$emailAuth = $emailSec['auth'];
    	$param = $emailKey.'_'.$emailAuth;
    	
    	$idSec = Base_Util_Secure::encodeSand(Base_Util_Secure::PASSWD_KEY,$userid);
    	$idKey = $idSec['key'];
    	$idAuth = $idSec['auth'];
    	$id = $idKey.'_'.$idAuth;
    	
    	$to = strval($email);    	
    	$subject = '修改邮箱地址';
        $webroot = $this->webroot;
        $body = <<<EOF
                            尊敬的兴教贷用户：<br/>
                            <br/>
                            您好，请点击以下链接激活您的绑定邮箱<br/>
                            <br/>
                            激活链接：$webroot/account/edit/emailsuccess?param=$param&id=$id <br/>
                            若不能直接打开，请将地址复制至浏览器地址栏。<br/>
                            <br/>
                            谢谢！<br/>
                            兴教贷
EOF;
        Base_Mailer::getInstance()->send($to, $subject, $body);    	
    	$this->output();
    }
    
    /**
     * 接口/account/editapi/veriemail
     * @param newemail
     * @param $$token, csrf token
     * 验证新邮箱接口
     */
    public function veriemailAction() {
    	$newEmail = $_REQUEST['newemail'];
    	$webroot = Base_Config::getConfig('web')->root;    	
    	$sendUrl = $webroot.'/account/edit/emailsuccess';//跳到修改邮件成功页面    	
    }
    /** 
     * 接口: /account/editapi/getsmscode
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
    
    /**
     * 接口/account/editapi/unbindthird
     * 解绑入口
     */
    public function unbindthirdAction() {
    	$userid = $this->userid;
    	$thirdBindRet = User_Api::checkBind($userid);
    	$type = $thirdBindRet['type'];
    	if(!User_Api::delBind($userid,$type)) {
    		Base_Log::error(array(
    		'msg'    => '解绑失败',
    		'userid' => $userid,
    		'type'   => $type,
    		));
    		$errCode = Account_RetCode::UNBIND_FAIL;
    		$errMsg = Account_RetCode::getMsg($errCode);
    		$this->outputError($errCode,$errMsg);
    	}
    	$this->output();    	 
    }

}
