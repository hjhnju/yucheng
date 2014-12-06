<?php
/**
 * 用户API controller基础类
 * @author hejunhua <hejunhua@baidu.com>
 */
class User_Base_Api extends User_Base {
    
    public function init () {
        parent::init();
        $this->_apiversion   = (float)$this->getRequest()->getParam('version');
        Ap_Dispatcher::getInstance()->disableView();

        //打日志
        $this->baselog();
    }

    /**
     * log for every api
     */
    public function baselog(){         
        //解析du串
        Base_Log::notice(array(
            'controller' => Base_Ssl::filterValue($this->getRequest()->getControllerName()),
            'action'     => Base_Ssl::filterValue($this->getRequest()->getActionName()),
            'vuid'       => $this->_vuid,
            'duid'       => $this->_duid,
            'version'    => $this->_apiversion,
            'type'       => 'api',
        ));
    }

}
