<?php
/*
 * 载入yaf的配置文件，并将他assign到smarty一种
 */
function smarty_function_yafconf($params, &$smarty) {
    if (empty($params['file'])) {
        $smarty->trigger_error("yafconf: missing 'file' parameter");
        return;
    }
    
    if (empty($params['name'])) {
        $smarty->trigger_error("yafconf: missing 'name' parameter");
        return;
    }
    
    if (empty($params['tag'])) {
        $smarty->trigger_error("yafconf: missing 'tag' parameter");
        return;
    }
    
    $filename = MODULE_CONF_PATH . '/' . $params['file'];
    $value = Base_Config::getConfig($params['tag'], $filename);
    
    $smarty->assign($params['name'], $value);
}