<?php
/**
 * 投标完成
 */
class ConfirmController extends Base_Controller_Response {
	
	public function indexAction() {
	    //@todo支付接口验证
	    $merPriv = explode('_',$_REQUEST['MerPriv']);	    
	    $uid = intval(urldecode($merPriv[0]));
	    $loan_id = intval(urldecode($merPriv[1]));	    
 	    $amount = floatval($_POST['TransAmt']);
        	    
	    $logic = new Invest_Logic_Invest();
	    if ($logic->doInvest($uid, $loan_id, $amount)) {
	        $this->_view->assign('success', 1);
	    } else {
	        $this->_view->assign('success', 0);
	    }
	}
}
