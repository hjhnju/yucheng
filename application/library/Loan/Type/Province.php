<?php
/**
 * 开放的省份
 * @author jiangsongfang
 *
 */
class Loan_Type_Province extends Base_Type {
    /**
     * 广西
     * @var integer
     */
    // const GUANGXI = 2403;
    const GUANGXI = 2458;
    /**
     * 福建
     * @var integer
     */
    // const FUJIAN = 1310;
    const FUJIAN = 1394;
    /**
     * 湖南
     * @var integer
     */
    const HUNAN = 2034;
    
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
        self::GUANGXI => '广西省',
        self::FUJIAN  => '福建省',
        self::HUNAN  => '湖南省',
    );
}