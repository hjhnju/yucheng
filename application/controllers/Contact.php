<?php
/**
 * 联系我们
 */
class ContactController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    public function indexAction() {
    }
}
