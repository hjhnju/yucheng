<?php
/**
 * 用户类型
 * @author guojinli
 *
 */
class User_Type_Roles extends Base_Type {
    /**
     * 用户类型－个人用户
     * @var integer
     */
    const TYPE_PRIV = 1;
    /**
     * 用户类型－企业用户
     * @var integer
     */
    const TYPE_CORP = 2;
    /**
     * 用户类型－融资用户
     * @var integer
     */
    const TYPE_FINA = 3;
    
    /**
     * 用户类型－天使用户
     * @var integer
     */
    const TYPE_ANGEL = 4;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'roles';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'roles_name';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::TYPE_PRIV  => 'priv',
        self::TYPE_CORP  => 'corp',
        self::TYPE_FINA  => 'fina',
        self::TYPE_ANGEL => 'angel'
    );
}