<?php
/**
 * Created by IntelliJ IDEA.
 * User: baidu
 * Date: 14-12-20
 * Time: 上午10:55
 */

date_default_timezone_set("PRC");

require('../../libs/Smarty.class.php');

$smarty = new Smarty();

$smarty->caching = false;
$smarty->compile_check = true;
$smarty->debugging_ctrl = true;
$smarty->left_delimiter = '{%';
$smarty->right_delimiter = '%}';

$smarty->template_dir = "../../view/";

$output = $smarty->fetch('setting/login.phtml');

echo $output;