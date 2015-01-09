<?php 
/**
 * 财务模块查询逻辑类
 * @author lilu
 */
class Finance_Logic_Query extends Finance_Logic_Base {
	
	private static $transType = array(
	    'LOANS',     //放款交易
		'REPAYMENT', //还款交易
		'TENDER',    //投标交易
		'CASH',      //取现交易
		'FREEZE',    //冻结解冻交易
		'RECHARGE'   //充值交易
	);

	/**
	 * 查询余额
	 * @param string usrCustId
	 * @return array || boolean
	 */
	public function queryBalanceBg($userCustId) {
		$merCustId = strval(self::MERCUSTID);
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
        $userCustId = strval($userCustId);
        $result = $chinapnr->queryBalanceBg($userCustId, $merCustId);
		$result = $chinapnr->queryBalanceBg("6000060000696947",self::MERCUSTID);
		if(is_null($result)) {
			Base_Log::error(array(
				'msg'     => '请求汇付接口失败',
				'huifuid' => $userCustId,
			));
			return false;
		}
		return $result;		
	}
	
	/**
	 * 查询银行卡
	 * @param string usrCustId
	 * @return array || boolean
	 */
	public function queryBankCard($userCustId,$cardid) {
		$webroot = Base_Config::getConfig('web')->root;
		$merCustId = strval(self::MERCUSTID);
		$userCustId = strval($userCustId);
		$cardid = strval($cardid);
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		$result = $chinapnr->queryCardInfo($merCustId, $userCustId,$cardid);
		if(is_null($result)) {
			Base_Log::error(array(
				'msg'     => '请求汇付接口失败',
				'huifuid' => $userCustId,
				'cardid'  => $cardid,
			));
			return false;
		}
		return $result;
	}
	
	/**
	 * 商户子账户信息查询
	 * @return array || boolean
	 */
	public function queryAccts() {
		$merCustId = strval(self::MERCUSTID);
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		$result = $chinapnr->queryAccts($merCustId);
		if(is_null($result)) {
			Base_Log::error(array(
				'msg' => '请求汇付接口失败',
			));
			return false;
		}
		$merAcct = $result['AcctDetails'];
		return $merAcct;
	}
	
	/**
	 * 交易状态查询
	 * @param string $queryTransType 交易查询类型
	 * @return array || boolean
	 * 
	 * CAUTION!!!!注意这里的订单id
	 */
	public function queryTransStat($queryTransType) {		
		$merCustId = strval(self::MERCUSTID);
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		$queryTransType = strval($queryTransType);
		if(!in_array($queryTransType,self::$transType)) {			
			return false;
		}		
	    $orderInfo = $this->genOrderInfo();
	    $orderDate = $orderInfo['date'];
	    $orderId = $orderInfo['orderId'];	    
	    $return = $chinapnr->queryTransStat($merCustId, $orderId, $orderDate, $queryTransType);
	    if(is_null($return)) {
	    	Base_Log::error(array(
	    	    'msg' => '请求汇付接口失败',
	    	));
	    	return false;
	    }
		return $return;		
	}
	

	
	/**
	 * 充值对账(获取用户的充值记录)
	 * @param string $beginDate 开始时间
	 * @param string $endDate 结束时间
	 * @param integer $pageNum 数据所在页号
	 * @param integer $pageSize 每页记录数
	 * @return array || false 
	 */
	public function saveReconciliation($beginDate,$endDate,$pageNum,$pageSize) {
		$merCustId = strval(self::MERCUSTID);
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		if(intval($endDate-$beginDate) > 90) {
			Base_log::error(array(
			    'msg'      => '请求时间范围错误',
			    'beginDate'=> $beginDate,
			    'endDate'  => $endDate,
			));
			return false;
		}
		if(intval($pageNum) <= 0) {
		    Base_log::error(array(
			    'msg'      => '请求参数错误',
			    'pageNum' => $pageNum,
			));
			return false;
		}
		if(intval($pageSize) <= 0 && intval($pageSize) > 1000 ) {
			Base_log::error(array(
				'msg'      => '请求参数错误',
				'pageSize' => $pageSize,
			));
			return false;
		}
		$beginDate = strval($beginDate);
		$endDate = strval($endDate);
		$pageNum = strval($pageNum);
		$pageSize = strval($pageSize);
		$return = $chinapnr->saveReconciliation($merCustId, $beginDate, $endDate, $pageNum, $pageSize);
		if(is_null($return)) {
			Base_Log::error(array(
				'msg'       => '请求汇付接口失败',
			    'beginDate' => $beginDate,
			    'endDate'   => $endDate,
			    'pageNum'   => $pageNum,
			    'pageSize'  => $pageSize,
			));
			return false;
		}
		return $return;		
	}
	
