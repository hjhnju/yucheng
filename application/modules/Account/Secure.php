<?php
/**
 * 安全中心页面
 */
class SecureController extends Base_Controller_Response{
	
	public function init() {
		parent::init();
		$this->ajax= true;
	}
	
	public function index() {
		$$userid = User_Api::getUserId();
		$objUser = User_Api::getUserObject($userid);
		
	}
}