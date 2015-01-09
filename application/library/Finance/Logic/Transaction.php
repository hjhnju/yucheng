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
		$webroot   = Base_Config::getConfig('web')->root;
		$chinapnr  = Finance_Chinapnr_Logic::getInstance();
		$orderInfo = $this->genOrderInfo();
		$orderDate = $orderInfo['date'];
		$orderId   = $orderInfo['orderId'];
        
		$param = array(
		    'orderId'    => intval($orderId),
			'userId'     => intval($userid),
			'type'       => Finance_TypeStatus::NETSAVE,
			'amount'     => $transAmt,
			'status'     => Finance_TypeStatus::PROCESSING,
			'comment'    => '财务类充值订单处理中',
		);
		//充值订单入库
		$ret = $this->payOrderEnterDB($param);
		if(!$ret) {
			Base_Log::error(array(
				'msg' => '财务类充值订单入库失败',
			));
		}
		
		$merCustId  = strval(self::MERCUSTID);
		$huifuid    = strval($huifuid);
		$orderId    = strval($orderId);
		$orderDate  = strval($orderDate);
		$openBankId = strval($openBankId);
		$gateBusiId = strval($gateBusiId);
		$dcFlag     = strval($dcFlag);
		$transAmt   = strval($transAmt);
		$bgRetUrl   = $webroot.'/finance/bgcall/userregist';
		$retUrl     = '';
		$merPriv    = base64_encode(strval($userid));		
		//调用汇付API进行充值处理
		$chinapnr->netSave($merCustId, $huifuid, $orderId, $orderDate, 'B2C', 'CIB', 'D', $transAmt, $retUrl, $bgRetUrl, $merPriv);
	}
	
	/**
	 * 主动投标Logic层
	 * @param float   tranAmt
	 * @param integer userid
	 * @param array   uidborrowDetail
	 * @param boolean isFreeze
	 * @param string  freezeOrdId
	 * @param string  retUrl
	 * redirect
	 * 
	 */
	public function initiativeTender($transAmt, $userid, $uidborrowDetail, $isFreeze=true, $retUrl='') {
		$webroot  = Base_Config::getConfig('web')->root;
		$chinapnr = Finance_Chinapnr_Logic::getInstance();
		
	    $orderInfo = $this->genOrderInfo();
	    $orderDate = $orderInfo['date'];
	    $orderId   = $orderInfo['orderId'];
	    	    
	    $freezeOrdInfo = $this->genOrderInfo();
	    $freezeOrdId   = $freezeOrdInfo['orderId'];
	    //主动投标订单记录入表pay_order
	    $param = array(
	    	'orderId'     => intval($orderId),
	    	'userId'      => intval($userid),
	    	'freezeOrdId' => intval($freezeOrdId),
	    	'type'        => Finance_TypeStatus::INITIATIVETENDER,
	    	'amount'      => floatval($transAmt),
	    	'status'      => Finance_TypeStatus::PROCESSING,
	    	'comment'     => '财务类主动投标订单处理中',	
	    );
	    $ret = $this->payOrderEnterDB($param);
	    if(!$ret) {
	    	Base_Log::error(array(
	    	    'msg' => '财务类主动投标订单入库失败',
	    	));
	    } 

	    $merCustId = strval(self::MERCUSTID);	    
	    $orderId   = strval($orderId);
	    $orderDate = strval($orderDate);
	    $transAmt  = strval($transAmt);
	    $usrCustId = strval($this->getHuifuid($userid));
	    $maxTenderRate = '0.00';
	    $huifuborrowerDetails = array();
	    foreach ($uidborrowDetail as $key => $value) {
			foreach ($value as $k => $v) {
				$borrower = array(
				    'BorrowerCustId' => $this->getHuifuid($v['BorrowerUserId']),
					'BorrowerAmt'    => strval($v['BorrowerAmt']),
					'BorrowerRate'   => strval($v['BorrowerRate']),
					'ProId'          => strval($v['ProId']),
				);
			}
			$huifuborrowerDetails[] = $borrower;
	    }	    
	    $isFreeze    = strval($isFreeze);
	    $freezeOrdId = strval($freezeOrdId);
	    $retUrl      = strval($retUrl);   
	    $bgRetUrl    = $webroot.'/finance/bgcall/initiativeTender';
	    $merPriv     = base64_encode(strval($userid));
	    
	    $chinapnr->initiativeTender($merCustId,$orderId,$orderDate,$transAmt,$usrCustId,
	        $maxTenderRate,$huifuborrowerDetails,$isFreeze,$freezeOrdId,$retUrl,$bgRetUrl,$merPriv
		);	    
	}
	
	/**
	 * 投标撤销Logic层
	 * 
	 */
	public function tenderCancle($transAmt,$userid,$orderId,$orderDate,$retUrl) {
		$webroot = Base_Config::getConfig('web')->root;
		$chinapnr = Finance_Chinapnr_Logic::getInstance();
		$merCustId = strval(self::MERCUSTID);
		$orderId = strval($orderId);
		$orderDate = strval($orderDate);
		$transAmt = strval($transAmt);
		$usrCustId = strval($this->getHuifuid($userid));
		$isUnFreeze = strval(true);
		$unFreezeOrderId = '';
		$freezeTrxId = '';
		$retUrl = strval($retUrl);
		$bgRetUrl = $webroot.'/finance/bgcall/tenderCancle';
		$merPriv = base64_encode(strval($userid));		
		$chinapnr->tenderCancle($merCustId, $usrCustId, $orderId, $orderDate, $transAmt, $usrCustId,
		    $isUnFreeze, $unFreezeOrderId, $freezeTrxId, $retUrl, $bgRetUrl, $merPriv);		
	}
}




