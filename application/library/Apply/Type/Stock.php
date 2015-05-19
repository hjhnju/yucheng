<?php
/**
 * 股票，债券资产总额
 * @author guojinli
 *
 */
class Apply_Type_Stock extends Base_Type {
    /**
     * 少于200W
     * @var integer
     */
    const OPTION_1 = 1;
    /**
     * 少于1000W
     * @var integer
     */
    const OPTION_2 = 2;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'stock';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'stock_name';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::OPTION_1 => '< 200W',
        self::OPTION_2 => '< 1000W',
    );
}