<?php
/**
 * 借款列表
 * @author hejunhua
 *
 */
class ListAction extends Yaf_Action_Abstract {
    public function execute() {
        $page     = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : 20;
        $status   = isset($_REQUEST['st']) ? intval($_REQUEST['st']) : false;
        $filters  = array();
        if($status){
        	$filters = array('status' => $status);
        	$this->getView()->assign('status', $status);
        }

        $list     = Loan_Api::getLoans($page, $pagesize, $filters);
        $arrLoan  = $list['list'];
        $pageAll  = $list['pageall']; 
        $this->getView()->assign('arrLoan', $arrLoan);
        $this->getView()->assign('pageall', $pageAll);
        $this->getView()->assign('page', $page);
    }
}