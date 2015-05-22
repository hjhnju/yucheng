<?php
/**
 * 首页
 */
class IndexController extends Base_Controller_Page {
	//初始化
    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    public function indexAction() {
        
    }
}
