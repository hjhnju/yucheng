<?php
/**
 * 用户API controller基础类
 * @author hejunhua
 */
class Base_Controller_Api extends Base_Controller_Abstract {
    
    public function init () {
        parent::init();
        Ap_Dispatcher::getInstance()->disableView();

        //打日志
        $this->baselog();
    }

    /**
     * log for every api
     */
    protected function baselog(){
        Base_Log::notice(array(
            'controller' => $this->getRequest()->getControllerName(),
            'action'     => $this->getRequest()->getActionName(),
            //'userid'     => $this->_userid,
            'type'       => 'api',
        ));
    }

}
