<?php
/**
 * 合同协议
 * 无需登录
 */
class AgreementsController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    /**
     * 注册协议
     */
    public function registAction() {
    }
}