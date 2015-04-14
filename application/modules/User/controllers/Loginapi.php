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
        $strType   = User_Logic_Validate::getType($strName);
        $strCode   = isset($_POST['imagecode']) ? trim($_POST['imagecode']) : null;
        $isThird   = isset($_REQUEST['isthird']) ? intval($_REQUEST['isthird']) : 0;
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
        if(!User_Logic_Validate::check($strType, $strName)) {
            $intFails = intval($intFails) + 1;
            Yaf_Session::getInstance()->set(User_Keys::getFailTimesKey(), $intFails);
            if(empty($strType)){
                 return $this->ajaxError(User_RetCode::USERNAME_SYNTEX_ERROR,
                User_RetCode::getMsg(User_RetCode::USERNAME_SYNTEX_ERROR)
                );
            }
        }
       
        //登陆
        $logic   = new User_Logic_Login();
        $retCode = $logic->login($strType, $strName, $strPasswd);
        if(User_RetCode::SUCCESS !== $retCode) {
            $intFails = intval($intFails) + 1;
            Yaf_Session::getInstance()->set(User_Keys::getFailTimesKey(), $intFails);
            return $this->ajaxError($retCode, User_RetCode::getMsg($retCode));
        }
        Yaf_Session::getInstance()->set(User_Keys::getFailTimesKey(), 0);
        
        if($isThird>0){
            $openid     = Yaf_Session::getInstance()->get(User_Keys::getOpenidKey());
            $authtype   = Yaf_Session::getInstance()->get(User_Keys::getAuthTypeKey());
            $logicThird = new User_Logic_Third();
            $logicThird->binding($logic->checkLogin(), $openid, $authtype);
            Base_Log::notice(array(
                'msg'     => 'bind success',
                'openid'  => $openid,
                'name'    => $strName,
            ));
        }
        
        $redirectUri = Yaf_Session::getInstance()->get(User_Keys::LOGIN_REFER);
        Base_Log::notice(array(
            'msg'      => 'login success',
            'name'     => $strName,
            'redirect' => $redirectUri,
        ));

        if(!empty($redirectUri)){
            return $this->ajaxJump($redirectUri);
        }
        
        $this->ajax();
    }
     
}
