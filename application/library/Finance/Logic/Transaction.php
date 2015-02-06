<?php
/**
 * 财务模块公共逻辑层
 * @author lilu
 */
class Finance_Logic_Transaction extends Finance_Logic_Base{

    public function __construct(){
        parent::__construct();
        $this->webroot = Base_Config::getConfig('web')->root;
    }

    /**
     * 资金解冻接口logic层
     * @param int orinOrderId, 需要解冻的订单（注意区别解冻订单号） 
     * @return bool
     */
    protected function unfreezeOrder($orinOrderId, $retUrl='') {
        if(empty($orinOrderId)) {
            return false;
        }
        
        $arrOrderInfo = Finance_Logic_Order::getOrderInfo($orinOrderId);
        $userId       = $arrOrderInfo['userId'];
        $transAmt     = $arrOrderInfo['amount'];
        $freezeTrxId  = $arrOrderInfo['freezeTrxId'];

        if(empty($freezeTrxId)){
            Base_Log::warn(array(
                'msg'     => '该订单没有冻结标记，无法解冻',
                'orderId' => $orinOrderId,
            ));
            return false;
        }

        //新建一个资金解冻订单入库
        $paramOrder = array(
            'userId'      => intval($userId),
            'type'        => Finance_Order_Type::USRUNFREEZE,
            'amount'      => floatval($transAmt),
            'status'      => Finance_Order_Status::INITIALIZE,
            'freezeTrxId' => $freezeTrxId,
            'comment'     => '资金解冻',
        );
        $orderInfo = Finance_Logic_Order::saveOrder($paramOrder);
        if(empty($orderInfo)){
            return false;
        }

        $orderDate = $orderInfo['orderDate'];
        $orderId   = $orderInfo['orderId'];
        $merCustId = $this->merCustId;
        $retUrl    = strval($retUrl);
        $bgRetUrl  = $this->webroot.'/finance/bgcall/unfreezeOrder';
        $merPriv   = strval($userId).'_'.strval($transAmt).'_'.strval($orderId);
        //调用汇付API进行解冻处理
        $ret       = $this->chinapnr->usrUnFreeze($merCustId,$orderId,$orderDate,
            $freezeTrxId,$retUrl,$bgRetUrl,$merPriv);
        $respCode  = isset($ret['RespCode']) ? $ret['RespCode'] : '';
        $respDesc  = isset($ret['RespDesc']) ? $ret['RespDesc'] : '';
        if($respCode !== '000') {           
            Base_Log::error(array(
                'respCode' => $respCode,
                'respDesc' => $respDesc,
                'ret'      => $ret,
            ));
            return false;
        }

        //将解冻订单状态更改为成功
        Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::SUCCESS, 
            $respCode, $respDesc, array('freezeTrxId' => $freezeTrxId));

        //保存快照
        Finance_Logic_Order::saveRecord($orderId, $userId, Finance_Order_Type::TENDERFREEZE,
            $transAmt, '解冻订单记录');

