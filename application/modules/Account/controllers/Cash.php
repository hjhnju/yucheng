<?php
/**
 * 充值提现页
 * 充值与提现的controller入口位于Fiance模块
 */
class CashController extends Base_Controller_Response {
	
	public function init() {
		parent::init();
		$this->userInfoLogic = new Account_Logic_UserInfo();
		$this->ajax = true;
	}
	
	/**
	 * 调用财务模块Finance_Api获取 账户余额
	 * assign至前端即可
	 * avlBal 可用余额
	 * acctBal 账户余额
	 * freBal 冻结余额
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
	 * 接口 /account/cash/list
	 * 获取充值提现列表
	 * @param type 0--全部 1--充值  2--提现
	 * @param startTime 
	 * @param endTime 
	 * @param date 0--今天  1--最近一周  2--1个月  3--3个月  4--半年
	 * @param page
	 * @return 标准json
	 * status 0: 处理成功
	 * status 1107:获取充值提现列表失败
	 * data
	 * {
	 * 	   'page'页码
	 *     'pageall':10 总共页码 
	 *     'all' 数据条数
	 *     'list'=
	 *         { 
	 *             'time'      时间
	 *             'transType' 交易类型
	 *             'serialNo'  交易流水号
	 *             'tranAmt'   交易金额
	 *             'avalBg'    可用余额
	 *         }
	 * }
	 */
	public function listAction() {
		$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 0;
		$startTime = isset($_REQUEST['startTime']) ? $_REQUEST['startTime'] : 0;
		$endTime = isset($_REQUEST['startTime']) ? $_REQUEST['endTime'] : 0;
		$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : 0;
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;//约定接口page从第一页开始
		$userId = $this->getUserId();
		$ret = Finance_Api::getRechargeWithDrawRecord($userId, $type, $startTime, $endTime, $date);
		//如果返回正常处理结果
		if($ret['status'] == 0) {
			$this->output($ret);
		} else {
			$errCode = Account_RetCode::GET_WITHDRAW_RECHARGE_FAIL; //1107错误码表示获取充值提现列表失败
			$errMsg = Account_RetCode::getMsg($errCode);
			$this->outputError($errCode,$errMsg);
		}		
	}

	/**
	 * /account/cash/recharge
	 * 充值入口
	 * @return 标准json
	 * status 0:成功
	 */
	public function rechargeAction() {
		
	}
}