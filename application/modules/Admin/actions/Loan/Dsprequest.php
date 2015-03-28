<?php
/**
* 借款申请表展示 
* @author hejunhua
*
*/
class DsprequestAction extends Yaf_Action_Abstract {
	public function execute() {
		$list = new Loan_List_Request();
		$list->setOrder('update_time asc');
		$list->setPagesize(PHP_INT_MAX);
		$list->joinType(new Loan_Type_SchoolType(), 'school_type');
		$list->joinType(new Loan_Type_Usage(), 'use_type');
		$list->joinType(new Loan_Type_Refund(), 'status');
		$list->joinType(new Loan_Type_Province(),'prov_id');
		$list = $list->toArray();
		$arrRequest  = $list['list'];
		$this->getView()->assign('arrRequest', $arrRequest );
	}
}