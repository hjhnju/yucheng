<?php
/**
 * 充值提现页
 * 充值与提现的controller入口位于Fiance模块
 */
class CashController extends Base_Controller_Response {
	
	public function init() {
		$this->setNeedLogin(false);
		
		parent::init();
		$this->userInfoLogic = new Account_Logic_UserInfo();
		$this->ajax = true;
	}
	
	/**
	 * 调用财务模块Finance_Api获取 账户余额
	 * assign至前端即可
	 * phone 用户手机号码
	 * bindbank 0--未绑定  1--绑定了银行卡
	 * banknum  银行卡号
	 * bankID  银行编号
	 * avlBal 可用金额
	 * withdrawfee 提现费用
     *
	 * userInfo 左上角用户信息
	 */
	public function indexAction() {
		$userid = $this->getUserId();
		$userinfo = $this->userInfoLogic->getUserInfo();
		//$userCustId = User_Api::getUserCustId()  用户模块封装接口获取用户在汇付天下的唯一ID
		$info = Finance_Api::queryBalanceBg($userCustId);
		$data = $info['data'];
		$avlBal = isset($data['AvlBal']) ? $data['AvlBal'] : 0.00;//可用余额
		$acctBal = isset($data['AcctBal']) ? $data['AcctBal'] : 0.00;//账户余额     
		$frzBal = isset($data['FrzBal']) ? $data['FrzBal'] : 0.00;//冻结余额  
		$this->getView()->assign('avlBal', $avlBal);
		$this->getView()->assign('acctBal', $acctBal);
		$this->getView()->assign('frzBal', $frzBal);	
		$this->getView()->assign('userInfo',$userinfo);
	}
	
	/**
	 * /account/cash/recharge
	 * 充值入口
	 */
	public function rechargeAction() {
		
	}
	
	/**
	 * 提现入口
	 */
	public function withdrawAction() {
		
	}
	
	/**
	 * /account/cash/rechargesuc
	 * assign
	 * status 0--充值  1--提现
	 * 充值成功
	 */
	public function rechargesucAction() {
	
	}

	/**
	 * /account/cash/rechargesuc
	 * assign 
	 * status 0--充值  1--提现
	 * 提现成功
	 */
	public function withdrawsucAction() {
	
	}
}