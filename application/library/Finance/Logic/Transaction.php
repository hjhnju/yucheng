<?php
/**
 * 财务模块公共逻辑层
 * @author lilu
 */
class Finance_Logic_Transaction extends Finance_Logic_Base{

	/**
	 * 网银充值logic层
	 * @param int userid
	 * @param string huifuid
	 * @param float transAmt 
	 * @param string openBankId
	 * @param string gateBusiId
	 * @param string dcFlag
	 * redirect
	 */

	public function netsave($userid, $huifuid, $transAmt, $openBankId, $gateBusiId, $dcFlag) {
        $webroot   = Base_Config::getConfig('web')->root;
        $chinapnr  = Finance_Chinapnr_Logic::getInstance();
        $orderInfo = $this->genOrderInfo();
        $orderDate = $orderInfo['date'];
        $orderId   = $orderInfo['orderId'];
		//充值订单入库
		$paramOrder = array(
		    'orderId'   => intval($orderId),
			'orderDate' => intval($orderDate),
			'userId'    => intval($userid),
			'type'      => Finance_TypeStatus::NETSAVE,
			'amount'    => floatval($transAmt),
			'status'    => Finance_TypeStatus::PROCESSING,
			'comment'   => '财务类充值订单处理中',
		);
		$this->payOrderEnterDB($paramOrder);
		
		$merCustId  = strval(self::MERCUSTID);
		$huifuid    = strval($huifuid);
		$orderId    = strval($orderId);
		$orderDate  = strval($orderDate);
		$openBankId = strval($openBankId);
		$gateBusiId = strval($gateBusiId);
		$dcFlag     = strval($dcFlag);
		$transAmt   = strval($transAmt);
		$bgRetUrl   = $webroot.'/finance/bgcall/netsave';
		$retUrl     = '';
		$merPriv    = strval($userid);		
		//调用汇付API进行充值处理
		$chinapnr->netSave($merCustId, $huifuid, $orderId, $orderDate, $gateBusiId, $openBankId, $dcFlag, $transAmt, $retUrl, $bgRetUrl, $merPriv);
	}
	
