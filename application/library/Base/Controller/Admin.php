<?php
/**
 * 后台管理页面controller基础类
 * @author jiangsongfang
 */
class Base_Controller_Admin extends Base_Controller_Response {
    protected $loginUrl = '/admin/login';
    
    public function init() {
        parent::init();
        // 定义的默认的action
        $controller = $this->_request->controller;
        $action     = $this->_request->action;
        $filename   = 'modules/' . MODULE . '/actions/' . $controller . '/' . ucfirst($action) . '.php';
        
        $this->actions = array(
            $action => $filename,
        );
        
        $uri = $this->_request->getRequestUri();
        $this->_view->assign('uri', $uri);
    }
}