	/**
	 * 取现对账(获取用户的取现记录)
	 * @param string $beginDate 开始时间
	 * @param string $endDate 结束时间
	 * @param integer $pageNum 数据所在页号
	 * @param integer $pageSize 每页记录数
	 * @return array || false
	 * 
	 */
	public function cashReconciliation($beginDate,$endDate,$pageNum,$pageSize) {
		$merCustId = strval(self::MERCUSTID);
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		if(intval($endDate-$beginDate) > 90) {
			Base_Log::error(array(
				'msg'       => '请求时间范围错误',
				'beginDate' => $beginDate,
				'endDate'   => $endDate,
			));
			return false;
		}
		if(intval($pageNum) <= 0) {
			Base_Log::error(array(
				'msg'     => '请求参数错误',
				'pageNum' => $pageNum,
			));
			return false;
		}
		if(intval($pageSize) <= 0 && intval($pageSize) > 1000 ) {
			Base_Log::error(array(
			    'msg'     => '请求参数错误',
			    'pageSize' => $pageSize,
			));
			return false;
		}
		$beginDate = strval($beginDate);
		$endDate = strval($endDate);
		$pageNum = strval($pageNum);
		$pageSize = strval($pageSize);
		$return = $chinapnr->cashReconciliation($merCustId, $beginDate, $endDate, $pageNum, $pageSize);
		if(is_null($return)) {
			Base_Log::error(array(
				'msg'       => '请求汇付接口失败',
				'beginDate' => $beginDate,
				'endDate'   => $endDate,
				'pageNum'   => $pageNum,
				'pageSize'  => $pageSize
			));
			return false;
		}
		return $return;
	}
	
