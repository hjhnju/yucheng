<?php
/**
 * 我的借款页
 * @author lilu
 */
class LoanController extends Base_Controller_Page {

    CONST PAGESIZE   = 6; //每次出6条数据
    CONST BACKING    = 5;
    CONST ENDED      = 6;
    CONST TENDERFAIL = 9;
	public function init(){
        parent::init();
        $this->userInfoLogic = new Account_Logic_UserInfo();
        $this->ajax = true;
	}
	
	/**
	 * 接口/account/loan
	 * 渲染入口界面
	 */
	public function indexAction() {
		$userInfo = $this->userInfoLogic->getUserInfo($this->objUser);		
		$this->getView()->assign('userinfo',$userInfo);

		$userBg         = Finance_Api::getUserBalance($this->userid);
        $data['avlbal'] = Base_Util_Number::tausendStyle($userBg['AvlBal']);

        $arrLoan        = Loan_Api::getActiveRefunds($this->userid);
		// 最近一期待还款金额
		$data['amount'] = isset($arrLoan[0]['amount']) ? $arrLoan[0]['amount'] : 0.00;
		// 最近到期还款日
		$data['last_date'] = isset($arrLoan[0]['promise_time']) ? 
			strftime('%Y-%m-%d', $arrLoan[0]['promise_time']) : '无';
		$this->getView()->assign('data', $data);
	}
}
