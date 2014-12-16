<?php
/**
 * 财务模块封装汇付逻辑实现类
 * 
 * @author lilu
 *
 */
class Finance_Logic_Api extends Finance_Logic_Pay {
	
	/**
	 * 余额查询接口 Finance_Logic_Api::queryBalanceBg
	 * @param String $UserCustId 用户客户号(require)
	 * 
	 * @return API 返回array格式 {'status'=>,'statusInfo'=>,'data'=>}
	 * status=0 请求成功 返回正常数据
	 * status=502 参数错误
	 * status=
	 * data=array(
	 *    'AvlBal' 可用余额         
     *    'AcctBal' 账户余额         
     *    'FrzBal' 冻结余额   
	 * )
	 * 
	 */
	public static function queryBalanceBg($userCustId) {
		
	}
	
	/**
	 * 查询用户某一张银行卡信息接口 Finance_Logic_Api::queryThisCardInfo
	 * @param String $userCustId 用户客户号(required)
	 * @param String $cardId 开户银行帐号(require)     
	 * 
	 * @return API 返回array格式 {'status'=>,'statusInfo'=>,'data'=>}
	 * status=0 请求成功 返回正常数据
	 * status=502参数错误
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
	 * 银行卡查询接口 Finance_Logic_Api::queryThisCardInfo
	 * @param String $userCustId 用户客户号(required)    
	 * @param String $carId 开户银行账号(optional)
	 * 
	 * @return API 返回array格式 {'status'=>,'statusInfo'=>,'data'=>}
	 * status=0 请求成功 返回正常信息
	 * status=502  参数错误(1.userCustId查无此人 2.$userCustId或$carId格式出错 )
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
	public static function queryThisCardInfo($userCustId,$carId='') {
		
	}
	
	/**
	 * 主动投标接口 Finance_Logic_Api::initiativeTender
	 * @param String transAmt 交易金额(required)   
	 * @param String usrCustId 汇付平台的用户ID(required)    
	 * @param String maxTenderRate 最大投资手续费率(required)
	 * @param array BorrowerDetails 借款人信息(required)     
	 *        array(
     *            0 => array(
     *                "BorrowerCustId":"6000010000000014"，借款人客户号     
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
     * @param String $FreezeOrdId 冻结订单号(optional)    
     * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>}  
     * status=0 请求成功 返回正常信息  
     * status=  请求API出错  
     * status=502 参数错误  
     * data = array(
     *     'CmdId' 消息类型  
     *     'RespCode' 应答返回码    
     *     'RespDesc' 应答描述   
     *     'MerCustId' 商户客户号     
     *     'OrdId' 订单号   
     *     'OrdDate' 订单日期       
     *     'TransAmt' 交易金额       
     *     'UsrCustId' 用户客户号   
     *     'BgRetUrl' 商户后台应答地址   
     *     'IsFreeze' 是否冻结    
     *     'ChkValue' 签名  
     *     'TrxId' 本平台 交易唯一标识              
     *     'FreezeOrdId' 冻结订单号     
     *     'FreezeTrxId' 冻结标识   
     *     'RetUrl' 页面返回URL          
     *     'MerPriv' 商户私有域   
     *     'RespExt' 返参扩展域   
     * )   
     * 
	 */
	public static function initiativeTender($TransAmt,$UsrCustId,$MaxTenderRate,$BorrowDetail=null,$IsFreeze=true,$FreezeOrdId='') {
		
	}
	
	/**
	 * 投标撤销接口Finance_Logic_Api::tenderCancel
	 * @param String transAmt 交易金额    (required)
	 * @param String usrCustId 用户商户号   (required)
	 * @param boolean isUnFreeze 是否解冻(require)  true--解冻  false--不解冻
	 * @param String UnFreezeOrdId 解冻订单号(optional) 解冻订单号
	 * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>} 
	 * status=0 请求成功 返回正常信息
     * status=502  参数错误 
     * status=  请求API出错
     * data = array(   
     *     'Version' 版本号 
     *     'CmdId'  消息类型 
     *     'MerCusId' 商户客户号  
     *     'OrdId' 订单号 
     *     'OrdDate' 订单日期 
     *     'TransAmt' 交易金额 
     *     'UrsrCustId' 用户客户号  
     *     'IsUnFreeze' 是否解冻   
     *     'BgRetUrl' 商户后台应答地址  
     *     'ChkValue' 签名 
     *     'UnFreezeOrdId' 解冻订单号  
     *     'FreezeTrxId' 冻结标识 
     *     'RetUrl' 页面返回URL    
     *     'MerPriv' 商户私有域     
     * )
	 * 
	 */
	public static function tenderCancel($transAmt,$usrCustId,$isUnFreeze,$UnFreezeOrdId='') {
		
	}
	
