<?php
/**
 * 申请列表
 */
class ApiController extends Base_Controller_Api {
    protected $ajax = true;
    protected $needLogin = true;
	
	public function indexAction() {
	    $page = $this->getInt('page', 1);
	    $pagesize = 10;
	    $objUser = User_Api::checkLogin();
	    $filter = array('userid' => $objUser->userid);
	    $list = Apply_Api::getApplyList($page, $pagesize, $filter);
	    
	    $this->ajax($list);
	}
}
