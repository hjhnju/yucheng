<?php
/**
 * 借款的目的
 * @author guojinli
 *
 */
class Apply_Type_Purpose extends Base_Type {
    /**
     * 流动资金
     * @var integer
     */
    const OPTION_1 = 1;
    /**
     * 设备更新
     * @var integer
     */
    const OPTION_2 = 2;
    /**
     * 改扩建校区
     * @var integer
     */
    const OPTION_3 = 3;
    /**
     * 新开分校
     * @var string
     */
    const OPTION_4 = 4;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'school_nature';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'school_naturename';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::OPTION_1 => '流动资金',
        self::OPTION_2 => '设备更新',
        self::OPTION_3 => '改扩建校区',
        self::OPTION_4 => '新开分校',
    );
}