<?php
/**
 * 借款用途
 * @author jiangsongfang
 *
 */
class Loan_Type_Usage extends Base_Type {
    /**
     * 资金周转
     * @var integer
     */
    const FUND = 1;
    /**
     * 设备更新
     * @var integer
     */
    const EQUIPMENT = 2;
    /**
     * 改建扩建
     * @var integer
     */
    const BUILDING = 3;
    /**
     * 其他
     * @var integer
     */
    const OTHER = 4;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'use_type';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'use_typename';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        1 => '资金周转',
        2 => '设备更新',
        3 => '改建扩建',
        4 => '其他',
    );
}