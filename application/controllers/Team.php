<?php
/**
 * 团队介绍
 */
class TeamController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    public function indexAction() {
    }
}
