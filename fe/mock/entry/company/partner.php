<?php
/**
 * Created by IntelliJ IDEA.
 * User: baidu
 * Date: 15-1-3
 * Time: 下午1:24
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

$output = $smarty->fetch('company/partner.phtml');

echo $output;