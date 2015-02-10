<?php
/**
 * 开放的省份
 * @author jiangsongfang
 *
 */
class Loan_Type_Province extends Base_Type {
    /**
     * 是
     * @var integer
     */
    const GX = 2043;
    /**
     * 否
     * @var integer
     */
    const HB = 2042;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'level';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'level_name';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::GX => '广西省',
        self::HB  => '湖北省',
    );
}