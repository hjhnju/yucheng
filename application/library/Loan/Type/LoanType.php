<?php
/**
 * 标的类型
 * @author jiangsongfang
 *
 */
class Loan_Type_LoanType extends Base_Type {
    /**
     * 实地认证标
     * @var integer
     */
    const ENTITY = 1;
    /**
     * 信用认证标
     * @var integer
     */
    const CERTIFICATION = 2;
    /**
     * 机构担保标
     * @var integer
     */
    const ORGANIZATION = 3;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'type_id';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'loan_type';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::ENTITY => '实地认证标',
        self::CERTIFICATION => '信用认证标',
        self::ORGANIZATION => '机构担保标',
    );
}