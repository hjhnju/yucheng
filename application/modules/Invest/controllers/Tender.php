<?php
/**
 * 投标
 */
class TenderController extends Base_Controller_Api {
    
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
	        'id' => 3,
	        'amount' => 200,
	    );
	    if (!$this->checkParam($this->param, $_POST)) {
	        return $this->outputError(Base_RetCode::PARAM_ERROR);
	    }
	    
	    $loan_id = intval($_POST['id']);
	    $amount = intval($_POST['amount']);
	    $uid = $this->userid;
        // 检查是否允许投标
	    $logic = new Invest_Logic_Invest();
	    $allowed = $logic->allowInvest($uid, $loan_id);
	    if (!$allowed) {
	        return $this->ajaxError(Invest_RetCode::NOT_ALLOWED);
	    }
	    
	    // 获取投标的跳转URL
	    $url = $logic->invest($uid, $loan_id, $amount);
	    if ($url !== false) {
	        return $this->ajax(array('url'=> $url), '', Base_RetCode::NEED_REDIRECT);
	    }
        return $this->ajaxError();
	}
}
