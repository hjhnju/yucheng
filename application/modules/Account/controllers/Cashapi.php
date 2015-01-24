<?php 
/**
 * 充值提现实现类
 */
class CashapiController extends Base_Controller_Api {
	
	private $transLogic;
	private $huifuid;
	public function init(){
		parent::init();
		$this->transLogic = new Finance_Logic_Transaction();
		$this->huifuid = !empty($this->objUser) ? $this->objUser->huifuid : '';
	}
	
	/**
	 * 充值
	 * @param token csrf token 
	 * @param transamt 交易金额
	 * @return 标准json
	 */
	public function rechargeAction() {
		$userid  = $this->userid;
        $huifuid = $this->huifuid;	
        $transAmt = $_REQUEST['value'];
        $transAmt = sprintf('%.2f',$transAmt);      
        $openBankId = strval($_REQUEST['openBankId']);
        $gateBusiId = strval($_REQUEST['gateBusiId']);
        ///notice
        //$dcFlag     = strval($_REQUEST['dcFlag']);
        $transAmt   = sprintf('%.2f',300000);
        $gateBusiId = 'B2C';
        $openBankId = 'CIB';
        $dcFlag     = 'D';
        Base_Log::notice(array(
            'userid'     => $userid,
            'huifuid'    => $huifuid,
            'transAmt'   => $transAmt,
            'gateBusiId' => $gateBusiId,
            'openBankId' => $openBankId,
            'dcFlag'     => $dcFlag,
        ));
        $this->transLogic->netsave($userid, $huifuid, $transAmt, $openBankId, $gateBusiId, $dcFlag);         
	}
	
	/**
	 * 提现
	 * @param token csrf token
	 * @param transamt  交易金额
	 * @param 标准json
	 */
	public function withdrawAction() {
		$this->output();
	}
	
	/**
	 * 接口 /account/cashapi/list
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
		/* $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 0;
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
		}		 */
		$ret = array('page'=>1,
				'pageall'=>10,
				'all'=>10,
				'list'=>array()
		);
		$this->output($ret);
	
	}
}