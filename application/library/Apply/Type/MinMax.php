<?php
/**
 * 利率最小和最大
 * @author guojinli
 *
 */
class Apply_Type_MinMax extends Base_Type {
    /**
     * 
     * @var integer
     */
    const MIN = 0.13;
    /**
     * 
     * @var integer
     */
    const MAX = 0.18;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'min_max';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'min_maxname';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::MIN => '13%',
        self::MAX => '18%',
    );

    public static $values = array(
        'min' => self::MIN,
        'max' => self::MAX,
    );
}