        return true;     
    }
    
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
        //充值订单入库
        $paramOrder = array(
            'userId'    => intval($userid),
            'type'      => Finance_Order_Type::NETSAVE,
            'amount'    => floatval($transAmt),
            'status'    => Finance_Order_Status::INITIALIZE,
            'comment'   => '充值订单',
        );
        $orderInfo = Finance_Logic_Order::saveOrder($paramOrder);
        if(empty($orderInfo)){
            return false;
        }
        $orderDate  = $orderInfo['orderDate'];
        $orderId    = $orderInfo['orderId'];
        $huifuid    = strval($huifuid);
        $openBankId = strval($openBankId);
        $gateBusiId = strval($gateBusiId);
        $dcFlag     = strval($dcFlag);
        $transAmt   = sprintf('%.2f',$transAmt);
        $bgRetUrl   = $this->webroot.'/finance/bgcall/netsave';
        $retUrl     = $this->webroot.'/finance/ret';
        $merPriv    = strval($userid);              
        //调用汇付API进行充值处理
        $this->chinapnr->netSave($this->merCustId, $huifuid, $orderId, $orderDate, $gateBusiId, $openBankId, 
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
        $bgRetUrl     = $this->webroot . '/finance/bgcall/addBidInfo';
        $merPriv      = '';
        $reqExt       = '';       
        $mixRet       = $this->chinapnr->addBidInfo($this->merCustId, $proId, $borrCustId, $borrTotAmt, 
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
    public function initiativeTender($loanId, $transAmt, $userid, $arrDetails) {
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
        //主动投标订单记录入表finance_order
        $paramOrder = array(
            'userId'  => intval($userid),           
            'type'    => Finance_Order_Type::TENDERFREEZE,
            'amount'  => floatval($transAmt),
            'status'  => Finance_Order_Status::INITIALIZE,
            'comment' => '投标冻结',   
        );
        $orderInfo = Finance_Logic_Order::saveOrder($paramOrder);
        if(empty($orderInfo)){
            return false;
        }
        $orderDate = $orderInfo['orderDate'];
        $orderId   = $orderInfo['orderId'];

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
        $freezeOrdId   = Finance_Logic_Order::genOrderId();
        $freezeOrdId   = strval($freezeOrdId);
        $retUrl        = $this->webroot.'/finance/ret';
        $bgRetUrl      = $this->webroot.'/finance/bgcall/initiativeTender';
        $userid        = strval($userid);
        $proId         = $loanId;
        $merPriv       = $userid.'_'.$proId; //将userid与proid作为私有域传入
        Base_Log::notice(array(
            'merCustId'       => $this->merCustId,
            'orderId'         => $orderId,
            'orderDate'       => $orderDate,
            'transAmt'        => $transAmt,
            'usrCustId'       => $usrCustId,
            'maxTenderRate'   => $maxTenderRate,
            'borrowerDetails' => $borrowerDetails,
            'isFreeze'        => $isFreeze,
            'freezeOrdId'     => $freezeOrdId,
            'retUrl'          => $retUrl,
            'bgRetUrl'        => $bgRetUrl,
            'merPriv'         => $merPriv,
        ));
        $this->chinapnr->initiativeTender($this->merCustId, $orderId, $orderDate, $transAmt, $usrCustId,
            $maxTenderRate, $borrowerDetails, $isFreeze, $freezeOrdId, $retUrl, $bgRetUrl, $merPriv
        );      
    }

    /**
     * 投标确认
     * 1. 根据汇付状态修改资金冻结订单状态
     * 2. invest_api发起确定投标
     * 3. 若确定投标失败则发起资金解冻
     */
    public function tenderConfirm($orderId, $userId, $loanId, $transAmt, $freezeTrxId,
        $bolSucc, $respCode, $respDesc){
        if(!$bolSucc) {
            //财务类投标冻结订单状态更新为处理失败
            Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::FAILED, 
                $respCode, $respDesc);
            return true;
        }

        //将投标冻结订单状态更改为成功
        Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::SUCCESS, 
            $respCode, $respDesc, array('freezeTrxId' => $freezeTrxId));

        //保存快照
        Finance_Logic_Order::saveRecord($orderId, $userId, Finance_Order_Type::TENDERFREEZE,
            $transAmt, '投标冻结记录');

        $bolRet = Invest_Api::doInvest($orderId, $userId, $loanId, $transAmt);
        if (!$bolRet) {
            Base_Log::notice(array(
                'msg'      => '投资确认失败，发起资金解冻',
                'orderId'  => $orderId,
                'userId'   => $userId,
                'loanId'   => $loanId,
                'transAmt' => $transAmt,
            ));
            $bolRet2 = $this->unfreezeOrder($orderId);
            if(!$bolRet2) {
                Base_Log::error(array(
                    'msg'      => '资金解冻失败',
                    'orderId'  => $orderId,
                    'userId'   => $userId,
                    'loanId'   => $loanId,
                    'transAmt' => $transAmt,
                ));
                return false;
            }
        }
        return true;  
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
        $inCustId  = $this->getHuifuid(intval($inUserId));
        $outCustId = $this->getHuifuid(intval($outUserId));    
       
        //打款订单记录入表finance_order
        $paramOrder = array(
            'userId'    => intval($outUserId),//投标人的uid
            'type'      => Finance_Order_Type::LOANS,
            'amount'    => floatval(sprintf('%.2f',$transAmt)),
            'status'    => Finance_Order_Status::INITIALIZE,
            'comment'   => '订单打款',
        );
        $orderInfo = Finance_Logic_Order::saveOrder($paramOrder);
        if(empty($orderInfo)){
            return false;
        }
        $orderDate  = $orderInfo['orderDate'];
        $orderId    = $orderInfo['orderId'];
        $transAmt   = sprintf('%.2f',$transAmt);

        //收取费用
        $arrFeeInfo= Finance_Fee::totalFeeInfo($loanId, $transAmt);
        $fee       = $arrFeeInfo['total_fee'];
        $riskFee   = $arrFeeInfo['risk_fee'];
        $servFee   = $arrFeeInfo['serv_fee'];
        
        $subOrderInfo  = Finance_Logic_Order::getOrderInfo($subOrdId);
        $subOrdId      = strval($subOrdId);
        $subOrdDate    = strval($subOrderInfo['orderDate']);
        $freezeTrxId   = strval($subOrderInfo['freezeTrxId']);
        $arrDivDetails = array(
            //风险金账户
            array(
                'DivCustId'=> Base_Config::getConfig('huifu.merCustId', CONF_PATH.'/huifu.ini'),
                'DivAcctId'=> Base_Config::getConfig('huifu.acct.SDT2', CONF_PATH.'/huifu.ini'),
                'DivAmt'   => sprintf('%.2f', $riskFee),
            ),  
            //专属账户
            array(
                'DivCustId'=> Base_Config::getConfig('huifu.merCustId', CONF_PATH.'/huifu.ini'),
                'DivAcctId'=> Base_Config::getConfig('huifu.acct.MDT1', CONF_PATH.'/huifu.ini'),
                'DivAmt'   => sprintf('%.2f', $servFee),
            ),          
        );
        $jsonDivDetails  = json_encode($arrDivDetails);
        $feeObjFlag      = 'I';//手续费向入款人收取               
        $isDefault       = 'Y';
        $isUnFreeze      = 'Y';
        $unFreezeOrdId   = Finance_Logic_Order::genOrderId();
        $bgRetUrl        = $this->webroot.'/finance/bgcall/loans';
        $merPriv         = strval($outUserId);//投标人的uid
        $reqExt          = array(
            'ProId'   => strval($loanId),
        );
        $reqExt          = json_encode($reqExt);
        
        Base_Log::debug(array(
            'msg'            => '准备单笔放款',
            'merCustId'      => $this->merCustId,
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

        $mixRet  = $this->chinapnr->loans($this->merCustId, $orderId, $orderDate, $outCustId, 
            $transAmt, $fee, $subOrdId, $subOrdDate, $inCustId, 
            $jsonDivDetails, $feeObjFlag, $isDefault, $isUnFreeze, $unFreezeOrdId, 
            $freezeTrxId, $bgRetUrl, $merPriv, $reqExt);

        Base_Log::debug(array(
            'msg'=>'满标打款接口', 
            'ret'=> $mixRet,
        ));

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
            Base_Log::error(array(
                'respCode' => $respCode,
                'respDesc' => $respDesc,
            ));
            return $objRst;
        }

        Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::SUCCESS);

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
    public function tenderCancel($transAmt,$userid,$orderId,$retUrl='') {
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
        $transAmt  = sprintf('%.2f',$transAmt); 
        $avlBal    = Finance_Api::getUserAvlBalance($userid);
        //打款订单记录入表finance_order
        $paramOrder = array(
            'orderId'   => intval($orderId),
            'orderDate' => intval($orderDate),
            'userId'    => intval($userid),//投标人的uid
            'type'      => Finance_Order_Type::TENDERCANCEL,
            'amount'    => floatval($transAmt),
            'avlBal'    => floatval($avlBal),
            'status'    => Finance_Order_Status::PROCESSING,
            'comment'   => '主动投标撤销处理中',
        );
        //投标撤销订单入库
        Finance_Logic_Order::saveOrder($paramOrder);
        $arrOrderInfo = Finance_Logic_Order::getOrderInfo($orderId);
        $orderDate    = $arrOrderInfo['orderDate'];
        $freezeTrxId  = $arrOrderInfo['freezeTrxId'];
                
        $orderId         = strval($orderId);
        $orderDate       = strval($orderDate);
        $transAmt        = $transAmt;
        $usrCustId       = strval($usrCustId);
        $isUnFreeze      = strval('Y');
        $unFreezeOrdId   = Finance_Logic_Order::genOrderId();
        $unFreezeOrderId = strval($unFreezeOrdId);
        $freezeTrxId     = strval($freezeTrxId);
        $retUrl          = strval($retUrl);
        $bgRetUrl        = $this->webroot.'/finance/bgcall/tendercancel';
        $merPriv         = strval($userid);     

        $this->chinapnr->tenderCancel($this->merCustId, $usrCustId, $orderId, $orderDate, $transAmt, $usrCustId,
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
        $transAmt = sprintf('%.2f',$transAmt);
        $huifuid   = $this->getHuifuid($userid);
        
        //取现订单订单记录入表finance_order
        $paramOrder = array(
            'userId'      => intval($userid),
            'type'        => Finance_Order_Type::CASH,
            'amount'      => floatval($transAmt),
            'status'      => Finance_Order_Status::INITIALIZE,
            'comment'     => '提现订单初始化',
        );
        $orderInfo = Finance_Logic_Order::saveOrder($paramOrder);
        if(empty($orderInfo)){
            return false;
        }
        $orderDate  = $orderInfo['orderDate'];
        $orderId    = $orderInfo['orderId'];
        $servFee    = '';
        $openAcctId = '';
        $retUrl     = '';
        $bgRetUrl   = $this->webroot.'/finance/bgcall/tixian';

        $merPriv    = strval($userid);
        $reqExt     = array(array(
            'FeeObjFlag' => 'U',
            'CashChl'    => 'GENERAL',
        ));
        $reqExt = json_encode($reqExt);
        $this->chinapnr->cash($this->merCustId, $orderId, $huifuid, $transAmt, $servFee, '', $openAcctId, 
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
        $transAmt  = sprintf('%.2f',$transAmt);
        $huifuid   = $this->getHuifuid(intval($inUserId));
        //还款订单订单记录入表finance_order
        $paramOrder = array(
            'userId'    => intval($outUserId),//还款人的uid
            'type'      => Finance_Order_Type::REPAYMENT,
            'amount'    => floatval($transAmt),
            'status'    => Finance_Order_Status::INITIALIZE,
            'comment'   => '订单还款',
        );
        $orderInfo = Finance_Logic_Order::saveOrder($paramOrder);
        if(empty($orderInfo)){
            return false;
        }
        $orderDate  = $orderInfo['orderDate'];
        $orderId    = $orderInfo['orderId'];
        
        $outCustId  = strval($this->getHuifuid(intval($outUserId)));//还款人的汇付ID
        $arrOrderInfo = Finance_Logic_Order::getOrderInfo(intval($subOrdId));
        $subOrdDate = strval($arrOrderInfo['orderDate']);
        $outAcctId  = '';
        $transAmt   = $transAmt;
        $fee        = '0.00';
        $divDetails = '';
        $feeObjFlag = 'O';//像还款人收取手续费
        $bgRetUrl   = $this->webroot.'/finance/bgcall/repayment';
        $merPriv    = strval($outUserId);//借款人的uid
        $reqExt     = array('ProId'=> strval($loanId));
        $reqExt     = json_encode($reqExt);     
        $ret        = $this->chinapnr->repayment($this->merCustId, $orderId, $orderDate, $outCustId, 
            $subOrdId, $subOrdDate, $outAcctId, $transAmt, $fee, $inCustId, $inAcctId,
            $divDetails, $feeObjFlag, $bgRetUrl, $merPriv, $reqExt);
        
    }
    
    /**
     * 自动扣款转账(商户用)
     * 
     * 
     */
    public function transfer($outUserId,$outAcctId,$transAmt,$inUserId,$type=Finance_Order_Type::TRANSFER) {
        if(!isset($outUserId) || !isset($outAcctId) || !isset($transAmt) || !isset($inUserId)) {
            Base_Log::error(array(
                'msg' => '请求参数错误',
            ));
            return false;
        }
        $transAmt  = sprintf('%.2f',$transAmt);
        $huifuid   = $this->getHuifuid(intval($inUserId));
        
        //还款订单订单记录入表finance_order
        $paramOrder = array(
            'userId'    => intval($inUserId),
            'type'      => $type,
            'amount'    => floatval($transAmt),
            'status'    => Finance_Order_Status::PROCESSING,
            'comment'   => '自动扣款转账(商户用)订单处理中',
        );

        $orderInfo = Finance_Logic_Order::saveOrder($paramOrder);
        if(empty($orderInfo)){
            return false;
        }
        $orderDate  = $orderInfo['orderDate'];
        $orderId    = $orderInfo['orderId'];
        
        $outCustId = strval($outUserId);
        $outAcctId = strval($outAcctId);
        $inCustId  = strval($huifuid);
        $inAcctId  = '';
        $retUrl    = '';
        $bgRetUrl  = $this->webroot.'/finance/bgcall/transfer';
        $type      = strval($type);
        $merPriv   = strval($orderDate).'_'.strval($inUserId).'_'.$type;    
        $ret       = $this->chinapnr->transfer($orderId, $outCustId, $outAcctId, 
            $transAmt, $inCustId, $inAcctId, $retUrl, $bgRetUrl, $merPriv);      
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
        $huifuid   = $this->getHuifuid(intval($userid));
        $paramOrder     = array(
            'orderId'   => intval($orderId),
            'orderDate' => intval($orderDate),
            'userId'    => intval($userid),//还款人的uid
            'type'      => Finance_Order_Type::MERCASH,
            'amount'    => floatval(sprintf('%.2f',$transAmt)),
            'status'    => Finance_Order_Status::PROCESSING,
            'comment'   => '商户代取现订单处理中',
        );
        $orderInfo = Finance_Logic_Order::saveOrder($paramOrder);
        if(empty($orderInfo)){
            return false;
        }
        $orderDate  = $orderInfo['orderDate'];
        $orderId    = $orderInfo['orderId'];
        
        $usrCustId     = strval($this->getHuifuid(intval($userid)));
        $transAmt      = sprintf('%.2f',$transAmt);
        $servFee       = '';
        $servFeeAcctId = '';
        $retUrl        = '';
        $bgRetUrl      = $this->webroot.'/finance/bgcall/merTixian';
        $remark        = '';
        $charSet       = '';
        $merPriv       = strval($userid).'_'.strval($orderDate);
        $reqExt        = '';
        $ret = $this->chinapnr->merCash($this->merCustId,$orderId,$usrCustId,$transAmt,
            $servFee = '',$servFeeAcctId = '',$retUrl = '',
            $bgRetUrl,$remark = '',$charSet = '',$merPriv = '',$reqExt = '');       
        return $ret;
    }

    /**
     * 对$_REQUEST进行urldecode
     * @param array
     * @return array || flase
     */   
    protected function arrUrlDec($arrParam) {
        $ret = array();
        foreach ($arrParam as $key => $value) {
            if(!is_array($value)) {
                $ret[$key] = urldecode($value);
            } else {
                $ret[$key] = $this->arrUrlDec($value);//对数组值进行递归解码
            }
        }
        return $ret;
    }
}
