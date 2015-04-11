<?php
/**
 * 帮助中心
 * */
class HelpController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    public function indexAction() {
    } 
     
}