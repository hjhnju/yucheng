<?php
/**
 * 用户页面controller基础类
 * @author hejunhua <hejunhua@baidu.com>
 */
class Base_Controller_Page extends Base_Controller_Response {

    public function init () {
        parent::init();

        $webroot = Base_Config::getConfig('web.root');
        $this->getView()->assign('webroot', $webroot);

        //打日志
        $this->baselog();
    }
    
    protected function isAjax() {
        return false;
    }

    public function redirect($url){
        parent::redirect($url);
        exit;
    }

    /**
     * log for every page
     */
    protected function baselog(){         
        //解析du串
        Base_Log::notice(array(
            'controller' => $this->getRequest()->getControllerName(),
            'action'     => $this->getRequest()->getActionName(),
            //'userid'     => $this->_userid,
            'type'       => 'page',
        ));
    }
}
