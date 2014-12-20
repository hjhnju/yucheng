<?php
/**
 * 类型基类，用于配置id对应的类型
 * @author jiangsongfang
 *
 */
class Base_Type {
    public static function getDefaultKey() {
        return static::DEFAULT_KEYNAME;
    }
    
    public static function getDefaultField() {
        return static::DEFAULT_FIELD;
    }
    
    /**
     * 通过类型id获取类型名
     * @param integer $id
     * @return string
     */
    public static function getTypeName($id) {
        if (isset(static::$names[$id])) {
            return static::$names[$id];
        }
        return '';
    }
    
    /**
     * 将类型名附加到数据组中，会在数组中增加一个key值
     * @param array $data
     * @param string $key
     * @param string $field
     * @return array
     */
    public static function appendTypeName($data, $key = null, $field = null) {
        if (empty($data)) {
            return array();
        }
        if (empty($key)) {
            $key = static::getDefaultKey();
        }
        if (empty($field)) {
            $key = static::getDefaultField();
        }
        foreach ($data as $k => $v) {
            $data[$k][$field] = self::getTypeName($v[$key]);
        }
        return $data;
    }
}