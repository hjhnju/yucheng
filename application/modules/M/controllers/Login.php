<?php
/**
 * 微站登录
 */
class LoginController extends Base_Controller_Page {

    protected $loginUrl = '/m/login';
    
    public function init(){
        //未登录不跳转
        $this->setNeedLogin(false);

        parent::init();
    }
    /**
     * 登录
     *
     * /m/login
     * @param   
     * @assign   
     */
    public function indexAction() {
    	$this->getView()->assign('title', "登陆兴教贷");

        $logic  = new User_Logic_Login();
        $userid = $logic->checkLogin();
        if($userid){
            $this->redirect('/account/overview');
        }

        $u = isset($_REQUEST['u'])?trim($_REQUEST['u']):null;
        if(!empty($u)){
            $strRedirect = $u;
        }else{
            $strRedirect = $logic->loginRedirect($_SERVER['HTTP_REFERER']);
        }        
        Yaf_Session::getInstance()->set(User_Keys::LOGIN_REFER,$strRedirect);
        $intFails = Yaf_Session::getInstance()->get(User_Keys::getFailTimesKey());
        if($intFails >= 3) {
            $this->getView()->assign('url',$this->webroot . '/user/imagecode/getimage?type=login');
        }
    }
    
 

}
