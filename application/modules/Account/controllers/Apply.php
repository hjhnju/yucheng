<?php
/**
 * 融资用户融资列表页面
 */
class ApplyController extends Base_Controller_Page {
    protected $needLogin = true;

    public function init() {
        parent::init();
        $this->userInfoLogic = new Account_Logic_UserInfo();
    }
    
    /**
     * 融资列表默认请求页面
     */
    public function indexAction() {  
        $page = $this->getInt('page', 1);
        $pagesize = 10;

        $data       = Apply_Api::getApplyList($page, $pagesize);  
        $userInfo   = $this->userInfoLogic->getUserInfo($this->objUser); 
        $this->getView()->assign('userinfo',$userInfo); 
        $this->getView()->assign('data',$data);   
    }
  
}
