<?php
/**
 * 用户页面controller基础类
 * @author hejunhua
 */
class Base_Controller_Page extends Base_Controller_Abstract {

    public function init(){
        //增加日志字段
        $this->addBaseLogs(array('type'=>'page'));

        parent::init();

        $webroot = Base_Config::getConfig('web')->root;
        $this->getView()->assign('webroot', $webroot);
        $this->getView()->assign('feroot', $webroot . '/asset');

        //set csrf token
        $token = time() . mt_rand(10000, 99999);
        $ret   = Yaf_Session::getInstance()->set(Base_Keys::getCsrfTokenKey(), $token);
        //防止错误时验证无法访问
        $token = $ret ? $token : '';
        $this->getView()->assign('token', $token);
    }
    
    protected function isAjax(){
        return false;
    }

    public function redirect($url){
        parent::redirect($url);
        exit;
    }
}
