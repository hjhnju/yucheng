<?php
/**
 * 投资标的状态
 * @author jiangsongfang
 *
 */
class Invest_Type_RefundStatus extends Base_Type {
    /**
     * 1 正常
     * @var integer
     */
    const NORMAL = 1;
    /**
     * 2已还款 
     * @var integer
     */
    const RETURNED = 2;
    /**
     *  3已逾期 
     * @var integer
     */
    const OVER = 3;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'status';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'refund_status';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::AUDIT   => '正常',
        self::WAITING => '已还款',
        self::LENDING => '已逾期',
    );
}