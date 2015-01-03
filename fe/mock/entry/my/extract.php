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


//$smarty->assign('page', 1);
//$smarty->assign('pagesize', 10);
//$smarty->assign('pageall', 10);
//$smarty->assign('total', 10);
//$smarty->assign('avlBal', 10000);
//$smarty->assign('acctBal', 1000000);
//$smarty->assign('frzBal', 0.1);
//$smarty->assign('rechargeurl', 'http://充值');
//$smarty->assign('withdrawurl', 'http://提现');


$smarty->caching = false;
$smarty->compile_check = true;
$smarty->debugging_ctrl = true;
$smarty->left_delimiter = '{%';
$smarty->right_delimiter = '%}';

$smarty->template_dir = "../../view/";

$output = $smarty->fetch('my/extract.phtml');

echo $output;




