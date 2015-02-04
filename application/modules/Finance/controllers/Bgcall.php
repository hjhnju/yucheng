<?php 
/**
 * 汇付回调url入口Action类
 * 页面打印以下两种字符串
 * RECV_ORD_ID_TrxId
 * RECV_ORD_ID_OrderId
 * @author lilu
 * 
 */
class BgcallController extends Base_Controller_Page {
        
    public function init(){
        Yaf_Dispatcher::getInstance()->disableView();
        $this->setNeedLogin(false);
        parent::init();
        
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

    /**
     * 验签
     * @param  $arrFields 字段 array('field1', 'field2')
     * @param  $arrParams array('field' => 'value')
     */
    protected function verify($arrFields, $arrParams, $sign) {
        $chinapnr  = Finance_Chinapnr_Client::getInstance();
        $originStr = $chinapnr->getSignContent($arrParams, $arrFields);
        return $chinapnr->verify($originStr, $sign);
    }

    /**
     * 汇付天下回调Action
     * 资金解冻BgUrl回调webroot/Finance/bgcall/cancelTenderBG
     * 打印 RECV_ORD_ID_OrdId
     */
    public function canceltenderbgAction() {
    	if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) ||
    	   !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['OrdId']) || !isset($_REQUEST['OrdDate']) ||
    	   !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue']) ) {
    		$logParam        = $_REQUEST;
    		$logParam['msg'] = '汇付返回参数错误';
    	   	Base_Log($logParam);
    		return;
    	}   	
    	//SDK中已经验签，此处不再验签了
    	
        $_merPriv      = $_REQUEST['MerPriv'];
        $merPriv       = explode('_',$_merPriv);
        $userid        = intval($merPriv[0]);
        $transAmt      = floatval($merPriv[1]);
        $tenderOrderId = intval($merPriv[2]);
        
        $orderId       = intval($_REQUEST['OrdId']);
        $orderDate     = intval($_REQUEST['OrdDate']);
        $trxId         = $_REQUEST['TrxId'];
        $respCode      = $_REQUEST['RespCode'];
        $respDesc      = $_REQUEST['RespDesc'];
    	if($respCode !== '000') {
    		Base_Log::error(array(
    			'msg' => $respDesc,
    			'ret' => $_REQUEST,
    		));
    		//资金解冻订单状态更新为“处理失败”
    		return Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::FAILED, 
                $respCode, $respDesc);
    	}
    	//资金解冻订单状态更新为“处理成功”
    	Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::SUCCESS, $respCode, $respDesc);    	
    	
    	//投标订单状态更新为“资金已解冻”
    	// Finance_Logic_Order::updateOrderStatus($tenderOrderId, Finance_Order_Status::FREEZED, $respCode, $respDesc);
    	
    	//快照
    	Finance_Logic_Order::saveRecord($orderId, $userid, Finance_Order_Type::USRUNFREEZE,
            $transAmt, '资金解冻记录');

    	Base_Log::notice($_REQUEST);
    	//页面打印
    	$orderId = strval($orderId);
    	print('RECV_ORD_ID_'.$orderId);    	
    }
    /**
     * 汇付天下回调Action
     * 用户开户BgUrl回调webroot/Finance/bgcall/userregist
     * 打印RECV_ORD_ID_TrxId
     * 
     */
    public function userregistAction() {
        if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) ||
           !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['UsrId']) || !isset($_REQUEST['UsrCustId']) ||
           !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue']) ) {
            $logParam        = $_REQUEST;
            $logParam['msg'] = '汇付返回参数错误';
            Base_Log($logParam);
            return;
        }
        $retParam = $this->arrUrlDec($_REQUEST);
        //验签处理
        $signKeys = array("CmdId", "RespCode", "MerCustId", "UsrId", "UsrCustId", 
            "BgRetUrl", "TrxId", "RetUrl", "MerPriv");
        $bolVerify = $this->verify($signKeys, $retParam, $retParam['ChkValue']);
        if(!$bolVerify) {
            Base_Log::error(array(
                'msg'   => '验签错误',
                'CmdId' => $retParam,
            ));
            return;
        }              
        $trxId    = $retParam['TrxId'];
        $userid   = $retParam['MerPriv'];//取客户私用域中的userid
        $huifuid  = $retParam['UsrCustId'];//用户汇付id入库
        $realName = $retParam['UsrName'];//用户真实姓名入库
        $phone    = $retParam['UsrMp'];//用户手机号码入库
        $email    = $retParam['UsrEmail'];//用户email入库
        $idType   = $retParam['IdType'];//证件类型入库
        $idNo     = $retParam['IdNo'];//用户身份证号码入库
        $respCode = $retParam['RespCode']; 
        $respDesc = $retParam['RespDesc'];  
        
        //汇付返回非成功时的处理
        if($respCode !== '000') {
            $logParam        = $retParam;
            $logParam['msg'] = $respDesc;
            Base_Log::error($logParam);
            return ;
        }                   
        $userid   = intval($userid);
        $huifuid  = strval($huifuid);
        $email    = strval($email);
        $realName = strval($realName);
        if(!User_Api::setHuifuId($userid,$huifuid)) {       
            Base_Log::error(array(
                'msg'       => '汇付id入库失败',
                'userid:'   => $userid,
                'usrCustId' => $huifuid,
            ));
        }
        if(!User_Api::setRealName($userid,$realName)) {
            Base_Log::error(array(
                'msg'      => '用户真实姓名入库失败',
                'userid'   => $userid,
                'realName' => $realName,
            ));
        }
        /*
         if(!User_Api::setEmail($userid,$email)) {
            Base_Log::error(array(
                'msg'    => '用户email入库失败',
                'userid' => $userid,
                'email'  => $email,
            ));
        } 
        */
        //证件信息入库，默认为身份证
        if(!User_Api::setCertificate($userid,$idNo)) {
            Base_Log::error(array(
                'msg'    => '用户证件信息入库失败',
                'userid' => $userid,
                'idNo'   => $idNo,
            ));
        }
        Base_Log::notice($retParam);
        //页面打印值,汇付检验
        $trxId = strval($trxId);
        print('RECV_ORD_ID_'.$trxId);
    }
    
    /**
     * 汇付天下回调Action
     * 用户绑卡回调webroot/Finance/bgcall/userbindcard
     * 打印RECV_ORD_ID_TrxId
     * 
     */
    public function userbindcardAction() {
        if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['UsrCustId']) ||
           !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue']) || !isset($_REQUEST['RespCode']) ||
           !isset($_REQUEST['RespDesc'])) {
            $logParam = $_REQUEST;
            $logParam['msg'] = '汇付返回参数错误';
            Base_Log::error($logParam);     
            return ;
        }
        //对$_REQUSET参数进行递归urldecode
        $retParam = $this->arrUrlDec($_REQUEST);
        //验签处理
        $signKeys = array("CmdId", "RespCode", "MerCustId", "OpenAcctId", "OpenBankId", "UsrCustId", "TrxId", "BgRetUrl", "MerPriv");
        $bolVerify = $this->verify($signKeys, $retParam, $retParam['ChkValue']);
        if(!$bolVerify) {
            Base_Log::error(array(
                'msg'    => '验签错误',
                'params' => $retParam,
            ));
            return;
        }
        
        $trxId     = $retParam['TrxId'];
        $userid    = $retParam['MerPriv'];//取客户私用域中的userid
        $usrCustId = $retParam['UsrCustId'];
        $respCode  = $retParam['RespCode'];
        $respDesc  = $retParam['RespDesc'];
        //汇付返回不成功时的处理
        if($respCode !== '000') {
            $logParam = $retParam;
            $logParam['msg'] = $respDesc;
            Base_Log::error($logParam);
            return;
        }   
        Base_Log::notice($retParam);
        $trxId = strval($trxId);
        print('RECV_ORD_ID_'.$trxId);
    }
    
    /**
     * 汇付天下回调Action
     * 网银充值回调URL
     * 打印RECV_ORD_ID_OrderId
     */
    public function netsaveAction() {
           if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) || 
           !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['UsrCustId']) || !isset($_REQUEST['OrdId']) || 
           !isset($_REQUEST['OrdDate']) || !isset($_REQUEST['TransAmt']) || !isset($_REQUEST['BgRetUrl']) ||
           !isset($_REQUEST['ChkValue']) || !isset($_REQUEST['FeeAmt']) || !isset($_REQUEST['FeeCustId']) ||
           !isset($_REQUEST['FeeAcctId'])) {
            $logParam        = $_REQUEST;
            $logParam['msg'] = '汇付返回参数错误';
            Base_Log::error($logParam);     
            return; 
        }
        $retParam  = $this->arrUrlDec($_REQUEST);      
        //验签处理
        $signKeys  = array("CmdId", "RespCode", "MerCustId", "UsrCustId", "OrdId", "OrdDate", "TransAmt", "TrxId", "RetUrl","BgRetUrl","MerPriv");
        $bolVerify = $this->verify($signKeys, $retParam, $retParam['ChkValue']);
        if(!$bolVerify) {
            Base_Log::error(array(
                'msg'   => '验签错误',
                'CmdId' => $retParam['CmdId'],
            ));
            return;
        }
        
        $trxId     = $retParam['TrxId'];
        $orderId   = intval($retParam['OrdId']);
        $orderDate = intval($retParam['OrdDate']);
        $userid    = intval($retParam['MerPriv']);//取客户私用域中的userid
        $huifuid   = strval($retParam['UsrCustId']); //用户的huifuid
        $transAmt    = floatval($retParam['TransAmt']);
      
        $arrBal   = Finance_Api::getUserBalance($userid);
        $balance  = $arrBal['AcctBal'];//用户余额
        $avlBal   = $arrBal['AvlBal'];//用户可用余额
        $total    = Finance_Api::getPlatformBalance();//系统余额
        
        $lastip   = Base_Util_Ip::getClientIp();
        $respCode = $retParam['RespCode'];
        $respDesc = $retParam['RespDesc'];
        if($respCode !== '000') {           
            $logParam = $retParam;
            $logParam['msg'] = $respDesc;
            Base_Log::error($logParam);         
            //充值财务订单状态更新为处理失败           
            Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::FAILED, 
                $respCode, $respDesc);
            return ;
        }        
        
        //充值财务订单状态更新为处理成功
        Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::SUCCESS, 
            $respCode, $respDesc);      
        //充值财务记录入库
        Finance_Logic_Order::saveRecord($orderId, $userid, Finance_Order_Type::NETSAVE,
            $transAmt, '充值记录');
        /* if(!Msg_Api::sendmsg(0,$userid,3,"充值消息")) {
            Base_Log::error(array(
                'msg' => "充值消息发送失败",
                'from' => 0,
                'to' => $userid,
            ));
        }  */       
        Base_Log::notice($retParam);
        //页面打印
        $trxId = strval($trxId);
        print('RECV_ORD_ID_'.$trxId);
    }
    
    /**
     * 汇付回调Action
     * 主动投标回调URL
     * 打印RECV_ORD_ID_OrderId
     */
    public function initiativeTenderAction() {
        if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) || 
           !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['OrdId']) || !isset($_REQUEST['OrdDate']) || 
           !isset($_REQUEST['TransAmt']) || !isset($_REQUEST['UsrCustId']) || !isset($_REQUEST['IsFreeze']) || 
           !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue'])) {
            $logParam = array();
            $logParam = $_REQUEST;
            $logParam['msg'] = '汇付返回参数错误';
            Base_Log::error($logParam);
            return;
        }
       
        $retParam = $this->arrUrlDec($_REQUEST);        
        //验签处理
        $signKeys = array("CmdId", "RespCode", "MerCustId", "OrdId", "OrdDate", "TransAmt", "UsrCustId", "TrxId", "IsFreeze",
            "FreezeOrdId","FreezeTrxId","RetUrl","BgRetUrl","MerPriv","RespExt");
        $bolVerify = $this->verify($signKeys, $retParam, $retParam['ChkValue']);
        if(!$bolVerify) {
            Base_Log::error(array(
                'msg'   => '验签错误',
                'params' => $retParam,
            ));
            return;
        }
        $merPriv     = explode('_',$_REQUEST['MerPriv']);
        $userid      = intval($merPriv[0]);
        $proId       = intval($merPriv[1]);        
        $huifuid     = $retParam['UsrCustId'];
        $orderId     = intval($retParam['OrdId']);
        
        $orderDate   = intval($retParam['OrdDate']);
        $transAmt      = floatval($retParam['TransAmt']);
        $freezeOrdId = $retParam['FreezeOrdId'];
        $freezeTrxId = $retParam['FreezeTrxId'];

        $arrBal   = Finance_Api::getUserBalance($userid);
        $balance  = $arrBal['AcctBal'];//用户余额
        $avlBal   = $arrBal['AvlBal'];//用户可用余额
        $total    = Finance_Api::getPlatformBalance();//系统余额
        
        $lastip      = Base_Util_Ip::getClientIp();
        $respCode    = $retParam['RespCode'];
        $respDesc    = $retParam['RespDesc'];

        if($respCode !== '000') {
            $logParam = $retParam;
            $logParam['msg'] = $respDesc;
            Base_Log::error($logParam);
            //财务类投标冻结订单状态更新为处理失败
            Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::FAILED, 
                $respCode, $respDesc);
            return ;
        }
        //将投标冻结订单状态更改为成功
        Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::SUCCESS, 
            $respCode, $respDesc);
        //投标冻结后保存快照
        Finance_Logic_Order::saveRecord($orderId, $userid, Finance_Order_Type::TENDERFREEZE,
            $transAmt, '投标冻结记录');

        print('RECV_ORD_ID_'.strval($orderId));     
    }
    
    /**
     * 汇付回调Action
     * 投标撤销回调URL
     * 打印RECV_ORD_ID_OrderId
     */
    public function tenderCancelAction() {
        if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['OrdId']) || 
           !isset($_REQUEST['OrdDate']) || !isset($_REQUEST['TransAmt']) || !isset($_REQUEST['UsrCustId']) || 
           !isset($_REQUEST['IsUnFreeze']) || !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['RespCode']) || 
           !isset($_REQUEST['RespDesc']) || !isset($_REQUEST['ChkValue'])) {
            $logParam = $_REQUEST;
            $logParam['msg'] = '汇付返回参数错误';
            Base_Log::error($logParam);
            return;
        }
        $retParam = $this->arrUrlDec($_REQUEST);
        //验签处理
        $signKeys = array("CmdId", "RespCode", "MerCustId", "OrdId", "OrdDate", "TransAmt", "UsrCustId", "IsUnFreeze", "UnFreezeOrdId",
            "FreezeTrxId","RetUrl","BgRetUrl","MerPriv","RespExt");
        $bolVerify = $this->verify($signKeys, $retParam, $retParam['ChkValue']);
        if(!$bolVerify) {
            Base_Log::error(array(
                'msg' => '验签错误',
                'CmdId' => $retParam,
            ));
            return;
        }
        
        $userid    = intval($retParam['MerPriv']);
        $orderId   = intval($retParam['OrdId']);
        $orderDate = intval($retParam['OrdDate']);
        $transAmt  = floatval($retParam['TransAmt']);
        $huifuid   = $retParam['UsrCustId'];
        $respCode  = $retParam['RespCode'];
        $respDesc  = $retParam['RespDesc'];

        $arrBal   = Finance_Api::getUserBalance($userid);
        $balance  = $arrBal['AcctBal'];//用户余额
        $avlBal   = $arrBal['AvlBal'];//用户可用余额
        $total    = Finance_Api::getPlatformBalance();//系统余额

        if($respCode !== '000') {
            $logParam        = $retParam;
            $logParam['msg'] = $respDesc;
            Base_Log::error($logParam);
            //将finance_order表状态更改为“处理失败”
            Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::FAILED, 
                $respCode, $respDesc);
            return;
        }
        //将finance_order表状态更改为“处理成功”
        Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::SUCCESS, 
            $respCode, $respDesc);
        //记录入表finance_record
        Finance_Logic_Order::payRecordEnterDB($orderId, $userid, Finance_Order_Type::TENDERCANCEL, 
            $transAmt, '投标撤销记录');
        Base_Log::notice($retParam);
        print('RECV_ORD_ID_'.strval($orderId));
    }
    /**
     * 汇付回调Action
     * 标信息录入回调Action
     * 打印RECV_ORD_ID_ProId
     */
    public function addBidInfoAction() {
        if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) ||
            !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['ProId']) || !isset($_REQUEST['BorrCustId']) ||
            !isset($_REQUEST['BorrTotAmt']) || !isset($_REQUEST['ProArea']) || !isset($_REQUEST['BgRetUrl']) ||
            !isset($_REQUEST['ChkValue'])) {
            Base_Log::error(array(
                'msg' => '汇付返回参数错误',
                'args' => $_REQUEST,
            ));
            return;
        }
        $retParam = $this->arrUrlDec($_REQUEST);
        //验签处理SDK中验过了
        $cmdId      = $retParam['CmdId'];
        $respCode   = $retParam['RespCode'];
        $respDesc   = $retParam['RespDesc'];
        $merCustId  = $retParam['MerCustId'];
        $proId      = $retParam['ProId'];
        $borrCustId = $retParam['BorrCustId'];
        $borrTotAmt = $retParam['BorrTotAmt'];
        $proArea    = $retParam['ProArea'];
        $bgRetUrl   = $retParam['BgRetUrl'];            
        if($respCode !== '000') {
            $logParam        = $retParam;
            $logParam['msg'] = $respDesc;
            Base_Log::error($logParam);
            return ;
        }
        Base_Log::notice($retParam);
        print('RECV_ORD_ID_'.strval($proId));
    }
        
    /**
     * 汇付回调Action
     * 提现回调Action
     * notice:异步对账
     * 打印RECV_ORD_ID_OrderId
     */
    public function tixianAction() {
        if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) || 
           !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['OrdId']) || !isset($_REQUEST['UsrCustId']) || 
           !isset($_REQUEST['TransAmt']) || !isset($_REQUEST['FeeAmt']) || !isset($_REQUEST['FeeCustId']) ||
           !isset($_REQUEST['FeeAcctId']) || !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue']) ) {
            Base_Log::error(array(
                'msg' => '汇付返回参数错误',
                'args' => $_REQUEST,
            ));     
            return;     
        }
        $retParam  = $this->arrUrlDec($_REQUEST);     
        $userId    = intval($retParam['MerPriv']);
        $huifuid   = $retParam['UsrCustId'];
        $orderId   = intval($retParam['OrdId']);
        $orderDate = intval($retParam['OrdDate']);
        $transAmt  = floatval($retParam['TransAmt']);
        
        $arrBal   = Finance_Api::getUserBalance($userid);
        $balance  = $arrBal['AcctBal'];//用户余额
        $avlBal   = $arrBal['AvlBal'];//用户可用余额
        $total    = Finance_Api::getPlatformBalance();//系统余额
        
        $lastip    = Base_Util_Ip::getClientIp();
        $respCode  = $retParam['RespCode'];
        $respDesc  = $retParam['RespDesc'];
        $respType  = $retParam['RespType'];
        //同步异步返回
        if(!isset($_REQUEST['RespType'])) { 
            //验签处理
            $signKeys = array("CmdId", "RespCode", "MerCustId", "OrdId", "UsrCustId", "TransAmt", "OpenAcctId", "OpenBankId", "FeeAmt",
                "FeeCustId","FeeAcctId","ServFee","ServFeeAcctId","RetUrl","BgRetUrl","MerPriv","RespExt");
            $bolVerify = $this->verify($signKeys, $retParam, $retParam['ChkValue']);
            if(!$bolVerify) {
                Base_Log::error(array(
                    'msg'    => '验签错误',
                    'params' => $retParam,
                ));
                return;
            }   
            //同步异步返回处理中
            if($respCode === '999') {
                //finance_order状态更改为“处理中”
                Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::PROCESSING, 
                    $respCode, $respDesc);
            }  
            if($respCode === '000') {
                //对finance_order表进行状态更新，更新为“处理成功”
                Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::SUCCESS, 
                    $respCode, $respDesc);
                //插入记录至finance_record表
                Finance_Logic_Order::saveRecord($orderId, $userid, Finance_Order_Type::CASH, 
                    $transAmt, '充值记录');
            }               
        }                   
        //存在异步对账
        if(isset($_REQUEST['RespType'])) {
            //验签处理
            $signKeys = array("RespType", "RespCode", "MerCustId", "OrdId", "UsrCustId", "TransAmt", "OpenAcctId", "OpenBankId", "RetUrl", "BgRetUrl","MerPriv","RespExt");
            $bolVerify = $this->verify($signKeys, $retParam, $retParam['ChkValue']);
            if(!$bolVerify) {
                Base_Log::error(array(
                    'msg'   => '验签错误',
                    'CmdId' => $retParam['CmdId'],
                ));
                return;
            }
            $refunds = new Finance_List_Order();
            $filters = array('orderId' => $orderId);
            $refunds->setFilter($filters);
            $list   = $refunds->toArray();
            $status = $list['list'][0]['status'];//finance_order表中状态
            //异步对账显示取现成功
            if($respType === '000') {
                if($status === '999') {
                    //更新finance_order表状态为“处理成功”
                    Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::SUCCESS,
                        $respCode, $respDesc);
                    //插入提现记录到finance_record表
                    Finance_Logic_Order::saveRecord($orderId, $userid, Finance_Order_Type::CASH,
                        $transAmt, '充值记录');
                }
            }
            if($respType === '400') {
                if($status === '999') {
                    //更改finance_order表状态为“处理失败”
                    Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::FAILED, 
                        $respCode, $respDesc);
                }
                if($status === '000') {
                    //首先将finance_order表状态更改为“处理失败”
                    Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::FAILED, 
                        $respCode, $respDesc);
                    //再将finance_record中对应的成功记录进行删除
                    Finance_Logic_Order::payRecordDelete($orderId);                                 
                }
            }   
        } 
        Base_Log::notice($retParam);
        print('RECV_ORD_ID_'.strval($orderId));
    }
    
    /**
     * 汇付天下回调
     * 满标打款
     */
    public function loansAction() {

        if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) ||
           !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['OrdId']) || !isset($_REQUEST['OrdDate']) ||
           !isset($_REQUEST['OutCustId']) || !isset($_REQUEST['TransAmt']) || !isset($_REQUEST['Fee']) || 
           !isset($_REQUEST['InCustId']) || !isset($_REQUEST['SubOrdId']) || !isset($_REQUEST['SubOrdDate']) ||
           !isset($_REQUEST['FeeObjFlag']) || !isset($_REQUEST['IsDefault']) || !isset($_REQUEST['IsUnFreeze']) ||
           !isset($_REQUEST['UnFreezeOrdId']) || !isset($_REQUEST['FreezeTrxId']) || !isset($_REQUEST['BgRetUrl']) ||
           !isset($_REQUEST['ChkValue'])) {
            Base_Log::error(array(
                'msg' => '汇付返回参数错误',
                'req' => $_REQUEST,
            ));                 
            return;
        }       
        $retParam = $this->arrUrlDec($_REQUEST);
        //验签处理SDK中验过了
        $userid    = intval($retParam['MerPriv']);//投标人的uid
        $orderId   = intval($retParam['OrdId']);
        $orderDate = intval($retParam['OrdDate']);
        $subOrdId  = intval($retParam['SubOrdId']);
        $amount    = floatval($retParam['TransAmt']);
        $arrBal    = Finance_Api::getUserBalance($userid);
        $balance   = $arrBal['AcctBal'];//用户余额
        $avlBal    = $arrBal['AvlBal'];//用户可用余额
        $total     = Finance_Api::getPlatformBalance();//系统余额
        
        $lastip    = Base_Util_Ip::getClientIp();
        $respCode  = strval($retParam['RespCode']);
        $respDesc  = strval($retParam['RespDesc']);
        
        //汇付返回错误
        if($respCode !== '000') {
             Base_Log::error(array(
                'msg'       => $respDesc,
                'respCode'  => $respCode,
                'userid'    => $userid,
                'orderId'   => $orderId,
                'orderDate' => $orderDate,
            ));
            //将finance_order表状态更改为“处理失败”
            return Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::FAILED,
                $respCode, $respDesc);
        }
        //将finance_order表状态更新为“处理成功”
        Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::SUCCESS,
            $respCode, $respDesc);
        //将打款记录插入至表finance_record中
        Finance_Logic_Order::saveRecord($orderId, $userid, Finance_Order_Type::CASH,
            $amount, '财务类满标打款记录');
        Base_Log::notice($retParam);
        print('RECV_ORD_ID_'.strval($orderId));
    }
    
    /**
     * 汇付回调接口
     * 还款回调
     */
    public function repaymentAction() {
        if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) || 
           !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['OrdId']) || !isset($_REQUEST['OrdDate']) || 
           !isset($_REQUEST['OutCustId']) || !isset($_REQUEST['SubOrdId']) || !isset($_REQUEST['SubOrdDate']) ||
           !isset($_REQUEST['OutAcctId']) || !isset($_REQUEST['TransAmt']) || !isset($_REQUEST['Fee']) ||
           !isset($_REQUEST['InCustId']) || !isset($_REQUEST['InAcctId']) || !isset($_REQUEST['BgRetUrl']) ||
           !isset($_REQUEST['MerPriv']) || !isset($_REQUEST['ChkValue'])) {
            Base_Log::error(array(
                'msg' => '汇付返回参数错误',
                'req' => $_REQUEST,
            ));
            return;
        }
        $retParam = $this->arrUrlDec($_REQUEST);
        //验签处理SDK中验过了       
        $userid    = intval($retParam['MerPriv']);//借款人的uid
        $orderId   = intval($retParam['OrdId']);
        $orderDate = intval($retParam['OrdDate']);
        $subOrdId  = intval($retParam['SubOrdId']);
        $amount    = floatval($retParam['TransAmt']);
        $arrBal   = Finance_Api::getUserBalance($userid);
        $balance  = $arrBal['AcctBal'];//用户余额
        $avlBal   = $arrBal['AvlBal'];//用户可用余额
        $total    = Finance_Api::getPlatformBalance();//系统余额
        
        $lastip    = Base_Util_Ip::getClientIp();
        $respCode  = $retParam['RespCode'];
        $respDesc  = $retParam['RespDesc'];
        if($respCode !=='000') {
            Base_Log::error(array(
                'msg'       => $respDesc,
                'userid'    => $userid,
                'orderId'   => $orderId,
                'orderDate' => $orderDate,
                'respCode'  => $respCode,               
            ));
            //将finance_order表状态更改为“处理失败”
            Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::FAILED,
                 $respCode, $respDesc);
            return;
        }       
        //将finance_order表状态更改为“处理成功”
        Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::SUCCESS, 
            $respCode, $respDesc);
        //插入还款记录至表finance_record
        Finance_Logic_Order::saveRecord($orderId, $userid, Finance_Order_Type::REPAYMENT,
            $amount, '财务类还款记录');
        Base_Log::notice($retParam);
        print('RECV_ORD_ID_'.strval($orderId));     
    }
    
    /**
     * 汇付回调Action
     * 自动扣款转账(商户用)回调
     */
    public function transferAction() {
        if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['OrdId']) || 
           !isset($_REQUEST['OutCustId']) || !isset($_REQUEST['OutAcctId']) || !isset($_REQUEST['TransAmt']) ||
           !isset($_REQUEST['InCustId']) || !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue']) ) {
            $logParam        = $_REQUEST;
            $logParam['msg'] = '汇付返回参数错误';
            Base_Log::error($logParam);
            return;
        }      
       
        //验签处理SDK中验过了       
        $merPriv = explode('_',$_REQUEST['MerPriv']);   
        Base_Log::debug(array(
            $merPriv,
        ));
        $userid    = $merPriv[1];
        $orderId   = intval($_REQUEST['OrdId']);
        $orderDate = $merPriv[0];
        $amount    = floatval($_REQUEST['TransAmt']);
        $type      = $merPriv[2];
        
        $arrBal   = Finance_Api::getUserBalance($userid);
        $balance  = $arrBal['AcctBal'];//用户余额
        $avlBal   = $arrBal['AvlBal'];//用户可用余额
        $total    = Finance_Api::getPlatformBalance();//系统余额

        $lastip    = Base_Util_Ip::getClientIp();
        $respCode  = $_REQUEST['RespCode'];
        $respDesc  = $_REQUEST['RespDesc'];     
        if($respCode !== '000') {
            $logParam = $_REQUEST;
            $logParam['msg'] = $respDesc;
            Base_Log::error($logParam);
            //将finance_order表状态更改为“处理失败”
            Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::FAILED, $respCode, $respDesc);
            return ;
        }
        //将finance_order表状态更改为“处理成功”
        Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::SUCCESS, $respCode, $respDesc);
        //将该条记录插入至表finance_record中
        Finance_Logic_Order::saveRecord($orderId, $userid, Finance_Order_Type::TRANSFER,
            $amount, '自动扣款转账');
        Base_Log::notice($_REQUEST);
        print('RECV_ORD_ID_'.strval($orderId));     
    }
    
    /**
     * 汇付回调Action
     * 商户待取现回调
     * 打印RECV_ORD_ID_OrdId
     */
    public function merTixianAction() {
        if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) || 
           !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['OrdId']) || !isset($_REQUEST['UsrCustId']) || 
           !isset($_REQUEST['TransAmt']) || !isset($_REQUEST['FeeAmt']) || !isset($_REQUEST['FeeCustId']) || 
           !isset($_REQUEST['FeeAcctId']) || !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue'])) {
            $logParam = $_REQUEST;
            $logParam['msg'] = '请求参数错误';
            Base_Log::error($logParam);     
        }
        $retParam = $this->arrUrlDec($_REQUEST);
        //验签处理SDK中验过了
        
        $orderId   = $_REQUEST['OrdId'];
        $_merPriv  = $retParam['MerPriv'];
        $merPriv   = explode('_',$_merPriv);      
        $userid    = $merPriv[0];
        $orderDate = $merPriv[1];
        $transAmt  = $retParam['TransAmt'];
        $respCode  = $retParam['RespCode'];
        $respDesc  = $retParam['RespDesc'];      
        $lastip    = Base_Util_Ip::getClientIp();
        
        $arrBal  = Finance_Api::getUserBalance($userid);
        $balance = $arrBal['AcctBal'];//用户余额
        $avlBal  = $arrBal['AvlBal'];//用户可用余额
        $total   = Finance_Api::getPlatformBalance();//系统余额

        if($respCode !== '000') {
            $logParam        = $retParam;
            $logParam['msg'] = $respDesc;
            Base_Log::error($logParam);
            //将finance_order表状态字段更改为“处理失败”
            Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::FAILED, $respCode, $respDesc);
            return ;
        }
        //将finance_order表状态字段更改为“处理成功”
        Finance_Logic_Order::updateOrderStatus($orderId, Finance_Order_Status::SUCCESS, $respCode, $respDesc);
        //将记录如表finance_record
        Finance_Logic_Order::saveRecord($orderId, $userid, Finance_Order_Type::MERCASH,
            $amount, '商户代取现记录');
        Base_Log::notice($retParam);
        print('RECV_ORD_ID_'.strval($orderId));
    }
    
    /**
     * 汇付天下回调Action
     * 企业开户回调webroot/finance/bgcall/corpRegist
     * 打印RECV_ORD_ID_TrxId
     */
    public function corpRegistAction() {
        if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) || 
           !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['UsrId']) || !isset($_REQUEST['AuditStat']) ||
           !isset($_REQUEST['AuditStat']) || !isset($_REQUEST['TrxId'])  || !isset($_REQUEST['BgRetUrl']) || 
           !isset($_REQUEST['ChkValue'])) {
            $logParam = $_REQUEST;
            $logParam['msg'] = '汇付返回参数错误';
            Base_Log::error($logParam);
            return;
        }
        $retParam  = $this->arrUrlDec($_REQUEST);
        $signKeys  = array("CmdId","RespCode","MerCustId","UsrId","UsrName","UsrCustId","AuditStat", "TrxId",
        "OpenBankId","CardId", "RetUrl","BgRetUrl","RespExt");
        $bolVerify = $this->verify($signKeys, $retParam, $retParam['ChkValue']);
        if(!$bolVerify) {
            Base_Log::error(array(
                'msg' => '验签错误',
                'req' => $retParam,
            ));
            return;
        }
        $userid  = $retParam['MerPriv'];
        $huifuid = $retParam['UsrCustId'];
        //将企业汇付Id入库
        if(!empty($huifuid)) {
            if(!User_Api::setHuifuId($userid,$huifuid)) {
                Base_Log::error(array(
                    'msg'       => '企业汇付id入库失败',
                    'userid:'   => $userid,
                    'usrCustId' => $huifuid,
                ));
            }
        }       
        $trxId = strval($retParam['TrxId']);
        Base_Log::notice($retParam);
        print('RECV_ORD_ID_'.$trxId);
    }
}
