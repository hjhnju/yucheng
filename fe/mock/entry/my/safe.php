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

$smarty->assign('phone',2);
$smarty->assign('phonenum','186****9930');
$smarty->assign('phoneurl','http://');
$smarty->assign('certinfo',2);
$smarty->assign('realname','李璐');
$smarty->assign('certinfonum','211321**********13');
$smarty->assign('certinfourl','http://');
$smarty->assign('thirdpay',2);
$smarty->assign('huifuid','汇付平台id');
$smarty->assign('thirdpayurl','http://');
$smarty->assign('email',2);
$smarty->assign('emailnum','李璐@.163');
$smarty->assign('emailpay','http://');
$smarty->assign('modifypswurl','http://');
$smarty->assign('thirdPlatform','第三方绑定平台，qq,weibo');
$smarty->assign('thirdNickName','第三方昵称');
$smarty->assign('thirdloginurl','http://');
$smarty->assign('bindthirdlogin', 2);
$smarty->assign('score', 10);


$smarty->caching = false;
$smarty->compile_check = true;
$smarty->debugging_ctrl = true;
$smarty->left_delimiter = '{%';
$smarty->right_delimiter = '%}';

$smarty->template_dir = "../../view/";

$output = $smarty->fetch('my/safe.phtml');

echo $output;




