<?php
/**
 * 评估等级
 * @author hejunhua
 *
 */
class Invest_Type_LevelName extends Base_Type {
    /**
     * AAA
     * @var integer
     */
    const AAA = 1;
    /**
     * AA
     * @var integer
     */
    const AA = 2;
    /**
     * A
     * @var integer
     */
    const A = 3;
    /**
     * B
     * @var integer
     */
    const B = 4;
    /**
     * C
     * @var integer
     */
    const C = 5;
    /**
     * D
     * @var integer
     */
    const D = 6;
    /**
     * E
     * @var integer
     */
    const E = 7;
    /**
     * A
     * @var integer
     */
    const HR = 8;
    
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
        self::AAA => 'aaa',
        self::AA  => 'aa',
        self::A   => 'a',
        self::B   => 'b',
        self::C   => 'c',
        self::D   => 'd',
        self::E   => 'd',
        self::HR  => 'hr',
    );
}