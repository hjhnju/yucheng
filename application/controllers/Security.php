<?php
/**
 * 安全保障
 */
class SecurityController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    public function indexAction() {
    }
}
