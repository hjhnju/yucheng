<?php
/**
* 借款申请表展示 
* @author hejunhua
*
*/
class DsprequestAction extends Yaf_Action_Abstract {
	public function execute() {
	    $page     = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
	    $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : 20;
		$list = new Loan_List_Request();
		$list->setOrder('update_time asc');
		$list->setPagesize(PHP_INT_MAX);
		$list->joinType(new Loan_Type_SchoolType(), 'school_type');
		$list->joinType(new Loan_Type_Usage(), 'use_type');
		$list->joinType(new Loan_Type_Refund(), 'status');
		$list->joinType(new Loan_Type_Province(),'prov_id');
		$list = $list->toArray();
		$arrRequest  = $list['list'];
		$pageAll     = $list['pageall'];
		$this->getView()->assign('arrRequest', $arrRequest );
		$this->getView()->assign('pageall', $pageAll);
		$this->getView()->assign('page', $page);
	}
}