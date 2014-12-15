<?php
/**
 * 查询类接口实现类
 * 余额查询
 * 银行卡查询
 * 表明可选的参数若无，传入null
 * 返回值均以json格式封装，未标明必须的字段可以没有
 */
class Finance_Logic_Query extends Finance_Logic_Pay{

    /**
     * 余额查询接口queryBalanceBg
     * @param String $UserCustId 用户客户号(必须)
     * 
     * @return json
     * [
     *     {
     *         'CmdId' 消息类型(必须)
     *         'RespCode' 应答返回码 (必须)
     *         'RespDesc' 应答描述(必须)
     *         'MerCustId' 商户客户号(必须)
     *         'UsrCustId' 用户客户号(必须)
     *         'ChkValue' 签名(必须)
     *         'AvlBal' 可用余额 
     *         'AcctBal' 账户余额 
     *         'FrzBal' 冻结余额 
     *     {
     * ]
     */
    public function queryBalanceBg($UserCustId){
    
    }
    
    /**
     * 银行卡查询接口QueryCardInfo
     * @param String $UserCustId 用户客户号(必须)
     * @param String $cardId 开户银行帐号
     * 
     * @return json  
     * [
     *     {
     *         'CmdId' 消息类型(必须)
     *         'RespCode' 应答返回码 (必须)
     *         'RespDesc' 应答描述(必须)
     *         'MerCustId' 商户客户号(必须)
     *         'UsrCustId' 用户客户号(必须)
     *         'CardId' 开户银行帐号
     *         'UsrCardInfolist' 用户银行卡信息列表
     *                          [
     *                              { 
     *                                  'MerCustId'商户客户号(必须)
     *                                  'UsrCustId'用户客户号 (必须)
     *                                  'UsrName'真实名称 (必须)
     *                                  'CertId'证件号码(必须)
     *                                  'BankId'银行代号(必须)
     *                                  'CardId'开户银行账号(必须)
     *                                  'RealFlag'银行卡是否实名(必须)
     *                                  'UpdDateTime'时间 (必须)
     *                                  'ProvId'银行省份(必须)
     *                                  'AreaId'银行地区 (必须)
     *                                  'IsDefault'是否默认(必须)  
     *                              }
     *                          ]
     *         'ChkValue' 签名(必须)     
     *     }
     * ]
     */
    public function queryCardInfo($userCustId,$carId='ICBC'){
    }    
}