	/**
	 * 标信息录入Logic层
	 * @param investID 标的唯一标示
	 * @param borrUserId 借款人uid
	 * @param borrTotAmt 借款总金额
	 * @param yearRate 年利率
	 * @param retType 还款方式   01等额本息  02等额本金  03按期付息，到期还本  04一次性还款   99其他
	 * @param bidStartDate 时间戳投标开始时间
	 * @param bidEndDate 时间戳投标截止时间
	 * @param float retAmt 总还款金额
	 * @param int retDate 应还款日期
	 * @param proArea 项目所在地
	 * @return array || boolean
	 * 
	 */
	public function addBidInfo($proId,$borrUserId,$borrTotAmt,$yearRate,$retType,$bidStartDate,$bidEndDate,$retAmt,$retDate,$proArea) {
	    $webroot  = Base_Config::getConfig('web')->root;
		$chinapnr = Finance_Chinapnr_Logic::getInstance();
		if(!isset($proId) || !isset($borrUserId) || !isset($borrTotAmt) || !isset($yearRate) ||
		   !isset($retType) || !isset($bidStartDate) || !isset($bidEndDate) || !isset($proArea)) {
		    Base_Log::error(array(
		    	'msg' => '请求参数出错',
		    ));   	
		    return false;
		}		
		$merCustId = strval(self::MERCUSTID);
		$proId = strval($proId);
		$borrCustId = strval($this->getHuifuid(intval($borrUserId)));
		$borrTotAmt = strval($borrTotAmt);
		$yearRate = strval($yearRate);
		$retType = '0'.strval($retType);
		$bidStartDate = date("YmdHis",$bidStartDate);
		$bidStartDate = strval($bidStartDate);
		$bidEndDate = date("YmdHis",$bidEndDate);		
		$bidEndDate = strval($bidEndDate);
		$retAmt = ($retAmt);
		$retDate = date("Ymd",$retDate);
		$retDate = strval($retDate);
		$guarCompId = '';
		$guarAmt = '';
		$proArea = strval($proArea);
		$bgRetUrl = $webroot.'/finance/bgcall/addBidInfo';
		$merPriv = '';
		$reqExt = '';		
		$ret = $chinapnr->addBidInfo($merCustId,$proId,$borrCustId,$borrTotAmt,$yearRate,$retType,$bidStartDate,$bidEndDate,$retAmt,
				$retDate,$guarCompId,$guarAmt,$proArea,$bgRetUrl,$merPriv,$reqExt);
		
		return $ret;
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
	 */
	public function initiativeTender($loanId, $transAmt, $userid, $uidborrowDetail, $isFreeze='Y', $retUrl='') {
		$webroot  = Base_Config::getConfig('web')->root;
		$chinapnr = Finance_Chinapnr_Logic::getInstance();
		
	    $orderInfo = $this->genOrderInfo();
	    $orderDate = $orderInfo['date'];
	    $orderId   = $orderInfo['orderId'];
	    
	        	    
	    //主动投标订单记录入表finance_order
	    $param = array(
	    	'orderId'     => intval($orderId),
	    	'orderDate'   => intval($orderDate),
	    	'userId'      => intval($userid),	    	
	    	'freezeOrdId' => intval($freezeOrdId),
	    	'type'        => Finance_TypeStatus::INITIATIVETENDER,
	    	'amount'      => floatval($transAmt),
	    	'status'      => Finance_TypeStatus::PROCESSING,
	    	'comment'     => '财务类主动投标订单处理中',	
	    );
	    $this->payOrderEnterDB($param);
 
	    $merCustId = strval(self::MERCUSTID);	    
	    $orderId   = strval($orderId);
	    $orderDate = strval($orderDate);
	    $transAmt  = strval($transAmt);
	    $usrCustId = strval($this->getHuifuid($userid));
	    $maxTenderRate = '0.10';
	    
	    //BorrowerRate=1 谁来给？？
	    $huifuborrowerDetails = array(
	    	array(
	    		'BorrowerCustId' => strval($this->getHuifuid(intval($uidborrowDetail[0]['BorrowerUserId']))),//借款人汇付id
	    		'BorrowerAmt'  => $transAmt,
	    		'BorrowerRate' => strval($uidborrowDetail[0]['BorrowerRate']),//借款手续费率
	    		'ProId' => strval($loanId),//标的唯一标识
	    	)
	    );			
	    $huifuborrowerDetails = json_encode($huifuborrowerDetails);
	    $isFreeze    = strval($isFreeze);
	    //订单号唯一性
	    $freezeOrdInfo = $this->genOrderInfo();
	    $freezeOrdId   = $freezeOrdInfo['orderId'];
	    $freezeOrdId = strval($freezeOrdId);
	    $retUrl      = "";   
	    $bgRetUrl    = $webroot.'/finance/bgcall/initiativeTender';
	    $userid      = strval($userid);
	    $proId       = strval($loanId);
	    //$merPriv     = $userid.'_'.$proId;
	    $merPriv = '';
	    $chinapnr->initiativeTender($merCustId,$orderId,$orderDate,$transAmt,$usrCustId,
	        $maxTenderRate,$huifuborrowerDetails,$isFreeze,$freezeOrdId,$retUrl,$bgRetUrl,$merPriv
		);	    
	}
	
	/**
	 * 满标打款Logic层
	 * @param int subOrdId  对应借款投标的orderID
	 * @param int inCustId  借款人的uid
	 * @param int OutCustId 投标人的uid
	 * @param float transAmt 放款金额
	 * 
	 */
	public function loans($loanId,$subOrdId,$inUserId,$outUserId,$transAmt) {
		$webroot = Base_Config::getConfig('web')->root;
		$chinapnr = Finance_Chinapnr_Logic::getInstance();
		$orderInfo = $this->genOrderInfo();
		$orderDate = $orderInfo['date'];
		$orderId   = $orderInfo['orderId'];		
		$inHuifuId = $this->getHuifuid(intval($inUserId));
		$outHuifuId = $this->getHuifuid(intval($outUserId));	
			
		//打款订单记录入表finance_order
		$paramOrder = array(
			'orderId'   => intval($orderId),
			'orderDate' => intval($orderDate),
			'userId'    => intval($outUserId),//投标人的uid
			'type'      => Finance_TypeStatus::LOANS,
			'amount'    => floatval($transAmt),
			'status'    => Finance_TypeStatus::PROCESSING,
			'comment'   => '财务类打款订单处理中',
		);
	    $this->payOrderEnterDB($paramOrder);
	    //投标记录表状态更改为“打款中”
	    $this->payTenderUpdate(intval($subOrdId),Finance_TypeStatus::PAYING);	    
	    
		$merCustId = strval(self::MERCUSTID);
		$orderId   = strval($orderId);
		$orderDate = strval($orderDate);
		$outCustId = strval($outHuifuId);
		$transAmt  = strval($transAmt);
			
		$loanInfo = Loan_Api::getLoanInfo(intval($loanId));
		$duration = $loanInfo['days'];
		$riskLevel = $loanInfo['level'];
		
		$arrayFee = $this->getFee($riskLevel,$transAmt,$duration);
		$fee = strval($arrayFee['all']);
		$prepareFee = strval($fee['prepareFee']);//风险准备金
		$serviceFee = strval($fee['serviceFee']);//融资服务费
		
		$tenderInfo = $this->getTenderInfo($subOrdId);
		$subOrdId = strval($subOrdId);
		$subOrdDate = strval($tenderInfo['orderDate']);
		$inCustId = strval($inHuifuId);
		$arrDivDetails = array(
			//风险金账户
			array(
			    'DivCustId'=>'6000060000677575',
				'DivAcctId'=>'SDT000002',
				'DivAmt'   => floatval($prepareFee),
		    ),
			//专属账户
			array(
				'DivCustId'=>'6000060000677575',
				'DivAcctId'=>'MDT000001',
				'DivAmt'   => floatval($serviceFee),
			),			
		);
		$jsonDivDetails = json_encode($arrDivDetails);
		$feeObjFlag = 'I';//手续费向入款人收取				
		$isDefault  = 'Y';
		$isUnFreeze = 'Y';
		$unFreezeOrdInfo = $this->genOrderInfo();
		$unFreezeOrdId   = strval($unFreezeOrdInfo['orderId']);
		$freezeTrxId     = strval($tenderInfo['freezeTrxId']);
		$bgRetUrl        = $webroot.'/finance/bgcall/loans';
		$merPriv         = strval($outUserId);//投标人的uid
		$reqExt          = '';
		$ret = $chinapnr->loans($merCustId, $orderId, $orderDate, $outCustId, $transAmt, $fee, $subOrdId, $subOrdDate,
		    $inCustId, $jsonDivDetails,$feeObjFlag, $isDefault, $isUnFreeze, $unFreezeOrdId, $freezeTrxId, $bgRetUrl,
		    $merPriv, $reqExt);	
		return $ret;	
	}
	/**
	 * 投标撤销Logic层
	 * @param float tramsAmt
	 * @param int userid
	 * @param int orderId
	 * @param int orderDate
	 * @param string retUrl
	 * redirect
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
	
	/**
	 * 取现Logic层
	 * @param int userid
	 * @param float transAmt
	 * @param string openAcctId
	 * redirect
	 */
	public function cash($userid,$transAmt,$openAcctId) {
		$webroot   = Base_Config::getConfig('web')->root;
		$chinapnr  = Finance_Chinapnr_Logic::getInstance();
		$merCustId = strval(self::MERCUSTID);
		$orderInfo = $this->genOrderInfo();
		$orderDate = $orderInfo['date'];
		$orderId   = $orderInfo['orderId'];
		//取现订单订单记录入表finance_order
		$param = array(
			'orderId'     => intval($orderId),
			'orderDate'   => intval($orderDate),
			'userId'      => intval($userid),
			'type'        => Finance_TypeStatus::CASH,
			'amount'      => floatval($transAmt),
			'status'      => Finance_TypeStatus::ORDER_INITIALIZE,
			'comment'     => '财务类提现订单初始化',
		);
        $this->payOrderEnterDB($param);

        $orderId = strval($orderId);
		$huifuid = $this->getHuifuid($userid);
		$huifuid = '6000060000696947';
		$transAmt = strval($transAmt);
		$servFee = '';
		$openAcctId = '4367423378320018938';
		$retUrl = '';
		$bgRetUrl = $webroot.'/finance/bgcall/cash';
		$merPriv = strval($userid);
		$reqExt = array(array(
			'FeeObjFlag' => 'U',
			'FeeAcctId'  => '',
			'CashChl'    => 'GENERAL',
		));
		$reqExt = json_encode($reqExt);
		//echo ($reqExt);die;
		$chinapnr->cash($merCustId, $orderId, $huifuid, $transAmt, $servFee, '', 
		    $openAcctId, $retUrl, $bgRetUrl, '', '', $merPriv, $reqExt);
	}
	
	/**
	 * 还款Logic层
	 * @param string outUserId 出账账户号：还款人的uid
	 * @param string inUserId 入账账户号：投资人的uid
	 * @param string subOrdId 关联的投标订单号
	 * @param float transAmt 交易金额
	 * @param int loanId
	 * @return array || boolean
	 * 
	 */
	public function repayment($outUserId,$inUserId,$subOrdId,$transAmt,$loanId) {
		if(!isset($outUserId) || !isset($inUserId) || !isset($subOrdId) ||
		   !isset($transAmt) || !isset($proId)) {
		    Base_Log::error(array(
		    	'msg' => '请求参数错误',
		    ));   	
		    return false;
		}
		$webroot   = Base_Config::getConfig('web')->root;
		$chinapnr  = Finance_Chinapnr_Logic::getInstance();
		
		$orderInfo = $this->genOrderInfo();
		$orderId   = $orderInfo['orderId'];
		$orderDate = $orderInfo['date'];
		//还款订单订单记录入表finance_order
		$param = array(
			'orderId'   => intval($orderId),
			'orderDate' => intval($orderDate),
			'userId'    => intval($outUserId),//还款人的uid
			'type'      => Finance_TypeStatus::REPAYMENT,
			'amount'    => floatval($transAmt),
			'status'    => Finance_TypeStatus::PROCESSING,
			'comment'   => '财务类还款订单初始化',
		);
		$this->payOrderEnterDB($param);
		
		$merCustId = strval(self::MERCUSTID);
		$ordId = strval($orderId);
		$ordDate = strval($orderDate);
		$outCustId = strval($this->getHuifuid(intval($outUserId)));//还款人的汇付ID
		$tenderInfo = $this->getTenderInfo(intval($subOrdId));
		$subOrdId = strval($subOrdId);
		$subOrdDate = strval($tenderInfo['orderDate']);
		$outAcctId = '';
		$transAmt = strval($transAmt);
		$loanInfo = Loan_Api::getLoanInfo(intval($loanId));
		$duration = $loanInfo['days'];
		$riskLevel = $loanInfo['level'];
		$fee = strval($this->getFee($riskLevel,$transAmt,$duration));
		$serviceFee = $fee['serviceFee'];
		$prepareFee = $fee['prepareFee'];
		$inCustId = strval($this->getHuifuid(intval($inUserId)));//收款人的汇付ID
		$inAcctId = '';
		/////////////////////////////////////////////////////////////////////////
		//管理费到底应该怎么收取
		//打到专属户中！！
		$arrDivDetails = array(
			//风险金账户
			array(
				'DivCustId'=>'6000060000677575',
				'DivAcctId'=>'SDT000002',
				'DivAmt'   => floatval($prepareFee),
			),
			//专属账户
			array(
				'DivCustId'=>'6000060000677575',
				'DivAcctId'=>'MDT000001',
				'DivAmt'   => floatval($serviceFee),
			),
		);
		/////////////////////////////////////////////////////////////////////////
		$divDetails = json_encode($arrDivDetails);
		$feeObjFlag = 'O';//像还款人收取手续费
		$bgRetUrl = $webroot.'/finance/bgcall/repayment';
		$merPriv = strval($outUserId);//借款人的uid
		$reqExt = '';
		$ret = $chinapnr->repayment($merCustId, $ordId, $ordDate, $outCustId, $subOrdId, $subOrdDate, $outAcctId, 
				$transAmt, $fee, $inCustId, $inAcctId, $divDetails, $feeObjFlag, $bgRetUrl, $merPriv, $reqExt);
		
	}
	
	/**
	 * 自动扣款转账(商户用)
	 * 
	 * 
	 */
	public function transfer($outUserId,$outAcctId,$transAmt,$inUserId) {
		if(!isset($outCustId) || !isset($outAcctId) || !isset($transAmt) || !isset($inCustId)) {
			Base_Log::error(array(
				'msg' => '请求参数错误',
			));
			return;
		}
		$webroot   = Base_Config::getConfig('web')->root;
		$chinapnr  = Finance_Chinapnr_Logic::getInstance();
		
		$orderInfo = $this->genOrderInfo();
		$orderId   = $orderInfo['orderId'];
		$orderDate = $orderInfo['date'];
		
		//还款订单订单记录入表finance_order
		$param = array(
			'orderId'   => intval($orderId),
			'orderDate' => intval($orderDate),
			'userId'    => intval($outUserId),//还款人的uid
			'type'      => Finance_TypeStatus::TRANSFER,
			'amount'    => floatval($transAmt),
			'status'    => Finance_TypeStatus::PROCESSING,
			'comment'   => '财务类自动扣款转账(商户用)订单初始化',
		);
		$this->payOrderEnterDB($param);
		
		$ordId = strval($orderId);
		$outCustId = strval($this->getHuifuid(intval($outUserId)));
		$outAcctId = strval($outAcctId);
		$transAmt = strval($transAmt);
		$inCustId = strval($this->getHuifuid(intval($inUserId)));
		$inAcctId = '';
		$retUrl = '';
		$bgRetUrl = $webroot.'/finance/bgcall/transfer';
		$merPriv = strval($orderDate);		
		$ret = $chinapnr->transfer($ordId,$outCustId,$outAcctId,$transAmt,$inCustId,$inAcctId = '',$retUrl = '',$bgRetUrl,$merPriv = '');		
		return $ret;
	}
}




