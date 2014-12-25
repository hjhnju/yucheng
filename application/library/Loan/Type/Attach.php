<?php
/**
 * 附件类型
 * @author jiangsongfang
 *
 */
class Loan_Type_Attach extends Base_Type {
    /**
     * 认证材料
     * @var integer
     */
    const CERTIFICATION = 1;
    /**
     * 合同协议
     * @var integer
     */
    const CONTRACT = 2;
    /**
     * 实地照片
     * @var integer
     */
    const ENTITY = 3;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'type';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'attach_type';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::CERTIFICATION => '认证材料',
        self::CONTRACT => '合同协议',
        self::ENTITY => '实地照片',
    );
}