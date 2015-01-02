<?php
/**
 * 投资列表
 */
class DetailController extends Base_Controller_Response {
	
	public function indexAction() {
	    $id = intval($_GET['id']);
	    if (empty($id)) {
	        $this->outputError(Base_RetCode::PARAM_ERROR);
	    }
	    
	    $loan = Loan_Api::getLoanDetail($id);
	    $this->_view->assign('data', $loan);
	    
	    $uid = $this->getUserId();
	    if (!empty($uid)) {
    	    $amount = Invest_Api::getAccountAmout($uid);
    	    $user = array(
    	        'uid' => $uid,
    	        'amount' => $amount,
    	        'amount_text' => number_format($amount, 2),
    	    );
    	    $this->_view->assign('user', $user);
	    }
	}
}
