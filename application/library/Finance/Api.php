<?php
/**
 * 财务模块封装汇付逻辑实现类
 * @author lilu
 */
class Finance_Api {
	
	/**
	 * 商户子账户信息查询 Finance_Api::queryAccts
	 * @return 返回array格式 {'status'=>,'statusInfo'=>,'data'=>}
	 * status=0请求成功  返回正常数据
	 * status=Finance_RetCode::REQUEST_API_ERROR 请求汇付API接口失败
	 * data=array(
	 * 
	 * )
	 */
	public static function queryAccts() {
		$ret = new Base_Result();
		$queryLogic = new Finance_Logic_Query();
		$return = $queryLogic->queryAccts();
		
		if($return === false) {			
			$ret->status = Finance_RetCode::REQUEST_API_ERROR;
			$ret->data = array();
			$ret->statusInfo = Finance_RetCode::getMsg($ret->status);
			
			$logParam = array();
			$logParam['msg'] = Finance_RetCode::getMsg(Finance_RetCode::REQUEST_API_ERROR);
			Base_Log::error($logParam);
			
			return $ret->format();
		} 
		if($return['RespCode'] !== '000') {
			$ret->status = $return['RespCode'];
			$ret->data = array();
			$ret->statusInfo = $return['RespDesc'];
			
			$logParam = array();
			$logParam['msg'] = $return['RespDesc']; 
			$logParam = array_merge($logParam,$return);
			Base_Log::error($logParam);
			
			return $ret->format();
		}
		$ret->status = $return['RespCode'];
		$ret->data = $return;
		Base_Log::notice($return);		
		return $ret->format();
	}
	
	/**
	 * 余额查询接口 Finance_Api::queryBalanceBg
	 * @param String $UserCustId 用户客户号(require)
	 * 
	 * @return API 返回array格式 {'status'=>,'statusInfo'=>,'data'=>}
	 * status=0 请求成功 返回正常数据
	 * status=Base_RetCode::PARAM_ERROR 参数错误
	 * status=Finance_RetCode::REQUEST_API_ERROR 请求汇付API接口失败
	 * data=array(
	 *    'avlBal' 可用余额         
     *    'acctBal' 账户余额         
     *    'frzBal' 冻结余额   
	 * )
	 */	
	public static function queryBalanceBg($userCustId) {
		if(!isset($userCustId) || empty($userCustId)) {
			Base_Log::error(array(
				'msg' => '请求参数错误',
				'userCustId' => $userCustId,
			));
			return false;
		}
		$ret = new Base_Result();
		$queryLogic = new Finance_Logic_Query();
		$return = $queryLogic->queryBalanceBg($userCustId);
		if($return === false) {
			$ret->status = Finance_RetCode::REQUEST_API_ERROR;
			$ret->data = array(
			    'avlBal'  => '0.00',
				'acctBal' => '0.00',
				'frzBal'  => '0.00',
			);
			$ret->statusInfo = Finance_RetCode::getMsg($ret->status);
			
			$logParam = array();
			$logParam['msg'] = Finance_RetCode::getMsg(Finance_RetCode::REQUEST_API_ERROR);
			$logParam['userCustId'] = $userCustId;
			Base_Log::error($logParam);
						
			return $ret->format();
		}
		if($return['RespCode'] !== '000') {
			$ret->status = $return['RespCode'];
			$ret->data = array(
				'avlBal'  => '0.00',
				'acctBal' => '0.00',
				'frzBal'  => '0.00',
			);
			$ret->statusInfo = $return['RespDesc'];
			
			$logParam = array();
			$logParam['msg'] = $return['RespDesc']; 
			$logParam = array_merge($logParam,$return);
			Base_Log::error($logParam);
			
			return $ret->format();
		} 
		$ret->status = $return['RespCode'];
		$ret->data = array(
			'avlBal'  => $return['AvlBal'],
			'acctBal' => $return['AcctBal'],
			'frzBal'  => $return['FrzBal'],
		);		
		Base_Log::notice($return);		
		return $ret->format();		
	}
	
