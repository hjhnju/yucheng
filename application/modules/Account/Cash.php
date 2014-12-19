<?php
/**
 * 充值提现页
 * 充值与提现的controller入口位于Fiance模块
 */
class CashController extends Base_Controller_Response {
	
	public function init() {
		parent::init();
		$this->ajax = true;
	}
	
	/**
	 * 调用财务模块Finance_Api获取 账户余额
	 * assign至前端即可
	 */
	public function indexAction() {
		//$userCustId = User_Api::getUserCustId()
		$info = Finance_Api::queryBalanceBg($userCustId);
		$data = $info['data'];
		$avlBal = isset($data['AvlBal'])?$data['AvlBal']:0.00;//可用余额
		$acctBal =isset($data['AcctBal'])?$data['AcctBal']:0.00;//账户余额     
		$frzBal = isset($data['FrzBal'])?$data['FrzBal']:0.00;//冻结余额  
		$this->getView()->assign("avlBal", $avlBal);
		$this->getView()->assign("acctBal", $acctBal);
		$this->getView()->assign("frzBal", $frzBal);	
	}
	
	/**
	 * 接口 /account/cash/rechargeWithdrawList
	 * @param type 0--充值  1--提现
	 * @param startTime 
	 * @param endTime 
	 * @param date 0--今天  1--最近一周  2--1个月  3--3个月  4--半年
	 * @return 标准json
	 * status 0: 处理成功
	 * status 1107:获取充值提现列表失败
	 */
	public function rechargeWithdrawListAction() {
		$type = $_REQUEST['type'];
		$startTime = $_REQUEST['startTime'];
		$endTime = $_REQUEST['startTime'];
		$date = $_REQUEST['date'];
		//$userId = User_Api::getUserId()
		$ret = Finance_Api::getRechargeWithDrawRecord($userId,$type,$startTime,$endTime,$date);
		//判断$ret合法性
		//合法：
		//$this->output($ret);
		//非法
		//$this->outputError(Account_RetCode::GET_WITHDRAW_RECHARGE_FAIL,Account_RetCode::getMsg(GET_WITHDRAW_RECHARGE_FAIL));
		
	}
	
}