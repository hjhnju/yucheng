<?php
/**
 * 投标失败
 */
class FailController extends Base_Controller_Response {
	
	public function indexAction() {
	    $info = $this->_request->getRequest('info');
	    $this->_view->assign('info', $info);
	}
}