	/**
	 * 获取用户余额Finance_Api::getUserBalance
	 * @param int userid
	 * @return array || false
	 */
	public static function getUserBalance($userid) {
		if(!isset($userid) || $userid <= 0) {
			Base_Log::error(array(
				'msg'    => '请求参数错误',
				'userid' => $userid,
			));
			return false;
		}
		$baseLogic = new Finance_Logic_Base();
		$userid = intval($userid);
		$huifuid = $baseLogic->getHuifuid($userid);
		$ret = Finance_Api::queryBalanceBg($huifuid);
		return $ret;		
	}
	/**
	 * 添加标的信息借口 Finance_Api::addBidInfo
	 * @param int proId 标的唯一标示
	 * @param int borrUserId 借款人uid
	 * @param float borrTotAmt 借款总金额
	 * @param float yearRate 年利率
	 * @param int retType 还款方式   1等额本息  2等额本金  3按期付息，到期还本   4一次性还款   99其他
	 * @param int bidStartDate 时间戳投标开始时间
	 * @param int bidEndDate 时间戳投标截止时间
	 * @param float retAmt 总还款金额
	 * @param int retDate 应还款日期
	 * @param int proArea 项目所在地
	 * @return boolean
	 * 
	 */
	public static function addBidInfo($proId,$borrUserId,$borrTotAmt,$yearRate,$retType,$bidStartDate,
		$bidEndDate,$retAmt,$retDate,$proArea) {
		
        $transLogic = new Finance_Logic_Transaction();
        $return = $transLogic->addBidInfo($proId,$borrUserId,$borrTotAmt,$yearRate,$retType,
        	$bidStartDate,$bidEndDate,$retAmt,$retDate,$proArea);
        if(is_null($return) || !$return) {
        	Base_Log::error(array(
        		'msg'          => Finance_RetCode::getMsg(Finance_RetCode::REQUEST_API_ERROR),
        		'proId'        => $proId,
        		'borrUserId'   => $borrUserId,
        		'borrTotAmt'   => $borrTotAmt,
        		'yearRate'     => $yearRate,
        	    'retType'      => $retType,
        	    'bidStartDate' => $bidStartDate,
        	    'bidEndDate'   => $bidEndDate,
        	    'retAmt'       => $retAmt,
        	    'retDate'      => $retDate,
        	    'proArea'      => $proId,
        	));
        	return false;
        }
        Base_Log::notice($return);
        return true;
	}
	
	/**
	 * 银行卡查询接口 Finance_Api::queryCardInfo
	 * @param String $userCustId 用户客户号(required)    
	 * @param String $carId 开户银行账号(optional)
	 * 
	 * @return API 返回array格式 {'status'=>,'statusInfo'=>,'data'=>}
	 * status=0 请求成功 返回正常信息
	 * status=Base_RetCode::PARAM_ERROR  参数错误(1.userCustId查无此人 2.$userCustId或$carId格式出错 )
     * status=  该用户无此卡(有其他的卡)
     * status=  该用户没有绑定任何银行卡
     * status=  请求API出错
     * data=array( 
     *     0=>array(
     *         'MerCustId'商户客户号 
     *         'UsrCustId'用户客户号  
     *         'UsrName'真实名称  
     *         'CertId'证件号码 
     *         'BankId'银行代号 
     *         'CardId'开户银行账号 
     *         'RealFlag'银行卡是否实名 
     *         'UpdDateTime'时间  
     *         'ProvId'银行省份 
     *         'AreaId'银行地区 
     *         'IsDefault'是否默认 
     *     )
     *     1=>(...)
     *     2=>(...)
     *     ...     
     * )
     *  
	 */ 
	public static function queryCardInfo($userCustId,$cardId='') {
		$ret = new Base_Result();		
		$queryLogic = new Finance_Logic_Query();
		$return = $queryLogic->queryBankCard($userCustId,$cardId);
		if($return == false) {
			$ret->status = Finance_RetCode::REQUEST_API_ERROR;
			$ret->data = array();
			$ret->statusInfo = Finance_RetCode::getMsg($ret->status);
			
			$logParam = array();
			$logParam['msg'] = Finance_RetCode::getMsg(Finance_RetCode::REQUEST_API_ERROR);
			$logParam['userCustId'] = $userCustId;
			$logParam['cardId'] = $cardId;
			
			Base_Log::error($logParam);
			
			return $ret->format();
		} 
		if($return['RespCode'] != '000') {
			$ret->status = $return['RespCode'];
			$ret->data = $return;
			$ret->statusInfo = $return['RespDesc'];
			
			$logParam = array();
			$logParam['msg'] = $return['RespDesc'];
			$logParam = array_merge($logParam,$return);
			Base_Log::error($logParam);
			
			return $ret->format();
		} 
		if (empty($return['UsrCardInfolist'])) {
			$ret->status = Finance_RetCode::NOTBINDANYCARD;
			$ret->data = array();
			$ret->statusInfo = Finance_RetCode::getMsg($ret->status);
		
			$logParam = array();
			$logParam['msg'] = Finance_RetCode::getMsg(Finance_RetCode::NOTBINDANYCARD);
			$logParam = array_merge($logParam,$return);
			Base_Log::notice($logParam);
			
			return $ret->format();
		} 
		$ret->status = $return['RespCode'];
		$ret->data = $return;

		Base_Log::notice($return);
		
		return $ret->format();
	}
	
