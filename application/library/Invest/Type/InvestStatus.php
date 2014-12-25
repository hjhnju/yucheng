<?php
/**
 * 投标状态
 * @author jiangsongfang
 *
 */
class Invest_Type_InvestStatus extends Base_Type {
    /**
     * 2投标中 
     * @var integer
     */
    const LENDING = 2;
    /**
     * 5回款中
     * @var integer
     */
    const REFUNDING = 5;
    /**
     * 6已完成
     * @var integer
     */
    const FINISHED = 6;
    /**
     * 7已撤销
     * @var integer
     */
    const CANCEL = 7;
    /**
     * 9投标失败
     * @var integer
     */
    const FAILED = 9;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'status';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'loan_status';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::LENDING => '投标中',
        self::REFUNDING => '回款中',
        self::FINISHED => '已完成',
        self::CANCEL => '已撤销',
        self::FAILED => '借款失败',
    );
}