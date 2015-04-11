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
        
        // $inviteInfo = Awards_Api::getInviteAwards($userid);//获取邀请列表
        $inviteInfo = array(
            'invitee_awards'     => '50.00',
            'invitee_cnt'        => 10,
            'invitee_invest_cnt' => 5,
            'invitee_list'       => array(
                array(
                    'username'        => 'hj***ju',
                    'phone'           => '186***043',
                    'regist_progress' => 1, //已开通
                    'invested'        => 1, //已投资
                    'amount'          => '30.00',
                ),
                array(
                    'username'        => 'hj***ju2',
                    'phone'           => '186***043',
                    'regist_progress' => 0, //未开通
                    'invested'        => 0, //未投资
                    'amount'          => null, //暂无奖励
                ),
            ),
        );

        $this->getView()->assign('inviteurl', $inviteUrl);   
        $this->getView()->assign('userinfo', $userInfo);
        $this->getView()->assign('inviterinfo', $inviteInfo);        
    }
  
}
