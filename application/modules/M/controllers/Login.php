<?php
/**
 * 微站登录
 */
class LoginController extends Base_Controller_Page {

    public function init(){
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
    }
    
 

}
