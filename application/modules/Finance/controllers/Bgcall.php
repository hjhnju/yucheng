<?php 
/**
 * 汇付回调url入口Action类
 * 页面打印以下两种字符串
 * RECV_ORD_ID_TrxId
 * RECV_ORD_ID_OrderId
 * @author lilu
 */
class BgcallController extends Base_Controller_Page{
	
	CONST PRIVATEKEY = '35333034333000000000000000000000000000003030303031303234303031333937353132303138AC16BABD43DF71C18A10EF2284D4CECE8CDA746066A4273D489866D28D873CC02908C3AD55068F0FCABD4C2D07DBDA314968B81CFED57F7A3512F0659D62CB16C754A8B0BB8F8CC2FD4A78C8375536B68F88FC31069AA91E11117450BA68448CC258FB7A0B462730FBC49D4DBC87693466662FF7022D75834E4C0CD26B439BF370AD20057458000BA6FB1CEDFD1C6CDB1037A86CFD1CDE2D463A453756B1E34858D121C8F8562778D3861AAA997372052256C1D65B5D492B582F84FF047BABA2448EC3B52C45427C80E2C173ED735807DCFBF13349016D2DFFC7C814E15A9C5991D5E240D54A3BB8529631460D4D2E38A6E052BACD3F9DB14097B567C8798E7E00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000100015B9E57F039CB9D5F60D568FAF139F0B1B9C0E2899C0A9B3C682EBB89676F74B708898BA4DB04B0F879710C7DFBE83C4FA51C14BC394729282948554D06680C9740C2DF9FEDD8C2A99C1280A5B79C8A80A7955C38941D0D3926F090B78CA95B6E1BEF8B8CF7F1CE097BBAF675C99664DFD75A195021026CE9E551E986B51CBF71DFE3778C4181688C8A543477ACFCD4527FA56DEFA8E08E9ABCA5FC0B96B29BD251234F2ABAC5BA4FA39E834FF2D47C2A2A845830ECEB006463B1A3BFC2077B363DE6DA81C2E5F440FB359FFAB62FD373905AE60D16CCBAA5F7375BD9B8B5DD0D68A12B33C9E54406D68DC316C33CE4036F2559A8449B23FBA54546BE15A756EFFD4D8F5ABDC3A4BBC2E8BB38D3BF95D947C3BA49763F83EA1EEB9BC9AC33E6CAC804BFB45F24DA38CA9BB79FBD7A65DE282B268EE80C4EF808B228CD201CC761E23B4D7734652642';
	CONST PUBLICKEY = '393939393939000000000000000000000000000030303031F60CB7B659222AEB12654EBB05C43CC2408154D57EED62D8F46FB946815A631D4A708DBA667673F69A279E371CA16064296643CBB0785E18FFDA84DB065DCA42D48349D3839B6723B604AC0BF19994147E56C6EFFD7BF6CF37E766D58E6CC6EF023B2A03E00D85829C51550012B1ABBF5710D6D9BED03A69BEC144D73EE2154F000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001000133D851D515306218';
	
	private $huifuLogic;
	private $financeLogic;
	private $scureTool;
	
