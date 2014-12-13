<?php
/**
 * 查询类接口实现类
 * 表明可选的参数若无，传入null
 * 返回值均以json格式封装
 */
class Finance_Logic_Query{

    /**
     * 余额查询接口queryBalanceBg
     * @param String $userCustId 用户客户号
     * @json return
     * [
     *     {
     *         'cmdId' 消息类型
     *         'respCode' 应答返回码 
     *         'respDesc' 应答描述
     *         'merCustId' 商户客户号
     *         'usrCustId' 用户客户号
     *         'avlBal' 可用余额
     *         'acctBal' 账户余额
     *         'frzBal' 冻结余额
     *     {
     * ]
     */
    public function queryBalanceBg($version,$merCustId,$userCustId){
    
    }
    
    /**
     * 银行卡查询接口QueryCardInfo
     * @param String $version 汇付版本号
     * @param String $merCustId 商户客户号
     * @param String $userCustId 用户客户号
     * @param String $cardId 开户银行帐号(可选)
     * @json return 
     * @return String $cmdId 消息类型
     * @return String $respCode 应答返回码 
     * @return String $respDesc 应答描述
     * @return String $merCustId 商户客户号
     * @return String $usrCustId 用户客户号
     * @return String $cardId 开户银行帐号(可选)
     * @return array $usrCardInfoList 用户银行卡信息列表(
     * merCustId商户客户号,
     * usrCustId 用户客户号,
     * username真实姓名,
     * certId证件号码,
     * BankId银行代号,
     * CardId开户银行帐号,
     * RealFlag银行卡是否实名,
     * UpDateTime 时间YYYYMMDDhhmmss,
     * ProcId 银行省份,
     * AreaId 银行地区,
     * IsDefault是否默认,)
     */
    public function queryCardInfo($version,$merCustId,$userCustId,$carId=null){
    }    



}
