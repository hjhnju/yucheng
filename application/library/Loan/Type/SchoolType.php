<?php
/**
 * 学校类型
 * @author jiangsongfang
 *
 */
class Loan_Type_SchoolType extends Base_Type {
    /**
     * 学前教育
     * @var integer
     */
    const BEFORE = 1;
    /**
     * 基础教育
     * @var integer
     */
    const BASE = 2;
    /**
     * 职业教育
     * @var integer
     */
    const JOB = 3;
    /**
     * 高等教育
     * @var integer
     */
    const HIGH = 4;
    /**
     * 教育培训机构
     * @var integer
     */
    const TRAIN = 5;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'school_type';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'school_typename';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        1 => '学前教育',
        2 => '基础教育',
        3 => '职业教育',
        4 => '高等教育',
        5 => '教育培训机构',
    );
}