	/**
	 * 删除银行卡接口
	 * @param string huifuid
	 * @param string cardId
	 * @return array
	 * status:0   删除成功
	 * status:Finance_RetCode::REQUEST_API_ERROR 请求汇付API接口失败
	 * status:汇付返回值的RespCode
	 * data=array(
	 *     'huifuid'
	 *     'cardId'
	 * )
	 */
	public function delCard($huifuid,$card) {
		if(!isset($huifuid) || empty($huifuid) || !isset($card) || empty($card)) {
			Base_Log::error(array(
				'msg' => '请求参数错误',
				'huifuid' => $huifuid,
				'card'    => $card,
			));
		}
		$ret = new Base_Result();
		$userManageLogic = new Finance_Logic_UserManage();
		$return = $userManageLogic->delCard($huifuid,$card);
		if($return == false) {
			$ret->status = Finance_RetCode::REQUEST_API_ERROR;
			$ret->data = array();
			$ret->statusInfo = Finance_RetCode::getMsg($ret->status);
			
			$logParam = array();
			$logParam['msg'] = Finance_RetCode::getMsg(Finance_RetCode::REQUEST_API_ERROR);
			Base_Log::error($logParam);
			
			return $ret->format();
		} 
		if ($return['RespCode'] != "000") { //汇付返回值为非正常处理结构
			$ret->status = $return['RespCode'];
			$ret->data = $return;
			$ret->statusInfo = $return['RespDesc'];
			
			$logParam = array();
			$logParam['msg'] = $return['RespDesc'];			
			$logParam = array_merge($logParam,$return);
			Base_Log::error($logParam);
			
			return $ret->format();			
		} 		
		$ret = array(
			'status' => $return['RespCode'],
			'data'   => $return,
		);
		
		Base_Log::notice($return);
		
		return $ret->format();	
	}
	
	/**
	 * 主动投标接口 Finance_Api::initiativeTender
	 * @param int proId 借款ID
	 * @param float transAmt 交易金额(required)   
	 * @param int usrid 用户ID(required)    
	 * @param float maxTenderRate 最大投资手续费率(required)
	 * @param array BorrowerDetails 借款人信息(required)     
	 *        array(
     *            0 => array(
     *                "BorrowerUserId":借款人userid    
     *                "BorrowerAmt": "20.01"， 借款金额
     *                "BorrowerRate":"0.18" 借款手续费率(必须) 
     *            )
     *            1 =>array(
     *                ...
     *                ...
     *                ...
     *            )
     *            ...
     *        )
     * @param boolean $IsFreeze 是否冻结(required) true--冻结false--不冻结    
     * @param string $FreezeOrdId 冻结订单号(optional)    
     * @param string retUrl 汇付回调返回url
     * @return false || redirect  
     * )   
     * 
	 */
	public static function initiativeTender($loanId, $transAmt, $userid ,$borrowerDetails, $retUrl) {
		if(!isset($loanId) || empty($loanId) || !isset($transAmt) || empty($transAmt) || 
		   !isset($userid) || empty($userid) || !isset($borrowerDetails) || empty($borrowerDetails) ||
		   !isset($retUrl) || empty($retUrl)) {
		    Base_Log::error(array(
		    	'msg'             => '请求参数错误',
		    	'loanId'          => $loanId,
		    	'transAmt'        => $transAmt,
		    	'userid'          => $userid,
		    	'borrowerDetails' => $borrowerDetails,
		    	'retUrl'          => $retUrl,
		    ));
		    return false;	
		}
		$transLogic = new Finance_Logic_Transaction();
		Base_Log::notice(array(
			'loanId'          => $loanId,
			'transAmt'        => $transAmt,
			'userid'          => $userid,
			'borrowerDetails' => $borrowerDetails,
			'retUrl'          => $retUrl,			
		));
		$transLogic->initiativeTender($loanId, $transAmt, $userid, $borrowerDetails, $retUrl);		
	}
	
