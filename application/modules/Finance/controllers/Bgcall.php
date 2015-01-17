<?php 
/**
 * 汇付回调url入口类
 * 页面打印以下两种字符串
 * RECV_ORD_ID_TrxId
 * RECV_ORD_ID_OrderId
 * @author lilu
 */
class BgcallController extends Base_Controller_Page{
	
	private $huifuLogic;
	private $financeLogic;
	const VERSION_10 = "10";
	
	public function init(){
		$this->huifuLogic = Finance_Chinapnr_Logic::getInstance();//需要对返回值进行验签工作
		$this->financeLogic = new Finance_Logic_Base();
		Yaf_Dispatcher::getInstance()->disableView();
		$this->setNeedLogin(false);
		parent::init();
	}
	
	/**
	 * 汇付天下回调Action
	 * 用户开户回调URL
	 * 打印RECV_ORD_ID_TrxId
	 */
	public function userregistAction() {
		if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) ||
           !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['UsrId']) || !isset($_REQUEST['UsrCustId']) ||
           !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue']) ) {
            Base_Log(array(
                'msg' => '汇付返回参数错误',           
            ));
            return;
        }
		$trxId    = urldecode($_REQUEST['TrxId']);
		$userid   = urldecode($_REQUEST['MerPriv']);//取客户私用域中的userid
		$huifuid  = urldecode($_REQUEST['UsrCustId']);//用户汇付id入库
		$realName = urldecode($_REQUEST['UsrName']);//用户真实姓名入库
		$phone    = urldecode($_REQUEST['UsrMp']);//用户手机号码入库
		$email    = urldecode($_REQUEST['UsrEmail']);//用户email入库
		$idType   = urldecode($_REQUEST['IdType']);//证件类型入库
		$idNo     = urldecode($_REQUEST['IdNo']);//用户身份证号码入库
		$respCode = urldecode($_REQUEST['RespCode']); 
		$respDesc = urldecode($_REQUEST['RespDesc']);	
		if($respCode !== '000') {
			Base_Log::error(array(
			    'msg'      => $respDesc,
				'respCode' => $respCode,
			));
			return ;
		}			
		//验签
		/* $checkValue = $this->huifuLogic->sign(self::VERSION_10.$_REQUEST['CmdId'].$_REQUEST['MerCustId']
		.$_REQUEST['BgRetUrl'].$_REQUEST['RetUrl'].$_REQUEST['UsrId'].$_REQUEST['UsrName']
		.$_REQUEST['IdType'].$_REQUEST['IdNo'].$_REQUEST['UsrMp'].$_REQUEST['UsrEmail'].$_REQUEST['MerPriv']);
		
		$bolVeri = $this->huifuLogic->verify($checkValue,$_REQUEST['ChkValue']);
		if(!$bolVeri) {
			Base_Log::error(array(
				'msg' => '返回验签失败',
			));
		} */
		$userid = intval($userid);
		if(!User_Api::setHuifuId($userid,$huifuid)) {		
		    Base_Log::error( array(
			    'msg'       => '汇付id入库失败',
			    'userid:'   => $userid,
			    'usrCustId' => $huifuid,
			));
 		}
		if(!User_Api::setRealName($userid,$realName)) {
			Base_Log::error(array(
				'msg'    => '用户真实姓名入库失败',
				'userid' => $userid,
			));
 		}
		if(!User_Api::setEmail($userid,$email)) {
			Base_Log::error(array(
				'msg'    => '用户email入库失败',
				'userid' => $userid,
			));
 		}
		//证件信息入库，默认为身份证
		if(!User_Api::setCertificate($userid,$idNo)) {
			Base_Log::error(array(
				'msg'    => '用户证件信息入库失败',
				'userid' => $userid,
			));
 		}
		Base_Log::notice(array(
		    'trxId'    => $trxId,
		    'userid'   => $userid,
		    'huifuid'  => $huifuid,
		    'realName' => $realName,
		    'phone'    => $phone,
		    'email'    => $email,
		    'idTyp'    => $idTyp,
		    'idNo'     => $idNo,
		    'respCode' => $respCode,
		    'respDesc' => $respDesc,
		));
		//页面打印值,汇付检验
		print('RECV_ORD_ID_'.$trxId);
	}
	
	/**
	 * 汇付天下回调Action
	 * 企业开户回调URL
	 * 打印RECV_ORD_ID_TrxId
	 */
	public function corpRegistAction() {
		
	}
	/**
	 * 汇付天下回调Action
	 * 用户绑卡回调URL
	 * 打印RECV_ORD_ID_TrxId
	 */
	public function userbindcardAction() {
		if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['UsrCustId']) ||
		   !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue']) || !isset($_REQUEST['Version'])) {
		    Base_Log(array(
		    	'msg' => '汇付返回参数错误',
		    ));   	
		    return ;
		}
		$trxId     = $_REQUEST['TrxId'];
		$userid    = $_REQUEST['MerPriv'];//取客户私用域中的userid
		$usrCustId = $_REQUEST['UsrCustId'];
		$respCode  = $_REQUEST['RespCode'];
		$respDesc  = $_REQUEST['RespDesc'];
		if($respCode !== '000') {
			Base_Log::error(array(
			    'msg'      => $respDesc,
			    'respCode' => $respCode,
			));
			return ;
		}
		//验签！！
		
		
		Base_Log::notice($_REQUEST);
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
		    Base_Log::error(array(
		    	'msg' => '汇付返回参数错误'
		    ));	    
		    return;	
	    }
	    $orderId   = intval($_REQUEST['OrdId']);
	    $orderDate = intval($_REQUEST['OrdDate']);
	    $userid    = intval($_REQUEST['MerPriv']);//取客户私用域中的userid
	    $huifuid   = strval($_REQUEST['UsrCustId']); //用户的huifuid
	    $amount    = floatval($_REQUEST['TransAmt']);
	  
	    $bgret    = $this->financeLogic->balance($userid);
	    $balance  = floatval($bgret['userBg']['acctBal']);//用户余额
	    $total    = floatval($bgret['sysBg']['acctBal']);//系统余额
	    
	    $lastip   = Base_Util_Ip::getClientIp();
	    $respCode = $_REQUEST['RespCode'];
	    $respDesc = $_REQUEST['RespDesc'] ;

        if($respCode !== '000') {
        	Base_Log::error(array(
        	    'msg'      => $respDesc,
        	    'respCode' => $respCode,
        	));        	
        	//充值财务订单状态更新为处理失败         	
        	$this->financeLogic->payOrderUpdate($orderId,Finance_TypeStatus::ENDWITHFAIL,Finance_TypeStatus::NETSAVE);
        	return ;
        }
        //验签！！
        //充值财务订单状态更新为处理成功
        $this->financeLogic->payOrderUpdate($orderId,Finance_TypeStatus::ENDWITHSUCCESS,Finance_TypeStatus::NETSAVE);
        //充值财务记录入库
        $param = array(
            'orderId'   => $orderId,
        	'orderDate' => $orderDate,
        	'userId'    => $userid,
        	'type'      => Finance_TypeStatus::NETSAVE,
        	'amount'    => $amount,
        	'balance'   => $balance,
        	'total'     => $total,
        	'comment'   => '充值记录',
        	'ip'        => $lastip,        	
        );
        $this->financeLogic->payRecordEnterDB($param);
		Base::notice($_REQUEST);
		//页面打印
		print('RECV_ORD_ID_'.strval($orderId));
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
           	Base_Log::error(array(
           		'msg' => '汇付返回参数错误',
           	));
           	return;
        }
        $merPriv     = explode('_',$_REQUEST['MerPriv']);
        $userid      = intval($merPriv[0]);
        $proId       = intval($merPriv[1]);        
		$huifuid     = $_REQUEST['UsrCustId'];
		$orderId     = intval($_REQUEST['OrdId']);
		$orderDate   = intval($_REQUEST['OrdDate']);
		$amount      = floatval($_REQUEST['TransAmt']);
		$freezeOrdId = $_REQUEST['FreezeOrdId'];
		$freezeTrxId = $_REQUEST['FreezeTrxId'];
		$bgret       = $this->financeLogic->balance($userid);
		$balance     = floatval($bgret['userBg']['acctBal']);//用户余额
		$total       = floatval($bgret['sysBg']['acctBal']);//系统余额
		$lastip      = Base_Util_Ip::getClientIp();
		$respCode    = $_REQUEST['RespCode'];
		$respDesc    = $_REQUEST['RespDesc'];
		if($respCode !== '000') {
			Base_Log::error(array(
				'msg'     => $respDesc,
				'userid'  => $userid,
				'orderid' => $orderId,
			));
			//财务类主动投标订单状态更新为处理失败
			$this->financeLogic->payOrderUpdate($orderId, Finance_TypeStatus::ENDWITHFAIL,Finance_TypeStatus::INITIATIVETENDER);			
			return ;
		}
		//验签！！
		//将主动投标订单状态更改为成功
		$this->financeLogic->payOrderUpdate($orderId,Finance_TypeStatus::ENDWITHSUCCESS,Finance_TypeStatus::INITIATIVETENDER);
		//主动投标记录如表pay_record
		$param = array(
	        'orderId'     => $orderId,
			'orderDate'   => $orderDate,
			'userId'      => $userid,
			'type'        => Finance_TypeStatus::INITIATIVETENDER,
			'amount'      => $amount,
			'balance'     => $balance,
			'total'       => $total,
			'comment'     => '主动投标记录',
		);
		$this->financeLogic->payRecordEnterDB($param);
		//将投标记录插入至finance_tender中，状态为冻结中
		$paramTender = array(
			'orderId'     => $orderId, 
			'orderDate'   => $orderDate,
			'proId'       => $proId,/////////////////////////////////注意：此处的proId是什么含义比较合适
			'freezeTrxId' => $freezeTrxId,	
			'amount'      => $amount,
			'status'      => Finance_TypeStatus::FREEZING,
			'comment'     => "投标记录"
		);
		$this->financeLogic->payTenderEnterDB($paramTender);
		Base::notice($_REQUEST);
		print('RECV_ORD_ID_'.strval($orderId));		
    }
	
	/**
	 * 汇付回调Action
	 * 投标撤销回调URL
	 * 打印RECV_ORD_ID_OrderId
	 */
	public function tenderCancleAction() {

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
				));
				return ;
			}
			$cmdId      = urldecode($_REQUEST['CmdId']);
			$respCode   = urldecode($_REQUEST['RespCode']);
			$respDesc   = urldecode($_REQUEST['RespDesc']);
			$merCustId  = urldecode($_REQUEST['MerCustId']);
			$proId      = urldecode($_REQUEST['ProId']);
			$borrCustId = urldecode($_REQUEST['BorrCustId']);
			$borrTotAmt = urldecode($_REQUEST['BorrTotAmt']);
			$proArea    = urldecode($_REQUEST['ProArea']);
			$bgRetUrl   = urldecode($_REQUEST['BgRetUrl']);
			
			
			if($respCode !== '000') {
				Base_Log::error(array(
				    'msg'   => $respDesc,
				    'proId' => $proId,
				));
				return ;
			}
			Base_Log::notice(array(
				'cmdId'      => $cmdId,
				'respCode'   => $respCode,
				'respDesc'   => $respDesc,
				'merCustId'  => $merCustId,
				'proId'      => $proId,
				'borrCustId' => $borrCustId,
				'borrTotAmt' => $borrTotAmt,
				'proArea'    => $proArea,
				'bgRetUrl'   => $bgRetUrl,
			));
			print('RECV_ORD_ID_'.strval($proId));
	}
	
	/**
	 * 汇付回调Action
	 * 提现回调Action
	 * notice:异步对账
	 * 打印RECV_ORD_ID_OrderId
	 */
	public function cashAction() {
		if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) || 
		   !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['OrdId']) || !isset($_REQUEST['UsrCustId']) || 
		   !isset($_REQUEST['TransAmt']) || !isset($_REQUEST['FeeAmt']) || !isset($_REQUEST['FeeCustId']) ||
		   !isset($_REQUEST['FeeAcctId']) || !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue']) ) {
			Base_Log::error(array(
			    'msg' => '汇付返回参数错误',
			));		
			return;		
		}
		$userId    = intval($_REQUEST['MerPriv']);
		$huifuid   = $_REQUEST['UsrCustId'];
		$orderId   = intval($_REQUEST['OrdId']);
		$orderDate = intval($_REQUEST['OrdDate']);
		$transAmt  = floatval($_REQUEST['TransAmt']);
		$bgret     = $this->financeLogic->balance($userid);
		$balance   = floatval($bgret['userBg']['acctBal']);//用户余额
		$total     = floatval($bgret['sysBg']['acctBal']);//系统余额
		$lastip    = Base_Util_Ip::getClientIp();
		$respCode  = $_REQUEST['RespCode'];
		$respDesc  = $_REQUEST['RespDesc'];
		$respType  = $_REQUEST['RespType'];
		//同步异步返回
		if(!isset($_REQUEST['RespType'])) { 
			//同步异步返回处理中
			if($respCode === '999') {
                //finance_order状态更改为处理中
                $this->financeLogic->payOrderUpdate($orderId,Finance_TypeStatus::PROCESSING,Finance_TypeStatus::CASH);
			}  
			if($respCode === '000') {
				//对finance_order表进行状态更新，更新为处理成功
				$this->financeLogic->payOrderUpdate($orderId,Finance_TypeStatus::ENDWITHSUCCESS,Finance_TypeStatus::CASH);
				//插入记录至finance_record表
				$paramRecord = array(
					'orderId'   => $orderId,
					'orderDate' => $orderDate,
					'userId'    => $userId,
					'type'      => Finance_TypeStatus::CASH,
					'amount'    => $transAmt,
					'balance'   => $balance,
					'total'     => $total,
					'comment'   => '财务类充值记录',
					'ip'        => $lastip,
				);
				$this->financeLogic->payRecordEnterDB($paramRecord);
			}				
		}					
		//存在异步对账
		if(isset($_REQUEST['RespType'])) {
			$refunds = new Finance_List_Order();
			$filters = array('orderId' => $orderId);
			$refunds->setFilter($filters);
			$list = $refunds->toArray();
			$status = $list['list'][0]['status'];//finance_order表中状态
			//异步对账显示取现成功
			if($respType === '000') {
				if($status === '999') {
					//更新finance_order表状态为处理成功
					$this->financeLogic->payOrderUpdate($orderId,Finance_TypeStatus::ENDWITHSUCCESS,Finance_TypeStatus::CASH);
					//插入提现记录到finance_record表
					$paramRecord = array(
						'orderId'   => $orderId,
						'orderDate' => $orderDate,
						'userId'    => $userId,
						'type'      => Finance_TypeStatus::CASH,
						'amount'    => $transAmt,
						'balance'   => $balance,
						'total'     => $total,
						'comment'   => '财务类充值记录',
						'ip'        => $lastip,
					);
					$this->financeLogic->payRecordEnterDB($paramRecord);
				}
			}
			if($respType === '400') {
				if($status === '999') {
					//更改finance_order表状态为处理失败
					$this->financeLogic->payOrderUpdate($orderId,Finance_TypeStatus::ENDWITHFAIL,Finance_TypeStatus::CASH);
				}
				if($status === '000') {
					//首先将finance_order表状态更改为处理失败
					$this->financeLogic->payOrderUpdate($orderId,Finance_TypeStatus::ENDWITHFAIL,Finance_TypeStatus::CASH);
					//再将finance_record中对应的成功记录进行删除
					$this->financeLogic->payRecordDelete($orderId);									
				}
			}	
		} 
		Base_Log::notice($_REQUEST);
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
		    ));   	
		    return;
		}
		$userid    = intval($_REQUEST['MerPriv']);//投标人的uid
		$orderId   = intval($_REQUEST['OrdId']);
	    $orderDate = intval($_REQUEST['OrdDate']);
	    $subOrdId  = intval($_REQUEST['SubOrdId']);
	    $amount    = floatval($_REQUEST['TransAmt']);
	    $bgret     = $this->financeLogic->balance($userid);
	    $balance   = floatval($bgret['userBg']['acctBal']);//用户余额
	    $total     = floatval($bgret['sysBg']['acctBal']);//系统余额
	    $lastip    = Base_Util_Ip::getClientIp();
	    $respCode  = $_REQUEST['RespCode'];
	    $respDesc  = $_REQUEST['RespDesc'];
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
	    	$this->financeLogic->payOrderUpdate($orderId, Finance_TypeStatus::ENDWITHFAIL, Finance_TypeStatus::LOANS);
	    	//将finance_tender表状态更改为“打款失败”
	    	$this->financeLogic->payTenderUpdate($subOrdId, Finance_TypeStatus::PAYFAIDED);
            return ;
	    }
	    //验签！
	    //将finance_order表状态更新为“处理成功”
	    $this->financeLogic->payOrderUpdate($orderId, Finance_TypeStatus::ENDWITHSUCCESS, Finance_TypeStatus::LOANS);
	    //将finance_tender表状态更新为“已打款”
	    $this->financeLogic->payTenderUpdate($subOrdId, Finance_TypeStatus::HAVEPAYED);
	    //将打款记录插入至表finance_record中
	    $paramRecord = array(
	    	'orderId'   => $orderId,
	    	'orderDate' => $orderDate,
	    	'userId'    => $userid,
	    	'type'      => Finance_TypeStatus::CASH,
	    	'amount'    => $amount,
	    	'balance'   => $balance,
	    	'total'     => $total,
	    	'comment'   => '财务类满标打款记录',
	    	'ip'        => $lastip,
	    );
	    $this->financeLogic->payRecordEnterDB($paramRecord);
	    Base_Log::notice($_REQUEST);
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
            ));
	        return ;
	    }
	    $userid    = intval($_REQUEST['MerPriv']);//借款人的uid
	    $orderId   = intval($_REQUEST['OrdId']);
	    $orderDate = intval($_REQUEST['OrdDate']);
	    $subOrdId  = intval($_REQUEST['SubOrdId']);
	    $amount    = floatval($_REQUEST['TransAmt']);
	    $bgret     = $this->financeLogic->balance($userid);
	    $balance   = floatval($bgret['userBg']['acctBal']);//用户余额
	    $total     = floatval($bgret['sysBg']['acctBal']);//系统余额
	    $lastip    = Base_Util_Ip::getClientIp();
	    $respCode  = $_REQUEST['RespCode'];
	    $respDesc  = $_REQUEST['RespDesc'];
	    if($respCode !=='000') {
	    	Base_Log::error(array(
	    		'msg'       => $respDesc,
	    		'userid'    => $userid,
	    		'orderId'   => $orderId,
	    		'orderDate' => $orderDate,
	    		'respCode'  => $respCode,	    		
	    	));
	    	//将finance_order表状态更改为“处理失败”
	    	$this->financeLogic->payOrderUpdate($orderId, Finance_TypeStatus::ENDWITHFAIL, Finance_TypeStatus::REPAYMENT);
	    	return ;
	    }	    
	    //将finance_order表状态更改为“处理成功”
	    $this->financeLogic->payOrderUpdate($orderId, Finance_TypeStatus::ENDWITHSUCCESS, Finance_TypeStatus::REPAYMENT);
	    //插入还款记录至表finance_record
	    $paramRecord = array(
	    	'orderId'   => $orderId,
	    	'orderDate' => $orderDate,
	    	'userId'    => $userid,
	    	'type'      => Finance_TypeStatus::REPAYMENT,
	    	'amount'    => $amount,
	    	'balance'   => $balance,
	    	'total'     => $total,
	    	'comment'   => '财务类还款记录',
	    	'ip'        => $lastip,
	    );
	    $this->financeLogic->payRecordEnterDB($paramRecord);
	    Base_Log::notice($_REQUEST);
	    print('RECV_ORD_ID_'.strval($orderId));		
	}
	
	/**
	 * 汇付回调Action
	 * 自动扣款转账(商户用)回调
	 */
	public function transferAction() {
	    if(!isset($_REQUEST['Version']) || !isset($_REQUEST['CmdId']) || !isset($_REQUEST['OrdId']) || 
	       !isset($_REQUEST['OutCustId']) || !isset($_REQUEST['OutAcctId']) || !isset($_REQUEST['TransAmt']) ||
	       !isset($_REQUEST['InCustId']) || !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue']) ) {
	        Base_Log::error(array(
	        	'msg' => '汇付返回参数错误',
	        ));   	
	        return ;
	    }
	    $userid    = intval($_REQUEST['OutCustId']);
		$orderId   = intval($_REQUEST['OrdId']);
		$orderDate = intval($_REQUEST['MerPriv']);
		$amount    = floatval($_REQUEST['TransAmt']);
		$bgret     = $this->financeLogic->balance($userid);
		$balance   = floatval($bgret['userBg']['acctBal']);//用户余额
		$total     = floatval($bgret['sysBg']['acctBal']);//系统余额
		$lastip    = Base_Util_Ip::getClientIp();
		$respCode  = $_REQUEST['RespCode'];
		$respDesc  = $_REQUEST['RespDesc'];
		if($respCode !== '000') {
			Base_Log::error(array(
				'msg'       => $respDesc,
				'userid'    => $userid,
				'orderId'   => $orderId,
				'orderDate' => $orderDate,
			));
			//将finance_order表状态更改为“处理失败”
			$this->financeLogic->payOrderUpdate($userid,Finance_TypeStatus::ENDWITHFAIL,Finance_TypeStatus::TRANSFER);
			return ;
		}
		//将finance_order表状态更改为“处理成功”
		$this->financeLogic->payOrderUpdate($userid,Finance_TypeStatus::ENDWITHSUCCESS,Finance_TypeStatus::TRANSFER);
		//将该条记录插入至表finance_record中
		$paramRecord = array(
			'orderId'   => $orderId,
			'orderDate' => $orderDate,
			'userId'    => $userid,
			'type'      => Finance_TypeStatus::TRANSFER,
			'amount'    => $amount,
			'balance'   => $balance,
			'total'     => $total,
			'comment'   => '财务类还款记录',
			'ip'        => $lastip,
		);
		$this->financeLogic->payRecordEnterDB($paramRecord);
		Base_Log::notice($_REQUEST);
		print('RECV_ORD_ID_'.strval($orderId));
		
		
	}
}
