<?php
/**
 * 审核项类型
 * @author jiangsongfang
 *
 */
class Loan_Type_Audit extends Base_Type {
    /**
     * 企业认证
     * @var integer
     */
    const COMPANY = 1;
    /**
     * 担保人认证
     * @var integer
     */
    const GUARANTEE = 2;
    /**
     * 借款人认证
     * @var integer
     */
    const PRIVLENDER = 3;

    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::COMPANY   => '机构认证',
        self::GUARANTEE => '担保人认证',
        self::PRIVLENDER=> '借款人认证',
    );
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'type';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'guarantee_type';
}