	/**
	 * 投标撤销接口Finance_Api::tenderCancel
	 * @param String transAmt 交易金额    (required)
	 * @param String usrid 用户id   (required)
	 * @param boolean isUnFreeze 是否解冻(require)  true--解冻  false--不解冻
	 * @param String UnFreezeOrdId 解冻订单号(optional) 解冻订单号
	 * @return bool true--撤销成功  false--撤销失败
	 */
	public static function tenderCancel($transAmt,$userid,$orderId,$orderDate,$retUrl) {
		if(!isset($transAmt) || empty($transAmt) || !isset($userid) || empty($userid) ||
		   !isset($orderId) || empty($orderId) || !isset($orderDate) || empty($orderDate) ||
		   !isset($retUrl) || empty($retUrl)) {
		    Base_Log::error(array(
		    	'msg'       => '请求参数错误',
		    	'transAmt'  => $transAmt,
		    	'userid'    => $userid,
		    	'orderId'   => $orderId,
		    	'orderDate' => $orderDate,
		    	'retUrl'    => $retUrl,
		    ));   	
		}
		$transLogic = new Finance_Logic_Transaction();
		Base_Log::notice(array(
		    'transAmt'  => $transAmt,
		    'userid'    => $userid,
		    'orderId'   => $orderId,
		    'orderDate' => $orderDate,
		    'retUrl'    => $retUrl,
		));
		$transLogic->tenderCancel($transAmt,$userid,$orderId,$orderDate,$retUrl);		
	}
	
	/**
	 * 满标打款接口 Finance_Api::loans
	 * @param $loanId 借款项目ID
     * @param $subOrdId 对应投标的orderId
     * @param $inUserId 入账的userid
     * @param $outUserId 出账的userid
     * @param $transAmt 该笔打款金额
     * @return bool true--打款成功  false--打款失败
     * 
	 */
     public static function loans($loanId,$subOrdId,$inUserId,$outUserId,$transAmt) {
     	 if(!isset($loanId) || empty($loanId) || !isset($subOrdId) || empty($subOrdId) ||
		    !isset($inUserId) || empty($inUserId) || !isset($outUserId) || empty($outUserId) ||
		    !isset($transAmt) || empty($transAmt)) {
		     Base_Log::error(array(
		         'msg'        => '请求参数错误',
		     	 'loanId'     => $loanId,
		     	 'subOrderId' => $subOrdId,
		     	 'inUserId'   => $inUserId,
		     	 'outUserId'  => $outUserId,
		     	 'transAmt'   => $transAmt,
		     ));   	
		 }    	
      	 $transLogic = new Finance_Logic_Transaction();
      	 $return = $transLogic->loans($loanId, $subOrdId, $inUserId, $outUserId, $transAmt);
      	
      	 if(is_null($return)) {
      	     $logParam = array();
      	 	 $logParam['msg']       = Finance_RetCode::getMsg(Finance_RetCode::REQUEST_API_ERROR);
      	 	 $logParam['loanId']    = $loanId;
      	 	 $logParam['subOrdId']  = $subOrdId;
      	 	 $logParam['inUserId']  = $inUserId;
      	 	 $logParam['outUserId'] = $outUserId;
      	 	 $logParam['transAmt']  = $transAmt;
      	 	 Base_Log::error($logParam);
      	 	 return false;
      	 }
      	 $respCode = $return['RespCode'];
      	 $respDesc = $return['RespDesc'];
      	 if($respCode !== '000') {
      	     $logParam = array();
      	 	 $logParam['msg'] = $respDesc;
      	 	 $logParam = array_merge($logParam,$return);     	 	
      	 	 Base_Log::error($logParam);
      	 	 return false;
      	 }      	 
      	 Base_Log::notice($return);
      	 return true;     	
     }
     
