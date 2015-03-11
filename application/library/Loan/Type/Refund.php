<?php
/**
 * 还款状态
 * @author jiangsongfang
 *
 */
class Loan_Type_Refund extends Base_Type {
    /**
     * 正常
     * @var integer
     */
    const NORMAL = 1;
    /**
     * 已还款
     * @var integer
     */
    const REFUNDED = 2;
    /**
     * 逾期
     * @var integer
     */
    const OUTTIME = 3;
    
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
        self::NORMAL => '待还款',
        self::REFUNDED => '已还款',
        self::OUTTIME => '逾期',
    );
}