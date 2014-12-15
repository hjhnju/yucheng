<?php
/**
 * 用户管理类接口逻辑实现类
 * 用户开户
 * 用户绑卡
 * 企业开户
 * 传入的参数除必须字段外，若无则传入null
 * 均以json格式返回，为表明必须的字段可以没有
 */
class Finance_Logic_UserManage extends Finance_Logic_Pay{

    /**
     * 封装汇付天下API实现用户开户功能(由Fiance模块controller层转入调用)
     * @param String $UsrId 用户号
     * @param String $UsrName 真实名称
     * @param String $IdType 证件类型 身份证或其他
     * @param String $IdNo 证件号码
     * @param String $UsrMp 手机号
     * @param String $UsrEmail 用户Email
     * 
     * @return json or false
     * [
     *     {
     *         'CmdId' 消息类型UserRegister(必须)
     *         'RespCode' 应答返回码(必须)
     *         'RespDesc' 应答描述(必须)
     *         'MerCustId' 商户客户号(必须)
     *         'UsrId' 用户号 (必须)
     *         'UsrCustId' 用户客户号(必须)
     *         'BgRetUrl' 商户后台应答地址(必须)
     *         'ChkValue' 签名(必须)
     *         'TrxId' 本平台交易唯一标识
     *         'RetUrl' 页面返回URL 
     *         'MerPriv' 商户私有域 
     *         'IdType' 证件类型 
     *         'IdNo' 证件号码 
     *         'UsrMp' 手机号
     *         'UsrEmail' 用户Email 
     *         'UsrName' 真实名称       
     *     }
     * ]
     */
    public function userRegister($UsrId='',$UsrName='',$IdType='',$IdNo='',$UsrMp='',$UsrEmail=''){
    }

    /**
     * 封装汇付天下API实现用户绑卡功能(由Fiance模块controller层转入调用)
     * @param String $UsrCustId 用户客户号(必须) 
     * 
     * @return json or false
     * [
     *     {
     *         'CmdId' 消息类型(必须)
     *         'RespCode' 应答返回码 (必须)
     *         'RespDesc' 应答描述 (必须)
     *         'MerCustId' 商户客户号(必须)
     *         'UsrCustId' 用户客户号(必须)
     *         'BgRetUrl' 商户后台应答地址(必须)
     *         'ChkValue' 签名(必须)
     *         'OpenAcctId' 开户银行账号 
     *         'OpenBankId' 开户银行代号   
     *         'TrxId' 本平台交易唯一标识        
     *         'MerPriv' 商户私有域        
     *     }
     * ]
     */
    public function userBindCard($UsrCustId){
    
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
     * @return json
     * [ 
     *     {
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
     *     }
     * ]
     */
    public function corpRegister($BusiCode,$UsrId='',$UsrName='',$InstuCode='',$TaxCode='',$GuarType=''){
   
    }
} 
