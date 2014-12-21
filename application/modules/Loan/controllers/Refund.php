<?php
/**
 * 借款还款
 * @author jiangsongfang
 *
 */
class RequestController extends Base_Controller_Admin {
    
    protected $ajax = true;
    
	/**
	 * 借款还款操作
	 */
	public function indexAction() {
        $id = intval($_GET['id']);
        if (empty($id)) {
            $this->outputError();
            return false;
        }
        
        $refund = new Loan_Object_Refund($id);
        $refund->status = Loan_Type_Refund::REFUNDED;
        if ($refund->save()) {
            $this->output();
        } else {
            $this->outputError();
        }
	}
}