<?php
/**
 * 安全保障
 */
class SecurityController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    /**
     * 全方位投资保障
     */
    public function indexAction() {
    }

    /**
     * 低门槛高收益
     */
    public function profitAction() {
    }

    /**
     * 专注教育行业
     */
    public function focusAction() {
    }

}
