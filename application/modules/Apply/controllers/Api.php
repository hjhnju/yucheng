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
	    
	    $list = Apply_Api::getApplyList($page, $pagesize);
	    
	    $this->ajax($list);
	}
}
