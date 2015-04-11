<?php
/**
 * 媒体报道
 */
class RegistController extends Base_Controller_Page {

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
    	$this->getView()->assign('title', "注册兴教贷");
    } 
}
