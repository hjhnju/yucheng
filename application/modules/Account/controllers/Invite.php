<?php
/**
 * 好友邀请页面
 */
class InviteController extends Base_Controller_Page {
    
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
        $userid     = $this->userid;    
        $webroot    = Base_Config::getConfig('web')->root;
        
        $userInfo   = $this->userInfoLogic->getUserInfo($this->objUser);
        $inviteUrl  = Awards_Api::getInviteUrl($userid);
        $inviteUrl  = ($inviteUrl != false) ? $inviteUrl : ""; //获取该用户的专属邀请链接

        $data = array();
        $data['invitee_awards'] = Awards_Api::getInviteAwardsSum($userid);
        $data['invitee_cnt']    = User_Api::getInviteeCnt($userid);

        $this->getView()->assign('inviteurl', $inviteUrl);   
        $this->getView()->assign('userinfo', $userInfo);       
        $this->getView()->assign('data', $data);       
    }
  
}
