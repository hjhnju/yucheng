<?php
/**
 * 用户类型
 *
 */
class User_Type extends Base_Type {
    /**
     * 1 个人用户
     * @var integer
     */
    const PRI = 1;
    /**
     * 2 企业用户
     * @var integer
     */
    const CROP_1 = 2;
    /**
     * 3 新的企业用户 
     * @var integer
     */
    const CROP_2 = 3;
    /**
     * 4 天使
     * @var integer
     */
    const ANGEL = 4;
  
    public static $names = array(
        self::PRI     => '个人用户',
        self::CROP_1  => '企业用户',
        self::CROP_2  => '新的企业用户',
        self::ANGEL   => '天使',
    );
}