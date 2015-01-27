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
        $queryType = isset($_REQUEST['type']) ? $_REQUEST['type'] : 1;//查询类型  1--全部  2--充值  3--提现
        $time = $_REQUEST['data'];//时间范围
        $time = intval($time);
        $page = $_REQUEST['page'];//页码
        $startTime = $_REQUEST['startTime'];
		$endTime = $_REQUEST['endTime'];
		$baseLogic = new Finance_Logic_Base();
		$userid = $this->userid;
		$pageSize = 6;
		//只有在没有选择时间范围的情况下才可以进行开始时间与结束时间的数据拉取
		if(isset($startTime) && isset($endTime) && !isset($time)) {
	        $ret = $baseLogic->getReWiRecord($userid,$startTime,$endTime,$queryType,$page,$pageSize);
	        if(!$ret) {
	        	Base_Log::error(array(
	        	    'msg'       => '请求充值提现数据',
	        	    'userid'    => $userid,
	        	    'beginTime' => $begin,
	        	    'endTime'   => $endTime,
	        	    'queryType' => $queryType,
	        	));
	        	$ret = array(
	        		'page'     => 0,
	        		'pagesize' => 0,
	        		'pageall'  => 0,
	        		'total'    => 0,
	        		'list'     => array(),
	        	);
	        	$this->output($ret);
	        	return;
	        }
	        $this->output($ret);
			return;
		}
		if(isset($time)) {
			//今天
			if($time === 1) {
				$begin = mktime(0,0,0,date('m'),date('d'),date('Y'));			
				$end = time();
				$ret = $baseLogic->getReWiRecord($userid,$begin,$end,$queryType,$page,$pageSize);				
				if(!$ret) {
					Base_Log::error(array(
						'msg'       => '请求充值提现数据',
					    'userid'    => $userid,
					    'beginTime' => $begin,
					    'endTime'   => $endTime,
					    'queryType' => $queryType,
					));
					$ret = array(
						'page'     => 0,
						'pagesize' => 0,
						'pageall'  => 0,
						'total'    => 0,
						'list'     => array(),
					);
					$this->output($ret);
					return;
				}
                $this->output($ret);
                return;				
			}
			//最近一周
			if($time === 2) {
				//$beginLastweek=mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));				
				//$endLastweek=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
				$begin = strtotime('-1 week');
				$end = time();
				$ret = $baseLogic->getReWiRecord($userid,$begin,$end,$queryType,$page,$pageSize);
				if(!$ret) {
					Base_Log::error(array(
					    'msg'       => '请求充值提现数据',
					    'userid'    => $userid,
					    'beginTime' => $begin,
					    'endTime'   => $endTime,
					    'queryType' => $queryType,
					));
					$ret = array(
						'page'     => 0,
						'pagesize' => 0,
						'pageall'  => 0,
						'total'    => 0,
					    'list'     => array(),
					);
					$this->output($ret);
					return;
				}
				$this->output($ret);
				return;
			}
			//最近一个月
			if($time === 3) {
				//$beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));				
				//$endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
				$begin = strtotime('-1 month');
				$end = time();
				$ret = $baseLogic->getReWiRecord($userid,$begin,$end,$queryType,$page,$pageSize);
				if(!$ret) {
					Base_Log::error(array(
					    'msg'       => '请求充值提现数据',
					    'userid'    => $userid,
					    'beginTime' => $begin,
					    'endTime'   => $endTime,
					    'queryType' => $queryType,
					));
					$ret = array(
							'page'     => 0,
							'pagesize' => 0,
							'pageall'  => 0,
							'total'    => 0,
							'list'     => array(),
					);
					$this->output($ret);
					return;
				}
				$this->output($ret);
				return;
			}
			//最近三个月
			if($time === 4) {
				$begin = strtotime('-2 month');
				$end = time();	
				$ret = $baseLogic->getReWiRecord($userid,$begin,$end,$queryType,$page,$pageSize);
				if(!$ret) {
					Base_Log::error(array(
					    'msg'       => '请求充值提现数据',
					    'userid'    => $userid,
					    'beginTime' => $begin,
					    'endTime'   => $endTime,
					    'queryType' => $queryType,
					));
					$ret = array(
						'page'     => 0,
						'pagesize' => 0,
						'pageall'  => 0,
						'total'    => 0,
						'list'     => array(),
					);
					$this->output($ret);
					return;
				}
				$this->output($ret);
				return;
			}
			//最近半年
			if($time === 5) {
				$begin = strtotime('-6 month');
				$end = time();
				$ret = $baseLogic->getReWiRecord($userid,$begin,$end,$queryType,$page,$pageSize);
				if(!$ret) {
					Base_Log::error(array(
					    'msg'       => '请求充值提现数据',
					    'userid'    => $userid,
					    'beginTime' => $begin,
					    'endTime'   => $endTime,
					    'queryType' => $queryType,
					));
					$ret = array(
						'page'     => 0,
					    'pagesize' => 0,
						'pageall'  => 0,
						'total'    => 0,
						'list'     => array(),
					);
					$this->output($ret);
					return;
				}
				$this->output($ret);
				return;
			}
		}
		
	
	}
}