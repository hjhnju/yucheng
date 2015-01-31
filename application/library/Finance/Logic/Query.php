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
		if(!isset($userCustId)) {
			Base_Log::error(array(
				'msg' => '请求参数错误',
				'huifuid' => $userCustId,
			));
			return false;
		}
		$merCustId = strval(self::MERCUSTID);
		$chinapnr = Finance_Chinapnr_Logic::getInstance();
        $userCustId = strval($userCustId);
        $result = $chinapnr->queryBalanceBg($userCustId, $merCustId);
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
	public function queryBankCard($userCustId,$cardid='') {
        if(!isset($userCustId)) {
        	Base_Log::error(array(
        		'msg' => '请求参数错误',
        	));
        	return false;
        }
		$merCustId = strval(self::MERCUSTID);
		$chinapnr = Finance_Chinapnr_Logic::getInstance();
		$userCustId = strval($userCustId);
		$cardid = strval($cardid);
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
        $chinapnr = Finance_Chinapnr_Logic::getInstance();
		$result = $chinapnr->queryAccts($merCustId);
		
		if(is_null($result)) {
			Base_Log::error(array(
				'msg' => '请求汇付接口失败',
			));
			return false;
		} 
		return $result;
	}
	
	/**
	 * 交易状态查询
	 * @param string $orderId
	 * @param string $queryTransType 交易查询类型
	 * @return array || boolean
	 * 
	 * CAUTION!!!!注意这里的订单id
	 */
	public function queryTransStat($orderId,$orderDate,$queryTransType) {		
		if(!isset($orderId) || !isset($orderDate) || !isset($queryTransType)) {
			Base_Log::error(array(
				'msg' => '请求参数错误',
			));
			return false;
		}
		if(!in_array($queryTransType,self::$transType)) {	
			Base_Log::error(array(
				'msg'            => '请求参数错误',
				'queryTransType' => $queryTransType,
			));		
			return false;
		}	
		$merCustId = strval(self::MERCUSTID);
		$chinapnr = Finance_Chinapnr_Logic::getInstance();
		$queryTransType = strval($queryTransType);
        $orderId = strval($orderId);
        $orderDate = strval($orderDate);    
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
	 * 获取某一用户在本平台的充值提现记录
	 * @param string $beginDate 开始时间
	 * @param string $endDate 结束时间
	 * @param integer $pageNum 数据所在页号
	 * @param integer $pageSize 每页记录数
	 * @param string queryTransType 查询交易类型   CASH || RECHARGE || ALL
	 * 
	 */
	public function saveCashRecord($userid,$beginDate,$endDate,$pageNum,$pageSize,$queryTransType) {
		if(!isset($userid) || !isset($beginDate) || !isset($endDate) || !isset($pageNum) || !isset($pageSize) || !isset($queryTransType)) {
		    Base_Log::error(array(
		    	'msg' => '请求参数错误',
		    ));     	
		    return false;
		}
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
	
}