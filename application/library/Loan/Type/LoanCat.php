<?php
/**
 * 借款类型
 * @author jiangsongfang
 *
 */
class Loan_Type_LoanCat extends Base_Type {
    /**
     * 学校助力贷
     * @var integer
     */
    const SCHOOL = 1;
    /**
     * 教师圆梦贷
     * @var integer
     */
    const TEACHER = 2;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'cat_id';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'loan_cat';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::SCHOOL => '学校助业贷',
        self::TEACHER => '教师圆梦贷',
    );
}