<?php
/**
 * 借款列表
 * @author hejunhua
 *
 */
class IndexAction extends Yaf_Action_Abstract {
    public function execute() {
        $page     = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : 10;
        $list     = Loan_Api::getLoans($page, $pagesize);
        $arrLoan  = $list['list'];
        $this->getView()->assign('arrLoan', $arrLoan);
    }
}