	public function init(){
		$this->scureTool = new Finance_Chinapnr_SecureTool(self::PRIVATEKEY,self::PUBLICKEY);
		$this->huifuLogic = Finance_Chinapnr_Logic::getInstance();
		$this->financeLogic = new Finance_Logic_Base();
		Yaf_Dispatcher::getInstance()->disableView();
		$this->setNeedLogin(false);
		parent::init();
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
           	$logParam = $_REQUEST;
           	$logParam['msg'] = '汇付返回参数错误';
            Base_Log($logParam);
            return;
        }
        $retParam = $this->financeLogic->arrUrlDec($_REQUEST);
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
			$logParam = $retParam;
			$logParam['msg'] = $respDesc;
			Base_Log::error($logParam);
			return ;
		}			
		//验签处理
		$userid = intval($userid);
		$huifuid = strval($huifuid);
		$email = strval($email);
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
		if(!User_Api::setEmail($userid,$email)) {
			Base_Log::error(array(
				'msg'    => '用户email入库失败',
				'userid' => $userid,
				'email'  => $email,
			));
 		}
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
		$retParam = $this->financeLogic->arrUrlDec($_REQUEST);
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
			return ;
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
		   	$logParam = $_REQUEST;
		   	$logParam['msg'] = '汇付返回参数错误';
		    Base_Log::error($logParam);	    
		    return;	
	    }
	    $retParam  = $this->financeLogic->arrUrlDec($_REQUEST);
	    $trxId     = $retParam['TrxId'];
	    $orderId   = intval($retParam['OrdId']);
	    $orderDate = intval($retParam['OrdDate']);
	    $userid    = intval($retParam['MerPriv']);//取客户私用域中的userid
	    $huifuid   = strval($retParam['UsrCustId']); //用户的huifuid
	    $amount    = floatval($retParam['TransAmt']);
	  
	    $bgret    = $this->financeLogic->balance($userid);
	    $balance  = floatval(str_replace(',', '', $bgret['userBg']['acctBal']));//用户余额
	    $total    = floatval(str_replace(',', '', $bgret['sysBg']['acctBal']));//系统余额
	    
	    $lastip   = Base_Util_Ip::getClientIp();
	    $respCode = $retParam['RespCode'];
	    $respDesc = $retParam['RespDesc'];

        if($respCode !== '000') {
        	$logParam = $retParam;
        	$logParam['msg'] = $respDesc;
        	Base_Log::error($logParam);        	
        	//充值财务订单状态更新为处理失败         	
        	$this->financeLogic->payOrderUpdate($orderId, Finance_TypeStatus::ENDWITHFAIL, Finance_TypeStatus::NETSAVE, 
        		$respCode, $respDesc);
        	return ;
        }
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
		Base_Log::notice($_REQUEST);
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
        $retParam = $this->financeLogic->arrUrlDec($_REQUEST);
        $merPriv = explode('_',$retParam['MerPriv']);
        $userid = intval($merPriv[0]);
        $proId = intval($merPriv[1]);        
		$huifuid = $retParam['UsrCustId'];
		$orderId = intval($retParam['OrdId']);
		
		$orderDate = intval($retParam['OrdDate']);
		$amount = floatval($retParam['TransAmt']);
		$freezeOrdId = $retParam['FreezeOrdId'];
		$freezeTrxId = $retParam['FreezeTrxId'];
		
	    $bgret = $this->financeLogic->balance($userid);
	    $balance = floatval(str_replace(',', '', $bgret['userBg']['acctBal']));//用户余额
	    $total = floatval(str_replace(',', '', $bgret['sysBg']['acctBal']));//系统余额
	    
		$lastip = Base_Util_Ip::getClientIp();
		$respCode = $retParam['RespCode'];
		$respDesc = $retParam['RespDesc'];

		if($respCode !== '000') {
			$logParam = $retParam;
			$logParam['msg'] = $respDesc;
			Base_Log::error($logParam);
			//财务类主动投标订单状态更新为处理失败
			$this->financeLogic->payOrderUpdate($orderId, Finance_TypeStatus::ENDWITHFAIL, Finance_TypeStatus::INITIATIVETENDER, 
           	    $respCode, $respDesc);			
			return ;
		}
		//将主动投标订单状态更改为成功
		$this->financeLogic->payOrderUpdate($orderId,Finance_TypeStatus::ENDWITHSUCCESS,Finance_TypeStatus::INITIATIVETENDER);
		//主动投标记录如表pay_record
		$param = array(
	        'orderId'   => $orderId,
			'orderDate' => $orderDate,
			'userId'    => $userid,
			'type'      => Finance_TypeStatus::INITIATIVETENDER,
			'amount'    => $amount,
			'balance'   => $balance,
			'total'     => $total,
			'comment'   => '主动投标记录',
		);
		$this->financeLogic->payRecordEnterDB($param);
		//将投标记录插入至finance_tender中，状态为冻结中
		$paramTender = array(
			'orderId'     => $orderId, 
			'orderDate'   => $orderDate,
			'proId'       => $proId,
			'freezeTrxId' => $freezeTrxId,	
			'amount'      => $amount,
			'status'      => Finance_TypeStatus::FREEZING,
			'comment'     => "投标冻结中"
		);
		$this->financeLogic->payTenderEnterDB($paramTender);
		Base_Log::notice($retParam);
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
		$retParam = $this->financeLogic->arrUrlDec($_REQUEST);
		$userid = intval($retParam['MerPriv']);
		$orderId = intval($retParam['OrdId']);
		$orderDate = intval($retParam['OrdDate']);
		$transAmt = floatval($retParam['TransAmt']);
		$huifuid = $retParam['UsrCustId'];
		$respCode = $retParam['RespCode'];
		$respDesc = $retParam['RespDesc'];
		$bgret = $this->financeLogic->balance($userid);
		$balance = floatval(str_replace(',', '', $bgret['userBg']['acctBal']));//用户余额
		$total = floatval(str_replace(',', '', $bgret['sysBg']['acctBal']));//系统余额
		if($respCode !== '000') {
			$logParam = $retParam;
			$logParam['msg'] = $respDesc;
			Base_Log::error($logParam);
			//将finance_order表状态更改为“处理失败”
			$this->financeLogic->payOrderUpdate($orderId, Finance_TypeStatus::ENDWITHFAIL,Finance_TypeStatus::TENDERCANCEL, 
		        $respCode, $respDesc);
			return;
		}
		//将finance_order表状态更改为“处理成功”
		$this->financeLogic->payOrderUpdate($orderId, Finance_TypeStatus::ENDWITHSUCCESS,Finance_TypeStatus::TENDERCANCEL);
		//记录入表finance_record
		$param = array(
			'orderId'   => $orderId,
			'orderDate' => $orderDate,
			'userId'    => $userid,
			'type'      => Finance_TypeStatus::TENDERCANCEL,
			'amount'    => $transAmt,
			'balance'   => $balance,
			'total'     => $total,
			'comment'   => '投标撤销记录',
		);
		$this->financeLogic->payRecordEnterDB($param);
		//将finance_tender表状态更改为“投标已撤销”
		$this->financeLogic->payTenderUpdate($orderId,Finance_TypeStatus::CANCELD);
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
			));
			return ;
		}
		$retParam = $this->financeLogic->arrUrlDec($_REQUEST);
		
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
			$logParam = $retParam;
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
		$retParam = $this->financeLogic->arrUrlDec($_REQUEST);
		
		$userId    = intval($retParam['MerPriv']);
		$huifuid   = $retParam['UsrCustId'];
		$orderId   = intval($retParam['OrdId']);
		$orderDate = intval($retParam['OrdDate']);
		$transAmt  = floatval($retParam['TransAmt']);
		
		$bgret = $this->financeLogic->balance($userid);
		$balance = floatval(str_replace(',', '', $bgret['userBg']['acctBal']));//用户余额
		$total = floatval(str_replace(',', '', $bgret['sysBg']['acctBal']));//系统余额
		
		$lastip    = Base_Util_Ip::getClientIp();
		$respCode  = $retParam['RespCode'];
		$respDesc  = $retParam['RespDesc'];
		$respType  = $retParam['RespType'];
		//同步异步返回
		if(!isset($_REQUEST['RespType'])) { 
			//同步异步返回处理中
			if($respCode === '999') {
                //finance_order状态更改为“处理中”
                $this->financeLogic->payOrderUpdate($orderId,Finance_TypeStatus::PROCESSING,Finance_TypeStatus::CASH);
			}  
			if($respCode === '000') {
				//对finance_order表进行状态更新，更新为“处理成功”
				$this->financeLogic->payOrderUpdate($orderId,Finance_TypeStatus::ENDWITHSUCCESS,Finance_TypeStatus::CASH, 
			        $respCode, $respDesc);
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
					//更新finance_order表状态为“处理成功”
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
					//更改finance_order表状态为“处理失败”
					$this->financeLogic->payOrderUpdate($orderId,Finance_TypeStatus::ENDWITHFAIL,Finance_TypeStatus::CASH, 
					    $respCode, $respDesc);
				}
				if($status === '000') {
					//首先将finance_order表状态更改为“处理失败”
					$this->financeLogic->payOrderUpdate($orderId,Finance_TypeStatus::ENDWITHFAIL,Finance_TypeStatus::CASH, 
					    $respCode, $respDesc);
					//再将finance_record中对应的成功记录进行删除
					$this->financeLogic->payRecordDelete($orderId);									
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
		    ));   			    
		    return;
		}		
		$retParam = $this->financeLogic->arrUrlDec($_REQUEST);
		
		$userid    = intval($retParam['MerPriv']);//投标人的uid
		$orderId   = intval($retParam['OrdId']);
	    $orderDate = intval($retParam['OrdDate']);
	    $subOrdId  = intval($retParam['SubOrdId']);
	    $amount    = floatval($retParam['TransAmt']);
	    
        $bgret = $this->financeLogic->balance($userid);
		$balance = floatval(str_replace(',', '', $bgret['userBg']['acctBal']));//用户余额
		$total = floatval(str_replace(',', '', $bgret['sysBg']['acctBal']));//系统余额
		
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
	    	$this->financeLogic->payOrderUpdate($orderId, Finance_TypeStatus::ENDWITHFAIL, Finance_TypeStatus::LOANS,
	    	    $respCode, $respDesc);
	    	//将finance_tender表状态更改为“打款失败”
	    	$this->financeLogic->payTenderUpdate($subOrdId, Finance_TypeStatus::PAYFAIDED);
            return ;
	    }
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
            ));
	        return ;
	    }
	    $retParam = $this->financeLogic->arrUrlDec($_REQUEST);
	     
	    $userid    = intval($retParam['MerPriv']);//借款人的uid
	    $orderId   = intval($retParam['OrdId']);
	    $orderDate = intval($retParam['OrdDate']);
	    $subOrdId  = intval($retParam['SubOrdId']);
	    $amount    = floatval($retParam['TransAmt']);
	    
	    $bgret = $this->financeLogic->balance($userid);
		$balance = floatval(str_replace(',', '', $bgret['userBg']['acctBal']));//用户余额
		$total = floatval(str_replace(',', '', $bgret['sysBg']['acctBal']));//系统余额
	    
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
	    	$this->financeLogic->payOrderUpdate($orderId, Finance_TypeStatus::ENDWITHFAIL, Finance_TypeStatus::REPAYMENT, 
	    	    $respCode, $respDesc);
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
	       	$logParam = $_REQUEST;
	       	$logParam['msg'] = '汇付返回参数错误';
            Base_Log::error($logParam); 
	    }
	    $retParam = $this->financeLogic->arrUrlDec($_REQUEST);
	     
	    $userid    = intval($retParam['OutCustId']);
		$orderId   = intval($retParam['OrdId']);
		$orderDate = intval($retParam['MerPriv']);
		$amount    = floatval($retParam['TransAmt']);
		
		$bgret = $this->financeLogic->balance($userid);
		$balance = floatval(str_replace(',', '', $bgret['userBg']['acctBal']));//用户余额
		$total = floatval(str_replace(',', '', $bgret['sysBg']['acctBal']));//系统余额
		
		$lastip    = Base_Util_Ip::getClientIp();
		$respCode  = $retParam['RespCode'];
		$respDesc  = $retParam['RespDesc'];
		if($respCode !== '000') {
			$logParam = $retParam;
			$logParam['msg'] = $respDesc;
			Base_Log::error($logParam);
			//将finance_order表状态更改为“处理失败”
			$this->financeLogic->payOrderUpdate($userid,Finance_TypeStatus::ENDWITHFAIL,Finance_TypeStatus::TRANSFER,
			    $respCode, $respDesc);
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
			'comment'   => '财务类自动扣款转账(商户用)记录',
			'ip'        => $lastip,
		);
		$this->financeLogic->payRecordEnterDB($paramRecord);
		Base_Log::notice($retParam);
		print('RECV_ORD_ID_'.strval($orderId));		
	}
	
	/**
	 * 汇付回调Action
	 * 商户待取现回调
	 * 打印RECV_ORD_ID_OrdId
	 */
	public function merCashAction() {
		if(!isset($_REQUEST['CmdId']) || !isset($_REQUEST['RespCode']) || !isset($_REQUEST['RespDesc']) || 
		   !isset($_REQUEST['MerCustId']) || !isset($_REQUEST['OrdId']) || !isset($_REQUEST['UsrCustId']) || 
		   !isset($_REQUEST['TransAmt']) || !isset($_REQUEST['FeeAmt']) || !isset($_REQUEST['FeeCustId']) || 
		   !isset($_REQUEST['FeeAcctId']) || !isset($_REQUEST['BgRetUrl']) || !isset($_REQUEST['ChkValue'])) {
		   	$logParam = $_REQUEST;
		   	$logParam['msg'] = '请求参数错误';
		    Base_Log::error($logParam);   	
		}
		$retParam = $this->financeLogic->arrUrlDec($_REQUEST);
		$orderId = $_REQUEST['OrdId'];
		$_merPriv = $retParam['MerPriv'];
		$merPriv = explode('_',$_merPriv);		
		$userid = $merPriv[0];
		$orderDate = $merPriv[1];
		$transAmt = $retParam['TransAmt'];
		$respCode = $retParam['RespCode'];
		$respDesc = $retParam['RespDesc'];		
		$lastip    = Base_Util_Ip::getClientIp();
		
		$bgret = $this->financeLogic->balance($userid);
		$balance = floatval(str_replace(',', '', $bgret['userBg']['acctBal']));//用户余额
		$total = floatval(str_replace(',', '', $bgret['sysBg']['acctBal']));//系统余额
		
		if($respCode !== '000') {
			$logParam = $retParam;
			$logParam['msg'] = $respDesc;
			Base_Log::error($logParam);
			//将finance_order表状态字段更改为“处理失败”
			$this->financeLogic->payOrderUpdate($userid,Finance_TypeStatus::ENDWITHFAIL,Finance_TypeStatus::MERCASH,
			    $respCode, $respDesc);
			return ;
		}
		//将记录如表finance_record
		$paramRecord = array(
			'orderId'   => $orderId,
			'orderDate' => $orderDate,
		    'userId'    => $userid,
			'type'      => Finance_TypeStatus::MERCASH,
			'amount'    => $amount,
			'balance'   => $balance,
			'total'     => $total,
			'comment'   => '商户代取现记录',
			'ip'        => $lastip,
		);
		$this->financeLogic->payRecordEnterDB($paramRecord);
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
		}
		$retParam = $this->financeLogic->arrUrlDec($_REQUEST);
		
		$userid = $retParam['MerPriv'];
		$huifuid = $retParam['UsrCustId'];
		//将企业汇付Id入库
		if(!User_Api::setHuifuId($userid,$huifuid)) {
			Base_Log::error( array(
		    'msg'       => '企业汇付id入库失败',
			'userid:'   => $userid,
			'usrCustId' => $huifuid,
			));
		}
		$trxId = strval($retParam['TrxId']);
		Base_Log::notice($retParam);
		print('RECV_ORD_ID_'.$trxId);
	}
	

}
