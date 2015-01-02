<?php
/**
 * 投资列表
 */
class IndexController extends Base_Controller_Page {
    public function init() {
        $this->needLogin = false;
        parent::init();
    }
	
	public function indexAction() {
	    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
	    if ($page < 1) {
	        $page = 1;
	    }
	    $pagesize = 10;
	    $filter = $this->getFilters();
	    $list = Loan_Api::getLoans($page, $pagesize, $filter);
	    
	    $status = new Invest_Type_InvestStatus();
	    foreach ($list['list'] as $key => $val) {
	        $list['list'][$key]['status_name'] = $status->getTypeName($val['status']);
	    }
	    $this->_view->assign('data', $list);
	}
	
	private function getFilters() {
	    $keys = array('type' => 'type_id', 'cat' => 'cat_id');
	    $filters = array();
	    foreach ($keys as $key => $val) {
	        if (!empty($_REQUEST[$key])) {
	            $filters[$val] = $_REQUEST[$key];
	        }
	    }
	    $period = $this->getPeriod();
	    if (!empty($period)) {
	        $filters['period'][] = $period;
	    }
	    return $filters;
	}
	
	private function getPeriod() {
	    $from = $to = 0;
	    if (empty($_REQUEST['period'])) {
	        return false;
	    }
	    switch ($_REQUEST['period']) {
	        case 1:
	            $from = 1;
	            $to = 3;
	            break;
	        case 2:
	            $from = 4;
	            $to = 6;
	            break;
	        case 3:
	            $from = 5;
	            $to = 12;
	            break;
	        case 4:
	            $from = 12;
	            $to = 23;
	            break;
	        case 5:
	            $from = 24;
	            break;
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
