<?php
/**
 * 好友邀请页面
 */
class ApplyController extends Base_Controller_Page {
    
    CONST PAGESIZE = 20;    

    public function init() {
        parent::init();
        $this->userInfoLogic = new Account_Logic_UserInfo();
        $this->ajax          = true;
    }
    
    /**
     * assign至前端邀请url
     * inviteUrl 用户的专属邀请链接
     * userinfo 左上角信息
     */
    public function indexAction() {  
        $data       = Apply_Api::loadApply(3);   
        $userInfo   = $this->userInfoLogic->getUserInfo($this->objUser); 
        $this->getView()->assign('userinfo',$userInfo); 
        $this->getView()->assign('data',$data);   
    }
  
}
