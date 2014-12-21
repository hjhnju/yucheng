<?php
/**
 * 用户管理类接口逻辑实现类
 * 传入的参数除必须字段外，若无则传入null即可
 * 参数均以数组形式传入
 */
class Finance_Logic_UserManage{

    /**
     * 封装汇付天下API实现用户管理类功能
     * @param array $userRegisterParam(
     * String $version 版本号,目前固定为10(必须),
     * String $merCusId 商户客户号(必须),
     * String $UsrId 用户号,
     * String $UsrName 真实名称,
     * String $IdType 证件类型 身份证或其他...,
     * String $IdNo 证件号码,
     * String $UsrMp 手机号,
     * String $UsrEmail 用户Email,
     * String $retUrl 页面返回URL 将把处理结果返回至该页面上,
     * )
     *
     */
    public function userRegister($userRegisterParam){
        
    
    
    
    }

    /**
     * 用户绑卡实现
     * $param array $userBindCardParam(
     * String $version 版本号,目前固定为10(必须),
     * String $merCusId 商户客户号(必须),
     * String $usrCustId 用户客户号(必须) 即汇付用户ID,
     * )
     *
     */
    public function userBindCard($userBindCardParam){
    
    }

    /**
     * 企业开户实现
     * $param array $corpRegisterParam(
     * String $version 版本号,目前固定为10(必须),
     * String $merCusId 商户客户号(必须),
     * String $UsrId 用户号,
     * String $UsrName 真实名称,
     * String $InstuCode 组织机构代码,
     * String $BusiCode 营业执照编号(必须),
     * String $TaxCode 税务登记号,
     * String $GuarType 担保类型,
     * )
     *
     * 
     */
    public function corpRegister($corpRegisterParam){
    
    
    
    }


} 



