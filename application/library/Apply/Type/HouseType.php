<?php
/**
 * 住房类型
 * @author guojinli
 *
 */
class Apply_Type_Housetype extends Base_Type {
    /**
     * 自有住房
     * @var integer
     */
    const OPTION_1 = 1;
    /**
     * 租房
     * @var integer
     */
    const OPTION_2 = 2;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'house_type';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'house_typename';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::OPTION_1 => '自有住房',
        self::OPTION_2 => '租房',
    );
}