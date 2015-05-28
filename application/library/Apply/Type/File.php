<?php
/**
 * 上传附件的类型
 * @author guojinli
 *
 */
class Apply_Type_File extends Base_Type {
    /**
     * 申请人及担保人信息材料
     * @var integer
     */
    const GUARANTEE = 1;

    /**
     * 学校基本信息材料
     * @var integer
     */
    const BASIC = 2;

    /**
     * 学校财务证明材料
     * @var integer
     */
    const FINANCE = 3;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'file';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'file_name';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::GUARANTEE => '申请人及担保人信息材料',
        self::BASIC     => '学校基本信息材料',
        self::FINANCE   => '学校财务证明材料',
    );
}