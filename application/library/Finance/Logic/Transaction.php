<?php
/**
 * 交易类接口逻辑实现类
 * 传入的参数除必须字段外，若无则传入null即可
 * 参数均以数组形式传入
 */
class Finance_Logic_Transaction{
  
    /**
     * 网银充值实现
     * @param String usrCustId 用户客户号(必须)
     * @param String gateBusiId 支付网关业务代号:
     *              包括 B2C--B2C网银支付 
     *                   B2B--B2B网银支付 
     *                   FPAY--快捷支付 
     *                   POS--POS支付 
     *                   WPAY--定向支付 
     *                   WH--代扣
     * @param String OpenBankId 开户银行代号
     * @param String DcFlag 借贷记标记 D--借记,储蓄卡  C--贷记,信用卡
     * @param String TransAmt 交易金额(必须)
     * @param String retUrl 页面返回URL 交易结果将通过页面方式，发送至该地址上
     * @return json 
     * [
     *     {
     *         'cmdId' 消息类型
     *         'respCode' 应答返回码
     *         'respDesc' 应答描述
     *         'merCustId' 商户客户号
     *         'usrCustId' 用户客户号
     *         'ordId' 订单号
     *         'ordDate' 订单日期
     *         'transAmt' 交易金额
     *         'trxId' 本平台交易唯一标识(可选)
     *         'retUrl'页面返回URL(可选)
     *         'bgRetUrl' 商户后台应答地址
     *         'merPriv' 商户私有域(可选)
     *         'gateBusiId' 支付网关业务代号(可选)
     *         'gateBankId' 开户银行代号(可选)
     *         'chkValue' 签名
     *         'feeAmt' 手续费金额
     *         'feeCustId' 手续费扣款客户号
     *         'feeAccId' 手续费扣款子账户号
     *     }
     * ]
     */        
     public function netSave($version,$merCustId,$usrCustId,$GateBusiId,$OpenBankId,$DcFlag,$TransAmt,$retUrl){
    
     }
     
    /**
     * 主动投标
     * @param String transAmt 交易金额
     * @param String usrCustId 用户客户号
     * @param String maxTenderRate 最大投资手续费率 
     * @param String borrowDetails 借款人信息
     * @return
     * @return
     *  
     *
     */
     public function initiativeTender($version,$merCustId,$transAmt,$usrCustId,$maxTenderRate,$borrowDetails){
     }
  
    /**
     * 投标撤销
     * @param String transAmt 交易金额
     * @param String usrCustId 用户客户号
     * @return json
     * [
     *     {
     *         'cmdId' 消息类型
     *         'respCode' 应答返回码
     *         'respDesc' 应答描述
     *         'merCusId' 商户客户号
     *         'OrdId' 订单号
     *         'OrdDate' 订单日期
     *         'TransAmt' 交易金额
     *         'usrCustId' 用户客户号
     *         'retUrl' 页面返回URL(可选)
     *         'BgRetUrl' 商户后台应答地址
     *         'MerPriv' 商户私有域
     *         'ChkValue' 签名
     *     }
     * ]
     */
     public function tenderCancel($version,$merCustId,$transAmt,$usrCustId){
     }
     




} 
