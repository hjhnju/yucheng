<?php
/*
 * 载入类型的复选框
 */
function smarty_function_checkboxes($params, &$smarty) {
    
    if (empty($params['name'])) {
        $smarty->trigger_error("yafconf: missing 'name' parameter");
        return;
    }
    
    $tag = $params['tag'];
    $ary = $params['name']::$names;
    $str = '';
    foreach ($ary as $typeid => $type) {
        $str .= "<label><input type='checkbox' name='{$tag}[]' id='{$tag}_{$typeid}' value=\"$typeid\"> $type</label>";
    }
    return $str;
}