<?php
/**
 * 用户登录相关操作
 */
class LoginapiController extends Base_Controller_Api{
    
    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    /**
     * 标准登录过程
     * 状态返回0表示登录成功
     */    
    public function indexAction(){
        $strName   = trim($_POST['name']);
        $strPasswd = trim($_POST['passwd']);
        $strCode   = isset($_POST['imagecode']) ? trim($_POST['imagecode']) : null;
        //检查错误次数
        $intFails = Yaf_Session::getInstance()->get(User_Keys::getFailTimesKey());
        if(empty($strCode) && $intFails >= 3) {
            return $this->ajaxError(
                User_RetCode::NEED_PICTURE,
                User_RetCode::getMsg(User_RetCode::NEED_PICTURE), array(
                    'url' => $this->webroot . '/user/imagecode/getimage?type=login')
            );
        }

        //检查验证码
        if($strCode){
            $bolRet = User_Logic_ImageCode::checkCode('login', $strCode);
            if(!$bolRet){
                return $this->ajaxError(User_RetCode::IMAGE_CODE_WRONG,
                    User_RetCode::getMsg(User_RetCode::IMAGE_CODE_WRONG));
            }
        }
  
        //检查用户名语法
        if(!User_Logic_Validate::checkName($strName)) {
            $intFails = intval($intFails) + 1;
            Yaf_Session::getInstance()->set(User_Keys::getFailTimesKey(), $intFails);
    
            return $this->ajaxError(User_RetCode::USERNAME_SYNTEX_ERROR,
                User_RetCode::getMsg(User_RetCode::USERNAME_SYNTEX_ERROR)
            );
        }
       
        //登陆
        $logic   = new User_Logic_Login();
        $retCode = $logic->login($strName, $strPasswd);
        if(User_RetCode::SUCCESS !== $retCode) {
            $intFails = intval($intFails) + 1;
            Yaf_Session::getInstance()->set(User_Keys::getFailTimesKey(), $intFails);

            return $this->ajaxError($retCode, User_RetCode::getMsg($retCode));
        }
        Yaf_Session::getInstance()->set(User_Keys::getFailTimesKey(), 0);
        Base_Log::notice(array(
            'msg'   => 'login success',
            'name'  => $strName,
            'useid' => $logic->checkLogin()->userid,
        ));
        $redirectUri = Yaf_Session::getInstance()->get(User_Keys::LOGIN_REFER);
        if(!empty($redirectUri)){
            return $this->ajaxJump($redirectUri);
        }
        $this->ajax();
    }
    
    /**
     * 标准退出登录过程
     * 状态返回0表示登出成功
     */
    public function signOutAction(){
    	$logic   = new User_Logic_Login();
    	$ret = $logic->signOut();
    	if($ret){
    		$redirectUri = '/user/login/index';
    		$this->redirect($redirectUri);
    	}
    	$this->ajaxError();
    }    
}
