<?php
/**
 * 投标完成
 */
class ConfirmController extends Base_Controller_Response {
	
	public function indexAction() {
	    //@TODO fortest
	    $_POST = array(
	        'id' => 1,
	        'amount' => 100,
	    );
	    //@todo支付接口验证
	    
	    $loan_id = intval($_POST['id']);
	    $amount = floatval($_POST['amount']);
	    $uid = $this->getUserId();
	    
	    $logic = new Invest_Logic_Invest();
	    if ($logic->doInvest($uid, $loan_id, $amount)) {
	        $this->_view->assign('success', 1);
	    } else {
	        $this->_view->assign('success', 0);
	    }
	}
}
