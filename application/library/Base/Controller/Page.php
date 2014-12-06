<?php
/**
 * 用户页面controller基础类
 * @author hejunhua <hejunhua@baidu.com>
 */
class User_Base_Page extends User_Base {

    public function init () {
        parent::init();

        $this->getView()->assign('webroot', $this->_webroot);
        $this->getView()->assign('passroot', $this->_passroot);

        //打日志
        $this->baselog();
    }

    public function redirect($url){
        parent::redirect($url);
        exit;
    }

    /**
     * log for every page
     */
    public function baselog(){         
        //解析du串
        Base_Log::notice(array(
            'controller' => Base_Ssl::filterValue($this->getRequest()->getControllerName()),
            'action'     => Base_Ssl::filterValue($this->getRequest()->getActionName()),
            'vuid'       => $this->_vuid,
            'duid'       => $this->_duid,
            'type'       => 'page',
        ));
    }
}
