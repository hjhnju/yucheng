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
		$loanId = intval($_POST['id']);
		$amount  = intval($_POST['amount']);
		$uid     = $this->userid;
	    $sess = Yaf_Session::getInstance();
		
	    if (empty($loanId) || empty($amount) || empty($uid)) {
	        Base_Log::notice(array(
                'msg'  => '投标参数错误',
                'post' => $_POST,
            ));
	        if (!empty($loanId)) {
    	        $sess->set('invest_error', Invest_RetCode::PARAM_ERROR);
    	        return $this->redirect('/invest/detail?id=' . $loanId);
	        }
	        return $this->redirect('/invest/fail');
	    }
	    
        // 检查是否允许投标
	    $logic = new Invest_Logic_Invest();
	    $retCode = $logic->allowInvest($uid, $loanId);
	    if (Base_RetCode::SUCCESS !== $retCode) {
	        Base_Log::notice(array(
                'msg'  => Invest_RetCode::getMsg($retCode),
                'retCode' => $retCode,
                'post' => $_POST,
            ));
	        $sess->set('invest_error', $retCode);
	        return $this->redirect('/invest/detail?id=' . $loanId);
	    }
	    // 检查用户余额是否满足
	    $userAmount = Finance_Api::getUserAvlBalance($uid);
	    if ($amount > $userAmount) {
	        Base_Log::notice(array(
                'msg'  => '用户余额不够',
                'post' => $_POST,
            ));
	        $sess->set('invest_error', Invest_RetCode::AMOUNT_NOTENOUGH);
	        return $this->redirect('/invest/detail?id=' . $loanId);
	    }
	    
	    // 检查金额是否满足投标要求
	    if (!$logic->isAmountLegal($loanId, $amount)) {
	        Base_Log::notice(array(
                'msg'  => '投标金额不符合投标条件',
                'post' => $_POST,
            ));
	        $sess->set('invest_error', Invest_RetCode::AMOUNT_ERROR);
	        return $this->redirect('/invest/detail?id=' . $loanId);
	    }
	    
        Base_Log::notice(array(
            'msg'  => '主动投标',
            'post' => $_POST,
        ));
	    // 主动投标（会跳转至汇付）
	    return $logic->invest($uid, $loanId, $amount);
	}
}
