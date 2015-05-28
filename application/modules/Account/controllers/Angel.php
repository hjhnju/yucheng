<?php
/**
 */
class AngelController extends Base_Controller_Page {
    
    CONST PAGESIZE = 10;    

    public function init() {
        parent::init();
    }
    
    /**
     * assign至前端邀请url
     * inviteUrl 用户的专属邀请链接
     * userinfo 左上角信息
     */
    public function indexAction() {             
        $userid     = $this->userid;    
        $webroot    = Base_Config::getConfig('web')->root;
        
        $logic     = new Account_Logic_UserInfo();
        $userInfo  = $logic->getUserInfo($this->objUser);
        
        $this->getView()->assign('userinfo', $userInfo);
    }
  
}
