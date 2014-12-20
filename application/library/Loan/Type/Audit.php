<?php
/**
 * 审核项类型
 * @author jiangsongfang
 *
 */
class Loan_Type_Audit {
    /**
     * 企业认证
     * @var integer
     */
    const COMPANY = 1;
    /**
     * 担保人认证
     * @var integer
     */
    const GUARANTEE = 2;
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::COMPANY => '企业认证',
        self::GUARANTEE => '担保人认证',
    );
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'type';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'Guarantee_type';
    
    /**
     * 通过类型id获取类型名
     * @param integer $id
     * @return string
     */
    public static function getTypeName($id) {
        return self::$names[$id];
    }
    
    /**
     * 将类型名附加到数据组中，会在数组中增加一个key值
     * @param array $data
     * @param string $key
     * @param string $field
     * @return array
     */
    public static function appendTypeName($data, $key = self::DEFAULT_KEYNAME, 
            $field = self::DEFAULT_FIELD) {
        if (empty($data)) {
            return array();
        }
        foreach ($data as $k => $v) {
            $data[$k][$field] = self::getTypeName($v[$key]);
        }
        return $data;
    }
}