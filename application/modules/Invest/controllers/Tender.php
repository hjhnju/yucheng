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
		$loanId = intval($_REQUEST['id']);
		$amount = floatval($_REQUEST['amount']);
		$rate   = isset($_REQUEST['rate'])?floatval($_REQUEST['rate']):0;
		$angel  = isset($_REQUEST['angel'])?$_REQUEST['angel']:'';
		$uid    = $this->userid;
		$sess   = Yaf_Session::getInstance();
		$isMobile = Base_Util_Mobile::isMobile();
		$investUrl = '/invest';
		if($isMobile){
		    $investUrl = '/m/invest';
		}
	    if (empty($loanId) || empty($amount) || empty($uid)) {
	        Base_Log::notice(array(
                'msg'  => '投标参数错误',
                'post' => $_REQUEST,
            ));
	        if (!empty($loanId)) {
    	        $sess->set('invest_error', Invest_RetCode::PARAM_ERROR);
    	        return $this->redirect("$investUrl/detail?id=" . $loanId);
	        }
	        return $this->redirect("$investUrl/fail");
	    }

        // 检查是否允许投标
	    $logic = new Invest_Logic_Invest();
	    $retCode = $logic->allowInvest($uid, $loanId);
	    if (Base_RetCode::SUCCESS !== $retCode) {
	        Base_Log::notice(array(
                'msg'  => Invest_RetCode::getMsg($retCode),
                'retCode' => $retCode,
                'post' => $_REQUEST,
            ));
	        $sess->set('invest_error', $retCode);
	        return $this->redirect("$investUrl/detail?id=" . $loanId);
	    }
	    // 检查用户余额是否满足
	    $userAmount = Finance_Api::getUserAvlBalance($uid);
	    if ($amount > $userAmount) {
	        Base_Log::notice(array(
                'msg'  => '用户余额不够',
                'post' => $_REQUEST,
            ));
	        $sess->set('invest_error', Invest_RetCode::AMOUNT_NOTENOUGH);
	        return $this->redirect("$investUrl/detail?id=" . $loanId);
	    }
	    //使用代金券时，则此金额包含代金券的金额，为投资人实际投资金额
	    $vocherAmt = 0.00;
	    $amount += $vocherAmt;
	    // 检查金额是否满足投标要求
	    if (!$logic->isAmountLegal($loanId, $amount)) {
	        Base_Log::notice(array(
                'msg'  => '投标金额不符合投标条件',
                'post' => $_REQUEST,
            ));
	        $sess->set('invest_error', Invest_RetCode::AMOUNT_ERROR);
	        return $this->redirect("$investUrl/detail?id=" . $loanId);
        }

        Base_Log::notice(array(
            'msg'  => '主动投标',
            'post' => $_REQUEST,
        ));
	    // 主动投标（会跳转至汇付）
	    $interest  = 0.00;
	    
	    $shareInfo = array();
	    $loan = Loan_Api::getLoanInfo($loanId);
	    $rate =  $loan['interest'] - $rate;
	    $intRate = intval($rate);
	    if(!empty($intRate)){
	        $logic     = new Awards_Logic_Invite();
	        $intUserid = $logic->decode($angel);
	        $shareInfo['uid']  = $intUserid;
	        $shareInfo['rate'] = $rate;
	    }
	    return $logic->invest($uid, $loanId, $amount, $interest, $vocherAmt, $shareInfo);
	}

	/**
	 * 确认投标页，选择代金券、利息券
	 */
	public function preAction(){

	}
}
