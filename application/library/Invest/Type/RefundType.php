<?php
/**
 * 还款方式
 * @author jiangsongfang
 *
 */
class Invest_Type_RefundType extends Base_Type {
    /**
     * 等额本息
     * @var integer
     */
    const AVERAGE = 1;
    /**
     * 按月付息，到期还本
     * @var integer
     */
    const MONTH_INTEREST = 2;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'refund_type';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'refund_typename';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::AVERAGE => '等额本息',
        self::MONTH_INTEREST => '按月付息，到期还本',
    );
}