     /**
      * 还款接口Finance_Api::Repayment
      * @param string outUserId 出账账户号：还款人的uid
	  * @param string inUserId 入账账户号：投资人的uid
	  * @param string subOrdId 关联的投标订单号
	  * @param float transAmt 交易金额
	  * @param int loanId 借款项目ID
      * @return boolean 还款成功/失败
      */
     public static function repayment($outUserId,$inUserId,$subOrdId,$transAmt,$loanId) {
     	if(!isset($outUserId) || !isset($inUserId) ||!isset($subOrdId) || !isset($transAmt) ||
      	   !isset($loanId) ) {
      	   	Base_Log::error(array(
      	   		'msg'       => '请求参数错误',
      	   		'outUserId' => $outUserId,
      	   		'inUserId'  => $inUserId,
      	   		'subOrdId'  => $subOrdId,
      	   		'transAmt'  => $transAmt,
      	   		'loanId'    => $loanId,
      	   	));
      	}
     	$transLogic = new Finance_Logic_Transaction();
     	$return = $transLogic->repayment($outUserId, $inUserId, $subOrdId, $transAmt, $loanId);  	
     	if($return === false) {
     		Base_Log::error(array(
     		    'msg'       => '请求参数错误',
     		    'outUserId' => $outUserId,
     		    'inUserId'  => $inUserId,
     		    'subOrdId'  => $subOrdId,
     		    'transAmt'  => $transAmt,
     		    'loanId'    => $loanId,
     		));
     		return false;
     	}
     	if(is_null($return)) {
     	    $errCode = Finance_RetCode::REQUEST_API_ERROR;
     	    $logParam = array();
     	    $logParam['msg']       = Finance_RetCode::getMsg($errCode);
     	    $logParam['outUserId'] = $outUserId;
     	    $logParam['inUserId']  = $inUserId;
     	    $logParam['subOrdId']  = $subOrdId;
     	    $logParam['transAmt']  = $transAmt;
     	    $logParam['loanId']    = $loanId;
     	    Base_Log::error($logParam);
     	    return false;
     	}
     	$respCode = $return['RespCode'];
     	$respDesc = $return['RespDesc'];
     	if($respCode !== '000') {
     		$logParam = array();
     		$logParam['msg'] = $respDesc;
     		$logParam = array_merge($logParam,$return);
     		Base_Log::error($logParam);
     		return false;
     	}
     	Base_Log::notice($return);
     	return true;
     }
     
     /**
      * 封装汇付天下API实现用户绑卡功能(由Fiance模块controller层转入调用)Finance_Api::userBindCard
      * @param String $UsrCustId 用户客户号(必须)
      *
      * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>} 
      * status=0 处理成功
      * status=Base_RetCode::PARAM_ERROR 参数错误
      * status=API调用失败
      * data=array(
      *         'CmdId' 消息类型 
      *         'RespCode' 应答返回码  
      *         'RespDesc' 应答描述  
      *         'MerCustId' 商户客户号 
      *         'UsrCustId' 用户客户号 
      *         'BgRetUrl' 商户后台应答地址 
      *         'ChkValue' 签名 
      *         'OpenAcctId' 开户银行账号
      *         'OpenBankId' 开户银行代号
      *         'TrxId' 本平台交易唯一标识
      *         'MerPriv' 商户私有域
      *      )
      *      
      */
     public static function userBindCard($userCustId){
     	 if(!isset($userCustId) || empty($usrCustId)) {
     	 	Base_Log::error(array(
     	 		'msg'        => '请求参数错误',
     	 		'userCustId' => $userCustId,
     	 	));
     	 }
         $userManageLogic = new Finance_Logic_UserManage();
         Base_Log::notice(array(
         	'userCustId' => $userCustId,
         ));
     	 $userManageLogic->userBindCard($userCustId,$userid);
     }
     
