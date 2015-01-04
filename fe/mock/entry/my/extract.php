<?php
/**
 * Created by IntelliJ IDEA.
 * User: mySunShinning(441984145@qq.com)
 *       yangbinYB(1033371745@qq.com)
 * Date: 14-12-16
 * Time: 上午11:31
 */

date_default_timezone_set("PRC");

require('../../libs/Smarty.class.php');

$smarty = new Smarty();


$smarty->assign('bindbank', 1);
$smarty->assign('banknum', '123123123123123');
$smarty->assign('avlBal', '100');
$smarty->assign('withdrawfee', '1');
$smarty->assign('bankID', 1);
$smarty->assign('phone', '18911491525');


$smarty->caching = false;
$smarty->compile_check = true;
$smarty->debugging_ctrl = true;
$smarty->left_delimiter = '{%';
$smarty->right_delimiter = '%}';

$smarty->template_dir = "../../view/";

$output = $smarty->fetch('my/extract.phtml');

echo $output;




