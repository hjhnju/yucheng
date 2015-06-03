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

	/**
	 * 保存上传过的文件
	 * @return [type] [description]
	 */
	public function filesAction(){
		$objUser = User_Api::checkLogin();
		$params = array(
		  	'apply_id' => $_POST['applyId'],
		 	'userid' => $objUser->userid,
		 	'type'	=> $_POST['type'],
		 	'title' => '',
		 	'url'   => $_POST['hash']
		 );
		$logic 	= new Apply_Logic_Attach();
		$objRet = $logic->saveFile($params);
		$this->ajax('', $objRet['statusInfo'], $objRet['status']);
	}
}
