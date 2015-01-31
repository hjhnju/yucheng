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
        
        $bgLogic = new Account_Logic_UserInfo();
        $userBg = $bgLogic->getUserBg($huifuid);
        $avlBal = $userBg['avlBal'];
    	//充值订单入库
		$paramOrder = array(
		    'orderId'   => intval($orderId),
			'orderDate' => intval($orderDate),
			'userId'    => intval($userid),
			'type'      => Finance_TypeStatus::NETSAVE,
			'amount'    => floatval($transAmt),
			'avlBal'    => floatval($avlBal),
			'status'    => Finance_TypeStatus::PROCESSING,
			'comment'   => '充值订单处理中',
		);
		$this->payOrderEnterDB($paramOrder);
		
		$merCustId  = strval(self::MERCUSTID);
		$huifuid    = strval($huifuid);
		$orderId    = strval($orderId);
		$orderDate  = strval($orderDate);
		$openBankId = strval($openBankId);
		$gateBusiId = strval($gateBusiId);
		$dcFlag     = strval($dcFlag);
		$transAmt   = sprintf('%.2f',$transAmt);
		$bgRetUrl   = $webroot.'/finance/bgcall/netsave';
		$retUrl     = '';
		$merPriv    = strval($userid);				
		//调用汇付API进行充值处理
		$chinapnr->netSave($merCustId, $huifuid, $orderId, $orderDate, $gateBusiId, $openBankId, 
	        $dcFlag, $transAmt, $retUrl, $bgRetUrl, $merPriv);
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
     * @param guarCompId 担保公司唯一标识
     * @param guarAmt 担保金额
     * @return Base_Result
     * data=array('orderId'=>)
     * 
     */
    public function addBidInfo($loanId, $borrUserId,$borrTotAmt,$yearRate,$retType,$bidStartDate,$bidEndDate,$retAmt,$retDate,$proArea, $guarCompId='', $guarAmt='') {     
        $objRst = new Base_Result();
        if(!isset($loanId) || !isset($borrUserId) || !isset($borrTotAmt) || !isset($yearRate) ||
           !isset($retType) || !isset($bidStartDate) || !isset($bidEndDate) || !isset($proArea)) {
            $objRst->status     = Base_RetCode::PARAM_ERROR;
            $objRst->statusInfo = Base_RetCode::getMsg(Base_RetCode::PARAM_ERROR);    
            Base_Log::warn(array(
                'msg'  => $objRst->statusInfo,
                'args' => func_get_args(),
            ));
            return $objRst;
        }
        //为借款标的生成一个订单号，返回
        $orderId      = Finance_Logic_Order::genOrderId();

        $proId        = $loanId;
        $webroot      = Base_Config::getConfig('web')->root;
        $chinapnr     = Finance_Chinapnr_Logic::getInstance();
        $merCustId    = strval(self::MERCUSTID);
        $borrCustId   = strval($this->getHuifuid(intval($borrUserId)));
        $borrTotAmt   = sprintf('%.2f',$borrTotAmt);
        $yearRate     = sprintf('%.2f',$yearRate);
        $retType      = '0'.strval($retType);
        $bidStartDate = date("YmdHis",$bidStartDate);
        $bidStartDate = strval($bidStartDate);
        $bidEndDate   = date("YmdHis",$bidEndDate);       
        $bidEndDate   = strval($bidEndDate);      
        $retAmt       = sprintf('%.2f',$retAmt);
        $retDate      = date("Ymd",$retDate);
        $retDate      = strval($retDate);
        $guarCompId   = strval($guarCompId);
        $guarAmt      = strval($guarAmt);
        $proArea      = strval($proArea);
        $bgRetUrl     = $webroot . '/finance/bgcall/addBidInfo';
        $merPriv      = '';
        $reqExt       = '';       
        $mixRet       = $chinapnr->addBidInfo($merCustId, $proId, $borrCustId, $borrTotAmt, 
            $yearRate, $retType, $bidStartDate, $bidEndDate, $retAmt,
            $retDate, $guarCompId,$guarAmt,$proArea,$bgRetUrl,$merPriv,$reqExt);        
        
        if(!is_null($mixRet)){
            $objRst->status = Base_RetCode::SUCCESS;
            $objRst->data   = array('orderId' => $orderId);
        }else{
            $objRst->status = Finance_RetCode::ADD_BIDINFO_FAIL;
            $objRst->statusInfo = Finance_RetCode::getMsg(Finance_RetCode::ADD_BIDINFO_FAIL);
        }

        return $objRst;
    }
    
    /**
     * @param float   tranAmt
     * @param integer userid
     * @param array   borrowerDetails, detail支持投资给多个借款人，BorrowerAmt总和要等于总投资额度
     * array(
     *     array('BorrowerUserId','BorrowerAmt'),
     * )
     * @param boolean isFreeze
     * @param string  freezeOrdId
     * @param string  retUrl
     * redirect
     */
        
    /**
     * 主动投标Logic层
     * @param int loanid 借款ID
     * @param float transAmt 交易金额(required)   
     * @param int usrid 用户ID(required)    
     * @param array $arrDetails 借款人信息(required)     
     *        array(
     *            0 => array(
     *                "BorrowerUserId":借款人userid    
     *                "BorrowerAmt": "20.01"， 借款金额
     *            )
     *            1 =>array(
     *                ...
     *                ...
     *            )
     *            ...
     *        )
     * redirect
     * 
     */
    public function initiativeTender($loanId, $transAmt, $userid, $arrDetails, $retUrl) {
        if(!isset($loanId) || !isset($transAmt) || !isset($userid) || !isset($arrDetails)) {
            Base_Log::error(array(
                'msg'        => '请求参数错误',
                'loanId'     => $loanId,
                'transAmt'   => $transAmt,
                'userid'     => $userid,
                'arrDetails' => $arrDetails,
            ));
        }

        $transAmt  = sprintf('%.2f',$transAmt);
        $usrCustId = strval($this->getHuifuid($userid));
        $loanId    = strval($loanId);


        $webroot  = Base_Config::getConfig('web')->root;
        $chinapnr = Finance_Chinapnr_Logic::getInstance();
        
        $orderInfo = $this->genOrderInfo();
        $orderDate = $orderInfo['date'];
        $orderId   = $orderInfo['orderId'];
        
        $bgLogic = new Account_Logic_UserInfo();
        $userBg = $bgLogic->getUserBg($usrCustId);
        $avlBal = $userBg['avlBal'];
        
        //主动投标订单记录入表finance_order
        $param = array(
            'orderId'     => intval($orderId),
            'orderDate'   => intval($orderDate),
            'userId'      => intval($userid),           
            'type'        => Finance_TypeStatus::INITIATIVETENDER,
            'amount'      => floatval($transAmt),
            'avlBal'      => floatval($avlBal),
            'status'      => Finance_TypeStatus::PROCESSING,
            'comment'     => '主动投标订单处理中',   
        );
        $this->payOrderEnterDB($param);
 
        $merCustId       = strval(self::MERCUSTID);       
        $orderId         = strval($orderId);
        $orderDate       = strval($orderDate);
        $maxTenderRate   = Finance_Fee::MaxTenderRate;
        $borrowerDetails = array();
        foreach ($arrDetails as $detail) {
            $borrowerDetails[] = array(
                //借款人汇付id
                'BorrowerCustId' => strval($this->getHuifuid($detail['BorrowerUserId'])),
                //投资给每个借款人的金额
                'BorrowerAmt'    => strval($detail['BorrowerAmt']),
                //最大借款手续费率
                'BorrowerRate'   => Finance_Fee::MaxBorrowerRate,
                //标的唯一标识
                'ProId'          => $loanId,
            );
        }      
        $borrowerDetails = json_encode($borrowerDetails);

        //冻结
        $isFreeze      = 'Y';
        //订单号唯一性
        $freezeOrdInfo = $this->genOrderInfo();
        $freezeOrdId   = $freezeOrdInfo['orderId'];
        $freezeOrdId   = strval($freezeOrdId);
        $retUrl        = strval($retUrl);
        $bgRetUrl      = $webroot.'/finance/bgcall/initiativeTender';
        $userid        = strval($userid);
        $proId         = $loanId;
        $merPriv       = $userid.'_'.$proId; //将userid与proid作为私有域传入
        Base_Log::notice(array(
            'merCustId'       => $merCustId,
            'orderId'         => $orderId,
            'orderDate'       => $orderDate,
            'transAmt'         => $transAmt,
            'usrCustId'       => $usrCustId,
            'maxTenderRate'   => $maxTenderRate,
            'borrowerDetails' => $borrowerDetails,
            'isFreeze'        => $isFreeze,
            'freezeOrdId'     => $freezeOrdId,
            'retUrl'          => $retUrl,
            'bgRetUrl'        => $bgRetUrl,
            'merPriv'         => $merPriv,
        ));
        $chinapnr->initiativeTender($merCustId, $orderId, $orderDate, $transAmt, $usrCustId,
            $maxTenderRate, $borrowerDetails, $isFreeze, $freezeOrdId, $retUrl, $bgRetUrl, $merPriv
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
    public function loans($loanId, $subOrdId, $inUserId, $outUserId, $transAmt) {
        $objRst = new Base_Result();
        if(!isset($loanId) || !isset($subOrdId) || !isset($inUserId) || !isset($outUserId) || !isset($transAmt)) {
            Base_Log::error(array(
                'msg'       => '请求参数错误',
                'loanId'    => $loanId,
                'subOrdId'  => $subOrdId,
                'inUserId'  => $inUserId,
                'outUserId' => $outUserId,
                'transAmt'  => $transAmt,
            ));
            $objRst->status     = Base_RetCode::PARAM_ERROR;
            $objRst->statusInfo = Base_RetCode::getMsg(Base_RetCode::PARAM_ERROR);
            return $objRst;  
        }
        $webroot    = Base_Config::getConfig('web')->root;
        $chinapnr   = Finance_Chinapnr_Logic::getInstance();
        $orderInfo  = $this->genOrderInfo();
        $orderDate  = $orderInfo['date'];
        $orderId    = $orderInfo['orderId'];     
        $inHuifuId  = $this->getHuifuid(intval($inUserId));
        $outHuifuId = $this->getHuifuid(intval($outUserId));    
       
        $bgLogic = new Account_Logic_UserInfo();
        $userBg = $bgLogic->getUserBg($outHuifuId);
        $avlBal = $userBg['avlBal'];       

        //打款订单记录入表finance_order
        $paramOrder = array(
            'orderId'   => intval($orderId),
            'orderDate' => intval($orderDate),
            'userId'    => intval($outUserId),//投标人的uid
            'type'      => Finance_TypeStatus::LOANS,
            'amount'    => floatval(sprintf('%.2f',$transAmt)),
            'avlBal'    => floatval($avlBal),
            'status'    => Finance_TypeStatus::PROCESSING,
            'comment'   => '打款订单处理中',
        );
        $this->payOrderEnterDB($paramOrder);
        //投标记录表状态更改为“打款中”
        $this->payTenderUpdate(intval($subOrdId), Finance_TypeStatus::PAYING);       
        
        $merCustId = strval(self::MERCUSTID);
        $orderId   = strval($orderId);
        $orderDate = strval($orderDate);
        $outCustId = strval($outHuifuId);
        $transAmt  = sprintf('%.2f',$transAmt);

        //收取费用
        $arrFeeInfo= Finance_Fee::totalFeeInfo($loanId, $transAmt);
        $fee       = $arrFeeInfo['totalFee'];
        $riskFee   = $arrFeeInfo['riskFee'];
        $servFee   = $arrFeeInfo['serviceFee'] + $arrFeeInfo['manageFee'];
        
        $tenderInfo    = $this->getTenderInfo($subOrdId);
        $subOrdId      = strval($subOrdId);
        $subOrdDate    = strval($tenderInfo['orderDate']);
        $inCustId      = strval($inHuifuId);
        $arrDivDetails = array(
            //风险金账户
            //TODO:配置化
            array(
                'DivCustId'=> '6000060000677575',
                'DivAcctId'=> 'SDT000002',
                'DivAmt'   => $riskFee,
            ),  
            //专属账户
            array(
                'DivCustId'=> '6000060000677575',
                'DivAcctId'=> 'MDT000001',
                'DivAmt'   => $servFee,
            ),          
        );
        $jsonDivDetails  = json_encode($arrDivDetails);
        $feeObjFlag      = 'I';//手续费向入款人收取               
        $isDefault       = 'Y';
        $isUnFreeze      = 'Y';
        $unFreezeOrdInfo = $this->genOrderInfo();
        $unFreezeOrdId   = strval($unFreezeOrdInfo['orderId']);
        $freezeTrxId     = strval($tenderInfo['freezeTrxId']);
        $bgRetUrl        = $webroot.'/finance/bgcall/loans';
        $merPriv         = strval($outUserId);//投标人的uid
        $reqExt          = array(
            'ProId'   => strval($loanId),
        );
        $reqExt          = json_encode($reqExt);
        
        Base_Log::notice(array(
            'msg'            => '准备单笔放款',
            'merCustId'      => $merCustId,
            'orderId'        => $orderId,
            'orderDate'      => $orderDate,
            'outCustId'      => $outCustId,
            'transAmt'       => $transAmt, 
            'fee'            => $fee,
            'subOrdDate'     => $subOrdId,
            'subOrdDate'     => $subOrdDate,
            'inCustId'       => $inCustId,
            'jsonDivDetails' => $jsonDivDetails,
            'feeObjFlag'     => $feeObjFlag,
            'isDefault'      => $isDefault,
            'isUnFreeze'     => $isUnFreeze,
            'unFreezeOrdId'  => $unFreezeOrdId,
            'freezeTrxId'    => $freezeTrxId,
            'bgRetUrl'       => $bgRetUrl,
            'merPriv'        => $merPriv,
            'reqExt'         => $reqExt,
        ));

        $mixRet  = $chinapnr->loans($merCustId, $orderId, $orderDate, $outCustId, 
            $transAmt, $fee, $subOrdId, $subOrdDate, $inCustId, 
            $jsonDivDetails, $feeObjFlag, $isDefault, $isUnFreeze, $unFreezeOrdId, 
            $freezeTrxId, $bgRetUrl, $merPriv, $reqExt);

        if(empty($mixRet)){
            $objRst->status     = Finance_RetCode::REQUEST_API_ERROR;
            $objRst->statusInfo = Finance_RetCode::getMsg(Finance_RetCode::REQUEST_API_ERROR);
            return $objRst;
        }
        $respCode = $mixRet['RespCode'];
        $respDesc = $mixRet['RespDesc'];
        if($respCode !== '000') {           
            $objRst->status     = $respCode;
            $objRst->statusInfo = $respDesc;
            Base_Log::error($objRst->format());
            return $objRst;
        }

        $objRst->status = Base_RetCode::SUCCESS;
        return $objRst; 
    }
    
    /**
     * 投标撤销Logic层
     * @param float tramsAmt
     * @param int userid
     * @param int orderId
     * @param int orderDate
     * @param string retUrl
     * redirect || false
     */
    public function tenderCancel($transAmt,$userid,$orderId,$orderDate,$freezeTrxId,$retUrl) {
        if(!isset($transAmt) || !isset($userid) || !isset($orderId) || !isset($orderDate) || 
           !isset($freezeTrxId) || !isset($retUrl)) {
            Base_Log::error(array(
                'msg'         => '请求参数错误',
                'transAmt'    => $transAmt,
                'userid'      => $userid,
                'orderId'     => $orderId,
                'orderDate'   => $orderDate,
                'freezeTrxId' => $freezeTrxId,
                'retUrl'      => $retUrl,
            ));
            return false;
        }       
        $usrCustId = strval($this->getHuifuid($userid));
        
        $transAmt = sprintf('%.2f',$transAmt);
        $bgLogic = new Account_Logic_UserInfo();
        $userBg = $bgLogic->getUserBg($usrCustId);
        $avlBal = $userBg['avlBal'];
        
        //打款订单记录入表finance_order
        $paramOrder = array(
            'orderId'   => intval($orderId),
            'orderDate' => intval($orderDate),
            'userId'    => intval($userid),//投标人的uid
            'type'      => Finance_TypeStatus::TENDERCANCEL,
            'amount'    => floatval($transAmt),
            'avlBal'    => floatval($avlBal),
            'status'    => Finance_TypeStatus::PROCESSING,
            'comment'   => '主动投标撤销处理中',
        );
        //投标撤销订单入库
        $this->payOrderEnterDB($paramOrder);
        
        $webroot = Base_Config::getConfig('web')->root;
        $chinapnr = Finance_Chinapnr_Logic::getInstance();
        $merCustId = strval(self::MERCUSTID);
        $orderId = strval($orderId);
        $orderDate = strval($orderDate);
        $transAmt = $transAmt;
        $usrCustId = strval($usrCustId);
        $isUnFreeze = strval('Y');
        $unFreezeOrdInfo = $this->genOrderInfo();
        $unFreezeOrdId = $unFreezeOrdInfo['orderId'];
        $unFreezeOrderId = strval($unFreezeOrdId);
        $freezeTrxId = strval($freezeTrxId);
        $retUrl = strval($retUrl);
        $bgRetUrl = $webroot.'/finance/bgcall/tendercancel';
        $merPriv = strval($userid);     

        $chinapnr->tenderCancel($merCustId, $usrCustId, $orderId, $orderDate, $transAmt, $usrCustId,
            $isUnFreeze, $unFreezeOrderId, $freezeTrxId, $retUrl, $bgRetUrl, $merPriv);     
    }
    
    /**
     * 取现Logic层
     * @param int userid
     * @param float transAmt
     * @param string openAcctId
     * autoRedirect || return false
     */
    public function cash($userid,$transAmt,$openAcctId) {
        if(!isset($userid) || !isset($transAmt) || !isset($openAcctId)) {
            Base_Log::error(array(
                'msg'        => '请求参数错误',
                'userid'     => $userid,
                'transAmt'   => $transAmt,
                'openAcctId' => $openAcctId,
            ));
            return false;
        }
        $webroot   = Base_Config::getConfig('web')->root;
        $chinapnr  = Finance_Chinapnr_Logic::getInstance();
        
        $transAmt = sprintf('%.2f',$transAmt);
        $merCustId = strval(self::MERCUSTID);
        $orderInfo = $this->genOrderInfo();
        $orderDate = $orderInfo['date'];
        $orderId   = $orderInfo['orderId'];
        $huifuid   = $this->getHuifuid($userid);
        
        $bgLogic = new Account_Logic_UserInfo();
        $userBg = $bgLogic->getUserBg($huifuid);
        $avlBal = $userBg['avlBal'];
        
        //取现订单订单记录入表finance_order
        $param = array(
            'orderId'     => intval($orderId),
            'orderDate'   => intval($orderDate),
            'userId'      => intval($userid),
            'type'        => Finance_TypeStatus::CASH,
            'amount'      => floatval($transAmt),
            'avlBal'      => floatval($avlBal),
            'status'      => Finance_TypeStatus::ORDER_INITIALIZE,
            'comment'     => '提现订单初始化',
        );
        $this->payOrderEnterDB($param);
        
        $orderId = strval($orderId);
        $transAmt = $transAmt;
        $servFee = '';
        $openAcctId = '';
        $retUrl = '';
        $bgRetUrl = $webroot.'/finance/bgcall/cash';
        $merPriv = strval($userid);
        $reqExt = array(array(
            'FeeObjFlag' => 'U',
            'CashChl'    => 'GENERAL',
        ));
        $reqExt = json_encode($reqExt);
        $chinapnr->cash($merCustId, $orderId, $huifuid, $transAmt, $servFee, '', $openAcctId, 
            $retUrl, $bgRetUrl, '', '', $merPriv, $reqExt);
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
           !isset($transAmt) || !isset($loanId)) {
            Base_Log::error(array(
                'msg' => '请求参数错误',
            ));     
            return false;
        }
        $webroot = Base_Config::getConfig('web')->root;
        $chinapnr = Finance_Chinapnr_Logic::getInstance();
        
        $transAmt = sprintf('%.2f',$transAmt);
        $orderInfo = $this->genOrderInfo();
        $orderId = $orderInfo['orderId'];
        $orderDate = $orderInfo['date'];
        $huifuid = $this->getHuifuid(intval($inUserId));
        
        $bgLogic = new Account_Logic_UserInfo();
        $userBg = $bgLogic->getUserBg($huifuid);
        $avlBal = $userBg['avlBal'];
        
        //还款订单订单记录入表finance_order
        $param = array(
            'orderId'   => intval($orderId),
            'orderDate' => intval($orderDate),
            'userId'    => intval($outUserId),//还款人的uid
            'type'      => Finance_TypeStatus::REPAYMENT,
            'amount'    => floatval($transAmt),
            'avlBal'    => floatval($avlBal),
            'status'    => Finance_TypeStatus::PROCESSING,
            'comment'   => '还款订单处理中',
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
        $transAmt = $transAmt;
        $loanInfo = Loan_Api::getLoanInfo(intval($loanId));
        $duration = $loanInfo['days'];
        ///test
        $duration = 10;
        $riskLevel = $loanInfo['level'];
        ///test
        $riskLevel = 10;
        $arrFee = $this->getFee($riskLevel,$transAmt,$duration);
        $fee = strval($arrFee['all']);
        $serviceFee = strval($arrFee['serviceFee']);
        $prepareFee = strval($arrFee['prepareFee']);
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
                'DivAmt'   => strval($prepareFee),
            ),
            //专属账户
            array(
                'DivCustId'=>'6000060000677575',
                'DivAcctId'=>'MDT000001',
                'DivAmt'   => strval($serviceFee),
            ),
        );
        /////////////////////////////////////////////////////////////////////////
        $divDetails = json_encode($arrDivDetails);
        $feeObjFlag = 'O';//像还款人收取手续费
        $bgRetUrl = $webroot.'/finance/bgcall/repayment';
        $merPriv = strval($outUserId);//借款人的uid
        $reqExt = array(
            'ProId' => strval($loanId),
        );
        $reqExt = json_encode($reqExt);     
        $ret = $chinapnr->repayment($merCustId, $ordId, $ordDate, $outCustId, $subOrdId, $subOrdDate, $outAcctId, 
            $transAmt, $fee, $inCustId, $inAcctId, $divDetails, $feeObjFlag, $bgRetUrl, $merPriv, $reqExt);
        
    }
    
    /**
     * 自动扣款转账(商户用)
     * 
     * 
     */
    public function transfer($outUserId,$outAcctId,$transAmt,$inUserId) {
        if(!isset($outUserId) || !isset($outAcctId) || !isset($transAmt) || !isset($inUserId)) {
            Base_Log::error(array(
                'msg' => '请求参数错误',
            ));
            return;
        }
        $webroot   = Base_Config::getConfig('web')->root;
        $chinapnr  = Finance_Chinapnr_Logic::getInstance();
        $transAmt  = sprintf('%.2f',$transAmt);
        $orderInfo = $this->genOrderInfo();
        $orderId   = $orderInfo['orderId'];
        $orderDate = $orderInfo['date'];
        
        $huifuid = $this->getHuifuid(intval($inUserId));
        $bgLogic = new Account_Logic_UserInfo();
        $userBg = $bgLogic->getUserBg($huifuid);
        $avlBal = $userBg['avlBal'];
        
        //还款订单订单记录入表finance_order
        $param = array(
            'orderId'   => intval($orderId),
            'orderDate' => intval($orderDate),
            'userId'    => intval($outUserId),//还款人的uid
            'type'      => Finance_TypeStatus::TRANSFER,
            'amount'    => floatval($transAmt),
            'avlBal'    => floatval($avlBal),
            'status'    => Finance_TypeStatus::PROCESSING,
            'comment'   => '自动扣款转账(商户用)订单处理中',
        );
        $this->payOrderEnterDB($param);
        
        $ordId = strval($orderId);
        $outCustId = strval($outUserId);
        $outAcctId = strval($outAcctId);
        $inCustId = strval($huifuid);
        $inAcctId = 'MDT000001';
        $retUrl = '';
        $bgRetUrl = $webroot.'/finance/bgcall/transfer';
        $merPriv = strval($orderDate).'_'.strval($inUserId);      
        $ret = $chinapnr->transfer($ordId, $outCustId, $outAcctId, $transAmt, $inCustId, $inAcctId, $retUrl, $bgRetUrl, $merPriv);      
        return $ret;
    }
    
    /**
     * 商户代取现接口Logic层
     * @param int userid
     * @param float transAmt
     * @return array || boolean
     */
    public function merCash($userid,$transAmt) {
        if(!isset($userid) || !isset($transAmt)) {
            Base_Log::error(array(
                'msg'      => '请求参数错误',
                'userid'   => $userid,
                'transAmt' => $transAmt,
            ));
            return false;
        }
        $orderInfo = $this->genOrderInfo();
        $orderId   = strval($orderInfo['orderId']);
        $orderDate = strval($orderInfo['date']);
        
        $huifuid = $this->getHuifuid(intval($userid));
        $bgLogic = new Account_Logic_UserInfo();
        $userBg = $bgLogic->getUserBg($huifuid);
        $avlBal = $userBg['avlBal'];
        $param = array(
            'orderId'   => intval($orderId),
            'orderDate' => intval($orderDate),
            'userId'    => intval($userid),//还款人的uid
            'type'      => Finance_TypeStatus::MERCASH,
            'amount'    => floatval(sprintf('%.2f',$transAmt)),
            'avlBal'    => floatval($avlBal),
            'status'    => Finance_TypeStatus::PROCESSING,
            'comment'   => '商户代取现订单处理中',
        );
        $this->payOrderEnterDB($param);
        
        $webroot   = Base_Config::getConfig('web')->root;
        $chinapnr  = Finance_Chinapnr_Logic::getInstance();
        $merCustId = strval(self::MERCUSTID);
        
        $usrCustId = strval($this->getHuifuid(intval($userid)));
        $transAmt = sprintf('%.2f',$transAmt);
        $servFee = '';
        $servFeeAcctId = '';
        $retUrl = '';
        $bgRetUrl = $webroot.'/finance/bgcall/merCash';
        $remark = '';
        $charSet = '';
        $merPriv = strval($userid).'_'.strval($orderDate);
        $reqExt = '';
        $ret = $chinapnr->merCash($merCustId,$orderId,$usrCustId,$transAmt,$servFee = '',$servFeeAcctId = '',$retUrl = '',
            $bgRetUrl,$remark = '',$charSet = '',$merPriv = '',$reqExt = '');       
        return $ret;
    }
}
