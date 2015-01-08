<?php
/**
 * 财务模块公共逻辑层
 * @author lilu
 */
class Finance_Logic_Transaction extends Finance_Logic_Base{

	/**
	 * 网银充值logic层
	 * @param integer userid
	 * @param integer huifuid
	 * @param integer transAmt 
	 * @param integer openBankId
	 * @param integer gateBusiId
	 * @param integer dcFlag
	 * 
	 */
	public function netsave($userid, $huifuid, $transAmt, $openBankId, $gateBusiId, $dcFlag) {
        $webroot     = Base_Config::getConfig('web')->root;
        $chinapnr    = Finance_Chinapnr_Logic::getInstance();
        $orderInfo   = $this->genOrderInfo();
        $orderDate   = strval($orderInfo['date']);
        $orderId     = strval($orderInfo['orderId']);
        $merCustId   = strval(self::MERCUSTID);
        $strTransAmt = strval($transAmt);
        $bgRetUrl    = $webroot.'/finance/bgcall/userregist';
        $retUrl      = '';
		$merPriv = strval($userid);
		//充值订单入库
		$timeNow = time();
		$param = array(
		    'order_id'     => $orderId,
			'user_id'      => $userid,
			'type'         => Finance_TypeStatus::NETSAVE,
			'amount'       => $transAmt,
			'status'       => Finance_TypeStatus::PROCESSING,
			'create_time'  => $timeNow,
			'update_time'  => $timeNow,
			'comment'      => '充值订单入库',	
		);
		//$ret = $this->payOrderEnterDB($param);
        Base_Log::debug($param);
        $ret = true;
		if($ret === false) {
			Base_Log::error("fail to create finance order");
		}
		//调用汇付API进行充值处理
		$chinapnr->netSave($merCustId, $huifuid, $orderId, $orderDate, $gateBusiId, $openBankId, $dcFlag, $strTransAmt, $retUrl, $bgRetUrl, $merPriv);
	}
	
    /**
     * 根据userId获取用户充值提现记录
     * @param String $userId
     * @return array
     */
    public function getRechargeWithDrawRecord($userId,$type) {
    	$recordList = new Finance_List_Record();
    	if($type === 0) {
    		$filterStr = '`type` IN (1,2)';
    	}
    	else {
    		$filterStr = "`type` = `{$type}`";
    	}
    	$recordList->setFilterString($filterStr);
    	$recordList->setOrder('create_time desc');
    	$recordList->setPagesize(PHP_INT_MAX);
    	$list = $recordList->toArray();
    	
    	var_dump($list);
    	return $list;
    }
   
    /**
     * 根据userId获取某个用户的总投资额
     * @param String $userId
     * @return array
     */
    public function fetchTenderAmonut($userId) {
    	$recordList = new Finance_List_Record();
    	$filters = array('user_id' => $userId,
    	                 'type' => 2);//type=2 为主动投标
    	$recordList->setFilter($filters);
    	// $list = array(
        //    'page' => $this->page,
        //    'pagesize' => $this->pagesize,
        //    'pageall' => $this->pageall,
        //    'total' => $this->total,
        //    'list' => $this->data,
        // );
    	$list = $refunds->toArray();
    	$totle = $list['total'];//取出的记录总条数
    	$data = $list['list'];
    	$sum = 0;
    	if(empty($data)){
    		$ret = array('data' => 0,
    		);
    	} else {
    		foreach ($data as $key => $val) {
    			$sum += $val['amount'];
    		}
    		$ret = array('data' => $sum);
    	}
    	   	   	
    	return $ret;
    }
    


}




