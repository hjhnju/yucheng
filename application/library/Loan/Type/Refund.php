<?php
/**
 * 还款状态
 * @author jiangsongfang
 *
 */
class Loan_Type_Refund {
    /**
     * 正常
     * @var integer
     */
    const NORMAL = 1;
    /**
     * 已还款
     * @var integer
     */
    const REFUNDED = 2;
    /**
     * 逾期
     * @var integer
     */
    const OUTTIME = 3;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'status';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'refund_status';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::NORMAL => '正常',
        self::REFUNDED => '已还',
        self::OUTTIME => '逾期',
    );
    
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