	/**
	 * 获取某一用户在本平台的充值提现记录
	 * @param string $beginDate 开始时间
	 * @param string $endDate 结束时间
	 * @param integer $pageNum 数据所在页号
	 * @param integer $pageSize 每页记录数
	 * @param string queryTransType 查询交易类型   CASH || RECHARGE || ALL
	 * 
	 */
	public function saveCashRecord($userid,$beginDate,$endDate,$pageNum,$pageSize,$queryTransType) {
		$merCustId = strval(self::MERCUSTID);
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		if(intval($userid) <= 0) {
			return false;
		}
		if($queryTransType != 'CASH' && $queryTransType != 'RECHARGE' && $queryTransType != 'ALL') {
			return false;
		}
		if(intval($endDate-$beginDate) > 90) {
			return false;
		}
		if(intval($pageNum) <= 0) {
			return false;
		}
		if(intval($pageSize) <= 0 && intval($pageSize) > 1000 ) {
			return false;
		}
		$huifuid = $this->getHuifuid($userid);
		$userid = strval($userid);
		$beginDate = strval($beginDate);
		$endDate = strval($endDate);
		$pageNum = strval($pageNum);
		$pageSize = strval($pageSize);
		$queryTransType = strval($queryTransType);
		//用户选择查询取现记录
		if($queryTransType === 'CASH') {
    		$return = $chinapnr->cashReconciliation($merCustId, $beginDate, $endDate, $pageNum, $pageSize);
    		$ret = array();
    		if(is_null($return)) {
    			Base_Log(array(
    				'msg'            => '请求汇付接口失败',
    				'userid'         => $userid,
    				'queryTransType' => $queryTransType,
    			));
    			return false;
    		}
    		$cashRet = $return['CashReconciliationDtoList'];
    		foreach ($cashRet as $key => $value) {
    			if($value['UsrCustId'] === $huifuid) {
    				$ret[] = array(
    					'time' => strtotime($value['PnrDate']),
    					'transType' => 3, //提现 3
    					'serialNo'  => $value['PnrSeqId'],
    					'tranAmt'   => $value['TransAmt'],				
    				);
    			}
    		}
    		return $ret;   		
		}
		//用户选择查询取现记录
		if($queryTransType === 'RECHARGE') {
			$return = $chinapnr->saveReconciliation($merCustId, $beginDate, $endDate, $pageNum, $pageSize);
			$ret = array();
			if(is_null($return)) {
				Base_Log(array(
				'msg'            => '请求汇付接口失败',
				'userid'         => $userid,
				'queryTransType' => $queryTransType,
				));
				return false;
			}
			$saveRet = $return['SaveReconciliationDtoList'];
			foreach ($saveRet as $key => $value) {
			    if($value['UsrCustId'] === $huifuid) {
					$ret[] = array(
					    'time' => strtotime($value['PnrDate']),
						'transType' => 2, //充值2
						'serialNo'  => $value['OrdId'],
						'tranAmt'   => $value['TransAmt'],
					);
				}
			}
			return $ret;
		}
		if($queryTransType === 'ALL') {
			$cashReturn = $chinapnr->cashReconciliation($merCustId, $beginDate, $endDate, $pageNum, $pageSize);
			$saveReturn = $chinapnr->saveReconciliation($merCustId, $beginDate, $endDate, $pageNum, $pageSize);
			if(is_null($cashReturn) || is_null($saveReturn)) {
				Base_Log::error(array(
					'msg'            => '请求汇付接口失败',
					'userid'         => $userid,
					'queryTransType' => $queryTransType,
				));
			}
			$ret = array();
		    foreach ($cashReturn as $key => $value) {
    			if($value['UsrCustId'] === $huifuid) {
    				$ret[] = array(
    					'time' => strtotime($value['PnrDate']),
    					'transType' => 1, //全部1
    					'serialNo'  => $value['PnrSeqId'],
    					'tranAmt'   => $value['TransAmt'],				
    				);
    			}
    		}
    		foreach ($saveReturn as $key => $value) {
    			if($value['UsrCustId'] === $huifuid) {
    				$ret[] = array(
    						'time' => strtotime($value['PnrDate']),
    						'transType' => 2, //全部1
    						'serialNo'  => $value['OrdId'],
    						'tranAmt'   => $value['TransAmt'],
    				);
    			}
    		}
			return $ret;           			
		}
	}
	/**
	 * 放还款对账
	 * @param string  $beginDate 开始时间
	 * @param string  $endDate 结束时间
	 * @param integer $pageNum 数据所在页号
	 * @param integer $pageSize 每页记录数
	 * @param string  $queryTransType 交易查询类型
	 * @return array || false
	 * 
	 */
	public function reconciliation($beginDate,$endDate,$pageNum,$pageSize,$queryTransType) {
		$merCustId = strval(self::MERCUSTID);
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		if(intval($endDate-$beginDate) > 90) {
			Base_Log::error(array(
			    'msg'       => '请求时间范围错误',
			    'beginDate' => $beginDate,
			    'endDate'   => $endDate,
			));
			return false;
		}
		if(intval($pageNum) <= 0) {
			Base_Log::error(array(
			    'msg'     => '请求参数错误',
			    'pageNum' => $pageNum,
			));
			return false;
		}
		if(intval($pageSize) <= 0 && intval($pageSize) > 1000 ) {
			Base_Log::error(array(
			    'msg'     => '请求参数错误',
			    'pageSize' => $pageSize,
			));
			return false;
		}
		if($queryTransType !== 'LOANS' && $queryTransType !== 'REPAYMENT') {
			Base_Log::error(array(
			    'msg'     => '请求参数错误',
			    'queryTransType' => $queryTransType,
			));
			return false;
		}
		$beginDate = strval($beginDate);
		$endDate = strval($endDate);
		$pageNum = strval($pageNum);
		$pageSize = strval($pageSize);
		$result = $chinapnr->reconciliation($merCustId, $beginDate, $endDate, $pageNum, $pageSize, $queryTransType);	
		if(is_null($result)) {
			Base_Log::error(array(
				'msg'           => '请求汇付接口失败',
				'beginDate'     => $beginDate,
				'endDate'       => $endDate,
				'pageNum'       => $pageNum,
				'pageSize'      => $pageSize,
				'queryTransType'=>$queryTransType,
			));
			return false;
		}	
		return $result;
	}
}