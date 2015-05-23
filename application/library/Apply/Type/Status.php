<?php
/**
 * 现金总额
 * @author guojinli
 *
 */
class Apply_Type_Status extends Base_Type {
    /**
     * 审核中
     * @var integer
     */
    
    const AUDIT = 1;
    /**
     * 通过审核
     * @var integer
     */
    const PROVE = 2;

    /**
     * 开启
     * @var integer
     */
    const PUBLISH = 3;

    /**
     * 审核失败
     * @var integer
     */
    const FAILED = 10;
    
    /**
     * @var string
     */
    const DEFAULT_KEYNAME = 'status';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'status_name';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::AUDIT     => '审核中',
        self::PROVE     => '通过审核',
        self::PUBLISH   => '开启',
        self::FAILED    => '审核失败',
    );
}