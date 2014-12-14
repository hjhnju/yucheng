<?php
/**
 * 借款列表
 * @author jiangsongfang
 *
 */
class ListController extends Base_Controller_Abstract {
    
	/**
	 * 借款列表
	 */
	public function indexAction() {
	    $page = intval($_REQUEST['page']);
	    $list = new Loan_List_Loan();
	    $list->setPage($page);
	    $this->addFilter($list);
	    
	    $this->_view->assign('data', $list->toArray());
	}
	
	/**
	 * @param Loan_List_Loan $list
	 */
	private function addFilter($list) {
	    $filters = $this->getFilter();
	    $list->setFilter($filters);
	    
	    $period = $this->getPeriod();
	    if (!empty($period)) {
	       $list->appendFilterString($period);
	    }
	}
	
	private function getFilter() {
	    $keys = array('status', 'type_id', 'cat_id');
	    $filters = array();
	    foreach ($keys as $key) {
	        if (isset($_REQUEST[$key])) {
	            $filters[$key] = $_REQUEST[$key];
	        }
	    }
	    return $filters;
	}
	
	private function getPeriod() {
	    $from = $to = 0;
	    if (isset($_REQUEST['from'])) {
	        $from = intval($_REQUEST['from']);
	    }
	    if (isset($_REQUEST['to'])) {
	        $to = intval($_REQUEST['to']);
	    }
	    $ary = array();
	    if ($from > 0) {
	        $ary[] = "duration >= $from";
	    }
	    if ($to > $from) {
	        $ary[] = "duration <= $to";
	    }
	    if (!empty($ary)) {
	       $filter = implode(' and ', $ary);
	       return $filter;
	    }
	    return null;
	}
}