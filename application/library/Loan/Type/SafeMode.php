<?php
/**
 * 保障方式
 * @author jiangsongfang
 *
 */
class Loan_Type_SafeMode extends Base_Type {
    
    /**
     * 收费权质押
     * @var integer
     */
    const PLEDGE = 3;
    /**
     * 大股东担保
     * @var integer
     */
    const SHAREHOLDER = 2;
    /**
     * 本息保障计划
     * @var integer
     */
    const CAPITAL = 1;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'safe_id';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'safemode';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::CAPITAL => '本息保障计划',
        self::SHAREHOLDER => '大股东担保',
        self::PLEDGE => '收费权质押',
    );
}
