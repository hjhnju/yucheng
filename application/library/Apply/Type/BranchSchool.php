<?php
/**
 * 分校数量
 * @author guojinli
 *
 */
class Apply_Type_BranchSchool extends Base_Type {
    /**
     * 0所
     * @var integer
     */
    const OPTION_0 = 0;
    /**
     * 1所
     * @var integer
     */
    const OPTION_1 = 1;
    /**
     * 2所
     * @var integer
     */
    const OPTION_2 = 2;
    /**
     * 3所
     * @var integer
     */
    const OPTION_3 = 3;
    /**
     * 4所
     * @var integer
     */
    const OPTION_4 = 4;
    /**
     * 5所
     * @var integer
     */
    const OPTION_5 = 5;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'branch';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'branch_name';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::OPTION_0 => '0',
        self::OPTION_1 => '1',
        self::OPTION_2 => '2',
        self::OPTION_3 => '3',
        self::OPTION_4 => '4',
        self::OPTION_5 => '5',
    );
}