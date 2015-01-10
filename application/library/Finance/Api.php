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
		if($return == false) {			
			$ret->status = Finance_RetCode::REQUEST_API_ERROR;
			$ret->data = array();
			$ret->statusInfo = Finance_RetCode::getMsg($ret->status);
			return $ret->format();
		} 
		if($return['RespCode'] != '000') {
			$ret->status = $return['RespCode'];
			$ret->data = array();
			$ret->statusInfo = $return['RespDesc'];
			return $ret->format();
		}
		$ret->status = Finance_RetCode::SUCCESS;
		$ret->data = $return;
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
		$ret = new Base_Result();
		$queryLogic = new Finance_Logic_Query();
		$return = $queryLogic->queryBalanceBg($userCustId);
		if($return == false) {
			$ret->status = Finance_RetCode::REQUEST_API_ERROR;
			$ret->data = array(
			    'avlBal'  => '0.00',
				'acctBal' => '0.00',
				'frzBal'  => '0.00',
			);
			$ret->statusInfo = Finance_RetCode::getMsg($ret->status);
			return $ret->format();
		}
		if($return['RespCode'] != '000') {
			$ret->status = $return['RespCode'];
			$ret->data = array(
				'avlBal'  => '0.00',
				'acctBal' => '0.00',
				'frzBal'  => '0.00',
			);
			$ret->statusInfo = $return['RespDesc'];
			return $ret->format();
		} 
		$ret->status = Finance_RetCode::SUCCESS;
		$ret->data = array(
			'avlBal'  => $return['AvlBal'],
			'acctBal' => $return['AcctBal'],
			'frzBal'  => $return['FrzBal'],
		);
		return $ret->format();		
	}
	
	/**
	 * 查询用户某一张银行卡信息接口 Finance_Api::queryThisCardInfo
	 * @param String $userCustId 用户客户号(required)
	 * @param String $cardId 开户银行帐号(require)     
	 * 
	 * @return API 返回array格式 {'status'=>,'statusInfo'=>,'data'=>}
	 * status=0 请求成功 返回正常数据
	 * status=Base_RetCode::PARAM_ERROR参数错误
	 * status=
	 * data=array(  
     *    'MerCustId'商户客户号   
     *    'UsrCustId'用户客户号   
     *    'UsrName'真实名称    
     *    'CertId'证件号码     
     *    'BankId'银行代号     
     *    'CardId'开户银行账号   
     *    'RealFlag'银行卡是否实名     
     *    'UpdDateTime'时间     
     *    'ProvId'银行省份  
     *    'AreaId'银行地区   
     *    'IsDefault'是否默认   
     * )
     * 
	 */
	public static function queryThisCardInfo($userCustId,$carId) {
		
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
		//TODO
		//升级判断填进去的卡号是不是该用户的卡。。目前平台还不需要根据用户卡号来查询银行卡信息，待升级
		$ret = new Base_Result();		
		$queryLogic = new Finance_Logic_Query();
		$return = $queryLogic->queryBankCard($userCustId,$cardId);
		if($return == false) {
			$ret->status = Finance_RetCode::REQUEST_API_ERROR;
			$ret->data = array();
			$ret->statusInfo = Finance_RetCode::getMsg($ret->status);
			return $ret->format();
		} 
		if($return['RespCode'] != '000') {
			$ret->status = $return['RespCode'];
			$ret->data = $return;
			$ret->statusInfo = $return['RespDesc'];
			return $ret->format();
		} 
		if (empty($return['UsrCardInfolist'])) {
			$ret->status = Finance_RetCode::NOTBINDANYCARD;
			$ret->data = array();
			$ret->statusInfo = Finance_RetCode::getMsg($ret->status);
			return $ret->format();
		} 
		$ret->status = Finance_RetCode::SUCCESS;
		$ret->data = $return;
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
		$ret = new Base_Result();
		$userManageLogic = new Finance_Logic_UserManage();
		$return = $userManageLogic->delCard($huifuid,$card);
		if($return == false) {
			$ret->status = Finance_RetCode::REQUEST_API_ERROR;
			$ret->data = array();
			$ret->statusInfo = Finance_RetCode::getMsg($ret->status);
			return $ret->format();
		} 
		if ($return['RespCode'] != "000") { //汇付返回值为非正常处理结构
			$ret->status = $return['RespCode'];
			$ret->data = $return;
			$ret->statusInfo = $return['RespDesc'];
			return $ret->format();			
		} 		
		$ret = array(
			'status' => Finance_RetCode::SUCCESS,
			'data'   => $return,
		);
		return $ret->format();	
	}
	
	/**
	 * 主动投标接口 Finance_Api::initiativeTender
	 * @param String transAmt 交易金额(required)   
	 * @param String usrid 用户ID(required)    
	 * @param String maxTenderRate 最大投资手续费率(required)
	 * @param array BorrowerDetails 借款人信息(required)     
	 *        array(
     *            0 => array(
     *                "BorrowerUserId":借款人userid    
     *                "BorrowerAmt": "20.01"， 借款金额
     *                "BorrowerRate":"0.18" 借款手续费率(必须) 
     *                "ProId"  项目ID
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
     * @return string requestURL 请求汇付的URL  
     * )   
     * 
	 */
	public static function initiativeTender($transAmt, $userid ,$uidborrowDetail,$retUrl) {
		$isFreeze = true;
		$transLogic = new Finance_Logic_Transaction();
		$transLogic->initiativeTender($transAmt, $userid, $uidborrowDetail, $isFreeze, $retUrl);		
	}
	
	/**
	 * 投标撤销接口Finance_Api::tenderCancel
	 * @param String transAmt 交易金额    (required)
	 * @param String usrid 用户id   (required)
	 * @param boolean isUnFreeze 是否解冻(require)  true--解冻  false--不解冻
	 * @param String UnFreezeOrdId 解冻订单号(optional) 解冻订单号
	 * @return bool true--撤销成功  false--撤销失败
	 */
	public static function tenderCancle($transAmt,$usrid,$orderId,$orderDate,$retUrl) {
		return true;
	}
	
	/**
	 * 满标打款接口 Finance_Api::loans
	 * @param String outCustId 出账客户号(require)--出账客户号， 由汇付生成， 用户的唯一性标识  
	 * @param String transAmt 交易金额 (require)
	 * //@param String fee 扣款手续费(require) 
	 * @param String inCustId 入账客户号(require)--入账客户号， 由汇付生成， 用户的唯一性标识  
	 * @param String feeObjFlag 续费收取对象标志(require) I--向入款客户号 InCustId 收取 O--向出款客户号 OutCustId 收取 
	 * @param String isDefault Y--默认添加资金池(require) N--不默认添加资金池  
	 * @param String isUnFreeze 是否解冻(require) Y--解冻 N--不解冻
	 * @param String unFreezeOrdId 解冻订单号(optional)  
	 * @param array DivDetails 分账账户串(optional)   
	 * 
	 *    (  
     *        0=>array(  
     *               'DivCustId'(分账商户号)=>  
     *               'DivAcctId'（分账账户号）=>  
     *               'DivAmt'（分账金额）=>  
     *           )  
     *        ...           
     *     ) 
     * @param array reqExt 扩展请求参数(optional) {'VocherAmt'=>'50.00',}  VocherAmt为代金券金额  
     * 
     * @return bool true--成功  false--失败
     * 
	 */
     public static function loans($outCustId, $transAmt, $inCustId, $feeObjFlag, $isDefault, $isUnFreeze, $unFreezeOrdId='', $DivDetails=null, $reqExt='') {
     	
     	
     }
     
     /**
      * 网银充值实现 Finance_Api::netSave
      * @param String $UsrCustId 用户客户号(require)
      * @param String $TransAmt 交易金额(require)
      * @param String $GateBusiId 支付网关业务代号:B2C--B2C网银支付  B2B--B2B网银支付  FPAY--快捷支付  POS--POS支付  WPAY--定向支付  WH--代扣(optional)
      * @param String $OpenBankId 开户银行代号(optional)
      * @param String $DcFlag 借贷记标记 D--借记,储蓄卡  C--贷记,信用卡(optional)
      *
      * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>}  
      * status=0 处理成功
      * status=Base_RetCode::PARAM_ERROR参数错误
      * status=API调用失败
      * data = array(      
      *         'CmdId' 消息类型(必须)
      *         'RespCode' 应答返回码(必须)
      *         'RespDesc' 应答描述(必须)
      *         'MerCustId' 商户客户号(必须)
      *         'UsrCustId' 用户客户号(必须)
      *         'OrdId' 订单号(必须)
      *         'OrdDate' 订单日期(必须)
      *         'TransAmt' 交易金额(必须)
      *         'BgRetUrl' 商户后台应答地址(必须)
      *         'FeeCustId' 手续费扣款客户号(必须)
      *         'FeeAccId' 手续费扣款子账户号(必须)
      *         'FeeAmt' 手续费金额(必须)
      *         'ChkValue' 签名(必须)
      *         'TrxId' 本平台交易唯一标识
      *         'RetUrl'页面返回URL
      *         'MerPriv' 商户私有域
      *         'GateBusiId' 支付网关业务代号
      *         'GateBankId' 开户银行代号
      *        )
      *  
      */
     public static function netSave($userid,$usrCustId,$transAmt,$gateBusiId,$openBankId,$dcFlag){
         $transLogic = new Finance_Logic_Transaction();
         $transLogic->netsave($userid, $usrCustId, $transAmt, $gateBusiId, $openBankId, $dcFlag);         
     }
     
     /**
      * 提现实现--考虑xjd平台要不要向用户收取提现手续费 Finance_Api::cash
      * @param String $TransAmt 交易金额(必须)
      * @param String $OpenAcctId 开户银行帐号
      *
      * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>}  
      * status=0 处理成功
      * status=Base_RetCode::PARAM_ERROR参数错误
      * status=API调用失败
      * data=array(
      *         'CmdId' 消息类型 (必须) 此处为Cash
      *         'RespCode' 应答返回码(必须)
      *         'RespDesc' 应答描述(必须)
      *         'MerCustId' 商户客户号(必须)
      *         'OrdId' 订单号(必须)
      *         'UsrCustId' 用户客户号(必须)
      *         'TransAmt' 交易金额(必须)
      *         'FeeAmt'手续费金额(必须)
      *         'FeeCustId'手续费扣款客户号(必须)
      *         'FeeAcctId'手续费扣款子帐户号(必须)
      *         'BgRetUrl'商户后台应答地址(必须)
      *         'ChkValue' 签名(必须)
      *         'OpenAcctId'开户银行帐号
      *         'OpenBankId'开户银行代号
      *         'RetUrl'页面返回URL
      *         'MerPriv'商户私有域
      * )
      *
      */
     public static function cash($transAmt,$openAcctId=''){
     
     	 
     }
     
     /**
      * 封装汇付天下API实现用户开户功能(由Fiance模块controller层转入调用)Finance_Api::userRegister
      * @param String $userId 用户号(optinal)
      * @param String $UsrName 真实名称(optinal)
      * @param String $IdType 证件类型 身份证或其他(optinal)
      * @param String $IdNo 证件号码(optinal)
      * @param String $UsrMp 手机号(optinal)
      * @param String $UsrEmail 用户Email(optinal)
      *
      * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>} 
      * status=0 处理成功
      * status=Base_RetCode::PARAM_ERROR 参数错误
      * status=API调用失败
      * data=array(
      *         'CmdId' 消息类型UserRegister 
      *         'RespCode' 应答返回码 
      *         'RespDesc' 应答描述 
      *         'MerCustId' 商户客户号 
      *         'UsrId' 用户号  
      *         'UsrCustId' 用户客户号 
      *         'BgRetUrl' 商户后台应答地址
      *         'ChkValue' 签名
      *         'TrxId' 本平台交易唯一标识
      *         'RetUrl' 页面返回URL
      *         'MerPriv' 商户私有域
      *         'IdType' 证件类型
      *         'IdNo' 证件号码
      *         'UsrMp' 手机号
      *         'UsrEmail' 用户Email
      *         'UsrName' 真实名称
      *       )
      */
     public static function userRegist($userName,$usrMp,$userid){
     	 $userManageLogic = new Finance_Logic_UserManage();
     	 $userManageLogic->userRegist($userName,$usrMp,$userid);     	 
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
     public static function userBindCard($usrCustId){
         $userManageLogic = new Finance_Logic_UserManage();
     	 $userManageLogic->userBindCard($usrCustId,$userid);
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
     public static function corpRegister($busiCode,$userId='',$usrName='',$instuCode='',$taxCode='',$guarType=''){
     	 
     }
     
     /**
      * 用户登录汇付login接口
      * redirect 
      */
     public static function userLogin($usrCustId) {
         $userManageLogic = new Finance_Logic_UserManage();
     	 $userManageLogic->userLogin($usrCustId);
     }
     
     /**
      * 用户信息修改接口
      * redirect
      */
     public static function acctModify($usrCustId) {
         $userManageLogic = new Finance_Logic_UserManage();
     	 $userManageLogic->userLogin($usrCustId);
     }
     /**
      * 获取充值提现列表数据
      * @param String $userId 用户号(require)
      * @param integer $type 记录类型   0--充值  1--提现
      * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>} 
      * status=0 处理成功
      * status=Base_RetCode::PARAM_ERROR 参数错误
      * status=API调用失败
      * data=array(
      *          'payType'交易类型,
      *          'serialNumber'交易流水号,
      *          'amount'交易金额,
      *          'balance'可用余额,
      *          'createTime'交易时间         
      * ) 
      */
     public static function getRechargeWithDrawRecord($userId, $type=0, $startTime, $endTime, $date) {
     	$financeLogic = new Finance_Logic_Base(); 
     	if($userId>0) {
     		$data = $financeLogic->getRechargeWithDrawRecord($userId);
     		if(empty($data)) {
     			$ret = array(
     			    'status'=>0,
     				'statusInfo' =>'The query result is empty',
     				'data' => $data,
     			);
     			
     		}
     		$ret = array(
     		    'status'=>0,
     			'statusInfo' =>'success' ,
     			'data' => $data,
     		);
        } else {
        	Base_Log::fatal(array('msg'=>'invalid param','userId'=>$userId,));
        	$data = null;
        	$ret = array(
        	    'status'=>Base_RetCode::PARAM_ERROR,
        		'statusInfo'=>Base_RetCode::getMsg(Base_RetCode::PARAM_ERROR),
        		'data' => $data,
        	);
        }    	
     	return $ret;    	    	 
     }	
     
     /**
      * 获取某个人投资者的总投资金额
      * @param String $userId 用户号(require)
      * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>}  
      * status=0 处理成功
      * status=502参数错误
      * data=array(
      *        'amount'总投资金额
      *      )
      */
     public static function tenderAmount($userId) {
     	$financeLogic = new Finance_Logic_Base();
     	$ret = array(); 
     	
     	if($userId>0) {
     		$data = $financeLogic->fetchTenderAmonut($userId);
     		$ret = array('status'=>0,
     				'statusInfo'=>'success to fetch tender amount',
     				'data' => $data,
     		 );
     	} else {
     		Base_Log::fatal(array('msg'=>'invalid param',
     		                      'userId'=>$userId,)
     		);
     		$data = null;
     		$ret = array('status'=>Base_RetCode::PARAM_ERROR,
     				'statusInfo'=>Base_RetCode::getMsg(Base_RetCode::PARAM_ERROR),
     				'data' => $data,
     		);	
     	}
     	return $ret;
     	    	
     }     
}