	/**
	 * 满标打款接口 Finance_Logic_Api::loans
	 * @param String outCustId 出账客户号(require)--出账客户号， 由汇付生成， 用户的唯一性标识  
	 * @param String transAmt 交易金额 (require)
	 * @param String fee 扣款手续费(require) 
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
     * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>}   
     * status=0 请求成功 返回正常信息  
     * status=502  参数错误
     * status=  请求API出错  
     * data=array(  
     *     'CmdId'  消息类型   
     *     'MerCusId' 商户客户号    
     *     'OrdId' 订单号   
     *     'OrdDate' 订单日期   
     *     'OutCustId' 出账客户号  
     *     'OutAcctId'出账子账户  
     *     'TransAmt' 交易金额   
     *     'Fee' 扣款手续费  
     *     'InCustId' 入账客户号  
     *     'InAcctId' 入账子账户  
     *     'SubOrdId' 订单号  
     *     'SubOrdDate' 订单日期     
     *     'FeeObjFlag' 续费收取对象标志I/O  
     *     'IsDefault' 是否默认  
     *     'IsUnFreeze' 是否解冻  
     *     'UnFreezeOrdId' 解冻订单号    
     *     'FreezeTrxId' 冻结标识   
     *     'BgRetUrl' 商户后台应答地址      
     *     'MerPriv' 商户私有域  
     *     'RespExt'返参扩展域  
     *     'ChkValue' 签名  
     * ) 
     * 
	 */
     public static function loans($outCustId,$transAmt,$fee,$inCustId,$feeObjFlag,$isDefault,$isUnFreeze,$unFreezeOrdId='',$DivDetails=null,$reqExt='') {
     	
     	
     }
     
     /**
      * 网银充值实现 Finance_Logic_Api::netSave
      * @param String $UsrCustId 用户客户号(require)
      * @param String $TransAmt 交易金额(require)
      * @param String $GateBusiId 支付网关业务代号:B2C--B2C网银支付  B2B--B2B网银支付  FPAY--快捷支付  POS--POS支付  WPAY--定向支付  WH--代扣(optional)
      * @param String $OpenBankId 开户银行代号(optional)
      * @param String $DcFlag 借贷记标记 D--借记,储蓄卡  C--贷记,信用卡(optional)
      *
      * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>}  
      * status=0 处理成功
      * status=502参数错误
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
     public static function netSave($UsrCustId,$TransAmt,$GateBusiId='B2C',$OpenBankId='ICBC',$DcFlag='D'){
     
     }
     
     /**
      * 提现实现--考虑xjd平台要不要向用户收取提现手续费 Finance_Logic_Api::cash
      * @param String $TransAmt 交易金额(必须)
      * @param String $OpenAcctId 开户银行帐号
      *
      * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>}  
      * status=0 处理成功
      * status=502参数错误
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
     public static function cash($TransAmt,$OpenAcctId=''){
     
     	 
     }
     
     /**
      * 封装汇付天下API实现用户开户功能(由Fiance模块controller层转入调用)Finance_Logic_Api::userRegister
      * @param String $UsrId 用户号(optinal)
      * @param String $UsrName 真实名称(optinal)
      * @param String $IdType 证件类型 身份证或其他(optinal)
      * @param String $IdNo 证件号码(optinal)
      * @param String $UsrMp 手机号(optinal)
      * @param String $UsrEmail 用户Email(optinal)
      *
      * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>} 
      * status=0 处理成功
      * status=502参数错误
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
     public static function userRegister($UsrId='',$UsrName='',$IdType='',$IdNo='',$UsrMp='',$UsrEmail=''){
     
     }
     
     /**
      * 封装汇付天下API实现用户绑卡功能(由Fiance模块controller层转入调用)Finance_Logic_Api::userBindCard
      * @param String $UsrCustId 用户客户号(必须)
      *
      * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>} 
      * status=0 处理成功
      * status=502参数错误
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
     public static function userBindCard($UsrCustId){
     
     }
     
     /**
      * 封装汇付天下API实现企业开户功能(由Fiance模块controller层转入调用)
      * @param String $BusiCode 营业执照编号(必须)
      * @param String $UsrId 用户号
      * @param String $UsrName 真实名称
      * @param String $InstuCode 组织机构代码
      * @param String $TaxCode 税务登记号
      * @param String $GuarType 担保类型
      *
      * @return API返回array格式 {'status'=>,'statusInfo'=>,'data'=>} 
      * status=0 处理成功
      * status=502参数错误
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
     public static function corpRegister($BusiCode,$UsrId='',$UsrName='',$InstuCode='',$TaxCode='',$GuarType=''){
     	 
     }
	
}