     /**
      * 封装汇付天下API实现企业开户功能(由Fiance模块controller层转入调用)
      * @param String $BusiCode 营业执照编号(必须)
      * @param String $userId 用户号
      * @param String $UsrName 真实名称
      * @param String $InstuCode 组织机构代码
      * @param String $TaxCode 税务登记号
      * @param String $GuarType 担保类型
      *
      * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>} 
      * status=0 处理成功
      * status=Base_RetCode::PARAM_ERROR 参数错误
      * status=API调用失败
      * data=array(
      *         'CmdId' 消息类型(必须)
      *         'RespCode' 应答返回码 (必须)
      *         'RespDesc' 应答描述 (必须)
      *         'MerCustId' 商户客户号 (必须)
      *         'UsrId' 用户号(必须)
      *         'AuditStat' 审核状态 (必须)
      *         'TrxId' 本平台交易唯一标识 (必须)
      *         'BgRetUrl' 商户后台应答地址 (必须)
      *         'ChkValue' 签名 (必须)
      *         'UsrName' 真实名称
      *         'UsrCustId' 用户客户号
      *         'AuditDesc' 审核状态描述
      *         'MerPriv' 商户私有域
      *         'OpenBankId' 开户银行代号
      *         'CardId' 开户银行账号
      *         'RespExt' 返参扩展域
      *     )
      *     
      */
     public static function corpRegist($userid,$userName,$busiCode,$instuCode='',$taxCode=''){
     	 $userManageLogic = new Finance_Logic_UserManage();
     	 $userManageLogic->corpRegist($userid,$userName,$busiCode,$instuCode='',$taxCode='');
     }
     
     
     /**
      * 商户代取现接口
      * @param int userid
      * @param float transAmount
      * @return bool
      * 
      */
     public static function merCash($userid,$transAmt) {
     	 $transLogic = new Finance_Logic_Transaction();
         $ret = $transLogic->merCash($userid,$transAmt);    	
         if($ret === false) {       	 
             Base_Log::error(array(
                 'msg'      => Base_RetCode::getMsg(Base_RetCode::PARAM_ERROR),
                 'userid'   => $userid,
                 'transAmt' => $transAmt,
             ));
             return false;
         }
         if(is_null($ret)) {
         	 Base_Log::error(array(
         	     'msg'      => '请求汇付API错误',
         	     'userid'   => $userid,
         	     'transAmt' => $transAmt,
         	 ));
         	 return false;
         }
         $respCode = $ret['RespCode'];
         $respDesc = $ret['RespDesc'];
         if($respCode !== '000') {
         	 $logParam = $ret;
         	 $logParam['msg'] = $respDesc;
         	 Base_Log::error($logParam);
         	 return false;
         }
         Base_Log::notice($ret);
         return true;
     	
     }
     /**
      * 用户登录汇付login接口
      * redirect 
      */
     public static function userLogin($userCustId) {
         if(!isset($userCustId) || empty($userCustId)) {
             Base_Log::error(array(
         	     'msg'       => '请求参数错误',
         	     'usrCustId' => $userCustId,
         	 ));
         }
         $userManageLogic = new Finance_Logic_UserManage();
         Base_Log::notice(array(
             'usrCustId' => $userCustId,
         ));
     	 $userManageLogic->userLogin($userCustId);
     }
     
     /**
      * 用户信息修改接口
      * redirect
      */
     public static function acctModify($userCustId) {
         if(!isset($userCustId) || empty($userCustId)) {
             Base_Log::error(array(
                 'msg' => '请求参数错误',
                 'usrCustId' => $userCustId,
             ));     	     	
     	 }
         $userManageLogic = new Finance_Logic_UserManage();
         Base_Log::notice(array(
             'userCustId' => $userCustId,
         ));
     	 $userManageLogic->userLogin($userCustId);
     }
     
    
}