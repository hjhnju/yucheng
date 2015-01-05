<?php
/**
 * 充值提现页
 * 充值与提现的controller入口位于Fiance模块
 */
class CashController extends Base_Controller_Response {
	
	private $huifuid;
	private $phone;
	public function init() {
		$this->setNeedLogin(false);
		$this->huifuid = !empty($this->objUser) ? $this->objUser->huifuid : 0;
		$this->phone = !empty($this->objUser) ? $this->objUser->phone : '';
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
		
		$huifuid = $this->huifuid;
		$userinfo = $this->userInfoLogic->getUserInfo();
		$blanceInfo = Finance_Api::queryBalanceBg($huifuid);
		$avlBal = $blanceInfo["data"]["avlBal"];
		$acctBal = strval($blanceInfo["data"]["acctBal"]);
		
		if($blanceInfo["status"] == Finance_RetCode::REQUEST_API_ERROR) {//请求汇付API失败
			Base_Log::error(Finance_RetCode::getMsg($bankCardInfo["status"]));
		} elseif ($blanceInfo["status"] != Base_RetCode::SUCCESS) {
			Base_Log::error(array(
			    'huifuid'  => $huifuid,
				'RespCode' => $blanceInfo['status'],
				'RespDes'  => $blanceInfo['respDesc'],				
			));
		} else {
            Base_Log::notice(array(
            	'huifuid' => $huifuid,
            	'avlBal'  => $avlBal,
            	'acctBal' => $acctBal,
            ));
		}
		//assign至前端
		$this->getView()->assign('avlBal', $avlBal);
		$this->getView()->assign('acctBal',$acctBal);
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
		$userid = $this->userid;
		$huifuid = $this->huifuid;
		$userinfo = $this->userInfoLogic->getUserInfo();
		$huifuid = "6000060000696947";
		$bankCardInfo = Finance_Api::queryCardInfo($huifuid);
		if($bankCardInfo["status"] == Finance_RetCode::REQUEST_API_ERROR) { //查询汇付接口失败
			$bindbank = 0;
			$banknum  = '';
			$bankID   = '';
			Base_Log::error(Finance_RetCode::getMsg($bankCardInfo["status"]));
		} elseif ($bankCardInfo["status"] == Finance_RetCode::NOTBINDANYCARD) {//用户未绑卡
			$bindbank = 0;
			$banknum  = '';
			$bankID   = '';
			Base_Log::notice(Finance_RetCode::getMsg($bankCardInfo["status"]),array(
			'userid' => $userid,
			'huiid'  => $huifuid,
			));
		} elseif ($bankCardInfo["status"] != 0) {
			$bindbank = 0;
			$banknum  = '';
			$bankID   = '';
			Base_Log::error(array(
				'userid'  => $userid,
				'huifuid' =>$huifuid,
				'RespCode' => $bankCardInfo['RespCode'],
				'RespDes'  => $bankCardInfo['RespDesc'],				
			));			
		} else {
			$bindbank = 1;
			$banknum = $bankCardInfo["data"]['UsrCardInfolist'][0]["CardId"];
			$bankID = $bankCardInfo["data"]['UsrCardInfolist'][0]["BankId"];
		}
		$this->getView()->assign('bindbank', $bindbank);
		$this->getView()->assign('banknum', $banknum);
		$this->getView()->assign('bankID', $bankID);
		
		$blanceInfo = Finance_Api::queryBalanceBg($huifuid);
		
		if($bankCardInfo["status"] == Finance_RetCode::REQUEST_API_ERROR) {//请求汇付API失败
			$avlBal = '0.00';
		} else {
			$avlBal = strval($blanceInfo["data"]["avlBal"]);
		}
		$this->getView()->assign('avlBal', $avlBal);
		$this->getView()->assign('userInfo',$userinfo);
		$this->getView()->assign('withdrawfee','1');
		$this->getView()->assign('phone',$this->phone);
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