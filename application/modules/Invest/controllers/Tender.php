<?php
/**
 * 投标
 */
class TenderController extends Base_Controller_Response {
    
    /**
     * 需要验证的参数
     * @var array
     */
    private $param = array(
        'id' => '借款ID错误',
        'amount' => '借款金额错误',
    );
	
	public function indexAction() {
	    //@TODO fortest
	    $_POST = array(
	        'id' => 1,
	        'amount' => 100,
	    );
	    if (!$this->checkParam($this->param, $_POST)) {
	        return $this->outputError(Base_RetCode::PARAM_ERROR);
	    }
	    
	    $loan_id = intval($_POST['id']);
	    $amount = floatval($_POST['amount']);
	    $uid = $this->getUserId();
	    
	    $logic = new Invest_Logic_Invest();
	    $url = $logic->invest($uid, $loan_id, $amount);
	    if ($url !== false) {
	        $this->redirect($url);
	        return false;
	    }
	}
}
