<?php 
class Utils_Array{

    public static function filter($arrVars, $arrFields){
        $arrBind = array();
        foreach ($arrVars as $field=>$value) {
            if(in_array($field, $arrFields)){
                $arrBind[$field] = $value;
            }
        }
        return $arrBind;
    }

    public static function toArray($objVars){
        array_walk($objVars, 'Utils_Array::to_array');
        return $objVars;
    }

    public static function to_array(&$value){
        $value = (array)$value;
    }

}