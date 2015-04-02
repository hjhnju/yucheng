<?php
/**
 * 满标列表
 * @author hejunhua
 *
 */
class MakeloanAction extends Yaf_Action_Abstract {
    public function execute() {
        $page     = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : 20;
        $status   = Loan_Type_LoanStatus::FULL_PAYING; //审核通过允许打款
        $filters  = array();
        if($status){
        	$filters = array('status' => $status);
        	$this->getView()->assign('status', $status);
        }

        $list     = Loan_Api::getLoans($page, $pagesize, $filters);
        var_dump($list);
        $arrLoan  = $list['list'];
        $this->getView()->assign('arrLoan', $arrLoan);
        $this->getView()->assign('page', $list['page']);
        $this->getView()->assign('pageall', $list['pageall']);
        
    }
}