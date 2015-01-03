<?php
/**
 * 关于我们
 */
class AboutController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    public function indexAction() {
    }
}
