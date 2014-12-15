<?php
/**
 * 交易类接口逻辑实现类
 * 网银充值
 * 提现
 * 主动投标
 * 投标撤销
 * 传入的参数除必须字段外，若无则传入null
 * 均以json格式返回，未表明必须的返回字段可以没有
 */
class Finance_Logic_Transaction extends Finance_Logic_Pay{
  
    /**
     * 网银充值实现
     * @param String $UsrCustId 用户客户号(必须)
     * @param String $TransAmt 交易金额(必须)
     * @param String $GateBusiId 支付网关业务代号:B2C--B2C网银支付  B2B--B2B网银支付  FPAY--快捷支付  POS--POS支付  WPAY--定向支付  WH--代扣        
     * @param String $OpenBankId 开户银行代号
     * @param String $DcFlag 借贷记标记 D--借记,储蓄卡  C--贷记,信用卡
     *
     * @return json 
     * [
     *     {
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
     *     }
     * ]
     */        
     public function netSave($UsrCustId,$TransAmt,$GateBusiId='B2C',$OpenBankId='ICBC',$DcFlag='D'){
    
     }

     /**
      * 提现实现--考虑xjd平台要不要向用户收取提现手续费
      * @param String $TransAmt 交易金额(必须)
      * @param String $OpenAcctId 开户银行帐号
      * 
      * @return json
      * [ 
      *     { 
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
      *     }
      * ]
      *
      */
     public function cash($TransAmt,$OpenAcctId=''){
        
     
     }
    /**
     * 主动投标
     * @param String $TransAmt 交易金额(必须)
     * @param String $UsrCustId 用户客户号(必须)
     * @param String $MaxTenderRate 最大投资手续费率 (必须)
     * @param array $BorrowDetails 借款人信息(必须)
     *        支持传送多个借款人信息，使用json 格式传送，
              [
                 {
                    "BorrowerCustId":"6000010000000014"，借款人客户号(必须)  
                    "BorrowerAmt": "20.01"， 借款金额 (必须)
                    "BorrowerRate":"0.18" 借款手续费率(必须)
                 }， 
                 {
                    "BorrowerCustId":"6000010000000014"， 
                    "BorrowerAmt":"20.01"，
                    "BorrowerRate":"0.18" 
                 }， 
                 {
                    "BorrowerCustId":"6000010000000014"， 
                    "BorrowerAmt": "20.01"， 
                    "BorrowerRate": "0.18" 
                 }
              ]
     * @param boolean $IsFreeze 是否冻结(必须) true--冻结 false--不冻结
     * @param String $FreezeOrdId 冻结订单号
     * 
     * @return json or false
     * [
     *     { 
     *         'CmdId' 消息类型 (必须)
     *         'RespCode' 应答返回码(必须)
     *         'RespDesc' 应答描述(必须)
     *         'MerCustId' 商户客户号(必须)
     *         'OrdId' 订单号(必须)
     *         'OrdDate' 订单日期(必须)
     *         'TransAmt' 交易金额(必须)
     *         'UsrCustId' 用户客户号(必须)
     *         'BgRetUrl' 商户后台应答地址 (必须)
     *         'IsFreeze' 是否冻结 (必须)
     *         'ChkValue' 签名(必须)
     *         'TrxId' 本平台 交易唯一标识       
     *         'FreezeOrdId' 冻结订单号
     *         'FreezeTrxId' 冻结标识 
     *         'RetUrl' 页面返回URL      
     *         'MerPriv' 商户私有域 
     *         'RespExt' 返参扩展域        
     *     }
     * ]
     */
     public function initiativeTender($TransAmt,$UsrCustId,$MaxTenderRate,$BorrowDetail=null,$IsFreeze=true,$FreezeOrdId=''){
     }
  
    /**
     * 投标撤销
     * @param String $TransAmt 交易金额(必须)
     * @param String $UsrCustId 用户客户号(必须)
     * @param boolean $IsUnFreeze 是否解冻 true--解冻 false--不解冻(必须)
     * @param String $UnFreezeOrdId 解冻订单号
     * @param String $FreezeTrxId 冻结标识 组成规则为：8位本平台日期+10位系统流水号 
     * @return json or false
     * [
     *     {
     *         'Version' 版本号(必须)
     *         'CmdId'  消息类型(必须)
     *         'MerCusId' 商户客户号(必须)
     *         'OrdId' 订单号(必须)
     *         'OrdDate' 订单日期(必须)
     *         'TransAmt' 交易金额(必须)
     *         'UrsrCustId' 用户客户号(必须)
     *         'IsUnFreeze' 是否解冻 (必须)
     *         'BgRetUrl' 商户后台应答地址(必须)
     *         'ChkValue' 签名(必须)
     *         'UnFreezeOrdId' 解冻订单号
     *         'FreezeTrxId' 冻结标识 
     *         'RetUrl' 页面返回URL    
     *         'MerPriv' 商户私有域
     *         
     *     }
     * ]
     */
     public function tenderCancel($transAmt,$usrCustId,$isUnFreeze=true,$UnFreezeOrdId='',$FreezeTrxId=''){


     }


} 


