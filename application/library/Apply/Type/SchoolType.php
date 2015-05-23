<?php
/**
 * 学校类型
 * @author guojinli
 *
 */
class Apply_Type_SchoolType extends Base_Type {
    /**
     * 学前教育
     * @var integer
     */
    const BEFORE = 1;
    /**
     * 基础教育
     * @var integer
     */
    const BASE = 2;
    /**
     * 职业教育
     * @var integer
     */
    const JOB = 3;
    /**
     * 高等教育
     * @var integer
     */
    const HIGH = 4;
    /**
     * 教育培训机构
     * @var integer
     */
    const TRAIN = 5;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'school_type';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'school_typename';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::BEFORE  => '学前教育',
        self::BASE    => '基础教育',
        self::JOB     => '职业教育',
        self::HIGH    => '高等教育',
        self::TRAIN   => '教育培训机构',
    );

    public static $items = array(
        self::BEFORE  => array(
            'label' => '学前教育',
            'money' => '¥1,000,000',
        ),
        self::BASE    => array(
            'label' => '基础教育',
            'money' => '¥5,000,000',
        ),
        self::JOB     => array(
            'label' => '职业教育',
            'money' => '¥1,000,000',
        ),
        self::HIGH    => array(
            'label' => '高等教育',
            'money' => '¥5,000,000',
        ),
        self::TRAIN   => array(
            'label' => '教育培训机构',
            'money' => '¥1,000,000',
        ),
    );
}