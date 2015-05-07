<?php
/**
 * 微站我的个人中心
 */
class AccountController extends Base_Controller_Page{
	protected $loginUrl = '/m/login'; 
    
    const ERROR_KEY = 'invest_error';
    
    public function init(){
        //未登录跳转
        $this->setNeedLogin(false);
        
        parent::init();
    }
     /*
     * 账户总览页面   我的账户
     * /m/account/overview
     */
    public function overviewAction() {
    	$this->getView()->assign('title', "我的账户"); 
    }
}