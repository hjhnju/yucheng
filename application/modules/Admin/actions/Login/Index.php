<?php
/**
 * 后台登录
 * @author jiangsongfang
 *
 */
class IndexAction extends Base_Controller_Action {
    public function execute() {
        if (!empty($_POST)) {
            $strName   = trim($_POST['name']);
            $strPasswd = md5(trim($_POST['passwd']));
            $strCode   = isset($_POST['imagecode']) ? trim($_POST['imagecode']) : null;
            //检查错误次数
            $intFails = Yaf_Session::getInstance()->get(User_Keys::getFailTimesKey());
            if(empty($strCode) && $intFails >= 3) {
                $this->_view->assign('imgcode', $this->webroot . '/user/imagecode/getimage?type=login');
            }
            
            //检查验证码
            if($strCode){
                $bolRet = User_Logic_ImageCode::checkCode('login', $strCode);
                if(!$bolRet){
                    return $this->outputError(Admin_RetCode::CAPTCHA_WRONG, Admin_RetCode::getMsg(Admin_RetCode::CAPTCHA_WRONG));
                }
            }
            
            //检查用户名语法
            if(!User_Logic_Validate::checkName($strName)) {
                $intFails = intval($intFails) + 1;
                Yaf_Session::getInstance()->set(User_Keys::getFailTimesKey(), $intFails);

                return $this->outputError(Admin_RetCode::USER_NOTEXISTS, Admin_RetCode::getMsg(Admin_RetCode::USER_NOTEXISTS));
            }
             
            //登陆
            $logic   = new User_Logic_Login();
            $retCode = $logic->login($strName, $strPasswd);
            if(User_RetCode::SUCCESS !== $retCode) {
                $intFails = intval($intFails) + 1;
                Yaf_Session::getInstance()->set(User_Keys::getFailTimesKey(), $intFails);

                return $this->outputError(Admin_RetCode::PASSWORD_WRONG, Admin_RetCode::getMsg(Admin_RetCode::PASSWORD_WRONG));
            }
            
            // 获取用户信息
            $user = User_Api::checkLogin();
            
            $admin = new Admin_Object_Admin($user->userid);
            if ($admin->status !== 0) {
                return $this->outputError(Admin_RetCode::NOT_ADMIN, Admin_RetCode::getMsg(Admin_RetCode::NOT_ADMIN));
            }
            
            Yaf_Session::getInstance()->set(User_Keys::getFailTimesKey(), 0);
            // $this->ajaxJump($redirectUri);
            Base_Log::notice(array(
                'msg'   => 'login success',
                'name'  => $strName,
                'useid' => $user->userid,
            ));
            return $this->redirect('/admin');
        }
    }
}