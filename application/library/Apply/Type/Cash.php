<?php
/**
 * 现金总额
 * @author guojinli
 *
 */
class Apply_Type_Cash extends Base_Type {
    /**
     * 小于50W
     * @var integer
     */
    const OPTION_1 = 1;
    /**
     * 小于100W
     * @var integer
     */
    const OPTION_2 = 2;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'cash';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'cash_name';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::OPTION_1 => '< 50W',
        self::OPTION_2 => '< 100W',
    );
}