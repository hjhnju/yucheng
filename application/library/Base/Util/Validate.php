<?php

/**
 * 基本且通用的类型、正则检查
 *
 */
class Base_Util_Validate {

    /**
     * 非负整数数组检查
     * @param
     * @return bool
     */
    public static function checkUIntArray($arrInts) {
        if (!is_array($arrInts)){
            return false;
        }
        
        foreach($arrInts as $int){
            if ($int < 0){
                return false;
            }
            if (intval($int) != $int){
                return false;
            }
        }
         
        return true;
    }

    /**
     * 正整数数组检查
     * @param
     * @return bool
     */
    public static function checkPosIntArray($arrInts) {
        if (!is_array($arrInts)){
            return false;
        }
        
        foreach($arrInts as $int){
            if ($int <= 0){
                return false;
            }
            if (intval($int) != $int){
                return false;
            }
        }
         
        return true;
    }

    /**
     * Url检查
     *
     * @param
     * @return bool
     */
    public static function checkUrl($strUrl) {
        if (preg_match('#^https?://#', $strUrl) <= 0){
            return false;
        }

        return true;
    }

    /**
     * Type类型检查
     *
     * @param string $strType
     * @param unknown_type $mixValue
     * @return bool
     */ 
    public static function checkType($strType, $mixValue, $other=null){
        switch ($strType){
            case 'INT': 
                return is_int($mixValue);
            case 'INT_POS':
                return (is_int($mixValue)&&($mixValue>0));
            case 'INT_NEG':
                return (is_int($mixValue)&&($mixValue<0));
            case 'INT_ZERO':
                return (0 === $mixValue);
            case 'NUM':
                return is_numeric($mixValue);
            case 'STR':
                return is_string($mixValue);
            case 'ARR':
                return is_array($mixValue);
            case 'BOOL':
                return is_bool($mixValue);
            case 'NULL':
                return is_null($mixValue);
            case 'OBJ':
                if (null == $other){
                    return is_object($mixValue);
                }else {
                    return is_a($mixValue, $other);
                }
            default:
                Base_Log::warn("unknown type: $strType");
                return false;
        }
    }
}
