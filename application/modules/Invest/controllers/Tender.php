<?php
/**
 * 投标
 */
class TenderController extends Base_Controller_Page {
    
    protected $outputView = 'tender/index.phtml';

    /**
     * 需要验证的参数
     * @var array
     */
    private $param = array(
        'id' => '借款ID错误',
        'amount' => '借款金额错误',
    );
	
	public function indexAction() {
	    if (!$this->checkParam($this->param, $_POST)) {
	        return $this->outputError(Base_RetCode::PARAM_ERROR);
	    }
	    
		$loan_id = intval($_POST['id']);
		$amount  = intval($_POST['amount']);
		$uid     = $this->userid;
        // 检查是否允许投标
	    $logic = new Invest_Logic_Invest();
	    $allowed = $logic->allowInvest($uid, $loan_id);
	    if (!$allowed) {
	    	//TODO: 错误打至项目详情页面
	        return $this->ajaxError(Invest_RetCode::NOT_ALLOWED, 
		    	Invest_RetCode::getMsg(Invest_RetCode::NOT_ALLOWED));
	    }
	    
        Base_Log::notice(array(
            'msg'  => '',
            'post' => $_POST,
        ));
	    // 主动投标（会跳转至汇付）
	    $logic->invest($uid, $loan_id, $amount);
	}
}
