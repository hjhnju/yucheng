<?php
/**
 * 用户登录相关操作
 */
class LoginController extends Base_Controller_Page{
    
    public function init(){
        parent::init();
        $this->loginLogic = new User_Logic_Login();
    }
    
    /**
     * 标准登录过程
     * 状态返回0表示登录成功
     */    
    public function indexAction(){
    }
  
    /**
     * 第三方登录跳转中间页
     */
    public function authAction(){
        $strType = trim($_REQUEST['type']);
        $this->redirect($this->loginLogic->getAuthCodeUrl($strType));
    }
}
