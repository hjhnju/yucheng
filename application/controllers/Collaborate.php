<?php
/**
 * 合作伙伴
 */
class CollaborateController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    public function indexAction() {
    }
}
