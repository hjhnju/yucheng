<?php
/**
 * 还款方式
 * @author guojinli
 *
 */
class Apply_Type_RefundType extends Base_Type {
    /**
     * 按月付息，到期还本
     * @var integer
     */
    const MONTH_INTEREST = 3;

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
        self::MONTH_INTEREST => '按月付息，到期还本',
    );
}