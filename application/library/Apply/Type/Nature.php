<?php
/**
 * 学校主体性质
 * @author guojinli
 *
 */
class Apply_Type_Nature extends Base_Type {
    /**
     * 民办非企业单位
     * @var integer
     */
    const OPTION_1 = 1;
    /**
     * 有限责任公司
     * @var integer
     */
    const OPTION_2 = 2;
    /**
     * 股份有限公司
     * @var integer
     */
    const OPTION_3 = 3;
    
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
        self::OPTION_1 => '民办非企业单位',
        self::OPTION_2 => '有限责任公司',
        self::OPTION_3 => '股份有限公司',
    );
}