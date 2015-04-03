<?php
/**
 * 借款列表
 * @author hejunhua
 *
 */
class AuditAction extends Yaf_Action_Abstract {
    public function execute() {
        $page     = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : PHP_INT_MAX;
        $arrStatus = array(Loan_Type_LoanStatus::AUDIT, Loan_Type_LoanStatus::WAITING);
        $strFilter = '';
        $filter = implode(',', $arrStatus);
        $strFilter .= "status IN ($filter)";

        $list     = Loan_Api::getLoans($page, $pagesize, array(), $strFilter);
        $arrLoan  = $list['list'];
        $pageAll  = $list['pageall'];  
        $this->getView()->assign('arrLoan', $arrLoan);
        $this->getView()->assign('pageall', $pageAll);
        $this->getView()->assign('page', $page);
    }
}