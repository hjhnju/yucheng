<?php
/**
 * 现金总额
 * @author guojinli
 *
 */
class Apply_Type_Cash extends Base_Type {
    /**
     * 5万以内
     * @var integer
     */
    const OPTION_1 = 1;

    /**
     * 5万~20万
     * @var integer
     */
    const OPTION_2 = 2;

    /**
     * 20万~50万
     * @var integer
     */
    const OPTION_3 = 3;
    /**
     * 50万以上
     * @var integer
     */
    const OPTION_4 = 4;
    
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
        self::OPTION_1 => '5万以内',
        self::OPTION_2 => '5万~20万',
        self::OPTION_3 => '20万~50万',
        self::OPTION_4 => '50万以上',
    );
}