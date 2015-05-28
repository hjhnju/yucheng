<?php
/**
 * 投标成功
 */
class SuccessController extends Base_Controller_Response {
	
	public function indexAction() {
	    $sess = Yaf_Session::getInstance();
	    $amount = $sess->get('invest_amount');
	    $name = isset($_REQUEST['name'])?$_REQUEST['name']:'';
	    $this->_view->assign('amount', Base_Util_Number::tausendStyle($amount));
	    $this->_view->assign('name',$name);
	}
}
