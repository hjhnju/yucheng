<?php
/**
 * 投资列表
 */
class IndexController extends Base_Controller_Page {
	
	public function indexAction() {
	    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
	    if ($page < 1) {
	        $page = 1;
	    }
	    $pagesize = 10;
	    $filter = $this->getFilters();
	    $list = Loan_Api::getLoans($page, $pagesize, $filter);
	    print_r($list);exit;
	    $this->_view->assign('data', $list);
	}
	
	private function getFilters() {
	    return array();
	}
}
