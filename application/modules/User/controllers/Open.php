<?php
/**
 * 用户注册相关操作
 */
class OpenController extends Base_Controller_Page{
    
    public function init(){
        //TODO:remove
        $this->setNeedLogin(false);
        parent::init();
    }
    
    /**
     * 用户开通汇付
     */
    public function IndexAction(){
        //
        
    }
}
