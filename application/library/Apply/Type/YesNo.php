<?php
/**
 * 是否
 * @author guojinli
 *
 */
class Apply_Type_YesNo extends Base_Type {
    /**
     * 是
     * @var integer
     */
    const YES = 1;
    /**
     * 否
     * @var integer
     */
    const NO = 0;
    
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
        self::YES => '是',
        self::NO  => '否',
    );
}