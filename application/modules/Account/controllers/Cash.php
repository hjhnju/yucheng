<?php
/**
 * 充值提现页
 * 充值与提现的controller入口位于Fiance模块
 */
class CashController extends Base_Controller_Page {
	
	private $huifuid;
	private $phone;
	
	public function init() {
		$this->setNeedLogin(false);		
		parent::init();
		$this->huifuid = !empty($this->objUser) ? $this->objUser->huifuid : '';
		$this->phone = !empty($this->objUser) ? $this->objUser->phone : '';
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
		$huifuid  = $this->huifuid;	
		$userinfo = $this->userInfoLogic->getUserInfo($this->objUser);
		$userBg   = $this->userInfoLogic->getUserBg($huifuid);
		$avlBal   = strval($userBg['avlBal']);
		$acctBal  = strval($userBg['acctBal']);
		$rechargeurl = "$this->webroot".'/account/cash/recharge';
		$withdrawurl = "$this->webroot".'/account/cash/withdraw';
		//assign至前端
		$this->getView()->assign('avlBal', $avlBal);
		$this->getView()->assign('acctBal',$acctBal);
		$this->getView()->assign('userinfo',$userinfo);
		$this->getView()->assign('rechargeurl',$rechargeurl);
		$this->getView()->assign('withdrawurl',$withdrawurl);
	}
	
	/**
	 * /account/cash/recharge
	 * 充值入口
	 */
	public function rechargeAction() {
		$userinfo = $this->userInfoLogic->getUserInfo($this->objUser);
		$this->getView()->assign('userinfo',$userinfo);
		
	}
	
	/**
	 * 提现入口
	 */
	public function withdrawAction() {
		$userid   = $this->userid;
		$huifuid  = $this->huifuid;
		$phone    = $this->phone;
		$userinfo = $this->userInfoLogic->getUserInfo($this->objUser);
		
		//FOR　TEST
		//$huifuid = "6000060000696947";
        $bankInfo = $this->userInfoLogic->getuserCardInfo($huifuid);
        $bindBank = $bankInfo['bindbank'];
        $bankNum  = $bankInfo['banknum'];
        $bankID   = $bankInfo['bankID'];
		$this->getView()->assign('bindbank', $bindBank);
		$this->getView()->assign('banknum', $bankNum);
		$this->getView()->assign('bankID', $bankID);
		
        $userBg = $this->userInfoLogic->getUserBg($huifuid);
        $avlBal = strval($userBg['avlBal']);
		$this->getView()->assign('avlBal', $avlBal);
		$this->getView()->assign('userinfo',$userinfo);
		$this->getView()->assign('withdrawfee','2');
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