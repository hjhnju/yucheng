<?php
/**
 * 注册
 */
class RegistController extends Base_Controller_Page {
    
    protected $loginUrl = '/m/login';
    
    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    /**
     * 注册页
     *
     * /m/regist
     * @param    
     * @assign   
     */
    public function indexAction() {
    	$this->getView()->assign('title', "注册兴教贷,请补全您的用户信息");
	    if(isset($_REQUEST['inviter'])){
	        $inviter = $_REQUEST['inviter'];
	        $this->getView()->assign('inviter', $inviter);
	    }
    } 
}
