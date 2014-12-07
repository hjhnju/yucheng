<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty replace modifier plugin
 * 
 */
function smarty_modifier_str_cut($string, $len)
{
    $len = $len * 2;
    return mb_strimwidth($string, 0, $len, '...', 'utf-8');
} 

?>