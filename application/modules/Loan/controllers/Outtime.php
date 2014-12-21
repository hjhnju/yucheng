<?php
/**
 * 借款逾期
 * @author jiangsongfang
 *
 */
class OuttimeController extends Base_Controller_Admin {
    
	/**
	 * 借款逾期列表
	 */
	public function indexAction() {
	    $page = intval($_REQUEST['page']);
	    $outtime = $this->getOuttime();
	    
	    $list = new Loan_List_Refund();
	    $list->setPage($page);
	    $list->setFilterString($outtime);
	    
	    $this->_view->assign('data', $list->toArray());
	}
	
	private function getOuttime() {
	    $time = date("Y-m-d H:i:s");
	    $filter = "promise_time < $time and status = 1";
	    return $filter;
	}
}