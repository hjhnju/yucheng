<?php
/*
 * 载入类型的列表
 */
function smarty_function_selection($params, &$smarty) {
    
    if (empty($params['name'])) {
        $smarty->trigger_error("yafconf: missing 'name' parameter");
        return;
    }
    
    $ary = $params['name']::$names;
    $str = '';
    foreach ($ary as $typeid => $type) {
        $str .= "<option value=\"$typeid\">$type</option>";
    }
    return $str;
}